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
            $plannedDate = $faker->unique()->dateTimeInInterval($startDate = '-2 years', $interval = '+ 1 days', $timezone = null);

            DB::table('paitent_meal_plannings')->insert([
                'patient_id' => $faker->numberBetween(1, 100),
                'planned_date' => $plannedDate,
                'total_calories' => $faker->numberBetween(1000, 3000),
                'total_fats' => $faker->numberBetween(10, 100),
                'total_carbs' => $faker->numberBetween(100, 300),
                'total_proteins' => $faker->numberBetween(50, 200),
                'is_active' => $faker->boolean,
                'created_by' => 1,
                'created_at' => $now,
                'updated_by' => null,
                'updated_at' => $now,
            ]);
        }
    }
}
