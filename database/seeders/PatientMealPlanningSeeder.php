<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class PatientMealPlanningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $now = now();

        for ($i = 0; $i < 1000; $i++) {

            DB::table('paitent_meal_plannings')->insert([
                'patient_id' => $faker->uuid(),
                'planned_date' => $faker->unique()->dateTimeBetween($startDate = '-2 years', $endDate = 'now')->format("Y-m-d"),
                'total_calories' => $faker->numberBetween(1000, 3000),
                'total_fats' => $faker->numberBetween(10, 100),
                'total_carbs' => $faker->numberBetween(100, 300),
                'total_proteins' => $faker->numberBetween(50, 200),
                'is_active' => $faker->boolean,
                'created_by' => $faker->uuid(),
                'created_at' => $faker->dateTimeBetween($startDate = '-2 years', $endDate = 'now')->format("Y-m-d"),
                'updated_by' => $faker->uuid(),
                'updated_at' => $faker->dateTimeBetween($startDate = '-2 years', $endDate = 'now')->format("Y-m-d"),
            ]);
        }
    }
}
