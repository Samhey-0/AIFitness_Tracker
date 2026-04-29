@extends('layouts.layout')
@section('header_title', 'Create Account')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[calc(100vh-140px)] w-full py-10">
    <div class="bg-white dark:bg-card p-8 md:p-10 rounded-3xl shadow-2xl shadow-gray-200/50 dark:shadow-none border border-gray-100 dark:border-gray-800 w-full max-w-md">
        
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold tracking-tight">Join FitTrack</h2>
            <p class="text-gray-500 font-medium mt-2">Start your fitness journey today</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf
            @if($errors->any())
                <div class="bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 font-medium text-sm p-4 rounded-2xl border border-red-200 dark:border-red-800">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="space-y-1.5">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Full Name</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="user" class="w-5 h-5 text-gray-400"></i>
                    </div>
                    <input type="text" name="name" required class="w-full pl-11 pr-4 py-3.5 rounded-2xl bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all font-medium">
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="mail" class="w-5 h-5 text-gray-400"></i>
                    </div>
                    <input type="email" name="email" required class="w-full pl-11 pr-4 py-3.5 rounded-2xl bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all font-medium">
                </div>
            </div>
            
            <div class="space-y-1.5">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="lock" class="w-5 h-5 text-gray-400"></i>
                    </div>
                    <input type="password" name="password" required class="w-full pl-11 pr-4 py-3.5 rounded-2xl bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all font-medium">
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Confirm Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="lock" class="w-5 h-5 text-gray-400"></i>
                    </div>
                    <input type="password" name="password_confirmation" required class="w-full pl-11 pr-4 py-3.5 rounded-2xl bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all font-medium">
                </div>
            </div>
            
            <button type="submit" class="w-full py-4 mt-2 bg-gradient-to-r from-primary to-blue-500 text-white font-bold text-lg rounded-2xl shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:-translate-y-0.5 transition-all">
                Create Account
            </button>
        </form>
        
        <p class="text-center font-medium text-gray-500 mt-8">
            Already have an account? <a href="{{ route('login') }}" class="text-primary font-bold hover:underline">Sign in</a>
        </p>
    </div>
</div>
@endsection
