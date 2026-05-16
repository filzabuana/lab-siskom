<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use App\Models\PeminjamanDetail;
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

        // Optimasi: Hitung jumlah stok yang keluar lewat tabel detail dalam satu query
        $query = Inventaris::query()
            ->withSum(['peminjamanDetails as stok_keluar' => function($q) {
                $q->whereHas('peminjaman', function($p) {
                    $p->whereIn('status', ['Dipinjam', 'Sedang Dipinjam', 'Disetujui']);
                });
            }], 'jumlah'); // menjumlahkan kolom 'jumlah' di tabel detail

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
                return [
                    'id'              => $item->id,
                    'kode_barang'     => $item->kode_barang,
                    'nama_aset'       => $item->nama_aset,
                    'kategori'        => $item->kategori,
                    'ruangan'         => $item->ruangan,
                    'jumlah_stok'     => (int) $item->jumlah_stok,
                    'jumlah_rusak'    => (int) $item->jumlah_rusak,
                    'stok_keluar'     => (int) ($item->stok_keluar ?? 0),
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

    /**
     * Detail Inventaris Khusus untuk Sisi Admin/PLP (Inertia)
     */
    public function show(Request $request, $id)
    {
        $item = Inventaris::findOrFail($id);

        $totalDipinjam = (int) PeminjamanDetail::where('inventaris_id', $id)
            ->whereHas('peminjaman', function($q) {
                $q->whereIn('status', ['Dipinjam', 'Sedang Dipinjam', 'Disetujui']);
            })
            ->sum('jumlah');

        $stokTersedia = max(0, $item->jumlah_stok - $totalDipinjam);

        return Inertia::render('Admin/Inventaris/Show', [
            'item'          => $item,
            'stok_tersedia' => $stokTersedia,
            'totalDipinjam' => $totalDipinjam,
            'isKatalog'     => false
        ]);
    }

    public function edit($id)
    {
        $inventaris = Inventaris::findOrFail($id);
        return Inertia::render('Admin/Inventaris/Edit', [
            'inventaris' => $inventaris
        ]);
    }

    public function update(Request $request, $id)
    {
        $item = Inventaris::findOrFail($id);

        $request->validate([
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

        $data = $request->except(['foto_barang', '_method']);

        if ($request->hasFile('foto_barang')) {
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

    /*
    |--------------------------------------------------------------------------
    | MIGRATION PHASE: PUBLIC KATALOG ROUTING (BLADE VIEW ONLY)
    |--------------------------------------------------------------------------
    |*/

    /**
     * Menampilkan daftar katalog alat untuk Publik/Umum (Blade)
     */
    public function katalogPublicIndex(Request $request)
    {
        $query = Inventaris::query()
            ->withSum(['peminjamanDetails as stok_keluar' => function($q) {
                $q->whereHas('peminjaman', function($p) {
                    $p->whereIn('status', ['Dipinjam', 'Sedang Dipinjam', 'Disetujui']);
                });
            }], 'jumlah')
            ->where('kondisi', '!=', 'Rusak Berat'); 

        if ($request->filled('search')) {
            $query->where('nama_aset', 'like', '%' . $request->search . '%');
        }

        $inventaris = $query->latest()->get()->map(function ($item) {
            $item->sisa_stok = max(0, $item->jumlah_stok - ($item->stok_keluar ?? 0));
            return $item;
        });

        return view('katalog.index', compact('inventaris'));
    }

    /**
     * Menampilkan detail item tunggal untuk Publik/Umum (Blade)
     */
    public function katalogPublicShow($id)
    {
        $item = Inventaris::findOrFail($id);

        $totalDipinjam = (int) PeminjamanDetail::where('inventaris_id', $id)
            ->whereHas('peminjaman', function($q) {
                $q->whereIn('status', ['Dipinjam', 'Sedang Dipinjam', 'Disetujui']);
            })
            ->sum('jumlah');

        $stokTersedia = max(0, $item->jumlah_stok - $totalDipinjam);

        return view('katalog.show', compact('item', 'stokTersedia', 'totalDipinjam'));
    }
}