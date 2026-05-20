<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use App\Models\InventarisItem;
use App\Models\Keranjang;
use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use App\Models\PeminjamanDetailItem;
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

            // Untuk barang bulk, hitung sisa riil sebelum diizinkan masuk keranjang
            if (!$barang->is_serialized) {
                $totalDipinjam = (int) PeminjamanDetail::where('inventaris_id', $barang->id)
                    ->whereHas('peminjaman', function($q) {
                        $q->whereIn(DB::raw('LOWER(status)'), ['dipinjam', 'sedang dipinjam', 'disetujui']);
                    })->sum('jumlah');
                
                $sisaStokRiil = max(0, $barang->jumlah_stok - $totalDipinjam);

                if ($request->jumlah > $sisaStokRiil) {
                    return back()->with('error', "Stok di rak sisa {$sisaStokRiil} unit. Tidak bisa memesan sejumlah {$request->jumlah}.");
                }
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
     */public function updateCart(Request $request, $id)
{
    $request->validate([
        'jumlah' => 'required|integer|min:1'
    ]);

    $item = Keranjang::with('inventaris')->where('user_id', Auth::id())->findOrFail($id);

    if ($request->jumlah > $item->inventaris->jumlah_stok) {
        return back()->with('error', 'Jumlah melebihi stok tersedia.');
    }

    // Tambahkan baris ini agar perubahan tersimpan ke DB
    $item->update([
        'jumlah' => $request->jumlah
    ]);

    return back()->with('message', 'Jumlah berhasil diupdate.');
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

    /**
     * ADMIN: Tampilkan Semua Permintaan Peminjaman Alat
     */
    public function indexAdmin()
    {
        $requests = Peminjaman::with([
            'user', 
            'details.inventaris.items' => function($query) {
                $query->where('status', 'tersedia');
            },
            'details.detailItems.inventarisItem'
        ])
        ->orderByRaw("CASE 
            WHEN status = 'Pending' THEN 1 
            WHEN status = 'Disetujui' THEN 2 
            WHEN status = 'Sedang Dipinjam' THEN 3 
            ELSE 4 END")
        ->orderBy('created_at', 'DESC')
        ->paginate(10)
        ->withQueryString();

        $requests->getCollection()->transform(function($peminjaman) {
            foreach ($peminjaman->details as $detail) {
                $detail->barcodes_terpilih = $detail->detailItems->map(function($di) {
                    return $di->inventarisItem ? $di->inventarisItem->barcode_aset : null;
                })->filter()->values()->toArray(); 
            }
            return $peminjaman;
        });

        return Inertia::render('Admin/Peminjaman/Index', [
            'requests' => $requests
        ]);
    }

    /**
     * ADMIN: UPDATE STATUS PEMINJAMAN
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
            'catatan' => 'nullable|string|max:255',
            'selected_barcodes' => 'nullable|array'
        ]);

        $peminjaman = Peminjaman::with('details.inventaris')->findOrFail($id);
        
        $oldStatus = strtolower($peminjaman->status);
        $newStatus = strtolower($request->status);

        return DB::transaction(function () use ($newStatus, $oldStatus, $peminjaman, $request) {
            
            // KONDISI 1: PERSETUJUAN DISETUJUI
            if ($newStatus === 'disetujui' && $oldStatus === 'pending') {
                foreach ($peminjaman->details as $detail) {
                    $item = $detail->inventaris;
                    $item->refresh(); 

                    if ($item->is_serialized) {
                        $stokTersediaRiil = $item->items()->where('status', 'tersedia')->count();
                        
                        if ($stokTersediaRiil < $detail->jumlah) {
                            throw new \Exception("Stok unit fisik {$item->nama_aset} yang siap pakai tidak mencukupi (Tersedia: {$stokTersediaRiil}).");
                        }

                        // Daftarkan relasi barcode fisik satu per satu
                        for ($n = 1; $n <= $detail->jumlah; $n++) {
                            $key = "{$detail->id}-{$n}";
                            $barcodeString = $request->selected_barcodes[$key] ?? null;
                            
                            if (!$barcodeString) {
                                throw new \Exception("Barcode untuk unit ke-{$n} pada alat {$item->nama_aset} belum ditentukan.");
                            }

                            $inventarisItem = InventarisItem::where('inventaris_id', $item->id)
                                                                ->where('barcode_aset', $barcodeString)
                                                                ->first();

                            if (!$inventarisItem) {
                                throw new \Exception("Unit fisik dengan kode {$barcodeString} tidak ditemukan.");
                            }

                            if ($inventarisItem->status !== 'tersedia') {
                                throw new \Exception("Unit {$barcodeString} saat ini tidak tersedia.");
                            }

                            PeminjamanDetailItem::create([
                                'peminjaman_detail_id' => $detail->id,
                                'inventaris_item_id'   => $inventarisItem->id,
                            ]);

                            $inventarisItem->update(['status' => 'dipinjam']);
                        }
                    } else {
                        // KONTROL BARANG BULK: Validasi real-time berbasis kalkulasi statis vs transaksi aktif
                        $totalDipinjam = (int) PeminjamanDetail::where('inventaris_id', $item->id)
                            ->whereHas('peminjaman', function($q) {
                                $q->whereIn(DB::raw('LOWER(status)'), ['dipinjam', 'sedang dipinjam', 'disetujui']);
                            })->sum('jumlah');

                        $sisaStokRiil = max(0, $item->jumlah_stok - $totalDipinjam);

                        if ($sisaStokRiil < $detail->jumlah) {
                            throw new \Exception("Gagal menyetujui. Sisa fisik {$item->nama_aset} di lab hanya {$sisaStokRiil} unit.");
                        }

                        // KEPUTUSAN ARSITEKTUR BARU: Tidak ada lagi $item->decrement() di sini. 
                        // Stok aman terkunci di angka plafon.
                    }
                }
            }

            // KONDISI 2: RESTORE / PEMULIHAN STATUS BARCODE (Khusus Serialized)
            $statusPengembalian = ['dibatalkan', 'ditolak', 'selesai'];
            $statusPerluBalikStok = ['disetujui', 'sedang dipinjam', 'dipinjam'];

            if (in_array($newStatus, $statusPengembalian) && in_array($oldStatus, $statusPerluBalikStok)) {
                foreach ($peminjaman->details as $detail) {
                    $item = $detail->inventaris;
                    
                    if ($item->is_serialized) {
                        $detailItems = PeminjamanDetailItem::where('peminjaman_detail_id', $detail->id)->get();
                        
                        foreach ($detailItems as $di) {
                            if ($di->inventarisItem) {
                                $di->inventarisItem->update(['status' => 'tersedia']);
                            }
                        }
                        
                        if (in_array($newStatus, ['dibatalkan', 'ditolak'])) {
                            PeminjamanDetailItem::where('peminjaman_detail_id', $detail->id)->delete();
                        }
                    } else {
                        // KEPUTUSAN ARSITEKTUR BARU: Tidak ada lagi $item->increment() di sini.
                        // Fluktuasi barang bulk otomatis selesai ketika status peminjaman berubah dari scope aktif.
                    }
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