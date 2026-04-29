@extends('layouts.layout')
@section('header_title', 'Detailed Statistics')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white dark:bg-card p-6 md:p-8 rounded-3xl shadow-xl shadow-gray-200/40 dark:shadow-none border border-gray-100 dark:border-gray-800">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Your Progress</h2>
            <p class="text-gray-500 font-medium mt-2">Analyze your weekly activity and trends.</p>
        </div>
        <div class="flex items-center gap-2 bg-gray-100 dark:bg-gray-800 p-1.5 rounded-xl">
            <button class="px-4 py-2 text-sm font-bold bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg shadow-sm">This Week</button>
            <button class="px-4 py-2 text-sm font-bold text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">Last Week</button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Steps Chart Card -->
        <div class="bg-white dark:bg-card rounded-3xl p-6 md:p-8 shadow-xl shadow-gray-200/40 dark:shadow-none border border-gray-100 dark:border-gray-800">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h3 class="text-lg font-extrabold flex items-center gap-3 text-gray-900 dark:text-white">
                        <div class="bg-primary/10 p-2 rounded-lg">
                            <i data-lucide="footprints" class="w-5 h-5 text-primary"></i>
                        </div>
                        Steps Taken
                    </h3>
                    <p class="text-sm font-medium text-gray-500 mt-1">Total steps over the last 7 days</p>
                </div>
            </div>
            <div class="h-64 relative w-full">
                <canvas id="stepsChart"></canvas>
            </div>
        </div>

        <!-- Duration Chart Card -->
        <div class="bg-white dark:bg-card rounded-3xl p-6 md:p-8 shadow-xl shadow-gray-200/40 dark:shadow-none border border-gray-100 dark:border-gray-800">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h3 class="text-lg font-extrabold flex items-center gap-3 text-gray-900 dark:text-white">
                        <div class="bg-indigo-500/10 p-2 rounded-lg">
                            <i data-lucide="clock" class="w-5 h-5 text-indigo-500"></i>
                        </div>
                        Active Minutes
                    </h3>
                    <p class="text-sm font-medium text-gray-500 mt-1">Time spent in workouts</p>
                </div>
            </div>
            <div class="h-64 relative w-full">
                <canvas id="durationChart"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isDark = () => document.documentElement.classList.contains('dark');
        
        Chart.defaults.color = isDark() ? '#9ca3af' : '#6b7280';
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.font.weight = '600';
        
        const labels = @json($labels);
        const stepsData = @json($stepsData);
        const durationData = @json($durationData);

        const getGridConfig = () => ({
            color: isDark() ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)',
            drawBorder: false,
        });

        // Steps Bar Chart
        const stepsCtx = document.getElementById('stepsChart').getContext('2d');
        const stepsChart = new Chart(stepsCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Steps',
                    data: stepsData,
                    backgroundColor: '#3b82f6',
                    borderRadius: 8,
                    barThickness: 16,
                    hoverBackgroundColor: '#2563eb'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: isDark() ? '#1e293b' : '#ffffff',
                        titleColor: isDark() ? '#ffffff' : '#111827',
                        bodyColor: isDark() ? '#cbd5e1' : '#4b5563',
                        borderColor: isDark() ? '#334155' : '#e5e7eb',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 12,
                        displayColors: false,
                        boxShadow: '0 4px 6px -1px rgba(0, 0, 0, 0.1)'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: getGridConfig(),
                        border: { display: false }
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false }
                    }
                }
            }
        });

        // Duration Line Chart
        const durationCtx = document.getElementById('durationChart').getContext('2d');
        const updateGradient = () => {
            const gradient = durationCtx.createLinearGradient(0, 0, 0, 250);
            gradient.addColorStop(0, isDark() ? 'rgba(99, 102, 241, 0.5)' : 'rgba(99, 102, 241, 0.2)');
            gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');
            return gradient;
        };

        const durationChart = new Chart(durationCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Minutes',
                    data: durationData,
                    borderColor: '#6366f1',
                    backgroundColor: updateGradient(),
                    borderWidth: 4,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#6366f1',
                    pointBorderWidth: 3,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: isDark() ? '#1e293b' : '#ffffff',
                        titleColor: isDark() ? '#ffffff' : '#111827',
                        bodyColor: isDark() ? '#cbd5e1' : '#4b5563',
                        borderColor: isDark() ? '#334155' : '#e5e7eb',
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 12,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: getGridConfig(),
                        border: { display: false }
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false }
                    }
                }
            }
        });

        // Watch for dark mode toggle to update chart colors dynamically
        const observer = new MutationObserver(() => {
            const newTextColor = isDark() ? '#9ca3af' : '#6b7280';
            const tooltipBg = isDark() ? '#1e293b' : '#ffffff';
            const tooltipTitle = isDark() ? '#ffffff' : '#111827';
            const tooltipBody = isDark() ? '#cbd5e1' : '#4b5563';
            const tooltipBorder = isDark() ? '#334155' : '#e5e7eb';
            
            Chart.defaults.color = newTextColor;
            
            // Update Steps Chart
            stepsChart.options.scales.y.grid = getGridConfig();
            stepsChart.options.plugins.tooltip.backgroundColor = tooltipBg;
            stepsChart.options.plugins.tooltip.titleColor = tooltipTitle;
            stepsChart.options.plugins.tooltip.bodyColor = tooltipBody;
            stepsChart.options.plugins.tooltip.borderColor = tooltipBorder;
            
            // Update Duration Chart
            durationChart.options.scales.y.grid = getGridConfig();
            durationChart.data.datasets[0].backgroundColor = updateGradient();
            durationChart.options.plugins.tooltip.backgroundColor = tooltipBg;
            durationChart.options.plugins.tooltip.titleColor = tooltipTitle;
            durationChart.options.plugins.tooltip.bodyColor = tooltipBody;
            durationChart.options.plugins.tooltip.borderColor = tooltipBorder;
            
            stepsChart.update();
            durationChart.update();
        });

        observer.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['class']
        });
    });
</script>
@endpush
@endsection
