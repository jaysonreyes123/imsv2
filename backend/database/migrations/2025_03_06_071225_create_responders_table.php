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
        Schema::create('responders', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('responder_types')->nullable();
            $table->foreign('responder_types')->references('id')->on('responder_types')->onDelete('cascade');

            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('contact_no')->nullable();

            $table->string('email_address')->nullable();
            $table->string('password')->nullable();

            $table->unsignedBigInteger('statuses')->nullable();
            $table->foreign('statuses')->references('id')->on('statuses')->onDelete('cascade');


            $table->string('assigned_to')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');

            $table->string('source')->default('crm');
            $table->integer('deleted')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responders');
    }
};
