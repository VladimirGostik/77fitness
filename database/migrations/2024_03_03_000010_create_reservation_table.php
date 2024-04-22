<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('trainer_id');
            $table->dateTime('start_reservation')->nullable();
            $table->dateTime('end_reservation')->nullable();
            $table->decimal('reservation_price', 10, 2);
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->timestamps();

            $table->foreign('client_id')->references('user_id')->on('clients')->onDelete('cascade');
            $table->foreign('trainer_id')->references('user_id')->on('trainers')->onDelete('cascade');
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
}
