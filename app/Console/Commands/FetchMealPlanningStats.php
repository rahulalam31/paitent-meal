<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

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


    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $startDate = now()->subYears(2)->format('Y-m-d');
        $endDate = now()->format('Y-m-d');

        $response = Http::get(route('api.patient-meal-planning.stats'), [
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        if ($response->successful()) {
            \Log::info('Meal planning stats fetched successfully', ['data' => $response->json()]);
        } else {
            \Log::error('Failed to fetch meal planning stats', ['status' => $response->status()]);
        }
    }
}
