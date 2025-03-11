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
        Schema::create('logistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->references('id')->on('users');
            $table->foreignId('project_id')->references('id')->on('projects');
            $table->foreignId('manager_id')->references('id')->on('users');
            $table->foreignId('assigned_to')->references('id')->on('users');
            $table->dateTime("registration_date");
            $table->dateTime("shipment_date");
            $table->enum("status", ["Pending", "In Shipment", "Completed", "Delayed", "Canceled"])->default("Pending")->nullable();
            $table->longText("notes")->nullable();
            $table->boolean("archive")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logistics');
    }
};
