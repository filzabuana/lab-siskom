<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Inventaris;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function index()
    {
        if (Auth::user()->is_admin) {
            // Admin melihat semua pengajuan, diurutkan dari yang terbaru
            $peminjamans = Peminjaman::with(['user', 'inventaris'])->latest()->get();
        } else {
            // Mahasiswa hanya melihat riwayat pinjamannya sendiri
            $peminjamans = Peminjaman::with('inventaris')
                            ->where('user_id', Auth::id())
                            ->latest()
                            ->get();
        }

        return view('peminjaman.index', compact('peminjamans'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'inventaris_id' => 'required|exists:inventaris,id',
            'jumlah_pinjam' => 'required|integer|min:1',
            'tgl_kembali_rencana' => 'required|date|after_or_equal:today',
            'keperluan' => 'required|string|max:255',
        ]);

        $alat = Inventaris::findOrFail($request->inventaris_id);

        // Proteksi: Cek apakah stok fisik mencukupi
        if ($request->jumlah_pinjam > $alat->jumlah_stok) {
            return back()->with('error', 'Maaf, stok alat tidak mencukupi untuk jumlah tersebut.');
        }

        Peminjaman::create([
            'user_id' => Auth::id(),
            'inventaris_id' => $request->inventaris_id,
            'jumlah_pinjam' => $request->jumlah_pinjam,
            'tgl_pinjam' => now(), // Tanggal pengajuan
            'tgl_kembali_rencana' => $request->tgl_kembali_rencana,
            'keperluan' => $request->keperluan,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Pengajuan peminjaman berhasil dikirim! Silakan tunggu konfirmasi PLP.');
    }
    public function updateStatus(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $alat = Inventaris::findOrFail($peminjaman->inventaris_id);

        if ($request->status == 'disetujui') {
            // Potong stok alat di lab
            $alat->decrement('jumlah_stok', $peminjaman->jumlah_pinjam);
            $peminjaman->update(['status' => 'disetujui']);
            
        } elseif ($request->status == 'selesai') {
            // Kembalikan stok alat ke lab
            $alat->increment('jumlah_stok', $peminjaman->jumlah_pinjam);
            $peminjaman->update([
                'status' => 'selesai',
                'tgl_kembali_aktual' => now()
            ]);
            
        } elseif ($request->status == 'ditolak') {
            $peminjaman->update([
                'status' => 'ditolak',
                'catatan_admin' => $request->catatan_admin
            ]);
        }

        return redirect()->back()->with('success', 'Status peminjaman berhasil diperbarui.');
    }
}