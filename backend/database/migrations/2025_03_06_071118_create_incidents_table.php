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
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();

            $table->string('incident_no')->nullable();


            $table->unsignedBigInteger('incident_types')->nullable();
            $table->foreign('incident_types')->references('id')->on('incident_types')->onDelete('cascade');

            $table->unsignedBigInteger('incident_statuses')->nullable();
            $table->foreign('incident_statuses')->references('id')->on('incident_statuses')->onDelete('cascade');

            $table->unsignedBigInteger('incident_priorities')->nullable();
            $table->foreign('incident_priorities')->references('id')->on('incident_priorities')->onDelete('cascade');

            $table->unsignedBigInteger('contact_statuses')->nullable();
            $table->foreign('contact_statuses')->references('id')->on('contact_statuses')->onDelete('cascade');

            $table->time('time_of_incident')->nullable();
            $table->date('date_of_incident')->nullable();
            
            $table->text('remarks')->nullable();

            $table->string('location')->nullable();
            $table->string('street_name')->nullable();
            $table->string('nearest_landmark')->nullable();
            $table->string('coordinates')->nullable();

            $table->string('caller_firstname')->nullable();
            $table->string('caller_lastname')->nullable();
            $table->string('caller_contact')->nullable();
            $table->unsignedBigInteger('caller_types')->nullable();
            $table->foreign('caller_types')->references('id')->on('caller_types')->onDelete('cascade');

            $table->unsignedBigInteger('contacts')->nullable();
            $table->foreign('contacts')->references('id')->on('contacts')->onDelete('cascade');

            $table->string('incident_resolution')->nullable();

            $table->json('responder_types')->default("[]");
            $table->string('assigned_by')->nullable();


            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            
            $table->string('source')->default('crm');
            $table->tinyInteger('deleted')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
