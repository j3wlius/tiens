<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Monthly Expense Report') }}
            </h2>
            <span class="text-sm text-gray-600">
                {{ Carbon\Carbon::create()->month($month)->format('F') }} {{ $year }}
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <!-- Total Amount -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-2">Total Amount</h3>
                        <p class="text-3xl font-bold text-indigo-600">
                            {{ number_format($totalAmount, 2) }}
                        </p>
                    </div>
                </div>

                <!-- Average Amount -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-2">Average Amount</h3>
                        <p class="text-3xl font-bold text-green-600">
                            {{ number_format($averageAmount, 2) }}
                        </p>
                    </div>
                </div>

                <!-- Highest Expense -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-2">Highest Expense</h3>
                        <p class="text-3xl font-bold text-red-600">
                            {{ number_format($maxExpense, 2) }}
                        </p>
                    </div>
                </div>

                <!-- Lowest Expense -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-2">Lowest Expense</h3>
                        <p class="text-3xl font-bold text-blue-600">
                            {{ number_format($minExpense, 2) }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Daily Expenses Chart -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Daily Expenses</h3>
                        <div class="h-64">
                            <canvas id="dailyExpensesChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Expenses by Type -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Expenses by Type</h3>
                        <div class="space-y-4">
                            @foreach($expensesByType as $type => $data)
                                <div>
                                    <div class="flex justify-between items-center">
                                        <span class="font-semibold">{{ $type }}</span>
                                        <span class="text-gray-600">{{ number_format($data['total'], 2) }}</span>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $data['count'] }} transactions (avg: {{ number_format($data['average'], 2) }})
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                                        <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ ($data['total'] / $totalAmount) * 100 }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Expenses Table -->
            <div class="mt-6 bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Detailed Expenses</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Received By</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($expenses as $expense)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $expense->date->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $expense->expense_type }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $expense->received_by }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $expense->description }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ number_format($expense->amount, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('dailyExpensesChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode(array_keys($dailyTotals->toArray())) !!},
                datasets: [{
                    label: 'Daily Expenses',
                    data: {!! json_encode(array_values($dailyTotals->toArray())) !!},
                    borderColor: 'rgb(79, 70, 229)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
