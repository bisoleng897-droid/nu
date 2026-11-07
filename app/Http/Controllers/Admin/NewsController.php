<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::orderByDesc('published_at')->orderByDesc('created_at')->paginate(10);

        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        if (empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $validated['slug'] = Str::slug($validated['title']).'-'.time();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('news', 'public');
        }

        $validated = $this->applyCoordinates($validated);

        News::create($validated);

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    public function show(News $news)
    {
        return view('admin.news.show', compact('news'));
    }

    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $validated = $request->validate($this->rules());

        if ($validated['title'] !== $news->title) {
            $validated['slug'] = Str::slug($validated['title']).'-'.time();
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('news', 'public');
        }

        $validated = $this->applyCoordinates($validated);

        $news->update($validated);

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(News $news)
    {
        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil dihapus.');
    }

    /**
     * Aturan validasi form berita (dipakai saat membuat dan mengubah).
     *
     * @return array<string, array<int, string>>
     */
    private function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'content' => 'required',
            'alamat' => 'nullable|string|max:500',
            'lat' => 'nullable|numeric|between:-90,90',
            'lng' => 'nullable|numeric|between:-180,180',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'published_at' => 'nullable|date',
        ];
    }

    /**
     * Buang koordinat peta bila admin mengosongkan alamat, supaya tidak ada
     * pin yang nyasar tanpa teks alamat pendukungnya.
     *
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function applyCoordinates(array $validated): array
    {
        $hasAddress = filled($validated['alamat'] ?? null)
            && filled($validated['lat'] ?? null)
            && filled($validated['lng'] ?? null);

        $validated['lat'] = $hasAddress ? $validated['lat'] : null;
        $validated['lng'] = $hasAddress ? $validated['lng'] : null;

        return $validated;
    }
}
