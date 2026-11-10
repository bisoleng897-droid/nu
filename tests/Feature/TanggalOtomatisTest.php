<?php

namespace Tests\Feature;

use App\Models\Dokumentasi;
use App\Models\News;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TanggalOtomatisTest extends TestCase
{
    use RefreshDatabase;

    private function loginAsAdmin(): User
    {
        User::create([
            'name' => 'Admin NU',
            'email' => 'admin2@nu-rancamaya.test',
            'username' => 'adminnu2',
            'password' => 'password',
        ]);

        $this->post(route('login'), ['username' => 'adminnu2', 'password' => 'password']);

        return User::firstOrFail();
    }

    public function test_tanggal_berita_otomatis_hari_ini_wib_bila_tidak_diisi(): void
    {
        $this->loginAsAdmin();

        $this->post(route('admin.news.store'), [
            'title' => 'Berita Auto Tanggal',
            'content' => 'Isi berita auto tanggal.',
        ])->assertRedirect(route('admin.news.index'));

        $news = News::firstOrFail();

        $this->assertSame(now('Asia/Jakarta')->toDateString(), $news->published_at->toDateString());
        $this->assertSame(now('Asia/Jakarta')->toDateString(), $news->created_at->toDateString());
    }

    public function test_tanggal_berita_manual_dari_luar_form_tetap_diabaikan(): void
    {
        $this->loginAsAdmin();

        $this->post(route('admin.news.store'), [
            'title' => 'Berita Tanggal Manual',
            'content' => 'Isi berita tanggal manual.',
            'published_at' => '2020-01-01',
        ])->assertRedirect(route('admin.news.index'));

        $this->assertSame(now('Asia/Jakarta')->toDateString(), News::firstOrFail()->published_at->toDateString());
    }

    public function test_edit_berita_tidak_mengubah_tanggal_publikasi(): void
    {
        $this->loginAsAdmin();

        $news = News::factory()->create([
            'title' => 'Judul Lama',
            'published_at' => '2026-01-15',
        ]);

        $this->put(route('admin.news.update', $news), [
            'title' => 'Judul Diubah',
            'content' => 'Isi diubah.',
            'published_at' => '2000-01-01',
        ])->assertRedirect(route('admin.news.index'));

        $fresh = $news->fresh();

        $this->assertSame('2026-01-15', $fresh->published_at->toDateString());
        $this->assertSame('Judul Diubah', $fresh->title);
    }

    public function test_tanggal_dokumentasi_otomatis_hari_ini_wib_bila_tidak_diisi(): void
    {
        Storage::fake('public');
        $this->loginAsAdmin();

        $this->post(route('admin.dokumentasi.store'), [
            'kategori' => 'Kajian',
            'judul' => 'Dok Auto Tanggal',
            'deskripsi' => 'Deskripsi dokumentasi.',
            'fotos' => [UploadedFile::fake()->image('dok.jpg')],
        ])->assertRedirect(route('admin.dokumentasi.index'));

        $dokumentasi = Dokumentasi::firstOrFail();

        $this->assertSame(now('Asia/Jakarta')->toDateString(), $dokumentasi->tanggal->toDateString());
        $this->assertSame(now('Asia/Jakarta')->toDateString(), $dokumentasi->created_at->toDateString());
        $this->assertSame(1, $dokumentasi->fotos()->count());
    }

    public function test_tanggal_dokumentasi_manual_dari_luar_form_tetap_diabaikan(): void
    {
        Storage::fake('public');
        $this->loginAsAdmin();

        $this->post(route('admin.dokumentasi.store'), [
            'kategori' => 'Kajian',
            'judul' => 'Dok Tanggal Manual',
            'deskripsi' => 'Deskripsi dokumentasi.',
            'tanggal' => '2020-01-01',
            'fotos' => [UploadedFile::fake()->image('dok.jpg')],
        ])->assertRedirect(route('admin.dokumentasi.index'));

        $this->assertSame(now('Asia/Jakarta')->toDateString(), Dokumentasi::firstOrFail()->tanggal->toDateString());
    }

    public function test_edit_dokumentasi_tidak_mengubah_tanggal_publikasi(): void
    {
        Storage::fake('public');
        $this->loginAsAdmin();

        $dokumentasi = Dokumentasi::create([
            'kategori' => 'Kajian',
            'judul' => 'Judul Lama',
            'deskripsi' => 'Lama',
            'tanggal' => '2026-02-20',
        ]);

        $this->put(route('admin.dokumentasi.update', $dokumentasi), [
            'kategori' => 'Kajian',
            'judul' => 'Judul Baru',
            'deskripsi' => 'Baru',
            'tanggal' => '2000-01-01',
        ])->assertRedirect(route('admin.dokumentasi.index'));

        $fresh = $dokumentasi->fresh();

        $this->assertSame('2026-02-20', $fresh->tanggal->toDateString());
        $this->assertSame('Judul Baru', $fresh->judul);
    }

    public function test_formulir_tambah_dan_edit_tidak_menampilkan_input_tanggal(): void
    {
        $this->loginAsAdmin();

        $this->get(route('admin.news.create'))
            ->assertOk()
            ->assertDontSee('name="published_at"', escape: false);

        $this->get(route('admin.dokumentasi.create'))
            ->assertOk()
            ->assertDontSee('name="tanggal"', escape: false);

        $news = News::factory()->create(['published_at' => '2026-01-15']);
        $this->get(route('admin.news.edit', $news))
            ->assertOk()
            ->assertDontSee('name="published_at"', escape: false);

        $dokumentasi = Dokumentasi::create(['judul' => 'Dok', 'tanggal' => '2026-02-20']);
        $this->get(route('admin.dokumentasi.edit', $dokumentasi))
            ->assertOk()
            ->assertDontSee('name="tanggal"', escape: false);
    }

    public function test_tanggal_publikasi_tersimpan_ditampilkan_di_halaman_publik(): void
    {
        $news = News::factory()->create([
            'title' => 'Berita Tanggal Tampil',
            'published_at' => '2026-05-01',
        ]);

        $this->get(route('news.show', $news))
            ->assertOk()
            ->assertSee('01 May 2026');

        $dokumentasi = Dokumentasi::create([
            'judul' => 'Dokumentasi Tanggal Tampil',
            'tanggal' => '2026-06-02',
        ]);

        $this->get(route('dokumentasi'))
            ->assertOk()
            ->assertSee('02 Jun 2026');

        $this->get(route('dokumentasi.show', $dokumentasi))
            ->assertOk()
            ->assertSee('02 Jun 2026');
    }

    public function test_tanggal_otomatis_mengikuti_hari_wib_saat_server_masih_tanggal_sebelumnya(): void
    {
        $this->loginAsAdmin();

        // 18:30 UTC = 01:30 WIB pada hari berikutnya.
        $this->travelTo(Carbon::parse('2026-11-10 18:30:00', 'UTC'));

        $this->post(route('admin.news.store'), [
            'title' => 'Berita Lewat Tengah Malam WIB',
            'content' => 'Isi berita.',
        ])->assertRedirect(route('admin.news.index'));

        $this->assertSame('2026-11-11', News::firstOrFail()->published_at->toDateString());
    }

    public function test_timezone_aplikasi_asia_jakarta(): void
    {
        $this->assertSame('Asia/Jakarta', config('app.timezone'));
        $this->assertSame('Asia/Jakarta', now()->timezoneName);
    }
}
