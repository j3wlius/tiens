<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="p-2 h-[90vh] overflow-y-auto">
        <div class="bg-white p-2 h-[90vh] overflow-hidden sm:rounded-lg">
            <div class="flex justify-between">
                <div>
                    <p class="text-2xl font-bold">Payments</p>
                </div>
                <div>
                    <!-- Import CSV Form -->
                    <form action="{{ route('payments.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" class="bg-gray-200 p-1 rounded">
                        <button type="submit" class="bg-green-600 text-white p-2 rounded">Import CSV</button>
                    </form>
                </div>
            </div>

            @if(session('success'))
                <div class="mt-4 p-2 bg-green-200 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>DIST NO</th>
                        <th>DIST NAME</th>
                        <th>NET PAY</th>
                        <th>CONTACT</th>
                        <th>STATUS</th>
                        <th>ERROR</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payments as $payment)
                    <tr>
                        <td>{{ $payment->id }}</td>
                        <td>{{ $payment->dist_no }}</td>
                        <td>{{ $payment->dist_name }}</td>
                        <td>{{ $payment->net_pay }}</td>
                        <td>{{ $payment->contacts }}</td>
                        <td>{{ $payment->status }}</td>
                        <td>{{ $payment->error }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
