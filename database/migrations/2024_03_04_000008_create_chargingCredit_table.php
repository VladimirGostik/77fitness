<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChargingCreditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('charging_credits', function (Blueprint $table) {
            $table->id(); // Use auto-incrementing primary key

            $table->string('external_transaction_id')->unique()->nullable(); // External transaction ID from payment gateway
            $table->unsignedBigInteger('client_id'); // ID of the client being charged
            $table->unsignedBigInteger('admin_id')->nullable(); // Optional admin ID for admin-initiated credit
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3);
            $table->string('payment_method', 255);
            $table->string('payment_status', 255);
            $table->timestamps();

            $table->foreign('client_id')->references('user_id')->on('clients')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('set null'); // Set null if admin is deleted

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('charging_credits');
    }
}
