<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Scheduled Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('scheduled-reports.store') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Name -->
                            <div>
                                <x-label for="name" value="{{ __('Report Name') }}" />
                                <x-input id="name" type="text" name="name" :value="old('name')" required class="mt-1 block w-full" />
                                <x-input-error for="name" class="mt-2" />
                            </div>

                            <!-- Type -->
                            <div>
                                <x-label for="type" value="{{ __('Report Type') }}" />
                                <select id="type" name="type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="expense_summary">Expense Summary</option>
                                    <option value="monthly_expense">Monthly Expense</option>
                                    <option value="distributor_summary">Distributor Summary</option>
                                </select>
                                <x-input-error for="type" class="mt-2" />
                            </div>

                            <!-- Frequency -->
                            <div>
                                <x-label for="frequency" value="{{ __('Frequency') }}" />
                                <select id="frequency" name="frequency" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="quarterly">Quarterly</option>
                                    <option value="yearly">Yearly</option>
                                </select>
                                <x-input-error for="frequency" class="mt-2" />
                            </div>

                            <!-- Recipients -->
                            <div>
                                <x-label for="recipients" value="{{ __('Recipients (one email per line)') }}" />
                                <textarea id="recipients" name="recipients" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('recipients') }}</textarea>
                                <x-input-error for="recipients" class="mt-2" />
                                <p class="mt-2 text-sm text-gray-500">Enter one email address per line</p>
                            </div>

                            <!-- Filters -->
                            <div>
                                <x-label for="filters" value="{{ __('Filters (Optional)') }}" />
                                <textarea id="filters" name="filters" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('filters') }}</textarea>
                                <x-input-error for="filters" class="mt-2" />
                                <p class="mt-2 text-sm text-gray-500">Enter filters in JSON format (e.g., {"category": "office", "min_amount": 100})</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-button>
                                {{ __('Create Schedule') }}
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
