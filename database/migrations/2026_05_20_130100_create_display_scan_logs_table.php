<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('display_scan_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamp('scanned_at');
            $table->string('qr_token_hash', 64)->nullable();
            $table->foreignId('pegawai_id')->nullable()->constrained('pegawai')->nullOnDelete();
            $table->foreignId('scanned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 50);
            $table->unsignedSmallInteger('task_count')->default(0);
            $table->string('message')->nullable();
            $table->timestamps();
            $table->index(['scanned_at', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('display_scan_logs');
    }
};
