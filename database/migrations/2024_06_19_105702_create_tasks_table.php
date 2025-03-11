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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assigned_to')->references('id')->on('users');
            $table->dateTime("start_date");
            $table->dateTime("end_date");
            $table->foreignId('client_id')->references('id')->on('users');
            $table->foreignId('department_id')->references('id')->on('departments');
            $table->foreignId('category_id')->references('id')->on('categories');
            $table->enum("priority", ["High", "Medium", "Normal", "Low"])->default("Low")->nullable();
            $table->enum("status", ["0%", "10%", "20%", "30%", "40%", "50%", "60%", "70%", "80%", "90%", "100%"])->default("0%")->nullable();
            $table->boolean("archive")->default(false);
            $table->longText("notes")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
