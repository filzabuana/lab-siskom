@component('mail::message')
# Verifikasi Email Pengajuan

Halo **{{ $pengajuan->nama }}**,

Terima kasih telah mengajukan Surat Bebas Laboratorium di Lab Komputasi & Pemrograman. 
Satu langkah lagi, silakan klik tombol di bawah untuk memverifikasi email Anda:

@component('mail::button', ['url' => $url])
Verifikasi Email
@endcomponent

*Link ini akan kadaluwarsa dalam 24 jam.*

Jika Anda tidak merasa melakukan pengajuan ini, abaikan email ini.

Salam,<br>
Admin Lab SISKOM Untan
@endcomponent