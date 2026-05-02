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
        'foto_barang'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Maks 2MB
        'kategori'        => 'required',
        'ruangan'         => 'required',
        'tahun_perolehan' => 'required|digits:4',
        'jumlah_stok'     => 'required|integer|min:0',
        'kondisi'         => 'required',
        'tipe_peminjaman' => 'required',
    ]);

    // 2. Ambil semua data input
    $data = $request->all();

    // 3. Logika Upload Foto
    if ($request->hasFile('foto_barang')) {
        $file = $request->file('foto_barang');
        
        // Buat nama file unik: timestamp + nama asli (tanpa spasi)
        $namaFile = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        
        // Pastikan folder tujuan ada di public/storage/inventaris
        $tujuanFolder = public_path('storage/inventaris');
        
        // Buat folder jika belum ada
        if (!file_exists($tujuanFolder)) {
            mkdir($tujuanFolder, 0755, true);
        }

        // Pindahkan file
        $file->move($tujuanFolder, $namaFile);
        
        // Simpan nama file ke array data untuk database
        $data['foto_barang'] = $namaFile;
    }

    // 4. Simpan ke Database
    \App\Models\Inventaris::create($data);

    // 5. Redirect dengan pesan sukses
    return redirect()->route('admin.inventaris.index')
                     ->with('success', 'Aset baru berhasil didaftarkan dengan foto.');
}
    public function show($id)
    {
        $item = Inventaris::findOrFail($id);
        return view('admin.inventaris.show', compact('item'));
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
        'kondisi'         => 'required',
    ]);

    $data = $request->all();

    // Logika Update Foto
    if ($request->hasFile('foto_barang')) {
        // Hapus foto lama jika ada
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