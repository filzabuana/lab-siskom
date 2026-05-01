<?php

namespace App\Http\Controllers;

use App\Models\Sop;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class SopController extends Controller
{
    public function index()
    {
        $semuaSop = Sop::all();
        return view('sop.index', compact('semuaSop'));
    }

    public function show($slug)
    {
        $sop = Sop::where('slug', $slug)->firstOrFail();
        return view('sop.show', compact('sop'));
    }

    public function create()
    {
        return view('sop.create');
    }

    public function store(Request $request)
    {
        // 1. Validasi input
        $request->validate([
    'judul' => 'required',
    'kategori' => 'required',
    'deskripsi' => 'required',
    'file_pdf' => 'required|mimes:pdf|max:2048',
    'alur_judul' => 'required|array|min:1', // Pastikan minimal ada 1 judul
    'alur_kode' => 'required|array|min:1',  // Pastikan minimal ada 1 kode
    'kategori_input_manual' => 'required_if:kategori,Lainnya',
    ]);

        // 2. Logika Penentuan Kategori
        $kategoriFinal = ($request->kategori === 'Lainnya') ? $request->kategori_input_manual : $request->kategori;

        // 3. Mengolah file PDF
        $pdfFile = $request->file('file_pdf');
        $pdfName = time() . '_' . $pdfFile->getClientOriginalName();
        $pdfFile->move(public_path('dokumen-sop'), $pdfName);

        // 4. Menggabungkan Banyak Alur menjadi JSON
        $dataAlur = [];
        if ($request->has('alur_judul')) {
            foreach ($request->alur_judul as $index => $judul) {
                $dataAlur[] = [
                    'judul' => $judul,
                    'kode'  => $request->alur_kode[$index]
                ];
            }
        }
        $alurJson = json_encode($dataAlur);

        // 5. Simpan ke Database
        Sop::create([
            'judul' => $request->judul,
            'slug' => Str::slug($request->judul),
            'kategori' => $kategoriFinal,
            'deskripsi' => $request->deskripsi,
            'file_pdf' => $pdfName,
            'gambar_alur' => $alurJson, // Tersimpan sebagai teks JSON
        ]);

        return redirect()->route('sop.index')->with('success', 'SOP dengan multi-alur berhasil disimpan!');
    }

    public function destroy($id)
    {
        $sop = Sop::findOrFail($id);

        // 1. Hapus file PDF dari folder public
        if (File::exists(public_path('dokumen-sop/' . $sop->file_pdf))) {
            File::delete(public_path('dokumen-sop/' . $sop->file_pdf));
        }

        // 2. Logika hapus file gambar (jika ada file lama yang bukan JSON)
        // Karena sekarang kita pakai JSON, kita cek dulu apakah isinya file atau bukan
        if (!Str::contains($sop->gambar_alur, ['{', '['])) {
            if (File::exists(public_path('alur-sop/' . $sop->gambar_alur))) {
                File::delete(public_path('alur-sop/' . $sop->gambar_alur));
            }
        }

        $sop->delete();

        return redirect()->route('sop.index')->with('success', 'SOP berhasil dihapus!');
    }

    public function edit($id)
{
    $sop = Sop::findOrFail($id);
    // Decode JSON alur agar bisa di-looping di view
    $listAlur = json_decode($sop->gambar_alur, true) ?? [];
    return view('sop.edit', compact('sop', 'listAlur'));
}

public function update(Request $request, $id)
{
    $sop = Sop::findOrFail($id);

    $request->validate([
        'judul' => 'required',
        'kategori' => 'required',
        'deskripsi' => 'required',
        'file_pdf' => 'nullable|mimes:pdf|max:2048', // optional saat edit
        'alur_judul' => 'required|array',
        'alur_kode' => 'required|array',
    ]);

    // 1. Update File PDF jika ada upload baru
    if ($request->hasFile('file_pdf')) {
        // Hapus file lama
        if (File::exists(public_path('dokumen-sop/' . $sop->file_pdf))) {
            File::delete(public_path('dokumen-sop/' . $sop->file_pdf));
        }
        $pdfFile = $request->file('file_pdf');
        $pdfName = time() . '_' . $pdfFile->getClientOriginalName();
        $pdfFile->move(public_path('dokumen-sop'), $pdfName);
        $sop->file_pdf = $pdfName;
    }

    // 2. Olah ulang JSON Alur
    $dataAlur = [];
    foreach ($request->alur_judul as $index => $judul) {
        $dataAlur[] = [
            'judul' => $judul,
            'kode'  => $request->alur_kode[$index]
        ];
    }

    // 3. Update data lainnya
    $sop->update([
        'judul' => $request->judul,
        'slug' => Str::slug($request->judul),
        'kategori' => $request->kategori,
        'deskripsi' => $request->deskripsi,
        'gambar_alur' => json_encode($dataAlur),
    ]);

    return redirect()->route('sop.index')->with('success', 'SOP berhasil diperbarui!');
}
}