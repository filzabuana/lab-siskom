<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    
    @routes {{-- Tambahkan ini jika pakai Ziggy --}}
    @vite(['resources/css/app.css', 'resources/js/app-inertia.js'])
  </head>
  <body class="bg-slate-900">    
      @inertia
  </body>
</html>