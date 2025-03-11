<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Remove the `assigned_to` column
            $table->dropForeign(['assigned_to']); // Drop the foreign key if it exists
            $table->dropColumn('assigned_to'); // Drop the column itself
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Add the column back in case of rollback
            $table->foreignId('assigned_to')->constrained('users')->nullable();
        });
    }

};
