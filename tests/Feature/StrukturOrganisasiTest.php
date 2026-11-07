<?php

namespace Tests\Feature;

use App\Models\CalonAnggota;
use App\Models\Pengurus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class StrukturOrganisasiTest extends TestCase
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

    public function test_anggota_yang_disetujui_langsung_masuk_ke_susunan_pengurus(): void
    {
        $this->loginAsAdmin();
        $pendaftar = CalonAnggota::factory()->create([
            'nama_lengkap' => 'Ahmad Syaifuddin',
            'organisasi' => 'muslimat',
            'foto' => 'foto-calon-anggota/ahmad.jpg',
        ]);

        $this->post(route('admin.calon-anggota.approve', $pendaftar));

        $pengurus = Pengurus::query()->firstOrFail();

        $this->assertSame('Ahmad Syaifuddin', $pengurus->nama);
        $this->assertSame('Anggota', $pengurus->jabatan);
        $this->assertSame('muslimat', $pengurus->kategori);
        $this->assertSame('foto-calon-anggota/ahmad.jpg', $pengurus->foto);
        $this->assertSame($pendaftar->id, $pengurus->calon_anggota_id);
    }

    public function test_anggota_yang_disetujui_tampil_di_halaman_struktur_publik(): void
    {
        $this->loginAsAdmin();
        $pendaftar = CalonAnggota::factory()->create([
            'nama_lengkap' => 'Siti Maryam',
            'organisasi' => 'fatayat',
        ]);

        $this->post(route('admin.calon-anggota.approve', $pendaftar));

        $this->get(route('struktur.kategori', 'fatayat'))
            ->assertOk()
            ->assertSee('Siti Maryam')
            ->assertSee('Anggota');
    }

    public function test_pendaftar_yang_ditolak_tidak_masuk_ke_susunan_pengurus(): void
    {
        $this->loginAsAdmin();
        $pendaftar = CalonAnggota::factory()->create();

        $this->post(route('admin.calon-anggota.reject', $pendaftar), [
            'rejection_reason' => 'Pas foto tidak sesuai ketentuan.',
        ]);

        $this->assertDatabaseCount('pengurus', 0);
    }

    public function test_pendaftar_yang_masih_pending_tidak_tampil_di_struktur(): void
    {
        CalonAnggota::factory()->create([
            'nama_lengkap' => 'Belum Disetujui',
            'organisasi' => 'ansor',
        ]);

        $this->get(route('struktur.kategori', 'ansor'))
            ->assertOk()
            ->assertSee('Data pengurus belum tersedia')
            ->assertDontSee('Belum Disetujui');
    }

    #[DataProvider('kategori_ipnu_ippnu')]
    public function test_organisasi_ipnu_ippnu_dipetakan_ke_kategori_ipnu_atau_ippnu(string $jenisKelamin, string $expected): void
    {
        $this->loginAsAdmin();
        $pendaftar = CalonAnggota::factory()->create([
            'organisasi' => 'ipnu-ippnu',
            'jenis_kelamin' => $jenisKelamin,
        ]);

        $this->post(route('admin.calon-anggota.approve', $pendaftar));

        $this->assertSame($expected, Pengurus::query()->firstOrFail()->kategori);
    }

    public static function kategori_ipnu_ippnu(): array
    {
        return [
            'laki-laki menjadi ipnu' => ['L', 'ipnu'],
            'perempuan menjadi ippnu' => ['P', 'ippnu'],
        ];
    }

    public function test_anggota_baru_ditempatkan_di_akhir_daftar_kategori_yang_sama(): void
    {
        $tersedia = Pengurus::factory()->create([
            'nama' => 'Ketua Muslimat',
            'jabatan' => 'Ketua',
            'kategori' => 'muslimat',
            'urutan' => 1,
        ]);

        $this->loginAsAdmin();
        $pendaftar = CalonAnggota::factory()->create(['organisasi' => 'muslimat']);

        $this->post(route('admin.calon-anggota.approve', $pendaftar));

        $baru = Pengurus::query()->where('calon_anggota_id', $pendaftar->id)->firstOrFail();

        $this->assertSame(2, $baru->urutan);
        $this->assertNotSame($tersedia->id, $baru->id);
    }

    public function test_menyetujui_ulang_tidak_membuat_data_pengurus_ganda(): void
    {
        $this->loginAsAdmin();
        $pendaftar = CalonAnggota::factory()->approved()->create();

        $this->post(route('admin.calon-anggota.approve', $pendaftar))
            ->assertSessionHas('error');

        $this->assertDatabaseCount('pengurus', 0);
    }

    public function test_pengurus_ditambahkan_manual_admin_tetap_tampil_di_struktur(): void
    {
        Pengurus::factory()->create([
            'nama' => 'H. Ahmad Sobari',
            'jabatan' => 'Ketua MW NU Rancamaya',
            'kategori' => 'muslimat',
        ]);

        $this->get(route('struktur.kategori', 'muslimat'))
            ->assertOk()
            ->assertSee('H. Ahmad Sobari')
            ->assertSee('Ketua MW NU Rancamaya');
    }

    public function test_kategori_ipnu_dan_ippnu_ditampilkan_terpisah_pada_halaman_yang_sama(): void
    {
        Pengurus::factory()->create(['nama' => 'Ahmad Putra', 'kategori' => 'ipnu']);
        Pengurus::factory()->create(['nama' => 'Aisyah Putri', 'kategori' => 'ippnu']);

        $this->get(route('struktur.kategori', 'ipnu-ippnu'))
            ->assertOk()
            ->assertSee('IPNU — Laki-laki')
            ->assertSee('IPPNU — Perempuan')
            ->assertSee('Ahmad Putra')
            ->assertSee('Aisyah Putri');
    }
}
