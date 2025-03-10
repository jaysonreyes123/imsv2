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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('module');
            $table->foreign('module')->references('id')->on('modules')->onDelete('cascade');
            $table->integer('module_id');
            $table->integer('comment_id')->defualt(0);
            $table->text("comment")->nullable();
            $table->unsignedBigInteger('comment_by');
            $table->foreign('comment_by')->references('id')->on('users')->onDelete('cascade');
            $table->tinyInteger('action')->default(0);
            $table->tinyInteger('deleted')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
