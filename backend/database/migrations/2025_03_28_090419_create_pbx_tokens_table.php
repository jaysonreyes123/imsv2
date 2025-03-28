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
        Schema::create('pbx_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('access_token');
            $table->timestamp('expired_at');
            $table->string('last_scan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pbx_tokens');
    }
};
