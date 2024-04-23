<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypPermanentkyTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('typ_permanentky', function (Blueprint $table) {
            $table->id();
            $table->string('nazov');
            $table->decimal('cena', 10, 2);
            $table->integer('dlzka_platnosti'); // 30, 90, 180, or 365 days
            $table->integer('pocet_vstupov');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('typ_permanentky');
    }
}
