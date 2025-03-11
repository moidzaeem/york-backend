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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string("project_name");
            $table->foreignId('client_id')->references('id')->on('users');
            $table->foreignId('manager_id')->references('id')->on('users');
            $table->foreignId('category_id')->references('id')->on('categories');
            $table->dateTime("start_date");
            $table->dateTime("end_date");
            $table->enum("status", ["Pending", "Canceled", "Completed", "Postponed", "Ongoing"])->default("Pending")->nullable();
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
        Schema::dropIfExists('projects');
    }
};
