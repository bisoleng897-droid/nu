<?php

namespace Tests\Feature;

use App\Models\CalonAnggota;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PendaftaranAnggotaTest extends TestCase
{
    use RefreshDatabase;

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'nama_lengkap' => 'Ahmad Syaifuddin',
            'alamat' => 'Jl. Melati No. 10, RT 01/RW 02, Rancamaya, Bogor',
            'nomor_hp' => '081234567890',
            'nik' => '3201234567890001',
            'jenis_kelamin' => 'L',
            'organisasi' => 'muslimat',
            'foto' => UploadedFile::fake()->image('foto.jpg'),
        ], $overrides);
    }

    private function createAdmin(): User
    {
        return User::create([
            'name' => 'Admin NU',
            'email' => 'admin@nu-rancamaya.test',
            'username' => 'adminnu',
            'password' => 'password',
        ]);
    }

    private function loginAsAdmin(User $admin): void
    {
        $this->post(route('login'), ['username' => 'adminnu', 'password' => 'password']);
    }

    public function test_formulir_pendaftaran_ditampilkan_untuk_publik(): void
    {
        $this->get(route('daftar.create'))
            ->assertOk()
            ->assertSee('Daftar Menjadi Anggota')
            ->assertSee('NIK')
            ->assertSee('Pas Foto');
    }

    public function test_pendaftaran_berhasil_disimpan_dengan_status_pending(): void
    {
        Storage::fake('public');

        $this->post(route('daftar.store'), $this->validPayload())
            ->assertRedirect(route('daftar.success'));

        $this->assertDatabaseHas('calon_anggotas', [
            'nik' => '3201234567890001',
            'status' => CalonAnggota::STATUS_PENDING,
            'nomor_hp' => '+6281234567890',
            'nama_lengkap' => 'Ahmad Syaifuddin',
        ]);

        // Pendaftar tidak dibuatkan akun pengguna (tidak mendapat akses sebelum disetujui)
        $this->assertDatabaseCount('users', 0);
    }

    public function test_semua_field_wajib_divalidasi(): void
    {
        Storage::fake('public');

        $this->post(route('daftar.store'), [])
            ->assertSessionHasErrors(['nama_lengkap', 'alamat', 'nomor_hp', 'nik', 'jenis_kelamin', 'organisasi', 'foto']);
    }

    public function test_nik_harus_16_digit_angka_dan_tidak_boleh_duplikat(): void
    {
        Storage::fake('public');

        $this->post(route('daftar.store'), $this->validPayload(['nik' => '12345']))
            ->assertSessionHasErrors('nik');

        $this->post(route('daftar.store'), $this->validPayload(['nik' => '320123456789000a']))
            ->assertSessionHasErrors('nik');

        $this->post(route('daftar.store'), $this->validPayload(['nik' => null]))
            ->assertSessionHasErrors('nik');

        $this->post(route('daftar.store'), $this->validPayload())
            ->assertRedirect(route('daftar.success'));

        // NIK yang sama tidak dapat digunakan lagi
        $this->post(route('daftar.store'), $this->validPayload(['nama_lengkap' => 'Budi Santoso']))
            ->assertSessionHasErrors('nik');
    }

    public function test_nomor_hp_harus_format_yang_masuk_akal(): void
    {
        Storage::fake('public');

        $this->post(route('daftar.store'), $this->validPayload(['nomor_hp' => '12345']))
            ->assertSessionHasErrors('nomor_hp');

        $this->post(route('daftar.store'), $this->validPayload(['nomor_hp' => '081234567890']))
            ->assertRedirect(route('daftar.success'));
    }

    public function test_foto_harus_gambar_dengan_format_dan_ukuran_sesuai(): void
    {
        Storage::fake('public');

        $this->post(route('daftar.store'), $this->validPayload(['foto' => UploadedFile::fake()->create('foto.txt', 100)]))
            ->assertSessionHasErrors('foto');

        $this->post(route('daftar.store'), $this->validPayload(['foto' => UploadedFile::fake()->image('foto.jpg')->size(3000)]))
            ->assertSessionHasErrors('foto');
    }

    public function test_halaman_sukses_hanya_dapat_diakses_setelah_submit(): void
    {
        // Tanpa data sesi (mis. halaman di-refresh), kembali ke beranda.
        $this->get(route('daftar.success'))
            ->assertRedirect(route('home'));
    }

    public function test_pendaftaran_ipnu_ippnu_disesuaikan_dengan_jenis_kelamin(): void
    {
        Storage::fake('public');

        // Laki-laki yang memilih IPNU & IPPNU -> tersimpan sebagai IPNU
        $this->post(route('daftar.store'), $this->validPayload([
            'jenis_kelamin' => 'L',
            'organisasi' => 'ipnu-ippnu',
            'nik' => '3201234567890002',
        ]))->assertRedirect(route('daftar.success'));

        $this->assertDatabaseHas('calon_anggotas', [
            'nik' => '3201234567890002',
            'jenis_kelamin' => 'L',
            'organisasi' => 'ipnu',
        ]);

        // Perempuan yang memilih IPNU & IPPNU -> tersimpan sebagai IPPNU
        $this->post(route('daftar.store'), $this->validPayload([
            'nama_lengkap' => 'Siti Maryam',
            'jenis_kelamin' => 'P',
            'organisasi' => 'ipnu-ippnu',
            'nik' => '3201234567890003',
        ]))->assertRedirect(route('daftar.success'));

        $this->assertDatabaseHas('calon_anggotas', [
            'nik' => '3201234567890003',
            'jenis_kelamin' => 'P',
            'organisasi' => 'ippnu',
        ]);
    }

    public function test_label_organisasi_ipnu_dan_ippnu(): void
    {
        $laki = CalonAnggota::factory()->create(['jenis_kelamin' => 'L', 'organisasi' => 'ipnu']);
        $perempuan = CalonAnggota::factory()->create(['jenis_kelamin' => 'P', 'organisasi' => 'ippnu']);

        $this->assertSame('IPNU', $laki->organisasiLabel());
        $this->assertSame('IPPNU', $perempuan->organisasiLabel());
    }

    public function test_cek_status_memerlukan_nik_dan_nomor_pendaftaran_serta_tidak_menampilkan_nik_lengkap(): void
    {
        CalonAnggota::factory()->create([
            'nama_lengkap' => 'Ahmad Syaifuddin',
            'nik' => '3201234567890001',
            'nomor_pendaftaran' => 'NRC/2026/0001',
        ]);

        $this->post(route('cek-status.check'), [
            'nik' => '3201234567890001',
            'nomor_pendaftaran' => 'NRC/2026/0001',
        ])
            ->assertOk()
            ->assertSee('Menunggu Persetujuan')
            ->assertSee('****-****-****-0001')
            ->assertDontSee('3201234567890001');

        // Kombinasi salah: data tidak boleh ditampilkan
        $this->post(route('cek-status.check'), [
            'nik' => '3201234567890001',
            'nomor_pendaftaran' => 'NRC/2026/9999',
        ])
            ->assertOk()
            ->assertSee('Data pendaftaran tidak ditemukan')
            ->assertDontSee('Ahmad Syaifuddin');
    }

    public function test_admin_wajib_login_untuk_mengakses_manajemen_calon_anggota(): void
    {
        $this->get(route('admin.calon-anggota.index'))
            ->assertRedirect(route('login'));
    }

    public function test_admin_dapat_melihat_daftar_calon_anggota_pending(): void
    {
        $admin = $this->createAdmin();
        CalonAnggota::factory()->count(2)->create();
        CalonAnggota::factory()->approved()->create();

        $this->loginAsAdmin($admin);

        $this->get(route('admin.calon-anggota.index'))
            ->assertOk()
            ->assertSee('Menunggu Persetujuan');
    }

    public function test_admin_dapat_menyetujui_pendaftar_dan_menyimpan_waktu_serta_admin(): void
    {
        $admin = $this->createAdmin();
        $pendaftar = CalonAnggota::factory()->create();

        $this->loginAsAdmin($admin);

        $this->from(route('admin.calon-anggota.index'))
            ->post(route('admin.calon-anggota.approve', $pendaftar))
            ->assertRedirect(route('admin.calon-anggota.index'));

        $fresh = $pendaftar->fresh();
        $this->assertSame(CalonAnggota::STATUS_APPROVED, $fresh->status);
        $this->assertNotNull($fresh->approved_at);
        $this->assertSame($admin->id, $fresh->approved_by);
    }

    public function test_admin_dapat_menolak_pendaftar_dengan_alasan(): void
    {
        $admin = $this->createAdmin();
        $pendaftar = CalonAnggota::factory()->create();

        $this->loginAsAdmin($admin);

        $this->post(route('admin.calon-anggota.reject', $pendaftar), [
            'rejection_reason' => 'Pas foto tidak sesuai ketentuan.',
        ])
            ->assertRedirect(route('admin.calon-anggota.index'));

        $fresh = $pendaftar->fresh();
        $this->assertSame(CalonAnggota::STATUS_REJECTED, $fresh->status);
        $this->assertNotNull($fresh->rejected_at);
        $this->assertSame('Pas foto tidak sesuai ketentuan.', $fresh->rejection_reason);
    }

    public function test_penolakan_wajib_disertai_alasan(): void
    {
        $admin = $this->createAdmin();
        $pendaftar = CalonAnggota::factory()->create();

        $this->loginAsAdmin($admin);

        $this->post(route('admin.calon-anggota.reject', $pendaftar), [])
            ->assertSessionHasErrors('rejection_reason');
    }

    public function test_pendaftar_yang_sudah_diproses_tidak_dapat_diproses_lagi(): void
    {
        $admin = $this->createAdmin();
        $pendaftar = CalonAnggota::factory()->approved()->create();

        $this->loginAsAdmin($admin);

        $this->post(route('admin.calon-anggota.approve', $pendaftar))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertSame(CalonAnggota::STATUS_APPROVED, $pendaftar->fresh()->status);
    }

    public function test_detail_calon_anggota_hanya_dapat_diakses_admin(): void
    {
        $pendaftar = CalonAnggota::factory()->create();

        $this->get(route('admin.calon-anggota.show', $pendaftar))
            ->assertRedirect(route('login'));

        $admin = $this->createAdmin();
        $this->loginAsAdmin($admin);

        $this->get(route('admin.calon-anggota.show', $pendaftar))
            ->assertOk()
            ->assertSee($pendaftar->nama_lengkap)
            ->assertSee($pendaftar->nik);
    }

    public function test_halaman_publik_tidak_memiliki_tautan_untuk_membuat_akun_admin(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee('Daftar Admin')
            ->assertDontSee('Register Admin')
            ->assertDontSee('Daftar menjadi admin');

        $this->get(route('daftar.create'))
            ->assertOk()
            ->assertDontSee('Daftar Admin')
            ->assertDontSee('Register Admin');
    }
}
