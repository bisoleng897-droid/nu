<?php

namespace App\Http\Controllers;

use App\Models\CalonAnggota;
use App\Models\News;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalNews = News::count();
        $latestNews = News::orderByDesc('published_at')->orderByDesc('created_at')->take(5)->get();
        $pendingCount = CalonAnggota::where('status', CalonAnggota::STATUS_PENDING)->count();
        $latestPending = CalonAnggota::where('status', CalonAnggota::STATUS_PENDING)
            ->orderByDesc('submitted_at')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('totalNews', 'latestNews', 'pendingCount', 'latestPending'));
    }
}
