<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Scheduled Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('scheduled-reports.update', $scheduledReport) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Name -->
                            <div>
                                <x-label for="name" value="{{ __('Report Name') }}" />
                                <x-input id="name" type="text" name="name" :value="old('name', $scheduledReport->name)" required class="mt-1 block w-full" />
                                <x-input-error for="name" class="mt-2" />
                            </div>

                            <!-- Type -->
                            <div>
                                <x-label for="type" value="{{ __('Report Type') }}" />
                                <select id="type" name="type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="expense_summary" {{ $scheduledReport->type === 'expense_summary' ? 'selected' : '' }}>Expense Summary</option>
                                    <option value="monthly_expense" {{ $scheduledReport->type === 'monthly_expense' ? 'selected' : '' }}>Monthly Expense</option>
                                    <option value="distributor_summary" {{ $scheduledReport->type === 'distributor_summary' ? 'selected' : '' }}>Distributor Summary</option>
                                </select>
                                <x-input-error for="type" class="mt-2" />
                            </div>

                            <!-- Frequency -->
                            <div>
                                <x-label for="frequency" value="{{ __('Frequency') }}" />
                                <select id="frequency" name="frequency" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="daily" {{ $scheduledReport->frequency === 'daily' ? 'selected' : '' }}>Daily</option>
                                    <option value="weekly" {{ $scheduledReport->frequency === 'weekly' ? 'selected' : '' }}>Weekly</option>
                                    <option value="monthly" {{ $scheduledReport->frequency === 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="quarterly" {{ $scheduledReport->frequency === 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                    <option value="yearly" {{ $scheduledReport->frequency === 'yearly' ? 'selected' : '' }}>Yearly</option>
                                </select>
                                <x-input-error for="frequency" class="mt-2" />
                            </div>

                            <!-- Recipients -->
                            <div>
                                <x-label for="recipients" value="{{ __('Recipients (one email per line)') }}" />
                                <textarea id="recipients" name="recipients" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('recipients', implode("\n", $scheduledReport->recipients)) }}</textarea>
                                <x-input-error for="recipients" class="mt-2" />
                                <p class="mt-2 text-sm text-gray-500">Enter one email address per line</p>
                            </div>

                            <!-- Filters -->
                            <div>
                                <x-label for="filters" value="{{ __('Filters (Optional)') }}" />
                                <textarea id="filters" name="filters" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('filters', json_encode($scheduledReport->filters, JSON_PRETTY_PRINT)) }}</textarea>
                                <x-input-error for="filters" class="mt-2" />
                                <p class="mt-2 text-sm text-gray-500">Enter filters in JSON format (e.g., {"category": "office", "min_amount": 100})</p>
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_active" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{ $scheduledReport->is_active ? 'checked' : '' }}>
                                    <span class="ml-2">{{ __('Active') }}</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-button>
                                {{ __('Update Schedule') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const recipientsTextarea = document.getElementById('recipients');
            
            recipientsTextarea.addEventListener('change', function() {
                const emails = this.value.split('\n').filter(email => email.trim() !== '');
                this.value = emails.join('\n');
            });

            const filtersTextarea = document.getElementById('filters');
            
            filtersTextarea.addEventListener('change', function() {
                try {
                    const filters = JSON.parse(this.value);
                    this.value = JSON.stringify(filters, null, 2);
                } catch (e) {
                    // Invalid JSON, leave as is
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
