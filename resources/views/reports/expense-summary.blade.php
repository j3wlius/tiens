<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Expense Summary Report') }}
            </h2>
            <span class="text-sm text-gray-600">
                {{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Expense Summary Report</h2>
                    <div class="space-x-2">
                        <x-permission-check permission="reports.export">
                            <x-button wire:click="export" class="bg-green-500 hover:bg-green-700">
                                Export Report
                            </x-button>
                        </x-permission-check>
                        
                        <x-permission-check permission="reports.schedule">
                            <x-button wire:click="$emit('openScheduleModal')" class="bg-blue-500 hover:bg-blue-700">
                                Schedule Report
                            </x-button>
                        </x-permission-check>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Total Expenses Card -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-2">Total Expenses</h3>
                            <p class="text-3xl font-bold text-indigo-600">
                                {{ number_format($totalExpenses, 2) }}
                            </p>
                        </div>
                    </div>

                    <!-- Average Daily Expense -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-2">Average Daily Expense</h3>
                            <p class="text-3xl font-bold text-green-600">
                                {{ number_format($dailyExpenses->avg('total') ?? 0, 2) }}
                            </p>
                        </div>
                    </div>

                    <!-- Number of Transactions -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-2">Total Transactions</h3>
                            <p class="text-3xl font-bold text-blue-600">
                                {{ $expensesByType->sum('count') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Expenses by Type -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Expenses by Type</h3>
                            <div class="space-y-4">
                                @foreach($expensesByType as $expense)
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="font-semibold">{{ $expense->expense_type }}</p>
                                            <p class="text-sm text-gray-600">{{ $expense->count }} transactions</p>
                                        </div>
                                        <p class="font-bold">{{ number_format($expense->total, 2) }}</p>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ ($expense->total / $totalExpenses) * 100 }}%"></div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Daily Expenses Chart -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Daily Expenses Trend</h3>
                            <div class="h-64">
                                <!-- Add a chart here using your preferred JavaScript charting library -->
                                <canvas id="dailyExpensesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('dailyExpensesChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($dailyExpenses->pluck('date')) !!},
                    datasets: [{
                        label: 'Daily Expenses',
                        data: {!! json_encode($dailyExpenses->pluck('total')) !!},
                        borderColor: 'rgb(79, 70, 229)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                }
            });
        } else {
            console.error('Chart canvas not found');
        }
    </script>
    @endpush
</x-app-layout>
