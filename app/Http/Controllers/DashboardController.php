<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FitnessService;
use App\Models\Workout;
use App\Models\DailyStep;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $fitnessService;

    public function __construct(FitnessService $fitnessService)
    {
        $this->fitnessService = $fitnessService;
    }

    public function index()
    {
        $data = $this->fitnessService->getDashboardData();
        return view('index', $data);
    }

    public function track()
    {
        return view('track');
    }

    public function stats()
    {
        $data = $this->fitnessService->getChartData();
        return view('stats', $data);
    }

    public function storeWorkout(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'duration' => 'required|numeric|min:0', // in seconds from frontend
        ]);

        $minutes = round($validated['duration'] / 60);
        if ($minutes < 1) $minutes = 1; // minimum 1 min

        Workout::create([
            'user_id' => Auth::id(),
            'type' => $validated['type'],
            'duration' => $minutes,
            'date' => Carbon::today()->toDateString(),
        ]);

        return response()->json(['message' => 'Workout saved successfully', 'duration' => $minutes]);
    }

    public function addSteps(Request $request)
    {
        $validated = $request->validate([
            'count' => 'required|integer|min:1'
        ]);

        $step = DailyStep::firstOrCreate(
            ['user_id' => Auth::id(), 'date' => Carbon::today()->toDateString()],
            ['count' => 0, 'goal' => 10000]
        );

        $step->increment('count', $validated['count']);

        return response()->json(['message' => 'Steps added', 'new_total' => $step->count]);
    }
}
