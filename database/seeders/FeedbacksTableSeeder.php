<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class FeedbacksTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Generate dummy feedbacks against users
        for ($i = 1; $i <= 20; $i++) {
            DB::table('feedbacks')->insert([
                'user_id' => $faker->numberBetween(1, 10),
                'title' => $faker->sentence,
                'description' => $faker->paragraph,
                'category' => $faker->randomElement(['bug report', 'feature request', 'improvement']),
            ]);
        }
    }
}
