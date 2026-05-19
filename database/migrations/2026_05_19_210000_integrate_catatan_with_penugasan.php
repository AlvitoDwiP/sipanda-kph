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
        Schema::table('catatan_kegiatan', function (Blueprint $table) {
            $table->foreignId('penugasan_id')->nullable()->after('pegawai_id')->constrained('penugasan')->nullOnDelete();
            $table->date('tanggal_kegiatan')->nullable()->after('periode_tahun');
            $table->text('hasil_kegiatan')->nullable()->after('deskripsi');
            $table->text('kendala')->nullable()->after('hasil_kegiatan');
            $table->string('status_verifikasi')->default('menunggu_verifikasi')->after('status');
            $table->text('catatan_verifikasi')->nullable()->after('status_verifikasi');
            $table->foreignId('diverifikasi_oleh')->nullable()->after('catatan_verifikasi')->constrained('users')->nullOnDelete();
            $table->timestamp('diverifikasi_at')->nullable()->after('diverifikasi_oleh');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catatan_kegiatan', function (Blueprint $table) {
            $table->dropConstrainedForeignId('penugasan_id');
            $table->dropConstrainedForeignId('diverifikasi_oleh');
            $table->dropColumn([
                'tanggal_kegiatan',
                'hasil_kegiatan',
                'kendala',
                'status_verifikasi',
                'catatan_verifikasi',
                'diverifikasi_at',
            ]);
        });
    }
};
