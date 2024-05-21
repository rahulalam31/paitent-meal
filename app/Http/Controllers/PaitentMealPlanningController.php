<?php

namespace App\Http\Controllers;

use App\Models\paitent_meal_planning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaitentMealPlanningController extends Controller
{
    public function getMonthlyStats(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $results =  paitent_meal_planning::with('patient')->selectRaw("
                DATE_FORMAT(planned_date, '%M %Y') as month,
                COUNT(*) as total_plans,
                COUNT(CASE WHEN is_active = 1 THEN 1 END) as active_plans,
                AVG(total_calories) as avg_total_calories,
                AVG(total_fats) as avg_total_fats,
                AVG(total_carbs) as avg_total_carbs,
                AVG(total_proteins) as avg_total_proteins,
                GROUP_CONCAT (DATE_FORMAT(planned_date, '%d %M %Y')) as planned_days,
            ")
            ->whereBetween('planned_date', [$startDate, $endDate])
            ->groupBy(DB::raw("DATE_FORMAT(planned_date, '%Y-%m')"))
            ->get();

        $data = $results->map(function ($result) {
            $plannedDays = explode(',', $result->planned_days);
            $totalDays = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($result->month)), date('Y', strtotime($result->month)));
            $plannedPercentage = (count($plannedDays) / $totalDays) * 100;

            $allDays = array_map(function ($day) use ($result) {
                return date('d F Y', strtotime("$result->month-$day"));
            }, range(1, $totalDays));

            $skippedDays = array_diff($allDays, $plannedDays);

            return [
                'month' => $result->month,
                'planned_percentage' => round($plannedPercentage, 2) . ' %',
                'avg_total_calories' => round($result->avg_total_calories, 2),
                'avg_total_fats' => round($result->avg_total_fats, 2),
                'avg_total_carbs' => round($result->avg_total_carbs, 2),
                'avg_total_proteins' => round($result->avg_total_proteins, 2),
                'days_planning_skipped' => $skippedDays,
            ];
        });

        return response()->json(['data' => $data]);
    }

}
