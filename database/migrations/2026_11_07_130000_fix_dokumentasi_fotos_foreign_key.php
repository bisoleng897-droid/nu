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
        Schema::dropIfExists('dokumentasi_fotos');

        Schema::create('dokumentasi_fotos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dokumentasi_id')->constrained('dokumentasi')->cascadeOnDelete();
            $table->string('foto');
            $table->boolean('is_thumbnail')->default(false);
            $table->integer('urutan')->default(0);
            $table->timestamps();

            $table->index(['dokumentasi_id', 'is_thumbnail']);
            $table->index(['dokumentasi_id', 'urutan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumentasi_fotos');
    }
};
