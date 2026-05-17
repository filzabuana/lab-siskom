<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Inventaris;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia; // Wajib di-import untuk merender Vue 3
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    /**
     * =========================================================================
     * --- BAGIAN PUBLIK (TETAP MENGGUNAKAN BLADE) ---
     * =========================================================================
     */

    public function welcome()
    {
        $inventaris = Inventaris::where('tipe_peminjaman', 'Bisa Dipinjam')->take(4)->get();
        
        // Tarik postingan yang di-pin dulu, baru postingan terbaru lainnya
        $latestPosts = Post::where('is_published', true)
            ->orderBy('is_pinned', 'desc')
            ->latest()
            ->take(3)
            ->get();
        
        return view('welcome', compact('inventaris', 'latestPosts'));
    }

    public function index()
    {
        // Eager loading relasi 'user' agar bisa menampilkan nama penulis tanpa query berulang (N+1 Problem)
        $posts = Post::with('user')
            ->where('is_published', true)
            ->orderBy('is_pinned', 'desc')
            ->latest()
            ->paginate(9);

        return view('blog.index', compact('posts'));
    }

    public function show($slug)
    {
        $post = Post::with('user')->where('slug', $slug)->where('is_published', true)->firstOrFail();
        
        // Otomatis tambah hitungan view setiap kali artikel dibaca publik
        $post->increment('views_count');

        return view('blog.show', compact('post'));
    }

    /**
     * =========================================================================
     * --- BAGIAN ADMIN (MERKURI/INERTIA SYSTEM) ---
     * =========================================================================
     */

    // Halaman Panduan Menulis di Admin
    public function guide()
    {
        return Inertia::render('Admin/Posts/Guide');
    }

    // List Postingan Sisi Admin
    public function indexAdmin()
    {
        // Menyertakan data user penulis untuk ditampilkan di dashboard admin jika perlu
        $posts = Post::with('user')->latest()->paginate(10);
        
        return Inertia::render('Admin/Posts/Index', [
            'posts' => $posts
        ]);
    }

    // Form Tambah Artikel
    public function create()
    {
        return Inertia::render('Admin/Posts/Create', [
            'categories' => Post::CATEGORIES // Kirim daftar kategori baku ke Vue untuk isi dropdown
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'category' => ['required', Rule::in(Post::CATEGORIES)], // Validasi ketat sesuai enum model
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_published' => 'boolean',
            'is_pinned' => 'boolean',
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('blog', 'public');
        }

        Post::create([
            'user_id' => auth()->id(), // Otomatis mencatat siapa admin yang login
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'category' => $request->category,
            'image' => $path,
            'is_published' => $request->is_published ?? false,
            'is_pinned' => $request->is_pinned ?? false,
            'views_count' => 0,
        ]);

        return redirect()->route('admin.posts.index')->with('success', 'Artikel berhasil diterbitkan!');
    }

    // Form Edit Artikel
    public function edit($id)
    {
        $post = Post::findOrFail($id);

        return Inertia::render('Admin/Posts/Edit', [
            'post' => $post,
            'categories' => Post::CATEGORIES // Dropdown kategori tetap dikirim saat edit
        ]);
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'category' => ['required', Rule::in(Post::CATEGORIES)],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_published' => 'boolean',
            'is_pinned' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $post->image = $request->file('image')->store('blog', 'public');
        }

        $post->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'category' => $request->category,
            'image' => $post->image,
            'is_published' => $request->is_published ?? false,
            'is_pinned' => $request->is_pinned ?? false,
        ]);

        return redirect()->route('admin.posts.index')->with('success', 'Artikel berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Artikel telah dihapus.');
    }
}