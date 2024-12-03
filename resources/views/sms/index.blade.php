<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('SMS Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <!-- SMS Form -->
                <form action="{{ route('sms.send') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <x-label for="recipients" value="{{ __('Recipients') }}" />
                        <x-input id="recipients" class="block mt-1 w-full" type="text" name="recipients" required />
                        <p class="text-sm text-gray-500 mt-1">Separate multiple numbers with commas</p>
                    </div>

                    <div>
                        <x-label for="message" value="{{ __('Message') }}" />
                        <textarea id="message" name="message" rows="4" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required></textarea>
                        <p class="text-sm text-gray-500 mt-1">Character count: <span id="charCount">0</span></p>
                    </div>

                    <div class="flex items-center justify-end">
                        <x-button>
                            {{ __('Send SMS') }}
                        </x-button>
                    </div>
                </form>

                <!-- SMS History -->
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">SMS History</h3>
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recipients</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <!-- Add your SMS history records here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const messageTextarea = document.getElementById('message');
        const charCountSpan = document.getElementById('charCount');

        messageTextarea.addEventListener('input', function() {
            charCountSpan.textContent = this.value.length;
        });
    </script>
    @endpush
</x-app-layout>
