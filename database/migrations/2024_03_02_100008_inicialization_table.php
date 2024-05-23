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
        $users = [
            [
                'first_name' => 'Chris',
                'last_name' => 'Baqqero',
                'email' => 'chrisbaqqero@gmail.com',
                'password' => Hash::make('chrisbaqqero'),
                'phone_number' => '0948512283',
                'receive_notifications' => true,
                'role' => 2,
            ],
            [
                'first_name' => 'Jano',
                'last_name' => 'Osrihansky',
                'email' => 'janoosrihansky@gmail.com',
                'password' => Hash::make('janoosrihansky'),
                'phone_number' => '0911999000',
                'receive_notifications' => true,
                'role' => 2,
            ],
            [
                'first_name' => 'Laura',
                'last_name' => 'Kovacova',
                'email' => 'laurakovacova@gmail.com',
                'password' => Hash::make('laurakovacova'),
                'phone_number' => '0911555111',
                'receive_notifications' => true,
                'role' => 2,
            ],
            [
                'first_name' => 'Matej',
                'last_name' => 'Novak',
                'email' => 'matejnovak@gmail.com',
                'password' => Hash::make('matejnovak'),
                'phone_number' => '0911777222',
                'receive_notifications' => true,
                'role' => 2,
            ],
            [
                'first_name' => 'Naty',
                'last_name' => 'Hruskova',
                'email' => 'natyhruskova@gmail.com',
                'password' => Hash::make('natyhruskova'),
                'phone_number' => '0911888333',
                'receive_notifications' => true,
                'role' => 2,
            ],
            [
                'first_name' => 'Tamara',
                'last_name' => 'Iglarova',
                'email' => 'tamaraiglarova@gmail.com',
                'password' => Hash::make('tamaraiglarova'),
                'phone_number' => '0911555000',
                'receive_notifications' => true,
                'role' => 2,
            ],
            [
                'first_name' => 'Tomas',
                'last_name' => 'Petrovič',
                'email' => 'tomaspetrovic@gmail.com',
                'password' => Hash::make('tomaspetrovic'),
                'phone_number' => '0911999444',
                'receive_notifications' => true,
                'role' => 2,
            ],
        ];

        $trainerData = [
            [
                'specialization' => 'Strength Training',
                'description' => 'Specializujem sa na silové tréningy pre maximálny výkon a vytrvalosť. Moje tréningy sú zamerané na rozvoj sily, výbušnosti a vytrvalosti, pričom využívam moderné techniky a metódy. Kladiem dôraz na správnu techniku a bezpečnosť, aby som zabezpečil optimálne výsledky pre každého klienta.',
                'experience' => '5 rokov v silových tréningoch',
                'session_price' => 20,
                'profile_photo' => 'chris.jpg',
            ],
            [
                'specialization' => 'Cardio Workouts',
                'description' => 'Pomáham ľuďom zlepšiť kardiovaskulárne zdravie a vytrvalosť. Moje tréningy zahŕňajú rôzne formy kardio cvičení, od behu a bicyklovania až po intenzívne intervalové tréningy. Mám bohaté skúsenosti s tvorbou individuálnych tréningových plánov, ktoré pomáhajú klientom dosiahnuť ich fitness ciele.',
                'experience' => '7 rokov ako kardio tréner',
                'session_price' => 18,
                'profile_photo' => 'jano.jpg',
            ],
            [
                'specialization' => 'Yoga and Flexibility',
                'description' => 'Učím jogu a zlepšujem flexibilitu pre lepšie zdravie a relaxáciu. Moje lekcie sú zamerané na zlepšenie fyzickej i duševnej pohody, pričom kombinujem tradičné jogové techniky s modernými prístupmi. Verím, že pravidelné cvičenie jogy prispieva k lepšiemu zvládaniu stresu a celkovému zlepšeniu kvality života.',
                'experience' => '4 roky učenia jogy',
                'session_price' => 25,
                'profile_photo' => 'laura.jpg',
            ],
            [
                'specialization' => 'Functional Training',
                'description' => 'Zameriavam sa na funkčné tréningy pre každodenný život. Moje tréningy sú navrhnuté tak, aby zlepšovali celkovú pohyblivosť, koordináciu a silu, ktoré sú nevyhnutné pre každodenné aktivity. Používam rôzne pomôcky a cvičenia, ktoré simulujú reálne pohyby, čím pomáham klientom dosiahnuť lepšiu fyzickú kondíciu a prevenciu zranení.',
                'experience' => '6 rokov v tréningoch',
                'session_price' => 22,
                'profile_photo' => 'matej.jpg',
            ],
            [
                'specialization' => 'Nutrition Coaching',
                'description' => 'Poradím vám, ako správne stravovať pre dosiahnutie vašich cieľov. Ako nutričný poradca pomáham klientom vytvárať individuálne stravovacie plány, ktoré podporujú ich fitness ciele a zlepšujú celkové zdravie. Venujem sa vzdelávaniu o zdravom stravovaní a pomáham klientom zmeniť stravovacie návyky, ktoré vedú k dlhodobým výsledkom.',
                'experience' => '3 roky ako nutričný poradca',
                'session_price' => 30,
                'profile_photo' => 'naty.jpg',
            ],
            [
                'specialization' => 'HIIT Training',
                'description' => 'Vysoko intenzívne intervalové tréningy pre maximálne spaľovanie tuku. Moje tréningy sú navrhnuté tak, aby zvyšovali metabolizmus a zlepšovali celkovú kondíciu v krátkom čase. Používam rôzne cvičebné techniky, ktoré sú zamerané na maximálnu intenzitu a efektivitu, čo vedie k rýchlemu spaľovaniu kalórií a zlepšeniu svalového tonusu.',
                'experience' => '2 roky ako HIIT tréner',
                'session_price' => 28,
                'profile_photo' => 'tami.jpg',
            ],
            [
                'specialization' => 'Bodybuilding',
                'description' => 'Pomáham budovať svalovú hmotu a dosiahnuť kulturistické ciele. Moje tréningy sú zamerané na intenzívne silové cvičenia, správnu výživu a doplnky stravy, ktoré podporujú rast svalov. Kladiem dôraz na individuálny prístup, aby som zabezpečil, že každý klient dosiahne svoje ciele v kulturistike efektívne a bezpečne.',
                'experience' => '8 rokov v kulturistike',
                'session_price' => 35,
                'profile_photo' => 'tomas.jpg',
            ],
        ];

        foreach ($users as $index => $userData) {
            $userId = DB::table('users')->insertGetId(array_merge($userData, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));

            DB::table('trainers')->insert(array_merge($trainerData[$index], [
                'user_id' => $userId,
            ]));
        }

        // Create an admin record linked to the user
        $adminId = DB::table('users')->insertGetId([
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

        DB::table('admins')->insert([
            'user_id' => $adminId,
        ]);

        $articles = [
            [
                'title' => 'The Importance of Regular Exercise',
                'content' => 'Regular exercise is crucial for maintaining good health and well-being. Engaging in physical activity has numerous benefits that extend beyond physical fitness. Here are some key reasons why regular exercise is important:

                    1. Improves Cardiovascular Health: Exercise strengthens the heart and improves blood circulation, reducing the risk of cardiovascular diseases such as heart attack, stroke, and high blood pressure.
                    
                    2. Enhances Mental Health: Physical activity stimulates the release of endorphins, the bodys natural mood lifters. It can help alleviate symptoms of depression and anxiety, boost self-esteem, and improve sleep quality.
                    
                    3. Supports Weight Management: Regular exercise helps control weight by burning calories and increasing metabolism. Combined with a balanced diet, it can help prevent obesity and related health issues.
                    
                    4. Strengthens Muscles and Bones: Weight-bearing exercises like walking, running, and resistance training help build and maintain strong muscles and bones, reducing the risk of osteoporosis and fractures.
                    
                    5. Boosts Immune System: Regular physical activity enhances the immune system, making the body more efficient at fighting off infections and diseases.
                    
                    6. Increases Longevity: Studies have shown that regular exercise can add years to your life by reducing the risk of chronic diseases and promoting overall health.
                    
                    7. Enhances Flexibility and Balance: Exercise improves flexibility, balance, and coordination, which are essential for daily activities and can help prevent falls and injuries, especially in older adults.
                    
                    8. Provides Social Benefits: Participating in group exercises or sports can foster social connections, reduce feelings of loneliness, and provide a sense of community and support.
                    
                    Incorporating regular exercise into your routine doesnt have to be complicated. Even simple activities like walking, cycling, swimming, or gardening can make a significant difference in your health. The key is to find activities you enjoy and can sustain in the long term.',
                'cover_image' => 'cvicenie.jpg',
                'user_id' => 1,
            ],
            [
                'title' => 'Healthy Eating Habits',
                'content' => 'Healthy eating habits are essential for maintaining optimal health and well-being. A balanced diet provides the necessary nutrients that the body needs to function correctly and helps prevent chronic diseases. Here are some tips for developing and maintaining healthy eating habits:

                    1. Eat a Variety of Foods: Include a wide range of fruits, vegetables, whole grains, lean proteins, and healthy fats in your diet. Different foods provide different nutrients, so variety is key to a balanced diet.
                    
                    2. Focus on Whole Foods: Choose whole, unprocessed foods over processed ones. Whole foods are typically higher in nutrients and lower in added sugars, salt, and unhealthy fats.
                    
                    3. Control Portion Sizes: Be mindful of portion sizes to avoid overeating. Use smaller plates, listen to your bodys hunger cues, and stop eating when you feel satisfied, not overly full.
                    
                    4. Stay Hydrated: Drink plenty of water throughout the day. Water is essential for digestion, nutrient absorption, and overall health. Limit sugary drinks and sodas.
                    
                    5. Limit Added Sugars: Reduce your intake of foods and beverages with added sugars, such as candies, pastries, and soft drinks. Opt for naturally sweet foods like fruits to satisfy your sweet tooth.
                    
                    6. Choose Healthy Fats: Incorporate healthy fats into your diet, such as those found in avocados, nuts, seeds, and olive oil. Limit saturated and trans fats found in fried foods and baked goods.
                    
                    7. Eat Mindfully: Pay attention to what and how you eat. Avoid distractions like TV or smartphones during meals. Eating mindfully can help you enjoy your food more and prevent overeating.
                    
                    8. Plan Your Meals: Plan your meals and snacks ahead of time to ensure you have healthy options available. Preparing meals at home gives you more control over ingredients and portion sizes.
                    
                    9. Include Protein: Ensure you get enough protein in your diet to support muscle growth and repair. Good sources include lean meats, poultry, fish, eggs, dairy, beans, and legumes.
                    
                    10. Enjoy Treats in Moderation: Its okay to indulge in your favorite treats occasionally, but moderation is key. Balance indulgences with healthy choices throughout the day.
                    
                    By adopting these healthy eating habits, you can improve your overall health, boost your energy levels, and reduce the risk of chronic diseases. Remember that small, sustainable changes are more effective in the long term than drastic, short-lived diets.',
                'cover_image' => 'healty_food.jpg',
                'user_id' => 2,
            ],
            [
                'title' => 'Effective Workouts for Beginners',
                'content' => 'Starting a workout routine can be daunting, especially for beginners. However, with the right approach and guidance, it can be an enjoyable and rewarding experience. Here are some effective workouts for beginners to help you get started on your fitness journey:

                    1. Walking: Walking is a simple, low-impact exercise that is easy to incorporate into your daily routine. Aim for at least 30 minutes of brisk walking most days of the week.
                    
                    2. Bodyweight Exercises: These exercises use your body weight as resistance and are great for building strength and endurance. Examples include push-ups, squats, lunges, and planks.
                    
                    3. Yoga: Yoga is excellent for improving flexibility, balance, and mental well-being. Start with beginner-friendly poses and gradually progress to more advanced ones.
                    
                    4. Cycling: Whether you use a stationary bike or ride outdoors, cycling is a great cardiovascular exercise that can help improve your heart health and stamina.
                    
                    5. Strength Training: Incorporate light weights or resistance bands into your routine to build muscle and increase strength. Focus on major muscle groups like legs, back, chest, and arms.
                    
                    6. Swimming: Swimming is a full-body workout that is gentle on the joints. Its an excellent option for beginners, especially those with joint issues or injuries.
                    
                    7. Dance Workouts: Dancing is a fun way to get moving and burn calories. Join a dance class or follow online dance workout videos to get started.
                    
                    8. Group Fitness Classes: Joining a group fitness class can provide motivation and support. Look for beginner-friendly classes like aerobics, Zumba, or Pilates.
                    
                    9. Stretching: Dont forget to include stretching in your routine. It helps improve flexibility, prevent injuries, and reduce muscle soreness.
                    
                    10. Consistency is Key: The most important aspect of any workout routine is consistency. Start with manageable goals and gradually increase the intensity and duration of your workouts.
                    
                    Tips for Beginners:
                    
                    Start Slow: Begin with shorter, less intense workouts and gradually increase the intensity as your fitness level improves.
                    Listen to Your Body: Pay attention to how your body feels and avoid pushing yourself too hard, especially in the beginning.
                    Stay Hydrated: Drink plenty of water before, during, and after your workouts.
                    Rest and Recover: Allow your body time to rest and recover between workouts to prevent burnout and injuries.
                    Set Realistic Goals: Set achievable fitness goals and track your progress to stay motivated.
                    By incorporating these effective workouts into your routine, you can build a solid foundation for a healthy and active lifestyle. Remember to be patient with yourself and enjoy the process of becoming fitter and stronger.',
                'cover_image' => 'workouts_for_begginers.jpg',
                'user_id' => 3,
            ],
            [
                'title' => 'The Benefits of Cardiovascular Exercise',
                'content' => 'Cardiovascular exercise, also known as aerobic exercise, is any activity that increases your heart rate and breathing. It is an essential component of a well-rounded fitness routine and offers numerous health benefits. Here are some of the key benefits of cardiovascular exercise:

                1. Improves Heart Health: Cardiovascular exercise strengthens the heart, making it more efficient at pumping blood. This reduces the risk of heart disease, high blood pressure, and stroke.
                
                2. Enhances Lung Capacity: Regular aerobic exercise improves lung function and increases the amount of oxygen your body can use during physical activity.
                
                3. Aids in Weight Management: Cardiovascular exercise burns calories and helps maintain a healthy weight. It can also boost metabolism, making it easier to manage weight in the long term.
                
                4. Reduces Stress and Anxiety: Physical activity triggers the release of endorphins, which are natural mood lifters. Cardiovascular exercise can help reduce stress, anxiety, and symptoms of depression.
                
                5. Increases Energy Levels: Regular aerobic exercise improves overall stamina and endurance, leading to increased energy levels throughout the day.
                
                6. Lowers Risk of Chronic Diseases: Cardiovascular exercise helps lower the risk of chronic diseases such as type 2 diabetes, obesity, and certain types of cancer.
                
                7. Enhances Sleep Quality: Engaging in regular aerobic exercise can improve the quality of your sleep, helping you fall asleep faster and enjoy deeper sleep.
                
                8. Boosts Immune System: Regular cardiovascular exercise strengthens the immune system, making it more effective at fighting off infections and illnesses.
                
                9. Improves Mental Health: Aerobic exercise has been shown to enhance cognitive function, memory, and overall brain health. It can also reduce the risk of cognitive decline and dementia in older adults.
                
                10. Promotes Longevity: Studies have shown that individuals who engage in regular cardiovascular exercise tend to live longer and healthier lives.
                
                Types of Cardiovascular Exercise:
                
                Walking: A simple and accessible form of aerobic exercise suitable for all fitness levels.
                Running/Jogging: Higher-intensity options that provide excellent cardiovascular benefits.
                Cycling: Whether outdoors or on a stationary bike, cycling is a great way to get your heart pumping.
                Swimming: A full-body workout that is easy on the joints and highly effective for cardiovascular health.
                Dancing: A fun and enjoyable way to incorporate aerobic exercise into your routine.
                Rowing: An excellent full-body workout that improves cardiovascular fitness and muscle strength.
                Jump Rope: A high-intensity exercise that can be done anywhere and provides a great cardiovascular workout.
                Group Fitness Classes: Aerobics, Zumba, kickboxing, and other group classes offer structured and motivating cardiovascular workouts.
                To reap the benefits of cardiovascular exercise, aim for at least 150 minutes of moderate-intensity aerobic activity or 75 minutes of high-intensity aerobic activity per week, as recommended by health guidelines. Remember to choose activities you enjoy and vary your routine to keep it interesting and challenging.',
                'cover_image' => 'cardio.jpg',
                'user_id' => 4,
            ],
            [
                'title' => 'Building Muscle with Strength Training',
                'content' => 'Strength training, also known as resistance training or weightlifting, is a crucial component of a well-rounded fitness program. It involves exercises that improve muscle strength, endurance, and overall fitness by working against a resistance. Here are the benefits and tips for building muscle with strength training:

                    1. Increases Muscle Mass: Strength training promotes muscle growth by stimulating muscle fibers to repair and grow stronger after each workout.
                    
                    2. Enhances Metabolism: Building muscle increases your resting metabolic rate, meaning youll burn more calories even when at rest.
                    
                    3. Improves Bone Density: Resistance exercises help strengthen bones, reducing the risk of osteoporosis and fractures.
                    
                    4. Boosts Functional Strength: Strength training enhances your ability to perform daily activities with ease, such as lifting, carrying, and climbing stairs.
                    
                    5. Supports Weight Management: Increased muscle mass helps regulate body weight by burning more calories and reducing body fat.
                    
                    6. Enhances Athletic Performance: Strength training improves overall athletic performance by increasing power, speed, and agility.
                    
                    7. Reduces Injury Risk: Strengthening muscles, tendons, and ligaments helps prevent injuries by providing better support and stability to joints.
                    
                    8. Improves Mental Health: Regular strength training can boost self-esteem, reduce symptoms of anxiety and depression, and improve overall mental well-being.
                    
                    Types of Strength Training Exercises:
                    
                    Bodyweight Exercises: Push-ups, squats, lunges, and planks use your body weight as resistance.
                    Free Weights: Dumbbells, barbells, and kettlebells allow for a wide range of exercises targeting different muscle groups.
                    Resistance Bands: Versatile and portable bands that provide varying levels of resistance.
                    Weight Machines: Equipment found in gyms that target specific muscle groups and provide guided movements.
                    Compound Exercises: Movements that work multiple muscle groups at once, such as deadlifts, bench presses, and pull-ups.
                    Tips for Effective Strength Training:
                    
                    Start with Proper Form: Focus on mastering the correct technique before increasing weights to prevent injuries.
                    Gradually Increase Resistance: As you become stronger, progressively increase the weight or resistance to continue challenging your muscles.
                    Include Rest Days: Allow your muscles time to recover and repair by scheduling rest days between strength training sessions.
                    Warm-Up and Cool Down: Begin each session with a warm-up to prepare your muscles and end with a cool-down to aid recovery.
                    Set Realistic Goals: Set achievable strength training goals and track your progress to stay motivated.
                    Stay Consistent: Regularity is key to seeing results. Aim for at least two to three strength training sessions per week.
                    Incorporating strength training into your fitness routine can lead to significant improvements in physical health and overall well-being. Whether you prefer lifting weights at the gym or doing bodyweight exercises at home, the benefits of strength training are undeniable.',
                'cover_image' => 'strenght_training.jpg',
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
