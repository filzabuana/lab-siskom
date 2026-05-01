<?php

namespace App\Http\Controllers;

use App\Models\SuratBebasLab;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class SuratBebasLabController extends Controller
{
    // 1. Menampilkan Form untuk Mahasiswa
    public function create()
    {
        return view('bebas-lab.form');
    }

    // 2. Menyimpan Data Pengajuan & Kirim Email Verifikasi
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

        $pengajuan = SuratBebasLab::create([
            'nama'   => $request->nama,
            'nim'    => $request->nim,
            'prodi'  => $request->prodi,
            'email'  => $request->email,
            'status' => 'pending',
        ]);

        // Generate URL Verifikasi yang aman (Signed URL)
        $verificationUrl = URL::temporarySignedRoute(
            'bebas-lab.verify', 
            now()->addHours(24), 
            ['id' => $pengajuan->id]
        );

        // Kirim Email (Pastikan Bapak sudah membuat Mailable: php artisan make:mail VerifyBebasLab)
        // Mail::to($pengajuan->email)->send(new \App\Mail\VerifyBebasLab($verificationUrl, $pengajuan));

        return back()->with('success', 'Form berhasil dikirim! Silakan cek email Anda untuk melakukan verifikasi.');
    }

    // 3. Verifikasi Email oleh Mahasiswa
    public function verifyEmail(Request $request, $id)
    {
        // Mengecek apakah link valid dan belum expired
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

    // 4. (ADMIN ONLY) Dashboard List Pengajuan
    public function indexAdmin()
    {
        $data = SuratBebasLab::orderBy('created_at', 'desc')->get();
        return view('admin.bebas-lab.index', compact('data'));
    }

    // 5. (ADMIN ONLY) Update Status (Setujui/Tolak)
    public function updateStatus(Request $request, $id)
    {
        $pengajuan = SuratBebasLab::findOrFail($id);
        
        if ($request->action == 'setujui') {
            $pengajuan->update(['status' => 'disetujui']);
            // Logic Kirim Email Surat PDF di sini
        } else {
            $pengajuan->update([
                'status' => 'ditolak',
                'catatan_admin' => $request->catatan
            ]);
            // Logic Kirim Email Penolakan di sini
        }

        return back()->with('success', 'Status pengajuan berhasil diperbarui.');
    }
}