<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Inventaris;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * --- BAGIAN PUBLIK ---
     */

    public function welcome()
    {
        $inventaris = Inventaris::where('tipe_peminjaman', 'Bisa Dipinjam')->take(4)->get();
        $latestPosts = Post::where('is_published', true)->latest()->take(3)->get();
        
        return view('welcome', compact('inventaris', 'latestPosts'));
    }

    public function index()
    {
        $posts = Post::where('is_published', true)->latest()->paginate(9);
        return view('blog.index', compact('posts'));
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)->where('is_published', true)->firstOrFail();
        return view('blog.show', compact('post'));
    }

    /**
     * --- BAGIAN ADMIN ---
     */

    // Fungsi Panduan (Pastikan ini bisa diakses)
    public function guide()
    {
        return view('admin.posts.guide');
    }

    public function indexAdmin()
    {
        $posts = Post::latest()->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048' // Tambah webp sesuai panduan
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('blog', 'public');
        }

        Post::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'image' => $path,
            'is_published' => $request->has('is_published'),
        ]);

        return redirect()->route('admin.posts.index')->with('success', 'Artikel berhasil diterbitkan!');
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        // Logika update image yang lebih aman
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
            'is_published' => $request->has('is_published'),
            'image' => $post->image, // Pastikan image terbaru tersimpan
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