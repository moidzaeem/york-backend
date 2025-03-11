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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->references('id')->on('users');
            $table->foreignId('sales_person_id')->references('id')->on('users');
            $table->foreignId('category_id')->references('id')->on('categories');
            $table->float("offer_price", 2)->default(0.00);
            $table->dateTime("date");
            $table->enum("status", ["Bid Given", "Bid Not Given", "Accepted", "Rejected"])->default("Bid Not Given")->nullable();
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
        Schema::dropIfExists('sales');
    }
};
