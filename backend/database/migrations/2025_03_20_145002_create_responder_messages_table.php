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
        Schema::create('responder_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('responder_id')->nullable();
            $table->foreign('responder_id')->references('id')->on('responders')->onDelete('cascade');
            $table->text("message")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responder_messages');
    }
};
