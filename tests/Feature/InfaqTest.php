<?php

namespace Tests\Feature;

use App\Models\InfaqHistory;
use App\Models\InfaqSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InfaqTest extends TestCase
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

    /**
     * @return array<string, mixed>
     */
    private function validTransaksi(array $overrides = []): array
    {
        return array_merge([
            'tipe' => InfaqHistory::TIPE_KELUAR,
            'keterangan' => 'Beli sound system untuk kajian',
            'nominal' => 150000,
            'tanggal' => '2026-10-20',
        ], $overrides);
    }

    public function test_pengelolaan_infaq_wajib_login_sebagai_admin(): void
    {
        $this->get(route('admin.infaq.index'))->assertRedirect(route('login'));
        $this->post(route('admin.infaq.histori.store'), $this->validTransaksi())->assertRedirect(route('login'));
        $this->post(route('admin.infaq.qris.update'))->assertRedirect(route('login'));

        $histori = InfaqHistory::factory()->create();
        $this->delete(route('admin.infaq.histori.destroy', $histori))->assertRedirect(route('login'));

        $this->assertDatabaseHas('infaq_histories', ['id' => $histori->id]);
    }

    public function test_halaman_infaq_publik_terbuka_tanpa_login(): void
    {
        $this->get(route('infaq'))
            ->assertOk()
            ->assertSee('Infaq &amp; Donasi', escape: false);
    }

    public function test_foto_qris_tersimpan_di_disk_public_dan_tampil_di_halaman_utama(): void
    {
        Storage::fake('public');

        $this->loginAsAdmin();

        $this->post(route('admin.infaq.qris.update'), [
            'qris' => UploadedFile::fake()->image('qris.png'),
        ])->assertRedirect(route('admin.infaq.index'));

        $path = InfaqSetting::firstOrFail()->qris_image;

        Storage::disk('public')->assertExists($path);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('/storage/'.$path, escape: false);

        $this->get(route('infaq'))
            ->assertOk()
            ->assertSee('/storage/'.$path, escape: false);
    }

    public function test_foto_qris_lama_dihapus_saat_admin_menggantinya(): void
    {
        Storage::fake('public');

        $this->loginAsAdmin();

        $this->post(route('admin.infaq.qris.update'), [
            'qris' => UploadedFile::fake()->image('qris-baru.png'),
        ]);

        $lama = InfaqSetting::firstOrFail()->qris_image;

        $this->post(route('admin.infaq.qris.update'), [
            'qris' => UploadedFile::fake()->image('qris-ganti.png'),
        ]);

        $baru = InfaqSetting::firstOrFail()->qris_image;

        $this->assertNotSame($lama, $baru);
        Storage::disk('public')->assertExists($baru);
        Storage::disk('public')->assertMissing($lama);
    }

    public function test_pengunggahan_qris_menolak_file_yang_bukan_gambar(): void
    {
        Storage::fake('public');

        $this->loginAsAdmin();

        $this->post(route('admin.infaq.qris.update'), [
            'qris' => UploadedFile::fake()->create('laporan.pdf', 100, 'application/pdf'),
        ])->assertSessionHasErrors('qris');

        $this->post(route('admin.infaq.qris.update'), [])->assertSessionHasErrors('qris');

        $this->assertNull(InfaqSetting::first()?->qris_image);
    }

    public function test_pengeluaran_masuk_ke_histori_infaq_dan_tampil_di_halaman_publik(): void
    {
        $this->loginAsAdmin();

        $this->post(route('admin.infaq.histori.store'), $this->validTransaksi())
            ->assertRedirect(route('admin.infaq.index'));

        $histori = InfaqHistory::firstOrFail();

        $this->assertSame(InfaqHistory::TIPE_KELUAR, $histori->tipe);
        $this->assertSame('Beli sound system untuk kajian', $histori->keterangan);
        $this->assertSame(150000, $histori->nominal);
        $this->assertSame('2026-10-20', $histori->tanggal->toDateString());

        $this->get(route('admin.infaq.index'))
            ->assertOk()
            ->assertSee('Beli sound system untuk kajian');

        $this->get(route('infaq'))
            ->assertOk()
            ->assertDontSee('Beli sound system untuk kajian');
    }

    public function test_ringkasan_kas_menampilkan_saldo_dari_pemasukan_dikurangi_pengeluaran(): void
    {
        InfaqHistory::factory()->masuk()->create(['nominal' => 100000]);
        InfaqHistory::factory()->keluar()->create(['nominal' => 40000]);

        $this->get(route('infaq'))
            ->assertOk()
            ->assertSee('Rp 60.000');

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Rp 60.000');
    }

    public function test_validasi_transaksi_infaq_menolak_data_tidak_sah(): void
    {
        $this->loginAsAdmin();

        $this->post(route('admin.infaq.histori.store'), [
            'tipe' => 'asing',
            'keterangan' => '',
            'nominal' => 0,
            'tanggal' => 'bukan-tanggal',
        ])->assertSessionHasErrors(['tipe', 'keterangan', 'nominal', 'tanggal']);

        $this->post(route('admin.infaq.histori.store'), [])->assertSessionHasErrors([
            'tipe',
            'keterangan',
            'nominal',
            'tanggal',
        ]);

        $this->assertSame(0, InfaqHistory::count());
    }

    public function test_admin_dapat_menghapus_histori_infaq(): void
    {
        $this->loginAsAdmin();

        $histori = InfaqHistory::factory()->keluar()->create();

        $this->delete(route('admin.infaq.histori.destroy', $histori))
            ->assertRedirect(route('admin.infaq.index'));

        $this->assertDatabaseMissing('infaq_histories', ['id' => $histori->id]);
    }

    public function test_admin_dapat_menghapus_kedua_tipe_histori_infaq(): void
    {
        $this->loginAsAdmin();

        $masuk = InfaqHistory::factory()->masuk()->create(['nominal' => 100000]);
        $keluar = InfaqHistory::factory()->keluar()->create(['nominal' => 40000]);

        $this->assertCount(2, InfaqHistory::all());

        $this->delete(route('admin.infaq.histori.destroy', $masuk))
            ->assertRedirect(route('admin.infaq.index'));
        $this->delete(route('admin.infaq.histori.destroy', $keluar))
            ->assertRedirect(route('admin.infaq.index'));

        $this->assertDatabaseMissing('infaq_histories', ['id' => $masuk->id]);
        $this->assertDatabaseMissing('infaq_histories', ['id' => $keluar->id]);
        $this->assertCount(0, InfaqHistory::all());
    }
}
