<?php

namespace Tests\Feature;

use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BeritaTest extends TestCase
{
    use RefreshDatabase;

    private function loginAsAdmin(): User
    {
        $admin = User::create([
            'name' => 'Admin NU',
            'email' => 'admin@nu-rancamaya.test',
            'username' => 'adminnu',
            'password' => 'password',
        ]);

        $this->post(route('login'), ['username' => 'adminnu', 'password' => 'password']);

        return $admin;
    }

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Kajian Bulanan Oktober',
            'content' => 'Kajian rutin bulanan Oktober yang berlangsung di aula NU Rancamaya.',
            'published_at' => '2026-10-10',
        ], $overrides);
    }

    public function test_admin_wajib_login_untuk_mengelola_berita(): void
    {
        $this->get(route('admin.news.create'))->assertRedirect(route('login'));
        $this->post(route('admin.news.store'), $this->validPayload())->assertRedirect(route('login'));
    }

    public function test_berita_dengan_lokasi_dipeta_disimpan_beserta_koordinatnya(): void
    {
        $this->loginAsAdmin();

        $this->post(route('admin.news.store'), $this->validPayload([
            'alamat' => 'Masjid NU Rancamaya, Jl. Raya Bogor No. 12, Rancamaya',
            'lat' => '-6.6150000',
            'lng' => '106.9000000',
        ]))->assertRedirect(route('admin.news.index'));

        $news = News::firstOrFail();

        $this->assertTrue($news->hasCoordinates());
        $this->assertSame(-6.615, $news->lat);
        $this->assertSame(106.9, $news->lng);
    }

    public function test_koordinat_peta_dibuang_bila_alamat_dikosongkan(): void
    {
        $this->loginAsAdmin();

        $this->post(route('admin.news.store'), $this->validPayload([
            'alamat' => '',
            'lat' => '-6.6150000',
            'lng' => '106.9000000',
        ]))->assertRedirect(route('admin.news.index'));

        $news = News::firstOrFail();

        $this->assertNull($news->alamat);
        $this->assertFalse($news->hasCoordinates());
        $this->assertNull($news->lat);
        $this->assertNull($news->lng);
    }

    public function test_koordinat_di_luar_rentang_wah_ditolak(): void
    {
        $this->loginAsAdmin();

        $this->post(route('admin.news.store'), $this->validPayload([
            'alamat' => 'Rancamaya',
            'lat' => '120',
            'lng' => '106.9',
        ]))->assertSessionHasErrors('lat');

        $this->post(route('admin.news.store'), $this->validPayload([
            'alamat' => 'Rancamaya',
            'lat' => '-6.615',
            'lng' => '999',
        ]))->assertSessionHasErrors('lng');
    }

    public function test_edit_berita_memperbarui_koordinat_lokasi(): void
    {
        $this->loginAsAdmin();

        $news = News::factory()->create(['title' => 'Judul Lama']);

        $this->put(route('admin.news.update', $news), $this->validPayload([
            'title' => 'Judul Baru',
            'alamat' => 'Aula RT 04, Rancamaya, Bogor',
            'lat' => '-6.6',
            'lng' => '106.8',
        ]))->assertRedirect(route('admin.news.index'));

        $fresh = $news->fresh();

        $this->assertSame('Judul Baru', $fresh->title);
        $this->assertSame(-6.6, $fresh->lat);
        $this->assertSame(106.8, $fresh->lng);
    }

    public function test_berita_lama_tanpa_koordinat_tetap_menghasilkan_url_peta(): void
    {
        $news = News::factory()->withAddressOnly()->create();

        $this->assertFalse($news->hasCoordinates());
        $this->assertStringContainsString(urlencode('Aula RT 04, Rancamaya, Bogor'), $news->mapsEmbedUrl());
    }

    public function test_halaman_berita_menampilkan_peta_dari_koordinat_lokasi(): void
    {
        $news = News::factory()->withLocation()->create();

        $this->get(route('news.show', $news))
            ->assertOk()
            ->assertSee(e($news->mapsEmbedUrl()), escape: false)
            ->assertSee('Buka di Google Maps', escape: false);
    }

    public function test_berita_tanpa_alamat_tidak_menampilkan_peta(): void
    {
        $news = News::factory()->create(['alamat' => null]);

        $this->get(route('news.show', $news))
            ->assertOk()
            ->assertDontSee('Lokasi Kegiatan');
    }

    public function test_formulir_berita_menyediakan_pemilih_lokasi_peta(): void
    {
        $this->loginAsAdmin();

        $berita = News::factory()->withLocation()->create();

        $this->get(route('admin.news.create'))
            ->assertOk()
            ->assertSee('Cari nama tempat', escape: false)
            ->assertSee('name="lat"', escape: false)
            ->assertSee('name="lng"', escape: false);

        // Lokasi yang sudah tersimpan harus muncul sebagai titik peta awal.
        $this->get(route('admin.news.edit', $berita))
            ->assertOk()
            ->assertSee('value="-6.615"', escape: false)
            ->assertSee('value="106.9"', escape: false);
    }

    public function test_pencarian_berita_dilakukan_dari_halaman_berita_bukan_dari_beranda(): void
    {
        News::factory()->create(['title' => 'Kajian Rutin Bulanan Oktober']);
        News::factory()->create(['title' => 'Lomba Hafalan Al-Quran']);

        // Kolom pencarian hanya tersedia di halaman berita.
        $this->get(route('news.index'))
            ->assertOk()
            ->assertSee('name="q"', escape: false);

        $this->get(route('news.index', ['q' => 'Kajian']))
            ->assertOk()
            ->assertSee('Kajian Rutin Bulanan Oktober')
            ->assertDontSee('Lomba Hafalan Al-Quran');

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('name="q"', escape: false);
    }

    public function test_gambar_berita_tersimpan_di_disk_public_dan_tampil_di_halaman_publik(): void
    {
        Storage::fake('public');

        $this->loginAsAdmin();

        $this->post(route('admin.news.store'), $this->validPayload([
            'image' => UploadedFile::fake()->image('berita.jpg'),
        ]))->assertRedirect(route('admin.news.index'));

        $path = News::firstOrFail()->image;

        Storage::disk('public')->assertExists($path);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('/storage/'.$path, escape: false);
    }

    public function test_foto_pendaftar_dapat_diakses_lewat_url_penyimpanan(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('foto-calon-anggota/contoh.jpg', 'isi-file');

        $this->get('/storage/foto-calon-anggota/contoh.jpg')
            ->assertOk()
            ->assertHeader('Content-Type', 'image/jpeg');
    }

    public function test_url_penyimpanan_menolak_file_yang_tidak_ada_dan_path_traversal(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('news/contoh.jpg', 'isi-file');

        $this->get('/storage/news/contoh.jpg')->assertOk();

        $this->get('/storage/news/tidak-ada.jpg')->assertNotFound();

        $this->get('/storage/news/%2e%2e/%2e%2e/.env')->assertNotFound();
    }
}
