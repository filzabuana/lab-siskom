<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; }
        .container { width: 80%; margin: 20px auto; border: 1px solid #eee; padding: 20px; border-radius: 10px; }
        .header { text-align: center; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        .content { padding: 20px 0; }
        .status-box { padding: 10px; border-radius: 5px; font-weight: bold; text-align: center; margin: 15px 0; }
        .approved { background-color: #d4edda; color: #155724; }
        .rejected { background-color: #f8d7da; color: #721c24; }
        .footer { font-size: 11px; color: #777; margin-top: 30px; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="color: #007bff;">Laboratorium Pemrograman & Komputasi</h2>
            <p>Program Studi Rekayasa Sistem Komputer - FMIPA UNTAN</p>
        </div>

        <div class="content">
            <p>Halo, <strong>{{ $pengajuan->nama }}</strong> ({{ $pengajuan->nim }}),</p>
            <p>Berikut adalah hasil verifikasi pengajuan Surat Bebas Laboratorium Anda:</p>

            @if($status == 'disetujui')
                <div class="status-box approved">
                    STATUS: DISETUJUI
                </div>
                <p>Selamat! Anda telah dinyatakan bebas dari tanggungan peminjaman alat di Laboratorium. Bapak/Ibu PLP telah memproses pengajuan Anda.</p>
                <p>Silakan gunakan email ini sebagai bukti verifikasi jika diperlukan untuk keperluan administrasi tugas akhir/yudisium.</p>
            @else
                <div class="status-box rejected">
                    STATUS: DITOLAK
                </div>
                <p>Mohon maaf, pengajuan Anda belum dapat disetujui karena alasan berikut:</p>
                <blockquote style="background: #f9f9f9; padding: 10px; border-left: 5px solid #ccc;">
                    <em>"{{ $catatan }}"</em>
                </blockquote>
                <p>Silakan selesaikan tanggungan tersebut dan lakukan pengajuan ulang melalui sistem.</p>
            @endif
        </div>

        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh sistem. Mohon tidak membalas email ini.</p>
            <p>&copy; {{ date('Y') }} Lab RSK Universitas Tanjungpura</p>
        </div>
    </div>
</body>
</html>