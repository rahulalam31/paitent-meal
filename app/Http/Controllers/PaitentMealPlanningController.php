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
        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::parse($request->input('end_date'));

        $monthlyPlans = paitent_meal_planning::whereBetween('planned_date', [$startDate, $endDate])
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->planned_date)->format('F Y'); // Group by month and year
            });

        $data = $monthlyPlans->map(function ($plans, $month) {
            $totalPlans = $plans->count();
            $activePlans = $plans->where('is_active', 1)->count();
            $totalDaysInMonth = Carbon::parse($plans->first()->planned_date)->daysInMonth;

            $avgTotalCalories = $plans->avg('total_calories');
            $avgTotalFats = $plans->avg('total_fats');
            $avgTotalCarbs = $plans->avg('total_carbs');
            $avgTotalProteins = $plans->avg('total_proteins');

            $plannedDays = $plans->pluck('planned_date')->map(function ($date) {
                return Carbon::parse($date)->format('d F Y');
            })->toArray();

            $allDaysInMonth = collect(range(1, $totalDaysInMonth))->map(function ($day) use ($plans) {
                return Carbon::parse($plans->first()->planned_date)->format('F Y') . ' ' . str_pad($day, 2, '0', STR_PAD_LEFT);
            })->map(function ($day) {
                return Carbon::parse($day)->format('d F Y');
            });

            $skippedDays = $allDaysInMonth->diff($plannedDays);

            $plannedPercentage = ($activePlans / $totalDaysInMonth) * 100;

            return [
                'month' => $month,
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
