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
        Schema::create('back_charges', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('client_id')->references('id')->on('users');
            $table->foreignId('project_id')->references('id')->on('projects');
            $table->dateTime("date");
            $table->integer("floor");
            $table->string("unit_number");
            $table->longText("description_of_change");
            $table->longText("description_of_change_approval")->nullable();
            $table->longText("reason_for_change");
            $table->longText("reason_for_change_approval")->nullable();
            $table->longText("additional_notes")->nullable();
            $table->boolean("fix_amount")->default(1);
            $table->string("charge_amount");
            $table->string("york_signature");
            $table->string("client_signature")->nullable();
            $table->boolean("archive")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('back_charges');
    }
};
