<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($expense) ? 'Edit Expense' : 'Create New Expense' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ isset($expense) ? route('expenses.update', $expense) : route('expenses.store') }}" method="POST">
                        @csrf
                        @if(isset($expense))
                            @method('PUT')
                        @endif

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Date -->
                            <div>
                                <x-label for="date" value="{{ __('Date') }}" />
                                <x-input id="date" type="date" class="block mt-1 w-full" name="date" 
                                    value="{{ isset($expense) ? $expense->date->format('Y-m-d') : old('date') }}" required />
                                @error('date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Expense Type -->
                            <div>
                                <x-label for="expense_type" value="{{ __('Expense Type') }}" />
                                <x-input id="expense_type" type="text" class="block mt-1 w-full" name="expense_type" 
                                    value="{{ isset($expense) ? $expense->expense_type : old('expense_type') }}" required />
                                @error('expense_type')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Received By -->
                            <div>
                                <x-label for="received_by" value="{{ __('Received By') }}" />
                                <x-input id="received_by" type="text" class="block mt-1 w-full" name="received_by" 
                                    value="{{ isset($expense) ? $expense->received_by : old('received_by') }}" required />
                                @error('received_by')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <x-label for="description" value="{{ __('Description') }}" />
                                <textarea id="description" name="description" rows="3" 
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">{{ isset($expense) ? $expense->description : old('description') }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Amount -->
                            <div>
                                <x-label for="amount" value="{{ __('Amount') }}" />
                                <x-input id="amount" type="number" step="0.01" class="block mt-1 w-full" name="amount" 
                                    value="{{ isset($expense) ? $expense->amount : old('amount') }}" required />
                                @error('amount')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-button class="ml-4">
                                    {{ isset($expense) ? 'Update Expense' : 'Create Expense' }}
                                </x-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
