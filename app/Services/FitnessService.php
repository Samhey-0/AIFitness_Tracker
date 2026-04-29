<?php

namespace App\Services;

use App\Models\Workout;
use App\Models\DailyStep;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class FitnessService
{
    public function getDashboardData()
    {
        $userId = Auth::id();

        $lastWorkout = Workout::where('user_id', $userId)
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->first();
        
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        $weeklySteps = DailyStep::where('user_id', $userId)
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->sum('count');
        
        $todayStepsRecord = DailyStep::where('user_id', $userId)
            ->where('date', Carbon::today()->toDateString())
            ->first();
            
        $todaySteps = $todayStepsRecord ? $todayStepsRecord->count : 0;
        $stepGoal = $todayStepsRecord ? $todayStepsRecord->goal : 10000;

        return compact('lastWorkout', 'weeklySteps', 'todaySteps', 'stepGoal');
    }

    public function getChartData()
    {
        $userId = Auth::id();

        // Get steps for the last 7 days
        $steps = DailyStep::where('user_id', $userId)
            ->where('date', '>=', Carbon::now()->subDays(6)->toDateString())
            ->orderBy('date', 'asc')
            ->get();

        // Get workouts for the last 7 days, sum duration per day
        $workouts = Workout::where('user_id', $userId)
            ->where('date', '>=', Carbon::now()->subDays(6)->toDateString())
            ->selectRaw('date, SUM(duration) as total_duration')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Format data for Chart.js (filling missing days with 0)
        $labels = [];
        $stepsData = [];
        $durationData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateString = $date->toDateString();
            $labels[] = $date->format('D'); // Mon, Tue, etc.

            $step = $steps->firstWhere('date', $dateString);
            $stepsData[] = $step ? $step->count : 0;

            $workout = $workouts->firstWhere('date', $dateString);
            $durationData[] = $workout ? (int)$workout->total_duration : 0;
        }

        return compact('labels', 'stepsData', 'durationData');
    }
}
