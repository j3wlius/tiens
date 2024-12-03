<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Scheduled Reports') }}
            </h2>
            <x-permission-check permission="reports.schedule">
                <a href="{{ route('scheduled-reports.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    {{ __('Create New Schedule') }}
                </a>
            </x-permission-check>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    @if($reports->isEmpty())
                        <div class="text-center py-8">
                            <p class="text-gray-500">No scheduled reports found.</p>
                        </div>
                    @else
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold">Scheduled Reports</h2>
                            <x-permission-check permission="reports.schedule">
                                <x-button wire:click="$emit('openCreateModal')" class="bg-blue-500 hover:bg-blue-700">
                                    Schedule New Report
                                </x-button>
                            </x-permission-check>
                        </div>

                        <!-- Report List -->
                        <div class="mt-6">
                            @foreach($reports as $report)
                                <div class="border-b border-gray-200 py-4">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h3 class="text-lg font-semibold">{{ $report->name }}</h3>
                                            <p class="text-sm text-gray-600">{{ Str::title(str_replace('_', ' ', $report->type)) }}</p>
                                            <p class="text-sm text-gray-500">Frequency: {{ Str::title($report->frequency) }}</p>
                                        </div>
                                        <div class="space-x-2">
                                            <x-permission-check permission="reports.schedule">
                                                <a href="{{ route('scheduled-reports.edit', $report) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                <form action="{{ route('scheduled-reports.toggle', $report) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-{{ $report->is_active ? 'red' : 'green' }}-600 hover:text-{{ $report->is_active ? 'red' : 'green' }}-900">
                                                        {{ $report->is_active ? 'Disable' : 'Enable' }}
                                                    </button>
                                                </form>
                                                <form action="{{ route('scheduled-reports.destroy', $report) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this schedule?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                </form>
                                            </x-permission-check>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            {{ $reports->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
