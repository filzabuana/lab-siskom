<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use Illuminate\Http\Request;

class InventarisController extends Controller
{
    /**
     * Menampilkan daftar inventaris dengan filter dan pagination.
     * Terintegrasi dengan filter Kategori, Ruangan, dan Lokasi.
     */
    public function index(Request $request)
    {
        // 1. Ambil data unik untuk mengisi pilihan Dropdown di View
        // Menggunakan distinct() agar pilihan tidak duplikat
        $listKategori = Inventaris::distinct()->whereNotNull('kategori')->pluck('kategori');
        $listRuangan = Inventaris::distinct()->whereNotNull('ruangan')->pluck('ruangan');
        $listLokasi = Inventaris::distinct()->whereNotNull('catatan_lokasi')->pluck('catatan_lokasi');

        // 2. Tentukan limit data per halaman (default 10)
        $limit = $request->get('per_page', 10);

        // 3. Inisialisasi Query
        $query = Inventaris::query();

        // 4. Filter Pencarian (Nama Aset atau Kode Barang)
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_aset', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_barang', 'like', '%' . $request->search . '%');
            });
        }

        // 5. Filter Kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // 6. Filter Ruangan
        if ($request->filled('ruangan')) {
            $query->where('ruangan', $request->ruangan);
        }

        // 7. Filter Lokasi Spesifik (Berdasarkan catatan_lokasi)
        if ($request->filled('lokasi')) {
            $query->where('catatan_lokasi', $request->lokasi);
        }

        // 8. Eksekusi dengan Pagination & Urutkan Terbaru
        $semuaInventaris = $query->latest()->paginate($limit);

        // Menjaga agar parameter filter tetap ada di URL saat pindah halaman pagination
        $semuaInventaris->appends($request->all());

        // 9. Kirim semua data ke view
        return view('admin.inventaris.index', compact(
            'semuaInventaris', 
            'listKategori', 
            'listRuangan', 
            'listLokasi'
        ));
    }

    public function create()
    {
        return view('admin.inventaris.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang'     => 'required|unique:inventaris,kode_barang',
            'nama_aset'       => 'required|string|max:255',
            'foto_barang'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'kategori'        => 'required',
            'ruangan'         => 'required',
            'tahun_perolehan' => 'required|digits:4',
            'jumlah_stok'     => 'required|integer|min:0',
            'jumlah_rusak'    => 'required|integer|min:0',
            'kondisi'         => 'required',
            'tipe_peminjaman' => 'required',
            'deskripsi'       => 'nullable|string',
        ]);

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

        Inventaris::create($data);

        return redirect()->route('admin.inventaris.index')
                         ->with('success', 'Aset baru berhasil didaftarkan dengan data lengkap.');
    }

    public function show($id)
    {
        $item = Inventaris::findOrFail($id);
        $isKatalog = request()->is('katalog*');

        return view('admin.inventaris.show', compact('item', 'isKatalog'));
    }

    public function edit($id)
    {
        $item = Inventaris::findOrFail($id);
        return view('admin.inventaris.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Inventaris::findOrFail($id);

        $request->validate([
            'nama_aset'       => 'required|string|max:255',
            'foto_barang'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'tahun_perolehan' => 'required|digits:4',
            'jumlah_stok'     => 'required|integer|min:0',
            'jumlah_rusak'    => 'required|integer|min:0',
            'kondisi'         => 'required',
            'deskripsi'       => 'nullable|string',
        ]);

        $data = $request->all();

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
        $item = Inventaris::findOrFail($id);

        if ($item->foto_barang && file_exists(public_path('storage/inventaris/' . $item->foto_barang))) {
            unlink(public_path('storage/inventaris/' . $item->foto_barang));
        }

        $item->delete();

        return redirect()->route('admin.inventaris.index')
                         ->with('success', 'Aset berhasil dihapus secara permanen.');
    }

    public function katalog()
    {
        // Tetap menggunakan get() untuk katalog publik, menampilkan yang tidak rusak saja
        $inventaris = Inventaris::where('kondisi', '!=', 'Rusak')->get();

        return view('katalog.index', compact('inventaris'));
    }
}