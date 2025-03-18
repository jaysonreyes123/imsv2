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
        Schema::create('resources', function (Blueprint $table) {
            $table->id();

            $table->string('resources_name')->nullable();

            $table->unsignedBigInteger("resources_categories")->nullable();
            $table->foreign('resources_categories')->references('id')->on('resources_categories')->onDelete('cascade');

            $table->unsignedBigInteger("resources_types")->nullable();
            $table->foreign('resources_types')->references('id')->on('resources_types')->onDelete('cascade');

            $table->unsignedBigInteger("resources_statuses")->nullable();
            $table->foreign('resources_statuses')->references('id')->on('resources_statuses')->onDelete('cascade');

            $table->string('coordinates')->nullable();

            $table->string('resources_dispatchers')->nullable();

            $table->string('resources_conditions')->nullable();

            $table->integer('quantity')->default(1);
            $table->string('contact_info')->nullable();
            $table->date('date_acquired')->nullable();
            $table->text('remarks')->nullable();
            $table->string('owner')->nullable();
            
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('last_assigned')->nullable();
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
        Schema::dropIfExists('resources');
    }
};
