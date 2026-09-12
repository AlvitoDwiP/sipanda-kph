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
        Schema::create('pengajuan_data_kepegawaians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->onDelete('cascade');
            $table->foreignId('unitkerja_id')->nullable()->constrained('ref_unitkerja')->onDelete('set null');
            $table->foreignId('golongan_id')->nullable()->constrained('ref_golongan')->onDelete('set null');
            $table->foreignId('jabatan_id')->nullable()->constrained('ref_jabatan')->onDelete('set null');
            $table->enum('status_pegawai', ['aktif', 'nonaktif'])->default('aktif');
            $table->enum('status', ['menunggu_verifikasi', 'disetujui', 'ditolak'])->default('menunggu_verifikasi');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_data_kepegawaians');
    }
};
