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
        Schema::create('riwayat_kepegawaian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('unitkerja_id');
            $table->foreign('unitkerja_id')->references('id')->on('ref_unitkerja')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('golongan_id');
            $table->foreign('golongan_id')->references('id')->on('ref_golongan')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('jabatan_id');
            $table->foreign('jabatan_id')->references('id')->on('ref_jabatan')->onDelete('cascade')->onUpdate('cascade');
            $table->date('tgl_mulai');
            $table->date('tgl_selesai')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_kepegawaian');
    }
};
