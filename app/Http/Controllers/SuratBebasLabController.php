<?php

namespace App\Http\Controllers;

use App\Models\SuratBebasLab;
use App\Mail\VerifyBebasLab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Mail\NotifikasiBebasLab;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SuratBebasLabController extends Controller
{
    /**
     * 1. Menampilkan Form untuk Mahasiswa
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
                'regex:/^[Hh][0-9]+@student\.untan\.ac\.id$/i' // Khusus email student UNTAN
            ],
        ], [
            'email.regex' => 'Gunakan email resmi mahasiswa UNTAN (H... @student.untan.ac.id)',
            'nim.unique'  => 'NIM ini sudah pernah mengajukan surat bebas lab.'
        ]);

        // Simpan data ke database
        $pengajuan = SuratBebasLab::create([
            'nama'   => $request->nama,
            'nim'    => $request->nim,
            'prodi'  => $request->prodi,
            'email'  => $request->email,
            'status' => 'pending',
        ]);

        // Generate URL Verifikasi yang aman (Signed URL) berlaku 24 jam
        $verificationUrl = URL::temporarySignedRoute(
            'bebas-lab.verify', 
            now()->addHours(24), 
            ['id' => $pengajuan->id]
        );

        // Mengirim Email Verifikasi
        Mail::to($pengajuan->email)->send(new VerifyBebasLab($verificationUrl, $pengajuan));

        return back()->with('success', 'Form berhasil dikirim! Silakan cek email Anda untuk melakukan verifikasi.');
    }

public function dashboardAdmin()
{
    $notifPengajuan = SuratBebasLab::where('status', 'verified_email')->count();

    // SANGAT PENTING: Return ke 'dashboard', BUKAN 'admin.dashboard'
    // Supaya file layouts.app (CSS/Navigasi) tetap ikut terbawa.
    return view('dashboard', compact('notifPengajuan'));
}
    
    /**
     * 3. Verifikasi Email oleh Mahasiswa (Klik Link dari Email)
     */
    public function verifyEmail(Request $request, $id)
    {
        // Mengecek apakah link valid dan belum expired (Signed URL check)
        if (! $request->hasValidSignature()) {
            abort(403, 'Tautan verifikasi sudah kedaluwarsa atau tidak sah.');
        }

        $pengajuan = SuratBebasLab::findOrFail($id);
        
        // Update status hanya jika sebelumnya masih pending
        if ($pengajuan->status == 'pending') {
            $pengajuan->update([
                'status' => 'verified_email',
                'email_verified_at' => now()
            ]);
        }

        return view('bebas-lab.verified_success');
    }

    /**
     * 4. (ADMIN ONLY) Dashboard List Pengajuan
     */
    public function indexAdmin()
    {
        // Mengambil data terbaru untuk ditampilkan di tabel admin
        $data = SuratBebasLab::orderBy('created_at', 'desc')->get();
        return view('admin.bebas-lab.index', compact('data'));
    }

    /**
     * 5. (ADMIN ONLY) Update Status (Setujui/Tolak)
     */
    public function updateStatus(Request $request, $id)
{
    $pengajuan = SuratBebasLab::findOrFail($id);
    
    // Tentukan status dan catatan berdasarkan tombol yang diklik admin
    if ($request->action == 'setujui') {
        $pengajuan->update([
            'status' => 'disetujui'
        ]);
        $statusEmail = 'disetujui';
    } else {
        $pengajuan->update([
            'status' => 'ditolak',
            'catatan_admin' => $request->catatan
        ]);
        $statusEmail = 'ditolak';
    }

    // EKSEKUSI PENGIRIMAN EMAIL
    // Pastikan Bapak sudah membuat Mailable 'NotifikasiBebasLab'
    try {
        Mail::to($pengajuan->email)->send(new NotifikasiBebasLab(
            $pengajuan, 
            $statusEmail, 
            $request->catatan
        ));
    } catch (\Exception $e) {
        // Jika email gagal tapi database berhasil update, tetap beri info ke admin
        return back()->with('success', 'Status diperbarui, namun email gagal dikirim. Cek konfigurasi SMTP.');
    }

    return back()->with('success', 'Status pengajuan berhasil diperbarui dan email notifikasi telah dikirim.');
}


}