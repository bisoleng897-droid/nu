<?php

namespace App\Http\Controllers;

use App\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        $query = News::query()
            ->orderByDesc('created_at');

        if (request('q')) {
            $query->where('title', 'like', '%'.request('q').'%');
        }

        $allNews = $query->paginate(9)->withQueryString();

        return view('news.index', compact('allNews'));
    }

    public function show(News $news)
    {
        return view('news.show', compact('news'));
    }
}
