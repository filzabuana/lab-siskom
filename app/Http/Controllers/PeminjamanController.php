<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use App\Models\Keranjang;
use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PeminjamanController extends Controller
{
    /**
     * MAHASISWA: Tampilkan Katalog Alat dengan Pencarian dan Filter Kategori Dinamis
     */
    public function indexKatalog(Request $request)
    {
        // 1. Auto-cleanup keranjang basi (>24 jam)
        Keranjang::where('user_id', Auth::id())
                 ->where('expires_at', '<', now())
                 ->delete();

        // 2. Query dasar mengambil data inventaris yang "Bisa Dipinjam"
        $query = Inventaris::query()->where('tipe_peminjaman', 'Bisa Dipinjam');

        // 3. Jalankan Filter Pencarian (Nama Alat / Kode Barang / Merk)
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_aset', 'like', "%{$request->search}%")
                  ->orWhere('kode_barang', 'like', "%{$request->search}%")
                  ->orWhere('merk', 'like', "%{$request->search}%");
            });
        }

        // 4. Jalankan Filter Kategori Dinamis jika diisi
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // 5. Eksekusi pagination (Accessor secara otomatis menyuplai sisa stok riil)
        $katalog = $query->orderByRaw('jumlah_stok > 0 DESC') 
            ->orderBy('nama_aset', 'ASC')
            ->paginate(12)
            ->withQueryString();

        // 6. Ambil Daftar Kategori Unik untuk Filter
        $kategoriList = Inventaris::whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->distinct()
            ->orderBy('kategori', 'ASC')
            ->pluck('kategori')
            ->toArray();

        $isiKeranjang = Keranjang::where('user_id', Auth::id())->get();

        return Inertia::render('Peminjaman/Katalog', [
            'katalog' => $katalog,
            'keranjang' => $isiKeranjang,
            'kategoriList' => $kategoriList,
            'filters' => $request->only(['search', 'kategori'])
        ]);
    }

    /**
     * MAHASISWA: Tampilkan Halaman Keranjang
     */
    public function viewCart()
    {
        $isiKeranjang = Keranjang::with('inventaris')
            ->where('user_id', Auth::id())
            ->get()
            ->map(function ($item) {
                if ($item->inventaris) {
                    $item->inventaris->stok = $item->inventaris->jumlah_stok;
                }
                return $item;
            });

        return Inertia::render('Peminjaman/Cart', [
            'keranjang' => $isiKeranjang
        ]);
    }

    /**
     * MAHASISWA: Tambah alat ke keranjang
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'inventaris_id' => 'required|exists:inventaris,id',
            'jumlah' => 'required|integer|min:1'
        ]);

        $barang = Inventaris::findOrFail($request->inventaris_id);

        if ($barang->jumlah_stok < $request->jumlah) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        $cartItem = Keranjang::where('user_id', Auth::id())
            ->where('inventaris_id', $request->inventaris_id)
            ->first();

        if ($cartItem) {
            $totalBaru = $cartItem->jumlah + $request->jumlah;
            if ($totalBaru > $barang->jumlah_stok) {
                return back()->with('error', 'Total di keranjang melebihi stok tersedia.');
            }

            $cartItem->update([
                'jumlah' => $totalBaru,
                'expires_at' => now()->addHours(24)
            ]);
        } else {
            // Khusus perlindungan barang serialized tunggal di UI Keranjang
            if ($barang->is_serialized && $request->jumlah > $barang->jumlah_stok) {
                return back()->with('error', 'Stok unit fisik tersedia tidak mencukupi.');
            }

            Keranjang::create([
                'user_id' => Auth::id(),
                'inventaris_id' => $request->inventaris_id,
                'jumlah' => $request->jumlah,
                'expires_at' => now()->addHours(24)
            ]);
        }

        return back()->with('message', 'Alat berhasil dimasukkan ke keranjang.');
    }

    /**
     * MAHASISWA: Update Jumlah di Keranjang
     */
    public function updateCart(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1'
        ]);

        $item = Keranjang::with('inventaris')->where('user_id', Auth::id())->findOrFail($id);

        if ($request->jumlah > $item->inventaris->jumlah_stok) {
            return back()->with('error', 'Jumlah melebihi stok tersedia.');
        }

        $item->update(['jumlah' => $request->jumlah]);
        return back();
    }

    /**
     * MAHASISWA: Hapus satu item keranjang
     */
    public function destroyCart($id)
    {
        $getCart = Keranjang::with('inventaris')->where('user_id', Auth::id())->where('id', $id)->first();
        $namaAset = $getCart && $getCart->inventaris ? $getCart->inventaris->nama_aset : 'Item';
        
        if ($getCart) {
            $getCart->delete();
        }
        
        return back()->with('message', "{$namaAset} berhasil dihapus dari keranjang.");
    }

    /**
     * MAHASISWA: Proses Checkout
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'keperluan' => 'required|string|max:500',
            'tgl_pinjam' => 'required|date|after_or_equal:today',
            'tgl_kembali_rencana' => 'required|date|after:tgl_pinjam',
        ]);

        return DB::transaction(function () use ($request) {
            $user = Auth::user();
            $items = Keranjang::with('inventaris')->where('user_id', $user->id)->get();

            if ($items->isEmpty()) {
                return back()->with('error', 'Keranjang kosong.');
            }

            foreach ($items as $item) {
                if ($item->jumlah > $item->inventaris->jumlah_stok) {
                    return back()->with('error', "Stok {$item->inventaris->nama_aset} tiba-tiba tidak cukup.");
                }
            }

            $peminjaman = Peminjaman::create([
                'user_id' => $user->id,
                'tgl_pinjam' => $request->tgl_pinjam,
                'tgl_kembali_rencana' => $request->tgl_kembali_rencana,
                'keperluan' => $request->keperluan,
                'status' => 'Pending',
            ]);

            foreach ($items as $item) {
                PeminjamanDetail::create([
                    'peminjaman_id' => $peminjaman->id,
                    'inventaris_id' => $item->inventaris_id,
                    'jumlah'        => $item->jumlah,
                ]);
            }

            Keranjang::where('user_id', $user->id)->delete();

            return redirect()->route('peminjaman.history')->with('message', 'Permintaan peminjaman berhasil terkirim.');
        });
    }

    /**
     * MAHASISWA: Tampilkan Riwayat
     */
    public function history()
    {
        $riwayat = Peminjaman::with(['details.inventaris'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'DESC')
            ->get();

        return Inertia::render('Peminjaman/History', [
            'riwayat' => $riwayat
        ]);
    }

    /* | AREA ADMIN / PLP | */

    public function indexAdmin()
    {
        $requests = Peminjaman::with(['user', 'details.inventaris'])
            ->orderByRaw("CASE 
                WHEN status = 'Pending' THEN 1 
                WHEN status = 'Disetujui' THEN 2 
                WHEN status = 'Sedang Dipinjam' THEN 3 
                ELSE 4 END")
            ->orderBy('created_at', 'DESC')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/Peminjaman/Index', [
            'requests' => $requests
        ]);
    }

    /**
     * ADMIN: UPDATE STATUS PEMINJAMAN (HIBRIDA LOGIC)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
            'catatan' => 'nullable|string|max:255',
        ]);

        $peminjaman = Peminjaman::with('details.inventaris')->findOrFail($id);
        
        $oldStatus = strtolower($peminjaman->status);
        $newStatus = strtolower($request->status);

        return DB::transaction(function () use ($newStatus, $oldStatus, $peminjaman, $request) {
            
            // 1. KONDISI: PERSETUJUAN DISETUJUI
            if ($newStatus === 'disetujui' && $oldStatus === 'pending') {
                foreach ($peminjaman->details as $detail) {
                    $item = $detail->inventaris;
                    $item->refresh(); 

                    if ($item->jumlah_stok < $detail->jumlah) {
                        throw new \Exception("Stok {$item->nama_aset} tidak mencukupi.");
                    }
                    
                    // JIKA BARANG BULK (BUKAN SERIALIZED), kurangi nominal kolom database
                    if (!$item->is_serialized) {
                        $item->decrement('jumlah_stok', $detail->jumlah);
                    }
                    // Catatan: Jika serialized, pengurangan stok riil dihitung via relasi 
                    // status item ketika barang diserahterimakan (di-scan keluar).
                }
            }

            // 2. KONDISI: PEMBATALAN / PENOLAKAN / SELESI KEMBALI
            $statusPengembalian = ['dibatalkan', 'ditolak', 'selesai'];
            $statusPerluBalikStok = ['disetujui', 'sedang dipinjam', 'dipinjam'];

            if (in_array($newStatus, $statusPengembalian) && in_array($oldStatus, $statusPerluBalikStok)) {
                foreach ($peminjaman->details as $detail) {
                    $item = $detail->inventaris;
                    
                    // JIKA BARANG BULK, kembalikan nominal kolom database
                    if (!$item->is_serialized) {
                        $item->increment('jumlah_stok', $detail->jumlah);
                    }
                    // Catatan: Jika serialized, pemulihan status unit dari 'dipinjam' kembali ke 'tersedia' 
                    // dikendalikan langsung pada log inventaris_items sewaktu pengembalian fisik barang.
                }
            }

            // 3. Update Status Induk Transaksi Peminjaman
            $peminjaman->update([
                'status' => $request->status, 
                'catatan' => $request->catatan,
            ]);

            return back()->with('message', "Status peminjaman berhasil diubah.");
        });
    }
}