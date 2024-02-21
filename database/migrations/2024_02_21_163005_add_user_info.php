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
        Schema::table('users', function (Blueprint $table) {
            // Add new columns
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone_number')->nullable();
            $table->boolean('receive_notifications')->default(true);
            $table->tinyInteger('role')->default(1); // 1: client, 2: trainer, 3: admin

            // Remove existing 'name' column
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add back 'name' column
            $table->string('name');

            // Remove new columns
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('phone_number');
            $table->dropColumn('receive_notifications');
            $table->dropColumn('role');
        });
    }
};
