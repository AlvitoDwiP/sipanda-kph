<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('display_jobdesk_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('display_duration_seconds')->default(10);
            $table->boolean('show_employee_photo')->default(false);
            $table->timestamp('reset_requested_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('display_jobdesk_settings');
    }
};
