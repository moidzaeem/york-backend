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
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->string("name", 255);
            $table->enum("type", ["png", "jpg", "jpeg", "pdf", "doc", "docx", "ppt", "pptx"]);
            $table->string("path", 255);
            $table->string('photoable_type');
            $table->unsignedBigInteger('photoable_id');
            $table->timestamps();
            $table->index(['photoable_type', 'photoable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};
