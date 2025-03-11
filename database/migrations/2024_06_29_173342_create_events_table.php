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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string("event_name");
            $table->foreignId('client_id')->references('id')->on('users');
            $table->foreignId('assigned_to')->references('id')->on('users');
            $table->foreignId('department_id')->references('id')->on('departments');
            $table->dateTime("due_date");
            $table->enum("status", ["Pending", "Completed", "Canceled", "Failed", "Postponed"])->default("Pending")->nullable();
            $table->longText("notes")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
