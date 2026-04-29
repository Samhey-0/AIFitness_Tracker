@extends('layouts.layout')
@section('header_title', 'Dashboard Overview')

@section('content')
<div class="space-y-8" x-data="dashboard({{ $todaySteps }}, {{ $stepGoal }})">
    
    <!-- Welcome Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white dark:bg-card p-6 md:p-8 rounded-3xl shadow-xl shadow-gray-200/40 dark:shadow-none border border-gray-100 dark:border-gray-800">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Welcome back, {{ auth()->user()->name }}! 👋</h2>
            <p class="text-gray-500 font-medium mt-2">Ready to crush your goals today?</p>
        </div>
        <button @click="addSteps(500)" :disabled="loading" class="flex items-center gap-2 px-6 py-3.5 bg-gradient-to-r from-primary to-blue-500 text-white font-bold rounded-2xl shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:-translate-y-1 transition-all disabled:opacity-50 w-full md:w-auto justify-center">
            <i data-lucide="plus" class="w-5 h-5"></i>
            <span x-show="!loading">Log 500 Steps</span>
            <span x-show="loading" x-cloak>Logging...</span>
        </button>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column (Steps Circle) -->
        <div class="lg:col-span-1 bg-white dark:bg-card rounded-3xl p-8 shadow-xl shadow-gray-200/40 dark:shadow-none border border-gray-100 dark:border-gray-800 flex flex-col items-center justify-center relative overflow-hidden">
            <div class="absolute top-0 right-0 p-6 opacity-10 pointer-events-none">
                <i data-lucide="footprints" class="w-32 h-32 text-primary"></i>
            </div>
            
            <h3 class="text-xl font-bold w-full text-left mb-8 z-10">Today's Steps</h3>
            
            <div class="relative w-64 h-64 z-10">
                <!-- SVG Circle -->
                <svg class="w-full h-full transform -rotate-90 filter drop-shadow-xl" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="45" fill="none" stroke="currentColor" class="text-gray-100 dark:text-gray-800" stroke-width="8"></circle>
                    <circle cx="50" cy="50" r="45" fill="none" stroke="currentColor" class="text-primary transition-all duration-1000 ease-out" stroke-width="8" stroke-linecap="round" stroke-dasharray="283" :stroke-dashoffset="283 - (283 * Math.min(steps / goal, 1))"></circle>
                </svg>
                <!-- Inner Text -->
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <div class="bg-primary/10 p-3 rounded-full mb-2">
                        <i data-lucide="footprints" class="w-8 h-8 text-primary"></i>
                    </div>
                    <span class="text-5xl font-extrabold tracking-tight" x-text="steps"></span>
                    <span class="text-sm text-gray-400 font-bold mt-1 uppercase tracking-widest">/ <span x-text="goal"></span></span>
                </div>
            </div>
            <p class="text-sm font-medium text-gray-500 mt-8 text-center z-10 px-4">You're doing great! Keep it up to reach your daily goal.</p>
        </div>

        <!-- Right Column (Stats & Recent) -->
        <div class="lg:col-span-2 flex flex-col gap-8">
            
            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Calories Card -->
                <div class="bg-gradient-to-br from-orange-400 to-red-500 rounded-3xl p-8 text-white shadow-xl shadow-orange-500/30 relative overflow-hidden transform transition hover:-translate-y-1">
                    <i data-lucide="flame" class="w-32 h-32 absolute -right-6 -bottom-6 opacity-20 text-white"></i>
                    <div class="flex justify-between items-start relative z-10">
                        <div>
                            <p class="text-sm font-bold text-white/80 uppercase tracking-wider mb-1">Calories Burned</p>
                            <h3 class="text-4xl font-extrabold">450 <span class="text-lg font-medium opacity-80">kcal</span></h3>
                        </div>
                        <div class="bg-white/20 p-3 rounded-2xl backdrop-blur-md">
                            <i data-lucide="flame" class="w-8 h-8 text-white"></i>
                        </div>
                    </div>
                    <div class="mt-6 w-full bg-black/20 rounded-full h-1.5 relative z-10">
                        <div class="bg-white rounded-full h-1.5 w-3/4"></div>
                    </div>
                </div>
                
                <!-- Time Card -->
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-3xl p-8 text-white shadow-xl shadow-indigo-500/30 relative overflow-hidden transform transition hover:-translate-y-1">
                    <i data-lucide="activity" class="w-32 h-32 absolute -right-6 -bottom-6 opacity-20 text-white"></i>
                    <div class="flex justify-between items-start relative z-10">
                        <div>
                            <p class="text-sm font-bold text-white/80 uppercase tracking-wider mb-1">Active Time</p>
                            <h3 class="text-4xl font-extrabold">12.5 <span class="text-lg font-medium opacity-80">hrs</span></h3>
                        </div>
                        <div class="bg-white/20 p-3 rounded-2xl backdrop-blur-md">
                            <i data-lucide="clock" class="w-8 h-8 text-white"></i>
                        </div>
                    </div>
                    <div class="mt-6 w-full bg-black/20 rounded-full h-1.5 relative z-10">
                        <div class="bg-white rounded-full h-1.5 w-1/2"></div>
                    </div>
                </div>
            </div>

            <!-- Recent Workout -->
            <div class="bg-white dark:bg-card rounded-3xl p-8 shadow-xl shadow-gray-200/40 dark:shadow-none border border-gray-100 dark:border-gray-800 flex-1 flex flex-col">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold">Recent Workout</h3>
                    <a href="{{ route('track') }}" class="text-sm text-primary font-bold hover:bg-primary/10 px-4 py-2 rounded-xl transition-colors">Start New</a>
                </div>
                
                @if($lastWorkout)
                    <div class="flex items-center gap-5 bg-gray-50 dark:bg-gray-800/40 p-5 rounded-2xl border border-gray-100 dark:border-gray-700/50 hover:border-primary/30 transition-colors group cursor-pointer">
                        <div class="w-16 h-16 rounded-2xl bg-white dark:bg-gray-800 shadow-sm flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                            @if(strtolower($lastWorkout->type) === 'running')
                                <i data-lucide="person-standing" class="w-8 h-8"></i>
                            @elseif(strtolower($lastWorkout->type) === 'cycling')
                                <i data-lucide="bike" class="w-8 h-8"></i>
                            @else
                                <i data-lucide="dumbbell" class="w-8 h-8"></i>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h4 class="font-extrabold text-xl text-gray-900 dark:text-gray-100">{{ ucfirst($lastWorkout->type) }}</h4>
                            <p class="text-sm font-medium text-gray-500 flex items-center gap-1 mt-1">
                                <i data-lucide="calendar" class="w-4 h-4"></i> 
                                {{ \Carbon\Carbon::parse($lastWorkout->date)->format('M d, Y') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="font-black text-3xl text-primary">{{ $lastWorkout->duration }}</span>
                            <span class="text-sm font-bold text-gray-400 block -mt-1 uppercase tracking-widest">min</span>
                        </div>
                    </div>
                @else
                    <div class="flex-1 flex flex-col items-center justify-center text-center py-8">
                        <div class="w-20 h-20 rounded-full bg-gray-50 dark:bg-gray-800/50 flex items-center justify-center mb-4 text-gray-400 border border-dashed border-gray-200 dark:border-gray-700">
                            <i data-lucide="activity" class="w-10 h-10"></i>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 dark:text-gray-100">No workouts yet</h4>
                        <p class="text-gray-500 font-medium mt-1">Start tracking to see your history here.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('dashboard', (initialSteps, initialGoal) => ({
            steps: initialSteps,
            goal: initialGoal,
            loading: false,
            async addSteps(count) {
                this.loading = true;
                try {
                    const response = await fetch('/api/steps', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ count: count })
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        this.steps = data.new_total;
                    }
                } catch (e) {
                    console.error('Error adding steps', e);
                }
                this.loading = false;
            }
        }));
    });
</script>
@endpush
@endsection
