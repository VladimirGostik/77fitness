<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChargingCreditTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chargingCredit', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('admin_id')->nullable(); // New column for admin ID
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3);
            $table->string('payment_method', 255);
            $table->string('payment_status', 255);
            $table->enum('payment_type', ['hotovost', 'platobna_karta']); // Changed to enum
            $table->timestamps();

            $table->foreign('client_id')->references('user_id')->on('clients')->onDelete('cascade');
            $table->foreign('admin_id')->references('user_id')->on('admins')->onDelete('set null'); // Set null if admin is deleted
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chargingCredit');
    }
}
