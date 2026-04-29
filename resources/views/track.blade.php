@extends('layouts.layout')
@section('header_title', 'Track Workout')

@section('content')
<div x-data="stopwatch()" class="h-full flex flex-col justify-center min-h-[calc(100vh-140px)]">
    
    <div class="max-w-3xl mx-auto w-full space-y-10">
        
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">New Workout</h2>
            <p class="text-gray-500 font-medium mt-2">Select an activity and start your timer</p>
        </div>

        <!-- Activity Selector -->
        <div class="grid grid-cols-3 gap-4 md:gap-6">
            <button @click="activity = 'running'; saveState()" :class="activity === 'running' ? 'bg-gradient-to-br from-primary to-blue-500 text-white shadow-xl shadow-primary/30 border-transparent transform -translate-y-1' : 'bg-white dark:bg-card text-gray-600 dark:text-gray-300 border-gray-100 dark:border-gray-800 hover:border-primary/30'" class="p-6 md:p-8 rounded-3xl border-2 transition-all flex flex-col items-center gap-3">
                <i data-lucide="person-standing" class="w-8 h-8 md:w-10 md:h-10"></i>
                <span class="text-sm md:text-base font-bold tracking-wide">Running</span>
            </button>
            <button @click="activity = 'cycling'; saveState()" :class="activity === 'cycling' ? 'bg-gradient-to-br from-primary to-blue-500 text-white shadow-xl shadow-primary/30 border-transparent transform -translate-y-1' : 'bg-white dark:bg-card text-gray-600 dark:text-gray-300 border-gray-100 dark:border-gray-800 hover:border-primary/30'" class="p-6 md:p-8 rounded-3xl border-2 transition-all flex flex-col items-center gap-3">
                <i data-lucide="bike" class="w-8 h-8 md:w-10 md:h-10"></i>
                <span class="text-sm md:text-base font-bold tracking-wide">Cycling</span>
            </button>
            <button @click="activity = 'lifting'; saveState()" :class="activity === 'lifting' ? 'bg-gradient-to-br from-primary to-blue-500 text-white shadow-xl shadow-primary/30 border-transparent transform -translate-y-1' : 'bg-white dark:bg-card text-gray-600 dark:text-gray-300 border-gray-100 dark:border-gray-800 hover:border-primary/30'" class="p-6 md:p-8 rounded-3xl border-2 transition-all flex flex-col items-center gap-3">
                <i data-lucide="dumbbell" class="w-8 h-8 md:w-10 md:h-10"></i>
                <span class="text-sm md:text-base font-bold tracking-wide">Lifting</span>
            </button>
        </div>

        <!-- Timer Display -->
        <div class="flex flex-col items-center justify-center py-10">
            <div class="relative w-72 h-72 md:w-96 md:h-96 flex items-center justify-center bg-white dark:bg-card rounded-full shadow-[0_20px_50px_rgba(0,0,0,0.1)] dark:shadow-[0_20px_50px_rgba(0,0,0,0.5)] border-[12px] border-gray-50 dark:border-gray-800/50 transition-all duration-500" :class="running ? 'scale-105 border-primary/20' : ''">
                <!-- Pulsing ring when active -->
                <div x-show="running" class="absolute inset-0 rounded-full animate-ping border-4 border-primary opacity-20" x-cloak></div>
                
                <div class="text-center z-10">
                    <div class="text-7xl md:text-8xl font-black font-mono tracking-tighter text-gray-900 dark:text-white drop-shadow-sm" x-text="formattedTime">00:00</div>
                    <div class="text-sm md:text-base font-bold text-primary mt-4 uppercase tracking-[0.3em]" x-text="activity">RUNNING</div>
                </div>
            </div>
        </div>

        <!-- Controls -->
        <div class="flex justify-center items-center gap-6">
            <!-- Reset Button -->
            <button @click="reset" x-show="time > 0 && !running" x-cloak class="w-16 h-16 md:w-20 md:h-20 rounded-2xl bg-white dark:bg-card flex items-center justify-center text-red-500 transition-all hover:bg-red-50 dark:hover:bg-red-900/20 shadow-lg border border-gray-100 dark:border-gray-800 hover:scale-105 active:scale-95 group">
                <i data-lucide="square" class="w-6 h-6 md:w-8 md:h-8 fill-current group-hover:scale-90 transition-transform"></i>
            </button>
            
            <!-- Play/Pause Button -->
            <button @click="toggle" class="w-24 h-24 md:w-28 md:h-28 rounded-full flex items-center justify-center text-white shadow-2xl transition-all hover:scale-105 active:scale-95 group relative overflow-hidden" :class="running ? 'bg-gradient-to-br from-red-500 to-rose-600 shadow-red-500/40' : 'bg-gradient-to-br from-primary to-blue-600 shadow-primary/40'">
                <div class="absolute inset-0 bg-white/20 group-hover:opacity-0 transition-opacity"></div>
                <i data-lucide="pause" x-show="running" class="w-10 h-10 md:w-12 md:h-12 fill-current" x-cloak></i>
                <i data-lucide="play" x-show="!running" class="w-10 h-10 md:w-12 md:h-12 ml-2 fill-current"></i>
            </button>
        </div>

    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('stopwatch', () => ({
            activity: 'running',
            running: false,
            time: 0,
            interval: null,
            init() {
                const savedState = JSON.parse(localStorage.getItem('fittrack_stopwatch'));
                if (savedState) {
                    this.activity = savedState.activity;
                    this.time = savedState.time;
                    if (savedState.running) {
                        this.toggle(); 
                    }
                }
            },
            saveState() {
                localStorage.setItem('fittrack_stopwatch', JSON.stringify({
                    activity: this.activity,
                    time: this.time,
                    running: this.running
                }));
            },
            get formattedTime() {
                const min = Math.floor(this.time / 60).toString().padStart(2, '0');
                const sec = (this.time % 60).toString().padStart(2, '0');
                return `${min}:${sec}`;
            },
            toggle() {
                if (this.running) {
                    clearInterval(this.interval);
                    this.running = false;
                    this.saveState();
                } else {
                    this.running = true;
                    this.interval = setInterval(() => {
                        this.time++;
                        this.saveState();
                    }, 1000);
                }
            },
            async reset() {
                let duration = this.time;
                let currentActivity = this.activity;
                
                this.time = 0;
                this.running = false;
                clearInterval(this.interval);
                localStorage.removeItem('fittrack_stopwatch');

                try {
                    const response = await fetch('/api/workout', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ type: currentActivity, duration: duration })
                    });
                    if(response.ok) {
                        // Create a subtle notification instead of alert if possible, or simple alert
                        alert('Workout Saved Successfully!');
                        window.location.href = '/';
                    }
                } catch (error) {
                    alert('Error saving workout. Ensure you are online.');
                }
            }
        }))
    })
</script>
@endpush
@endsection
