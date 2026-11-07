<?php

use App\Http\Controllers\Admin\CalonAnggotaController as AdminCalonAnggotaController;
use App\Http\Controllers\Admin\DokumentasiController;
use App\Http\Controllers\Admin\InfaqController as AdminInfaqController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\PengurusController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalonAnggotaController;
use App\Http\Controllers\InfaqController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\StrukturController;
use App\Models\Dokumentasi;
use App\Models\InfaqHistory;
use App\Models\InfaqSetting;
use App\Models\News;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $news = News::orderByDesc('published_at')->orderByDesc('created_at')->take(5)->get();
    $dokumentasi = Dokumentasi::orderByDesc('tanggal')->take(4)->get();
    $qris = InfaqSetting::current();
    $infaqTotals = InfaqHistory::totals();

    return view('home', compact('news', 'dokumentasi', 'qris', 'infaqTotals'));
})->name('home');

// Halaman publik berita
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{news:slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/struktur', [StrukturController::class, 'index'])->name('struktur');
Route::get('/struktur/{kategori}', [StrukturController::class, 'show'])->name('struktur.kategori');
Route::get('/dokumentasi', function () {
    return view('pages.dokumentasi', ['dokumentasi' => Dokumentasi::orderByDesc('tanggal')->orderByDesc('id')->get()]);
})->name('dokumentasi');

// Infaq (publik): QRIS, ringkasan kas, dan histori transaksi
Route::get('/infaq', [InfaqController::class, 'index'])->name('infaq');

// Pendaftaran anggota (publik)
Route::get('/daftar', [CalonAnggotaController::class, 'create'])->name('daftar.create');
Route::post('/daftar', [CalonAnggotaController::class, 'store'])->name('daftar.store')->middleware('throttle:15,1');
Route::get('/daftar/berhasil', [CalonAnggotaController::class, 'success'])->name('daftar.success');
Route::get('/cek-status', [CalonAnggotaController::class, 'cekStatus'])->name('cek-status');
Route::post('/cek-status', [CalonAnggotaController::class, 'cekStatus'])->name('cek-status.check')->middleware('throttle:20,1');

// Auth admin
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Area admin (wajib login)
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('news', AdminNewsController::class);
    Route::resource('pengurus', PengurusController::class)->except('show')->parameters(['pengurus' => 'pengurus']);
    Route::resource('dokumentasi', DokumentasiController::class)->except('show');

    // Infaq: foto QRIS + pencatatan pemasukan/pengeluaran kas
    Route::get('/infaq', [AdminInfaqController::class, 'index'])->name('infaq.index');
    Route::post('/infaq/qris', [AdminInfaqController::class, 'updateQris'])->name('infaq.qris.update');
    Route::delete('/infaq/qris', [AdminInfaqController::class, 'removeQris'])->name('infaq.qris.destroy');
    Route::post('/infaq/histori', [AdminInfaqController::class, 'storeHistory'])->name('infaq.histori.store');
    Route::delete('/infaq/histori/{infaqHistory}', [AdminInfaqController::class, 'destroyHistory'])->name('infaq.histori.destroy');

    // Kelola calon anggota (seluruh endpoint wajib login + dicek role di server)
    Route::get('/calon-anggota', [AdminCalonAnggotaController::class, 'index'])->name('calon-anggota.index');
    Route::get('/calon-anggota/{calonAnggota}', [AdminCalonAnggotaController::class, 'show'])->name('calon-anggota.show');
    Route::get('/calon-anggota/{calonAnggota}/kta', [AdminCalonAnggotaController::class, 'downloadKta'])->name('calon-anggota.download-kta');
    Route::post('/calon-anggota/{calonAnggota}/approve', [AdminCalonAnggotaController::class, 'approve'])->name('calon-anggota.approve');
    Route::post('/calon-anggota/{calonAnggota}/reject', [AdminCalonAnggotaController::class, 'reject'])->name('calon-anggota.reject');
    Route::post('/calon-anggota/{calonAnggota}/inactivate', [AdminCalonAnggotaController::class, 'inactivate'])->name('calon-anggota.inactivate');
});
