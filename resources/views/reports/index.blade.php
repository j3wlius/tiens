<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reports') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Expense Summary Report -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Expense Summary Report</h3>
                        <form action="{{ route('reports.expense-summary') }}" method="GET" class="space-y-4">
                            <div>
                                <x-label for="start_date" value="{{ __('Start Date') }}" />
                                <x-input id="start_date" type="date" name="start_date" class="block mt-1 w-full" 
                                    required value="{{ old('start_date', now()->startOfMonth()->format('Y-m-d')) }}" />
                            </div>
                            <div>
                                <x-label for="end_date" value="{{ __('End Date') }}" />
                                <x-input id="end_date" type="date" name="end_date" class="block mt-1 w-full" 
                                    required value="{{ old('end_date', now()->format('Y-m-d')) }}" />
                            </div>
                            <div class="flex justify-end">
                                <x-button>
                                    Generate Report
                                </x-button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Monthly Expense Report -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Monthly Expense Report</h3>
                        <form action="{{ route('reports.monthly-expense') }}" method="GET" class="space-y-4">
                            <div>
                                <x-label for="month" value="{{ __('Month') }}" />
                                <select id="month" name="month" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                    @foreach(range(1, 12) as $month)
                                        <option value="{{ $month }}" {{ old('month', now()->month) == $month ? 'selected' : '' }}>
                                            {{ Carbon\Carbon::create()->month($month)->format('F') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-label for="year" value="{{ __('Year') }}" />
                                <select id="year" name="year" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                    @foreach(range(now()->year - 2, now()->year) as $year)
                                        <option value="{{ $year }}" {{ old('year', now()->year) == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex justify-end">
                                <x-button>
                                    Generate Report
                                </x-button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Distributor Summary Report -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Distributor Summary Report</h3>
                        <p class="text-gray-600 mb-4">View a summary of all distributors including regional distribution and recent additions.</p>
                        <div class="flex justify-end">
                            <a href="{{ route('reports.distributor-summary') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                View Report
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Export Reports -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Export Expense Report</h3>
                        <form action="{{ route('reports.export-expense') }}" method="GET" class="space-y-4">
                            <div>
                                <x-label for="export_start_date" value="{{ __('Start Date') }}" />
                                <x-input id="export_start_date" type="date" name="start_date" class="block mt-1 w-full" 
                                    required value="{{ old('start_date', now()->startOfMonth()->format('Y-m-d')) }}" />
                            </div>
                            <div>
                                <x-label for="export_end_date" value="{{ __('End Date') }}" />
                                <x-input id="export_end_date" type="date" name="end_date" class="block mt-1 w-full" 
                                    required value="{{ old('end_date', now()->format('Y-m-d')) }}" />
                            </div>
                            <div>
                                <x-label for="format" value="{{ __('Format') }}" />
                                <select id="format" name="format" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                                    <option value="csv">CSV</option>
                                    <option value="pdf">PDF</option>
                                </select>
                            </div>
                            <div class="flex justify-end">
                                <x-button>
                                    Export Report
                                </x-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
