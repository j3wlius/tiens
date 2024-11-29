<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($distributor) ? 'Edit Distributor' : 'Create New Distributor' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ isset($distributor) ? route('distributors.update', $distributor) : route('distributors.store') }}" method="POST">
                        @csrf
                        @if(isset($distributor))
                            @method('PUT')
                        @endif

                        <div class="grid grid-cols-1 gap-6">
                            <!-- Name -->
                            <div>
                                <x-label for="name" value="{{ __('Name') }}" />
                                <x-input id="name" type="text" class="block mt-1 w-full" name="name" 
                                    value="{{ isset($distributor) ? $distributor->name : old('name') }}" required />
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <x-label for="email" value="{{ __('Email') }}" />
                                <x-input id="email" type="email" class="block mt-1 w-full" name="email" 
                                    value="{{ isset($distributor) ? $distributor->email : old('email') }}" required />
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone Number -->
                            <div>
                                <x-label for="phone_number" value="{{ __('Phone Number') }}" />
                                <x-input id="phone_number" type="text" class="block mt-1 w-full" name="phone_number" 
                                    value="{{ isset($distributor) ? $distributor->phone_number : old('phone_number') }}" required />
                                @error('phone_number')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Residence -->
                            <div>
                                <x-label for="residence" value="{{ __('Residence') }}" />
                                <textarea id="residence" name="residence" rows="3" 
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" required>{{ isset($distributor) ? $distributor->residence : old('residence') }}</textarea>
                                @error('residence')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- National ID -->
                            <div>
                                <x-label for="national_id" value="{{ __('National ID') }}" />
                                <x-input id="national_id" type="text" class="block mt-1 w-full font-mono uppercase" name="national_id" 
                                    value="{{ isset($distributor) ? $distributor->national_id : old('national_id') }}" 
                                    placeholder="CF/CM followed by 12 characters"
                                    required />
                                <p class="text-gray-600 text-xs mt-1">Must start with CF or CM and be exactly 14 characters long</p>
                                @error('national_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <a href="{{ route('distributors.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 mr-2">
                                    Cancel
                                </a>
                                <x-button>
                                    {{ isset($distributor) ? 'Update Distributor' : 'Create Distributor' }}
                                </x-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
