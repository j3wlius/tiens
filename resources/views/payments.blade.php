@php
    use App\Models\Category;

@endphp
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
                <div class="mb-5">
                    <!-- Import CSV Form -->
                    <form action="{{ route('payments.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" class="bg-gray-200 p-1 rounded" required>
                        <input  type="date" name="month_of_pay" value="" required>
                        @php 
                            $categories = Category::latest()->get();
                        @endphp
                        <select name="category" required>
                            <option value="" disabled selected>Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="bg-gray-200 p-1 rounded">Import CSV</button>
                    </form>
                </div>
            </div>

            @if(session('success'))
                <div class="mt-4 p-2 bg-green-200 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <style>
                /* .status-badge {
                    font-weight: bold;
                    color: #fff;
                } */
            </style>

            
            <link href='https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.css' rel='stylesheet' />
            <link href='https://cdn.datatables.net/buttons/3.0.1/css/buttons.dataTables.css' rel='stylesheet' />
            <script src='https://code.jquery.com/jquery-3.7.1.js'></script>
            <script src='https://cdn.datatables.net/2.0.2/js/dataTables.js'></script>
            <script src='https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.js'></script>
            <script src='https://cdn.datatables.net/buttons/3.0.1/js/buttons.dataTables.js'></script>
            <script src='https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js'></script>
            <script src='https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js'></script>
            <script src='https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js'></script>
            <script src='https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js'></script>
    
    
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" integrity="sha512-H9jrZiiopUdsLpg94A333EfumgUBpO9MdbxStdeITo+KEIMaNfHNvwyjjDJb+ERPaRS6DpyRlKbvPUasNItRyw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
            <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js" integrity="sha512-uURl+ZXMBrF4AwGaWmEetzrd+J5/8NRkWAvJx5sbPSSuOb0bZLqf+tOzniObO00BjHa/dD7gub9oCGMLPQHtQA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
            
    
            <script>
                $(document).ready(function() {
                    new DataTable('#listtable', {
			layout: {
				topStart: {
				buttons: [
					'copyHtml5',
					{
					extend: 'excelHtml5',
					title: 'Tiens Payment Report',
					filename: 'Tiens Payment Report',
					},
					{
					extend: 'csvHtml5',
					title: 'Tiens Payment Report',
					filename: 'Tiens Payment Report',
					},
					{
					extend: 'pdfHtml5',
					orientation: 'landscape',
					pageSize: 'LEGAL',
					title: 'Tiens Payment Report',
					filename: 'Tiens Payment Report',
					// footer: true,
					// customize: function (doc) {
					// 	doc['footer'] = (function (page, pages) {
					// 	return {
					// 		columns: [
					// 		{ alignment: 'left', text: formattedDate + ', Generated by Tiens Admin' },
					// 		{ alignment: 'right', text: ['Page ', page, ' of ', pages] }
					// 		],
					// 		margin: [20, 10, 20, 10]
					// 	};
					// 	});
					// }
					},
				]
				}
			},
			scrollX: true,
		});
                });
            </script>
            
            {{-- <div style="width: 90%; display: flex; justify-content: center"> --}}
                {{-- <div style="width: 100%">                 --}}
                    <table id="listtable" class="table table-striped table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>DIST NO</th>
                                <th>DIST NAME</th>
                                <th>NET PAY</th>
                                <th>CONTACT</th>
                                <th>Month of Pay</th>
                                <th>Category</th>
                                <th>STATUS</th>
                                <th>Description</th>
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
                                <td>{{ $payment->month_of_pay}}</td>
                                <td>{{ Category::find($payment->category)->name }}</td>
                              
                                <td>
                                    <!-- Adjust color and text based on the status value -->
                                    <span class="badge 
                                        {{ $payment->status == 0 ? 'bg-danger' : 'bg-success' }}">
                                        {{ $payment->status == 0 ? 'Unpaid' : 'Paid' }}
                                    </span>
                                </td>
                                {{-- <td class="{{ $payment->status == 0 ? 'bg-danger' : 'bg-success' }}">{{ $payment->status == 0 ? 'Unpaid' : 'Paid' }}</td> --}}
                                <td>{{ $payment->description }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                {{-- </div>               --}}
            {{-- </div>  --}}
            
        </div>
    </div>
</x-app-layout>
