<?php

namespace App\Http\Controllers;

use App\Models\SuratBebasLab;
use App\Models\Peminjaman; // Tambahkan ini
use App\Models\Inventaris;  // Tambahkan jika perlu statistik alat
use App\Mail\VerifyBebasLab;
use App\Mail\NotifikasiBebasLab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SuratBebasLabController extends Controller
{
    /**
     * 1. Menampilkan Form untuk Mahasiswa (Umum)
     */
    public function create()
    {
        return view('bebas-lab.form');
    }

    /**
     * 2. Menyimpan Data Pengajuan & Kirim Email Verifikasi
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string|max:255',
            'nim'   => 'required|string|unique:surat_bebas_labs,nim',
            'prodi' => 'required|string',
            'email' => [
                'required',
                'email',
                'regex:/^[Hh][0-9]+@student\.untan\.ac\.id$/i'
            ],
        ], [
            'email.regex' => 'Gunakan email resmi mahasiswa UNTAN (H... @student.untan.ac.id)',
            'nim.unique'  => 'NIM ini sudah pernah mengajukan surat bebas lab.'
        ]);

        $pengajuan = SuratBebasLab::create([
            'nama'   => $request->nama,
            'nim'    => $request->nim,
            'prodi'  => $request->prodi,
            'email'  => $request->email,
            'status' => 'pending',
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'bebas-lab.verify', 
            now()->addHours(24), 
            ['id' => $pengajuan->id]
        );

        Mail::to($pengajuan->email)->send(new VerifyBebasLab($verificationUrl, $pengajuan));

        return back()->with('success', 'Form berhasil dikirim! Silakan cek email Anda untuk melakukan verifikasi.');
    }

    /**
     * DASHBOARD UTAMA (Adaptif: Admin vs Mahasiswa)
     */
    public function dashboardAdmin()
{
    $user = Auth::user();

    if ($user->is_admin) {
        $notifPengajuan = SuratBebasLab::where('status', 'verified_email')->count();
        
        // Statistik Admin
        $countPeminjamanAktif = Peminjaman::where('status', 'disetujui')->count(); 
        $countPending = Peminjaman::where('status', 'pending')->count();
        
        // Untuk Admin, Riwayat adalah semua yang sudah 'selesai' di lab
        $countTotal = Peminjaman::where('status', 'selesai')->count();

        return view('dashboard', compact('notifPengajuan', 'countPeminjamanAktif', 'countPending', 'countTotal'));
    }

    // --- LOGIKA MAHASISWA ---
    
    // 1. Sedang Dipinjam: Alat ada di mahasiswa (disetujui tapi belum selesai)
    $countPeminjamanAktif = Peminjaman::where('user_id', $user->id)
                                      ->where('status', 'disetujui')
                                      ->count();

    // 2. Menunggu Approval: Masih proses pengajuan
    $countPending = Peminjaman::where('user_id', $user->id)
                                ->where('status', 'pending')
                                ->count();

    // 3. Total Riwayat: Hanya yang SUDAH BERHASIL (Selesai pinjam & kembali)
    $countTotal = Peminjaman::where('user_id', $user->id)
                            ->where('status', 'selesai')
                            ->count();

    return view('dashboard', compact('countPeminjamanAktif', 'countPending', 'countTotal'));
}
    /**
     * 3. Verifikasi Email oleh Mahasiswa
     */
    public function verifyEmail(Request $request, $id)
    {
        if (! $request->hasValidSignature()) {
            abort(403, 'Tautan verifikasi sudah kedaluwarsa atau tidak sah.');
        }

        $pengajuan = SuratBebasLab::findOrFail($id);
        
        if ($pengajuan->status == 'pending') {
            $pengajuan->update([
                'status' => 'verified_email',
                'email_verified_at' => now()
            ]);
        }

        return view('bebas-lab.verified_success');
    }

    /**
     * 4. (ADMIN ONLY) List Pengajuan Bebas Lab
     */
    public function indexAdmin()
    {
        $data = SuratBebasLab::orderBy('created_at', 'desc')->get();
        return view('admin.bebas-lab.index', compact('data'));
    }

    /**
     * 5. (ADMIN ONLY) Update Status Bebas Lab
     */
    public function updateStatus(Request $request, $id)
    {
        $pengajuan = SuratBebasLab::findOrFail($id);
        
        if ($request->action == 'setujui') {
            $pengajuan->update(['status' => 'disetujui']);
            $statusEmail = 'disetujui';
        } else {
            $pengajuan->update([
                'status' => 'ditolak',
                'catatan_admin' => $request->catatan
            ]);
            $statusEmail = 'ditolak';
        }

        try {
            Mail::to($pengajuan->email)->send(new NotifikasiBebasLab(
                $pengajuan, 
                $statusEmail, 
                $request->catatan
            ));
        } catch (\Exception $e) {
            return back()->with('success', 'Status diperbarui, namun email gagal dikirim.');
        }

        return back()->with('success', 'Status pengajuan berhasil diperbarui.');
    }
}