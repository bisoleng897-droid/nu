<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Pengaturan infaq (sampai sekarang hanya foto QRIS yang diunggah admin).
        Schema::create('infaq_settings', function (Blueprint $table) {
            $table->id();
            $table->string('qris_image')->nullable();
            $table->timestamps();
        });

        // Histori infaq: pemasukan (infaq masuk) dan pengeluaran kas.
        Schema::create('infaq_histories', function (Blueprint $table) {
            $table->id();
            $table->string('tipe', 10);
            $table->string('keterangan', 500);
            $table->unsignedBigInteger('nominal');
            $table->date('tanggal');
            $table->timestamps();

            $table->index('tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infaq_histories');
        Schema::dropIfExists('infaq_settings');
    }
};
