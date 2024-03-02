<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Migration
{
    public function up()
    {
        // Create a new user record
        $userId = DB::table('users')->insertGetId([
            'first_name' => 'Vladko',
            'last_name' => 'Gostik',
            'email' => 'gostikvladko9@gmail.com',
            'password' => Hash::make('gostik753'), // Hashing the password
            'phone_number' => '0944160283',
            'receive_notifications' => true,
            'role' => 3, // Admin role
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create an admin record linked to the user
        DB::table('admins')->insert([
            'user_id' => $userId,

        ]);
    }

    public function down()
    {
        // Remove the admin record
        DB::table('admins')->delete();

        // Remove the user record
        DB::table('users')->where('email', 'gostikvladko9@gmail.com')->delete();
    }
}
