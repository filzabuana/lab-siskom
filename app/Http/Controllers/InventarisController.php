<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\File;

class InventarisController extends Controller
{
    /**
     * Menampilkan daftar inventaris untuk Admin (Inertia)
     */
    public function index(Request $request)
    {
        $listKategori = Inventaris::distinct()->whereNotNull('kategori')->pluck('kategori');
        $listRuangan = Inventaris::distinct()->whereNotNull('ruangan')->pluck('ruangan');
        $listLokasi = Inventaris::distinct()->whereNotNull('catatan_lokasi')->pluck('catatan_lokasi');

        $query = Inventaris::with('peminjaman');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_aset', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_barang', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('kategori')) $query->where('kategori', $request->kategori);
        if ($request->filled('ruangan')) $query->where('ruangan', $request->ruangan);
        if ($request->filled('lokasi')) $query->where('catatan_lokasi', $request->lokasi);

        $semuaInventaris = $query->latest()
            ->paginate($request->get('per_page', 10))
            ->withQueryString()
            ->through(function ($item) {
                $stokKeluar = $item->peminjaman
                    ->whereIn('status', ['dipinjam', 'sedang dipinjam', 'disetujui'])
                    ->sum('jumlah_pinjam');

                return [
                    'id'              => $item->id,
                    'kode_barang'     => $item->kode_barang,
                    'nama_aset'       => $item->nama_aset,
                    'kategori'        => $item->kategori,
                    'ruangan'         => $item->ruangan,
                    'jumlah_stok'     => $item->jumlah_stok,
                    'jumlah_rusak'    => $item->jumlah_rusak,
                    'sisa_stok'       => max(0, $item->jumlah_stok - $stokKeluar), 
                    'kondisi'         => $item->kondisi,
                    'foto_barang'     => $item->foto_barang,
                    'tipe_peminjaman' => $item->tipe_peminjaman,
                    'catatan_lokasi'  => $item->catatan_lokasi,
                ];
            });

        return Inertia::render('Admin/Inventaris/Index', [
            'semuaInventaris' => $semuaInventaris,
            'filters'         => $request->only(['search', 'kategori', 'ruangan', 'lokasi']),
            'listKategori'    => $listKategori,
            'listRuangan'     => $listRuangan,
            'listLokasi'      => $listLokasi
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Inventaris/Create');
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
        ]);

        $data = $request->except('foto_barang');

        if ($request->hasFile('foto_barang')) {
            $file = $request->file('foto_barang');
            $namaFile = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move(public_path('storage/inventaris'), $namaFile);
            $data['foto_barang'] = $namaFile;
        }

        Inventaris::create($data);

        return redirect()->route('admin.inventaris.index')
                         ->with('message', 'Aset baru berhasil didaftarkan.');
    }

    public function show(Request $request, $id)
    {
        $item = Inventaris::with('peminjaman')->findOrFail($id);

        $totalDipinjam = (int) $item->peminjaman
            ->whereIn('status', ['dipinjam', 'sedang dipinjam', 'disetujui'])
            ->sum('jumlah_pinjam');

        $stokTersedia = max(0, $item->jumlah_stok - $totalDipinjam);
        $isKatalog = $request->routeIs('katalog.*');

        if ($request->wantsJson() || $request->header('X-Inertia')) {
            return Inertia::render('Admin/Inventaris/Show', [
                'item'          => $item,
                'stok_tersedia' => $stokTersedia,
                'totalDipinjam' => $totalDipinjam,
                'isKatalog'     => $isKatalog
            ]);
        }

        return view('admin.inventaris.show', compact('item', 'isKatalog', 'stokTersedia', 'totalDipinjam'));
    }

    public function edit($id)
    {
        // PENTING: Menggunakan key 'inventaris' agar sesuai dengan props di Edit.vue
        $inventaris = Inventaris::findOrFail($id);
        return Inertia::render('Admin/Inventaris/Edit', [
            'inventaris' => $inventaris
        ]);
    }

    public function update(Request $request, $id)
    {
        $item = Inventaris::findOrFail($id);

        $request->validate([
            // Validasi unique kode_barang diabaikan untuk ID yang sedang diedit
            'kode_barang'     => 'required|unique:inventaris,kode_barang,' . $id,
            'nama_aset'       => 'required|string|max:255',
            'foto_barang'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'tahun_perolehan' => 'required|digits:4',
            'jumlah_stok'     => 'required|integer|min:0',
            'jumlah_rusak'    => 'required|integer|min:0',
            'kondisi'         => 'required',
            'kategori'        => 'required',
            'ruangan'         => 'required',
            'tipe_peminjaman' => 'required',
        ]);

        // Buang foto_barang dan _method agar tidak masuk ke query update
        $data = $request->except(['foto_barang', '_method']);

        if ($request->hasFile('foto_barang')) {
            // Hapus file lama jika ada
            if ($item->foto_barang && File::exists(public_path('storage/inventaris/' . $item->foto_barang))) {
                File::delete(public_path('storage/inventaris/' . $item->foto_barang));
            }
            
            $file = $request->file('foto_barang');
            $namaFile = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move(public_path('storage/inventaris'), $namaFile);
            $data['foto_barang'] = $namaFile;
        }

        $item->update($data);

        return redirect()->route('admin.inventaris.index')->with('message', 'Data aset berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = Inventaris::findOrFail($id);

        if ($item->foto_barang && File::exists(public_path('storage/inventaris/' . $item->foto_barang))) {
            File::delete(public_path('storage/inventaris/' . $item->foto_barang));
        }

        $item->delete();

        return redirect()->route('admin.inventaris.index')
                         ->with('message', 'Aset berhasil dihapus.');
    }

    public function katalog()
    {
        $inventaris = Inventaris::with('peminjaman')
            ->where('kondisi', '!=', 'Rusak Berat')
            ->where('tipe_peminjaman', 'Bisa Dipinjam')
            ->get()
            ->map(function ($item) {
                $stokKeluar = $item->peminjaman
                    ->whereIn('status', ['dipinjam', 'sedang dipinjam', 'disetujui'])
                    ->sum('jumlah_pinjam');
                
                $item->sisa_stok = max(0, $item->jumlah_stok - $stokKeluar);
                return $item;
            });

        return Inertia::render('Katalog/Index', ['inventaris' => $inventaris]);
    }
}