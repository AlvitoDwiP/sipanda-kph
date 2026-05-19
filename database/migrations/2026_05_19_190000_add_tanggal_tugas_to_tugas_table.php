<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tugas', function (Blueprint $table) {
            $table->date('tanggal_tugas')->nullable()->after('deskripsi');
        });

        DB::table('tugas')->whereNull('tanggal_tugas')->update([
            'tanggal_tugas' => DB::raw('deadline'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tugas', function (Blueprint $table) {
            $table->dropColumn('tanggal_tugas');
        });
    }
};
