<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use App\Models\InventarisItem;
use App\Models\PeminjamanDetail;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

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

        $query = Inventaris::query();

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
                // LOGIKA ADAPTIF UNTUK MENENTUKAN REKAP DATA STOK KELUAR
                if ($item->is_serialized) {
                    // Hitung jumlah record fisik berdasarkan status relasi tabel items
                    $stokKeluar = $item->items()->whereIn('status', ['dipinjam', 'dipakai_di_lab'])->count();
                } else {
                    // Untuk barang bulk, hitung manual akumulasi dari relasi detail peminjaman aktif (Disamakan lowercase)
                    $stokKeluar = (int) PeminjamanDetail::where('inventaris_id', $item->id)
                        ->whereHas('peminjaman', function($q) {
                            $q->whereIn(DB::raw('LOWER(status)'), ['dipinjam', 'sedang dipinjam', 'disetujui']);
                        })->sum('jumlah');
                }

                return [
                    'id'              => $item->id,
                    'kode_barang'     => $item->kode_barang,
                    'nama_aset'       => $item->nama_aset,
                    'kategori'        => $item->kategori,
                    'ruangan'         => $item->ruangan,
                    'is_serialized'   => $item->is_serialized,
                    'jumlah_stok'     => (int) $item->jumlah_stok,   // Nilai total kapasitas aset lab
                    'jumlah_rusak'    => (int) $item->jumlah_rusak, 
                    'stok_keluar'     => $stokKeluar,
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

    /**
     * Menyimpan atau menambahkan aset baru ke dalam database
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_barang'     => 'required|string|max:255',
            'nama_aset'       => 'required|string|max:255',
            'foto_barang'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'kategori'        => 'required',
            'ruangan'         => 'required',
            'tahun_perolehan' => 'required|digits:4',
            'jumlah_stok'     => 'required|integer|min:0',
            'jumlah_rusak'    => 'required|integer|min:0',
            'kondisi'         => 'required',
            'tipe_peminjaman' => 'required',
            'is_serialized'   => 'required|boolean',
        ]);

        DB::beginTransaction();

        try {
            $data = $request->except('foto_barang');

            if ($request->hasFile('foto_barang')) {
                $file = $request->file('foto_barang');
                $namaFile = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $file->move(public_path('storage/inventaris'), $namaFile);
                $data['foto_barang'] = $namaFile;
            }

            $inventaris = Inventaris::where('kode_barang', $request->kode_barang)->first();

            if ($inventaris) {
                if ($inventaris->is_serialized != $request->is_serialized) {
                    DB::rollBack();
                    return redirect()->back()->withErrors([
                        'kode_barang' => 'Kode barang ini sudah terdaftar dengan tipe pencatatan yang berbeda!'
                    ]);
                }

                // Jika tipenya BULK (0), akumulasikan ke total stok fisik tanpa memotong sisa rak
                if (!$request->is_serialized) {
                    $inventaris->increment('jumlah_stok', $request->jumlah_stok);
                    $inventaris->increment('jumlah_rusak', $request->jumlah_rusak);
                }
                
                $inventaris->update($request->only(['nama_aset', 'kategori', 'ruangan', 'kondisi', 'tipe_peminjaman', 'deskripsi', 'catatan_lokasi']));
            } else {
                $inventaris = Inventaris::create($data);
            }

            if ($request->is_serialized) {
                $lastItem = InventarisItem::where('inventaris_id', $inventaris->id)
                    ->orderBy('id', 'desc')
                    ->first();

                $startCounter = 1;
                
                if ($lastItem && preg_match('/_(\d+)$/', $lastItem->barcode_aset, $matches)) {
                    $startCounter = ((int) $matches[1]) + 1;
                }

                $totalLoop = $request->jumlah_stok + $request->jumlah_rusak;

                for ($i = 0; $i < $totalLoop; $i++) {
                    $currentIndex = $startCounter + $i;
                    $paddedIndex = str_pad($currentIndex, 3, '0', STR_PAD_LEFT);
                    $barcodeAset = $inventaris->kode_barang . '_' . $paddedIndex;
                    $statusUnit = ($i < $request->jumlah_stok) ? 'tersedia' : 'rusak';

                    InventarisItem::create([
                        'inventaris_id' => $inventaris->id,
                        'barcode_aset'  => $barcodeAset,
                        'status'        => $statusUnit,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.inventaris.index')->with('message', 'Aset berhasil diproses dan disinkronkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['kode_barang' => 'System Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Detail Inventaris untuk Admin/PLP (Inertia Vue)
     */
    public function show(Request $request, $id)
    {
        $item = Inventaris::findOrFail($id);

        if ($item->is_serialized) {
            $stokTersedia  = $item->items()->where('status', 'tersedia')->count();
            $totalDipinjam = $item->items()->whereIn('status', ['dipinjam', 'dipakai_di_lab'])->count();
            
            $item->load(['items.peminjamanDetails.peminjaman.user:id,name']);

            $item->setRelation('items', $item->items->map(function ($unit) {
                $detailAktif = $unit->peminjamanDetails->first(function ($detail) {
                    $statusPeminjaman = optional($detail->peminjaman)->status;
                    return $statusPeminjaman && in_array(strtolower($statusPeminjaman), [
                        'dipinjam', 'sedang dipinjam', 'disetujui', 'dipakai_di_lab'
                    ]);
                });

                $unit->peminjam_aktif = $detailAktif?->peminjaman?->user?->name ?? null;
                return $unit;
            }));

        } else {
            // UNTUK BULK: Hitung total kuantitas item yang sedang dipinjam secara real-time
            $totalDipinjam = (int) PeminjamanDetail::where('inventaris_id', $id)
                ->whereHas('peminjaman', function($q) {
                    $q->whereIn(DB::raw('LOWER(status)'), ['dipinjam', 'sedang dipinjam', 'disetujui']);
                })->sum('jumlah');

            $stokTersedia = max(0, $item->jumlah_stok - $totalDipinjam);
        }

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
            'is_serialized'   => 'required|boolean',
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

        // Jika barang serialized, kuantitas total dikunci agar tidak merusak relasi item fisik
        if ($request->is_serialized) {
            unset($data['jumlah_stok']);
            unset($data['jumlah_rusak']);
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

        return redirect()->route('admin.inventaris.index')->with('message', 'Aset berhasil dihapus.');
    }

    /**
     * Menampilkan daftar katalog alat untuk Publik/Umum (Blade)
     */
    public function katalogPublicIndex(Request $request)
    {
        $query = Inventaris::query()->where('kondisi', '!=', 'Rusak Berat'); 

        if ($request->filled('search')) {
            $query->where('nama_aset', 'like', '%' . $request->search . '%');
        }

        $inventaris = $query->latest()->get()->map(function ($item) {
            if ($item->is_serialized) {
                $item->sisa_stok = $item->items()->where('status', 'tersedia')->count();
            } else {
                $stokKeluar = (int) PeminjamanDetail::where('inventaris_id', $item->id)
                    ->whereHas('peminjaman', function($q) {
                        $q->whereIn(DB::raw('LOWER(status)'), ['dipinjam', 'sedang dipinjam', 'disetujui']);
                    })->sum('jumlah');
                $item->sisa_stok = max(0, $item->jumlah_stok - $stokKeluar);
            }
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

        if ($item->is_serialized) {
            $stokTersedia  = $item->items()->where('status', 'tersedia')->count();
            $totalDipinjam = $item->items()->whereIn('status', ['dipinjam', 'dipakai_di_lab'])->count();
        } else {
            $totalDipinjam = (int) PeminjamanDetail::where('inventaris_id', $id)
                ->whereHas('peminjaman', function($q) {
                    $q->whereIn(DB::raw('LOWER(status)'), ['dipinjam', 'sedang dipinjam', 'disetujui']);
                })->sum('jumlah');

            $stokTersedia = max(0, $item->jumlah_stok - $totalDipinjam);
        }

        return view('katalog.show', compact('item', 'stokTersedia', 'totalDipinjam'));
    }
}