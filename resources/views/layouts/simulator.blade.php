<!DOCTYPE html>
<html lang="id" class="dark"> {{-- Tambahkan class dark agar Tailwind konsisten --}}
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Lab Komputasi - Simulator' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app-blade.js'])
    <style>
        /* Reset dasar untuk memastikan canvas benar-benar memenuhi layar */
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            width: 100%;
        }
        /* Hilangkan scrollbar default jika ingin tampilan aplikasi yang sleek */
        ::-webkit-scrollbar {
            width: 0px;
        }
    </style>
</head>
<body class="bg-[#020617] antialiased"> {{-- Gunakan warna yang sama persis dengan bg simulator --}}
    <div id="app-site" class="min-h-screen"> {{-- Pastikan div utama punya tinggi minimal layar --}}
        @yield('content')
    </div>
</body>
</html>