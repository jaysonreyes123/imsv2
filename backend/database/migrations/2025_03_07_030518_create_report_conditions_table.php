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
        Schema::create('report_conditions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("report_id")->nullable();
            $table->foreign('report_id')->references('id')->on('reports')->onDelete('cascade');
            $table->string('module');
            $table->string("column")->nullable();
            $table->string("operator")->nullable();
            $table->string("value")->nullable();
            $table->string("type")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_conditions');
    }
};
