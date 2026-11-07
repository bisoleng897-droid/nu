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
        Schema::create('calon_anggotas', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pendaftaran')->nullable()->unique();
            $table->string('nama_lengkap');
            $table->text('alamat');
            $table->string('nomor_hp', 16);
            $table->string('nik', 16)->unique();
            $table->string('foto');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('organisasi');
            $table->string('status')->default('pending')->index();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calon_anggotas');
    }
};
