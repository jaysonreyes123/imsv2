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
        Schema::create('activity_related', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('activity_log_id');
            $table->foreign('activity_log_id')->references('id')->on('activity_mains')->onDelete('cascade');
            $table->unsignedBigInteger('related_module');
            $table->foreign('related_module')->references('id')->on('modules')->onDelete('cascade');
            $table->integer('related_item_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_related');
    }
};
