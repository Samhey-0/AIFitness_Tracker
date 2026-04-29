<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    public function generateWorkoutPlan($goal, $equipment)
    {
        $apiKey = config('services.gemini.key');
        $baseUrl = config('services.gemini.url');
        $url = "{$baseUrl}?key={$apiKey}";

        $prompt = "Act as a world-class fitness coach and nutritionist. Based on the user's goal [{$goal}] and equipment [{$equipment}], generate a dual plan. 
        1) A structured workout. 
        2) A personalized daily diet plan (Breakfast, Lunch, Dinner, Snack) including estimated calories and macros. 
        Return ONLY a single JSON object with the keys: 'workout' (with 'title' and 'exercises' array of {name, sets, reps}) and 'diet' (with 'title' and 'meals' array of {name, calories, protein}). 
        Do not include markdown formatting or backticks.";

        try {
            $response = Http::timeout(30)->withHeaders([
                'Content-Type' => 'application/json',
            ])->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $textResponse = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
                
                // Clean the response in case the AI includes markdown backticks despite instructions
                $textResponse = preg_replace('/^```json\s*|```$/m', '', trim($textResponse));
                
                return json_decode($textResponse, true);
            }

            Log::error('Gemini API Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('AIService Exception: ' . $e->getMessage());
            return null;
        }
    }
}
