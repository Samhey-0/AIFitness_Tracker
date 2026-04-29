<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AIService;
use App\Models\Workout;
use App\Models\Exercise;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AIWorkoutController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index()
    {
        return view('ai-generator');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'goal' => 'required|string|max:255',
            'equipment' => 'required|string|max:255',
        ]);

        $plan = $this->aiService->generateWorkoutPlan($request->goal, $request->equipment);

        if ($request->ajax() || $request->wantsJson()) {
            if (!$plan) {
                return response()->json(['error' => 'Failed to generate plan. Please try again.'], 500);
            }
            return response()->json(['result' => $plan]);
        }

        if (!$plan) {
            return back()->with('error', 'Failed to generate plan. Please try again.');
        }

        return view('ai-generator', [
            'generatedPlan' => $plan,
            'goal' => $request->goal,
            'equipment' => $request->equipment
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'exercises' => 'required|array',
        ]);

        $workout = Workout::create([
            'user_id' => Auth::id(),
            'type' => $request->title,
            'duration' => 45, // Default duration or could be estimated
            'date' => Carbon::today()->toDateString(),
        ]);

        foreach ($request->exercises as $ex) {
            Exercise::create([
                'workout_id' => $workout->id,
                'name' => $ex['name'],
                'sets' => $ex['sets'],
                'reps' => $ex['reps'],
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Workout saved to your profile!');
    }
}
