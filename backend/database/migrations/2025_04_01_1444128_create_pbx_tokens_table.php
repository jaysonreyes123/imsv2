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
            $table->unsignedBigInteger('pbx_id');
            $table->foreign('pbx_id')->references('id')->on('pbxes')->onDelete('cascade');
            $table->text('access_token');
            $table->timestamp('expired_at')->nullable();
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
