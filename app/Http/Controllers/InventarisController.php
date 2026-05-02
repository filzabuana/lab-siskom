<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use Illuminate\Http\Request;

class InventarisController extends Controller
{
    public function index()
    {
        // Mengambil semua data inventaris, urutkan dari yang terbaru
        $semuaInventaris = Inventaris::latest()->get();
        
        return view('admin.inventaris.index', compact('semuaInventaris'));
    }

    public function create()
    {
        return view('admin.inventaris.create');
    }

    public function store(Request $request)
{
    // 1. Validasi Input
    $request->validate([
        'kode_barang'     => 'required|unique:inventaris,kode_barang',
        'nama_aset'       => 'required|string|max:255',
        'foto_barang'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'kategori'        => 'required',
        'ruangan'         => 'required',
        'tahun_perolehan' => 'required|digits:4',
        'jumlah_stok'     => 'required|integer|min:0',
        'jumlah_rusak'    => 'required|integer|min:0', // Tambahkan ini
        'kondisi'         => 'required',
        'tipe_peminjaman' => 'required',
        'deskripsi'       => 'nullable|string',        // Tambahkan ini
    ]);

    // ... (logika upload foto tetap sama seperti kode Bapak) ...
    $data = $request->all();
    
    if ($request->hasFile('foto_barang')) {
        $file = $request->file('foto_barang');
        $namaFile = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        $tujuanFolder = public_path('storage/inventaris');
        if (!file_exists($tujuanFolder)) {
            mkdir($tujuanFolder, 0755, true);
        }
        $file->move($tujuanFolder, $namaFile);
        $data['foto_barang'] = $namaFile;
    }

    \App\Models\Inventaris::create($data);

    return redirect()->route('admin.inventaris.index')
                     ->with('success', 'Aset baru berhasil didaftarkan dengan data lengkap.');
}
    public function show($id)
{
    $item = Inventaris::findOrFail($id);
    
    // Logika untuk menentukan apakah ini akses dari katalog atau admin
    $isKatalog = request()->is('katalog*');

    return view('admin.inventaris.show', compact('item', 'isKatalog'));
}

    // Menampilkan form edit
public function edit($id)
{
    $item = Inventaris::findOrFail($id);
    return view('admin.inventaris.edit', compact('item'));
}

// Menyimpan perubahan data
public function update(Request $request, $id)
{
    $item = Inventaris::findOrFail($id);

    $request->validate([
        'nama_aset'       => 'required|string|max:255',
        'foto_barang'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'tahun_perolehan' => 'required|digits:4',
        'jumlah_stok'     => 'required|integer|min:0',
        'jumlah_rusak'    => 'required|integer|min:0', // Tambahkan ini
        'kondisi'         => 'required',
        'deskripsi'       => 'nullable|string',        // Tambahkan ini
    ]);

    $data = $request->all();

    // ... (logika update foto tetap sama seperti kode Bapak) ...
    if ($request->hasFile('foto_barang')) {
        if ($item->foto_barang && file_exists(public_path('storage/inventaris/' . $item->foto_barang))) {
            unlink(public_path('storage/inventaris/' . $item->foto_barang));
        }
        $file = $request->file('foto_barang');
        $namaFile = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        $file->move(public_path('storage/inventaris'), $namaFile);
        $data['foto_barang'] = $namaFile;
    }

    $item->update($data);

    return redirect()->route('admin.inventaris.index')->with('success', 'Data aset berhasil diperbarui.');
}
public function destroy($id)
{
    $item = \App\Models\Inventaris::findOrFail($id);

    // Hapus foto fisik jika ada sebelum data di database dihapus
    if ($item->foto_barang && file_exists(public_path('storage/inventaris/' . $item->foto_barang))) {
        unlink(public_path('storage/inventaris/' . $item->foto_barang));
    }

    $item->delete();

    return redirect()->route('admin.inventaris.index')
                     ->with('success', 'Aset berhasil dihapus secara permanen.');
}
    public function katalog()
    {
        // Mengambil semua alat kecuali yang kondisinya 'Rusak'
        $inventaris = Inventaris::where('kondisi', '!=', 'Rusak')->get();

        return view('katalog.index', compact('inventaris'));
    }
}