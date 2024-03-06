<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('email_verified_at')->nullable();
            $table->string('password');
            $table->decimal('credit', 10, 2)->default(0); // Added credit column
            $table->rememberToken();
            $table->timestamps();
        
            // You don't need to explicitly set primary key, 'id' method already does that
            // $table->primary('user_id');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

