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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string("subject", "255");
            $table->foreignId("owner_id")->references("id")->on("users");
            $table->foreignId("category_id")->references("id")->on("categories");
            $table->enum("status", ["New", "Solved", "Accepted", "Not Accepted", "Delayed"])->default("New")->nullable();
            $table->longText("description")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
