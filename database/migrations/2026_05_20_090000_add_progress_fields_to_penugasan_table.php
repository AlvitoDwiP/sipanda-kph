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
        Schema::table('penugasan', function (Blueprint $table) {
            $table->unsignedTinyInteger('progres_persen')->default(0)->after('status');
            $table->text('catatan_progres')->nullable()->after('catatan_kepegawaian');
            $table->text('catatan_revisi')->nullable()->after('catatan_progres');
            $table->timestamp('progres_updated_at')->nullable()->after('catatan_revisi');
            $table->timestamp('selesai_at')->nullable()->after('progres_updated_at');
            $table->text('alasan_pembatalan')->nullable()->after('selesai_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penugasan', function (Blueprint $table) {
            $table->dropColumn([
                'progres_persen',
                'catatan_progres',
                'catatan_revisi',
                'progres_updated_at',
                'selesai_at',
                'alasan_pembatalan',
            ]);
        });
    }
};
