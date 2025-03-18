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
        Schema::create('responder_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("incident_id");
            $table->foreign('incident_id')->references('id')->on('incidents')->onDelete('cascade');
            $table->unsignedBigInteger("responder_id");
            $table->foreign('responder_id')->references('id')->on('responders')->onDelete('cascade');
            $table->unsignedBigInteger("incident_statuses");
            $table->foreign('incident_statuses')->references('id')->on('incident_statuses')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responder_logs');
    }
};
