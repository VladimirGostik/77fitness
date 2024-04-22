<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('group_participants', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('client_id')->nullable(); // Client ID can be null for non-registered participants
            $table->string('name')->nullable(); // Added column for the name of non-registered participants
            $table->timestamps();
    
            $table->foreign('group_id')->references('id')->on('group_reservations')->onDelete('cascade');
            $table->foreign('client_id')->references('user_id')->on('clients')->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_participants');
    }
};

