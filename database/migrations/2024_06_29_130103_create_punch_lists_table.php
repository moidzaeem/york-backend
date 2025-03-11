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
        Schema::create('punch_lists', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('client_id')->references('id')->on('users');
            $table->foreignId('project_id')->references('id')->on('projects');
            $table->foreignId('manager_id')->references('id')->on('users');
            $table->foreignId('assigned_to')->references('id')->on('users');
            $table->dateTime("end_date");
            $table->string("address");
            $table->enum("priority", ["High", "Medium", "Normal", "Low"])->default("Normal")->nullable();
            $table->enum("status", ["Pending", "Canceled", "Completed", "Postponed", "Ongoing"])->default("Pending")->nullable();
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
        Schema::dropIfExists('punch_lists');
    }
};
