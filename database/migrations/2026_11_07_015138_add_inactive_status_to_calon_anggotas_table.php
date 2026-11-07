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
        Schema::table('calon_anggotas', function (Blueprint $table) {
            $table->text('inactive_reason')->nullable()->after('rejection_reason');
            $table->timestamp('inactivated_at')->nullable()->after('inactive_reason');
            $table->foreignId('inactivated_by')->nullable()->after('inactivated_at')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calon_anggotas', function (Blueprint $table) {
            $table->dropColumn(['inactive_reason', 'inactivated_at', 'inactivated_by']);
        });
    }
};
