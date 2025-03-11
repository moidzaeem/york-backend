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
        Schema::create('tenders', function (Blueprint $table) {
            $table->id();
            $table->string('project_name', 255);
            $table->foreignId('client_id')->references('id')->on('users');
            $table->foreignId('category_id')->references('id')->on('categories');
            $table->dateTime("closing_date");
            $table->enum("status", ["Offer Made", "Pending", "Won", "Lost"])->default("Pending")->nullable();
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
        Schema::dropIfExists('tenders');
    }
};
