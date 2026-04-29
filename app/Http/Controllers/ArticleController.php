<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.konten.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:Artikel,Berita,Acara',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $article = new Article();
        $article->title = $validated['title'];
        $article->slug = Str::slug($validated['title']) . '-' . time();
        $article->category = $validated['category'];
        $article->content = $validated['content'];
        $article->author_id = Auth::id();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('articles', 'public');
            $article->image_path = $path;
        }

        $article->save();

        return redirect()->route('admin.dashboard', ['tab' => 'konten'])->with('success', 'Konten berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $article = Article::findOrFail($id);
        return view('admin.konten.form', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:Artikel,Berita,Acara',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $article->title = $validated['title'];
        // Jangan ubah slug agar link SEO tidak mati, kecuali mau diubah
        $article->category = $validated['category'];
        $article->content = $validated['content'];

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($article->image_path && Storage::disk('public')->exists($article->image_path)) {
                Storage::disk('public')->delete($article->image_path);
            }
            
            $path = $request->file('image')->store('articles', 'public');
            $article->image_path = $path;
        }

        $article->save();

        return redirect()->route('admin.dashboard', ['tab' => 'konten'])->with('success', 'Konten berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        
        if ($article->image_path && Storage::disk('public')->exists($article->image_path)) {
            Storage::disk('public')->delete($article->image_path);
        }
        
        $article->delete();

        return redirect()->route('admin.dashboard', ['tab' => 'konten'])->with('success', 'Konten berhasil dihapus!');
    }
}
