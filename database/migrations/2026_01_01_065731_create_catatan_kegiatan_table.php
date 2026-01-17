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
        Schema::create('catatan_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pegawai_id');
            $table->foreign('pegawai_id')->references('id')->on('pegawai')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedTinyInteger('periode_bulan');
            $table->year('periode_tahun');
            $table->text('judul');
            $table->text('deskripsi');
            $table->text('catatan_status')->nullable();
            $table->string('status')->values(['draft', 'ajukan', 'setuju', 'tolak']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catatan_kegiatan');
    }
};
