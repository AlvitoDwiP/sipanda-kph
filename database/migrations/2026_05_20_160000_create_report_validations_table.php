<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_validations', function (Blueprint $table) {
            $table->id();
            $table->string('report_code')->unique();
            $table->string('validation_token')->unique();
            $table->string('report_type');
            $table->string('period_type')->nullable();
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->foreignId('generated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('generated_at');
            $table->string('file_hash', 64)->nullable();
            $table->string('status')->default('valid');
            $table->timestamp('revoked_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['report_type', 'generated_at']);
            $table->index(['status', 'revoked_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_validations');
    }
};
