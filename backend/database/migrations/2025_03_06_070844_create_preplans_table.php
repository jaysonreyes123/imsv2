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
        Schema::create('preplans', function (Blueprint $table) {
            $table->id();
            $table->string('preplan_name')->nullable();
            
            $table->unsignedBigInteger('incident_types')->nullable();
            $table->foreign('incident_types')->references('id')->on('incident_types')->onDelete('cascade');

            $table->text('preplan_classifications')->nullable();

            $table->text('initial_assessment')->nullable();
            $table->text('response_action')->nullable();
            $table->text('classification')->nullable();

            $table->string('incident_manager')->nullable();
            $table->string('incident_responder')->nullable();
            $table->string('support_staff')->nullable();

            $table->string('tools_and_equipment')->nullable();
            $table->string('personnel')->nullable();
            

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
        Schema::dropIfExists('preplans');
    }
};
