<?php

use App\Models\CalonAnggota;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Membersihkan bridge antara pendaftaran anggota dan susunan pengurus:
     * setiap baris pengurus hasil approval punya satu-satunya sumber
     * pendaftaran (calon_anggota_id) supaya tidak bisa terduplikasi.
     */
    public function up(): void
    {
        Schema::table('pengurus', function (Blueprint $table) {
            $table->foreignId('calon_anggota_id')
                ->nullable()
                ->unique()
                ->after('kategori')
                ->constrained('calon_anggotas')
                ->nullOnDelete();
        });

        $this->backfillPengurus();
    }

    /**
     * Backfill: masukkan pendaftar yang sudah approved ke tabel pengurus
     * supaya mereka langsung tampil di halaman struktur organisasi.
     */
    private function backfillPengurus(): void
    {
        CalonAnggota::query()
            ->where('status', CalonAnggota::STATUS_APPROVED)
            ->whereDoesntHave('pengurus')
            ->orderBy('id')
            ->each(function (CalonAnggota $calon): void {
                $calon->pengurus()->create($calon->pengurusFromPendaftaran());
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengurus', function (Blueprint $table) {
            $table->dropConstrainedForeignId('calon_anggota_id');
        });
    }
};
