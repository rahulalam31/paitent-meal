<?php

namespace App\Console\Commands;

use App\Models\paitent_meal_planning;
use Carbon\Carbon;
use Database\Seeders\PatientMealPlanningSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchMealPlanningStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mealplanning:fetch-stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch meal planning stats at regular intervals';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::enableQueryLog();
        $dateS = new Carbon('2022-08-11');
        $dateE = new Carbon('2023-08-24');
        $result = paitent_meal_planning::whereBetween('created_at', [$dateS->format('Y-m-d'), $dateE->format('Y-m-d')])->get();
        Log::info($result, DB::getQueryLog());
    }
}
