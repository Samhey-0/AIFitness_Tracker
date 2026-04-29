@extends('layouts.layout')
@section('header_title', 'AI Coach & Nutritionist')

@section('content')
<div class="max-w-6xl mx-auto space-y-8" x-data="{ 
    loading: false, 
    result: null, 
    error: null,
    goal: '',
    equipment: '',
    async fetchPlan() {
        if(!this.goal || !this.equipment) return;
        this.loading = true;
        this.error = null;
        this.result = null;
        try {
            const response = await fetch('{{ route('ai.generate') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ goal: this.goal, equipment: this.equipment })
            });
            const data = await response.json();
            if (response.ok) {
                this.result = data.result;
                setTimeout(() => lucide.createIcons(), 100);
            } else {
                this.error = data.error || 'Failed to generate plan.';
            }
        } catch (e) {
            this.error = 'Connection error. Please try again.';
        }
        this.loading = false;
        setTimeout(() => lucide.createIcons(), 100);
    }
}">
    
    <!-- Input Section -->
    <div class="bg-white dark:bg-card p-8 rounded-3xl shadow-xl shadow-gray-200/40 dark:shadow-none border border-gray-100 dark:border-gray-800" x-show="!result && !loading" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 scale-95">
        <div class="flex items-center gap-4 mb-8">
            <div class="bg-primary/10 p-4 rounded-2xl">
                <i data-lucide="brain-circuit" class="w-10 h-10 text-primary"></i>
            </div>
            <div>
                <h2 class="text-3xl font-extrabold tracking-tight">AI Coach & Nutritionist</h2>
                <p class="text-gray-500 font-medium mt-1">Generate a custom workout and meal plan in seconds.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="space-y-2">
                <label class="text-sm font-bold text-gray-700 dark:text-gray-300 ml-1 uppercase tracking-wider">Your Goal</label>
                <input type="text" x-model="goal" required placeholder="e.g. Muscle gain, 10kg weight loss" class="w-full px-6 py-4 rounded-2xl bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:ring-2 focus:ring-primary outline-none transition-all font-medium">
            </div>
            <div class="space-y-2">
                <label class="text-sm font-bold text-gray-700 dark:text-gray-300 ml-1 uppercase tracking-wider">Equipment</label>
                <input type="text" x-model="equipment" required placeholder="e.g. Full gym, Home with dumbbells" class="w-full px-6 py-4 rounded-2xl bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:ring-2 focus:ring-primary outline-none transition-all font-medium">
            </div>
        </div>

        <button @click="fetchPlan()" :disabled="loading || !goal || !equipment" class="w-full py-5 bg-gradient-to-r from-primary to-indigo-600 text-white font-bold text-lg rounded-2xl shadow-xl shadow-primary/30 hover:shadow-primary/50 hover:-translate-y-1 transition-all disabled:opacity-50 flex items-center justify-center gap-3">
            <span x-show="!loading" class="flex items-center gap-2">
                <i data-lucide="sparkles" class="w-6 h-6"></i> Generate My Custom Plan
            </span>
            <span x-show="loading" x-cloak class="flex items-center gap-3">
                <i data-lucide="loader-2" class="w-6 h-6 animate-spin"></i> 
                <span class="animate-pulse">Consulting AI Coach...</span>
            </span>
        </button>

        <div x-show="error" x-cloak class="mt-6 p-4 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-2xl border border-red-100 dark:border-red-800 font-medium text-center" x-text="error"></div>
    </div>

    <!-- Skeleton Loader -->
    <div x-show="loading" x-cloak class="bg-white dark:bg-card p-8 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-800 space-y-8 animate-pulse">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-6">
                <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded-lg w-1/2"></div>
                <div class="h-20 bg-gray-100 dark:bg-gray-800/50 rounded-2xl w-full"></div>
                <div class="h-20 bg-gray-100 dark:bg-gray-800/50 rounded-2xl w-full"></div>
            </div>
            <div class="space-y-6">
                <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded-lg w-1/2"></div>
                <div class="h-20 bg-gray-100 dark:bg-gray-800/50 rounded-2xl w-full"></div>
                <div class="h-20 bg-gray-100 dark:bg-gray-800/50 rounded-2xl w-full"></div>
            </div>
        </div>
    </div>

    <!-- The Professional Reveal -->
    <div x-show="result && !loading" x-cloak x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-10" class="space-y-8">
        
        <div class="flex justify-between items-center">
             <button @click="result = null" class="flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-primary transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Create New Plan
            </button>
            <div class="flex items-center gap-2 px-4 py-2 bg-green-500/10 text-green-500 rounded-full border border-green-500/20">
                <i data-lucide="check-circle" class="w-4 h-4 text-green-500"></i>
                <span class="text-xs font-bold uppercase tracking-widest">Plan Ready</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
            
            <!-- Column 1: AI Workout -->
            <div class="bg-white dark:bg-card rounded-3xl p-8 shadow-xl border border-gray-100 dark:border-gray-800 h-full">
                <div class="flex items-center gap-3 mb-8">
                    <div class="bg-primary/10 p-3 rounded-xl">
                        <i data-lucide="dumbbell" class="w-6 h-6 text-primary"></i>
                    </div>
                    <h3 class="text-2xl font-bold" x-text="result?.workout?.title"></h3>
                </div>
                
                <div class="space-y-4">
                    <template x-for="(ex, index) in result?.workout?.exercises" :key="index">
                        <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-800/40 rounded-2xl border border-gray-100 dark:border-gray-700/50">
                            <div class="w-10 h-10 rounded-lg bg-primary text-white flex items-center justify-center font-bold" x-text="index + 1"></div>
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900 dark:text-white" x-text="ex.name"></h4>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mt-1">
                                    <span x-text="ex.sets"></span> Sets × <span x-text="ex.reps"></span> Reps
                                </p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Column 2: AI Diet Plan -->
            <div class="bg-white dark:bg-card rounded-3xl p-8 shadow-xl border border-gray-100 dark:border-gray-800 h-full">
                <div class="flex items-center gap-3 mb-8">
                    <div class="bg-orange-500/10 p-3 rounded-xl">
                        <i data-lucide="utensils" class="w-6 h-6 text-orange-500"></i>
                    </div>
                    <h3 class="text-2xl font-bold" x-text="result?.diet?.title"></h3>
                </div>
                
                <div class="space-y-4">
                    <template x-for="(meal, index) in result?.diet?.meals" :key="index">
                        <div class="flex items-center gap-4 p-4 bg-orange-50/50 dark:bg-orange-900/10 rounded-2xl border border-orange-100 dark:border-orange-900/30">
                            <div class="w-10 h-10 rounded-lg bg-orange-500 text-white flex items-center justify-center">
                                <i data-lucide="utensils" class="w-5 h-5"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900 dark:text-white" x-text="meal.name"></h4>
                                <p class="text-xs font-bold text-orange-600 dark:text-orange-400 uppercase tracking-widest mt-1">
                                    <span x-text="meal.calories"></span> kcal • <span x-text="meal.protein"></span> Protein
                                </p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

        </div>

        <!-- Integrated Save Button -->
        <form action="{{ route('ai.save') }}" method="POST">
            @csrf
            <input type="hidden" name="title" :value="result?.workout?.title">
            <template x-for="(ex, index) in result?.workout?.exercises">
                <div>
                    <input type="hidden" :name="'exercises['+index+'][name]'" :value="ex.name">
                    <input type="hidden" :name="'exercises['+index+'][sets]'" :value="ex.sets">
                    <input type="hidden" :name="'exercises['+index+'][reps]'" :value="ex.reps">
                </div>
            </template>
            <button type="submit" class="w-full py-5 bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold text-lg rounded-3xl shadow-xl hover:scale-[1.02] transition-transform flex items-center justify-center gap-3">
                <i data-lucide="save" class="w-6 h-6"></i> Save Workout to History
            </button>
        </form>
    </div>

</div>
@endsection
