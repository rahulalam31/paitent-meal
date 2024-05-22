<?php

namespace App\Http\Controllers;

use App\Models\paitent_meal_planning;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaitentMealPlanningController extends Controller
{
    public function getMonthlyStats(Request $request)
    {
        // dd($request);
        $startDate = Carbon::parse($request->input('start_date'))->format('d/m/y');
        $endDate = Carbon::parse($request->input('end_date'))->format('d/m/y');

        $plans = paitent_meal_planning::whereBetween('planned_date', [$startDate, $endDate])
        ->orderBy('planned_date')
        ->get()
        ->groupBy(function ($plan) {
            return Carbon::parse($plan->planned_date)->format('Y-m'); // Group by year and month
        });

    $data = $plans->map(function ($plans, $yearMonth) {
        $monthYear = Carbon::createFromFormat('Y-m', $yearMonth)->format('F Y');
        $totalPlans = $plans->count();
        $activePlans = $plans->where('is_active', true)->count();
        $totalDaysInMonth = Carbon::createFromFormat('Y-m', $yearMonth)->daysInMonth;

        $avgTotalCalories = $plans->avg('total_calories');
        $avgTotalFats = $plans->avg('total_fats');
        $avgTotalCarbs = $plans->avg('total_carbs');
        $avgTotalProteins = $plans->avg('total_proteins');

        $plannedDays = $plans->pluck('planned_date')->map(function ($date) {
            return Carbon::parse($date)->format('d F Y');
        })->toArray();

        $allDaysInMonth = collect(range(1, $totalDaysInMonth))->map(function ($day) use ($yearMonth) {
            return Carbon::createFromFormat('Y-m-d', $yearMonth . '-' . str_pad($day, 2, '0', STR_PAD_LEFT))->format('d F Y');
        });

        $skippedDays = $allDaysInMonth->diff($plannedDays);

        $plannedPercentage = ($activePlans / $totalDaysInMonth) * 100;

        return [
            'month' => $monthYear,
            'planned_percentage' => round($plannedPercentage, 2) . ' %',
            'avg_total_calories' => round($avgTotalCalories, 2),
            'avg_total_fats' => round($avgTotalFats, 2),
            'avg_total_carbs' => round($avgTotalCarbs, 2),
            'avg_total_proteins' => round($avgTotalProteins, 2),
            'days_planning_skipped' => $skippedDays->values()->all(),
        ];
    });

    return response()->json(['data' => $data->values()->all()]);
    }

}
