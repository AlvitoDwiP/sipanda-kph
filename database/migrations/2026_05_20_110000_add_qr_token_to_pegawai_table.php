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
        Schema::table('pegawai', function (Blueprint $table) {
            $table->string('qr_token')->nullable()->unique()->after('status_pegawai');
            $table->timestamp('qr_generated_at')->nullable()->after('qr_token');
            $table->timestamp('qr_regenerated_at')->nullable()->after('qr_generated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pegawai', function (Blueprint $table) {
            $table->dropUnique(['qr_token']);
            $table->dropColumn(['qr_token', 'qr_generated_at', 'qr_regenerated_at']);
        });
    }
};
