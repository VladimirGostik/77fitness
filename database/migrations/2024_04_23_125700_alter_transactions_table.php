<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Remove the 'type' enum column
            $table->dropColumn('type');

            // Add separate columns for reservations, group reservations, and memberships
            $table->unsignedBigInteger('id_reservation')->nullable();
            $table->unsignedBigInteger('id_group_reservation')->nullable();
            $table->unsignedBigInteger('id_membership')->nullable();

            // Define foreign key constraints for new columns
            $table->foreign('id_reservation')->references('id')->on('reservations')->onDelete('cascade');
            $table->foreign('id_group_reservation')->references('id')->on('group_reservations')->onDelete('cascade');
            $table->foreign('id_membership')->references('id')->on('memberships')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Add back the 'type' enum column
            $table->enum('type', ['reservation', 'group_reservation', 'membership']);

            // Drop the new columns (assuming you don't need them anymore)
            $table->dropColumn('id_reservation');
            $table->dropColumn('id_group_reservation');
            $table->dropColumn('id_membership');
        });
    }
}
