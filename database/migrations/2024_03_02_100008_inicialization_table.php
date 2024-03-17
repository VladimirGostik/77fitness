<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InicializationTable extends Migration
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

        $userId2 = DB::table('users')->insertGetId([
            'first_name' => 'Marek',
            'last_name' => 'Kosec',
            'email' => 'marekkosec@gmail.com',
            'password' => Hash::make('marekkosec'), // Hashing the password
            'phone_number' => '0948512283',
            'receive_notifications' => true,
            'role' => 2, // Admin role
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $userId3 = DB::table('users')->insertGetId([
            'first_name' => 'Jano',
            'last_name' => 'Halaj',
            'email' => 'janohalaj@gmail.com',
            'password' => Hash::make('janohalaj'), // Hashing the password
            'phone_number' => '0911999000',
            'receive_notifications' => true,
            'role' => 2, // Admin role
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $userId4 = DB::table('users')->insertGetId([
            'first_name' => 'Tamara',
            'last_name' => 'Iglarova',
            'email' => 'tamaraiglarova@gmail.com',
            'password' => Hash::make('tmaraiglarova'), // Hashing the password
            'phone_number' => '0911555000',
            'receive_notifications' => true,
            'role' => 2, // Admin role
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create an admin record linked to the user
        DB::table('admins')->insert([
            'user_id' => $userId,

        ]);


        $photo1 = 'marekkosec.png';
        $photo2 = 'jankotrener.png';
        $photo3 = 'tamiiglarova.png';


        DB::table('trainers')->insert([
            [
                'user_id' => $userId2, // User ID for Marek Kosec
                'specialization' => 'Stare zeny...',
                'description' => 'Specializujem sa na jednoduchych ludi napriklad',
                'experience' => '5 rokov v goleme a FTVS',
                'session_price' => 15,
                'profile_photo' => $photo1,
            ],
            [
                'user_id' => $userId3, // User ID for Jano Halaj
                'specialization' => 'Kruhace v Liesku',
                'description' => 'Robim kruhove treningy v Liesku',
                'experience' => '12 rokov',
                'session_price' => 10,
                'profile_photo' => $photo2,
            ],
            [
                'user_id' => $userId4, // User ID for Jano Halaj
                'specialization' => 'Zadok a nohy',
                'description' => 'Trenujem baby co chcu vyzerat ako ja',
                'experience' => 'rok',
                'session_price' => 22,
                'profile_photo' => $photo3,
            ]
            // Add more trainers as needed
        ]);

        $articles = [
            [
                'title' => 'The Importance of Regular Exercise',
                'content' => 'Regular exercise is crucial for maintaining good health...',
                'cover_image' => 'exercise.jpg',
                'user_id' => 1,
            ],
            [
                'title' => 'Healthy Eating Habits',
                'content' => 'A balanced diet plays a key role in achieving and maintaining good health...',
                'cover_image' => 'healthy_eating.jpg',
                'user_id' => 2,
            ],
            [
                'title' => 'Effective Workouts for Beginners',
                'content' => 'Starting a fitness journey? Here are some effective workouts for beginners...',
                'cover_image' => 'workouts_for_beginners.jpg',
                'user_id' => 3,
            ],
            [
                'title' => 'The Benefits of Cardiovascular Exercise',
                'content' => 'Cardio exercises offer numerous benefits, including improved heart health...',
                'cover_image' => 'cardio_exercise.jpg',
                'user_id' => 4,
            ],
            [
                'title' => 'Building Muscle with Strength Training',
                'content' => 'Strength training is essential for building muscle mass and strength...',
                'cover_image' => 'strength_training.jpg',
                'user_id' => 1,
            ],
        ];

        foreach ($articles as $article) {
            // Insert articles with specified user_id values
            DB::table('articles')->insert([
                'title' => $article['title'],
                'content' => $article['content'],
                'cover_image' => $article['cover_image'],
                'user_id' => $article['user_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }


    public function down()
    {
        // Remove the admin record
        DB::table('admins')->delete();

        // Remove the user record
        DB::table('users')->where('email', 'gostikvladko9@gmail.com')->delete();
    }
}
