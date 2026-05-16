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
        
        // Karena sudah di-cast di Model, ini sudah otomatis jadi array PHP
        $flowchartsData = $sop->flowchart_data ?? [];

        return view('sop.show', compact('sop', 'flowchartsData'));
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
            'flowchart_data' => 'required', 
            'kategori_input_manual' => 'required_if:kategori,Lainnya',
        ]);

        // 2. Logika Penentuan Kategori
        $kategoriFinal = ($request->kategori === 'Lainnya') ? $request->kategori_input_manual : $request->kategori;

        // 3. Mengolah file PDF
        $pdfFile = $request->file('file_pdf');
        $pdfName = time() . '_' . $pdfFile->getClientOriginalName();
        $pdfFile->move(public_path('dokumen-sop'), $pdfName);

        // 4. Simpan ke Database
        Sop::create([
            'judul' => $request->judul,
            'slug' => Str::slug($request->judul),
            'kategori' => $kategoriFinal,
            'deskripsi' => $request->deskripsi,
            'file_pdf' => $pdfName,
            // Penting: decode JSON string dari input hidden menjadi array PHP
            'flowchart_data' => json_decode($request->flowchart_data, true),
        ]);

        return redirect()->route('sop.index')->with('success', 'SOP berhasil disimpan!');
    }

    public function edit($id)
    {
        $sop = Sop::findOrFail($id);
        return view('sop.edit', compact('sop'));
    }

    public function update(Request $request, $id)
    {
        $sop = Sop::findOrFail($id);

        $request->validate([
            'judul' => 'required',
            'kategori' => 'required',
            'deskripsi' => 'required',
            'file_pdf' => 'nullable|mimes:pdf|max:2048',
            'flowchart_data' => 'nullable', // Gunakan nama yang konsisten tanpa 's'
        ]);

        // 1. Update File PDF jika ada upload baru
        if ($request->hasFile('file_pdf')) {
            if (File::exists(public_path('dokumen-sop/' . $sop->file_pdf))) {
                File::delete(public_path('dokumen-sop/' . $sop->file_pdf));
            }
            $pdfFile = $request->file('file_pdf');
            $pdfName = time() . '_' . $pdfFile->getClientOriginalName();
            $pdfFile->move(public_path('dokumen-sop'), $pdfName);
            $sop->file_pdf = $pdfName;
        }

        // 2. Logika Penentuan Kategori
        $kategoriFinal = ($request->kategori === 'Lainnya') ? $request->kategori_input_manual : $request->kategori;

        // 3. Update data lainnya
        $sop->update([
            'judul' => $request->judul,
            'slug' => Str::slug($request->judul),
            'kategori' => $kategoriFinal,
            'deskripsi' => $request->deskripsi,
            'flowchart_data' => $request->flowchart_data ? json_decode($request->flowchart_data, true) : $sop->flowchart_data
        ]);

        return redirect()->route('sop.index')->with('success', 'SOP berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $sop = Sop::findOrFail($id);

        if (File::exists(public_path('dokumen-sop/' . $sop->file_pdf))) {
            File::delete(public_path('dokumen-sop/' . $sop->file_pdf));
        }

        $sop->delete();
        return redirect()->route('sop.index')->with('success', 'SOP berhasil dihapus!');
    }
}