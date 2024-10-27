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
                    <p class="text-2xl font-bold">Add Category</p>
                </div>
                
            </div>

            @if(session('success'))
                <div class="mt-4 p-2 bg-green-200 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            
            <!-- Include jQuery CDN -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

            <!-- Include DataTables CSS and JS files from CDN -->
            <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
            <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>


            <!-- Initialize DataTable with search and export options -->
            <script>
                $(document).ready(function() {
                    $('#payments-table').DataTable({
                        "paging": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                       
                    });
                });
            </script>


            <div class="mb-5 mt-5"> 
                <form action="{{ route('payments.add-category') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="text" name="name" placeholder="Category Name" value="{{ old('name') }}" required>
                    <button type="submit" class="btn btn-primary">Add</button>
                </form>
            </div>



            <div style="width: 50%; display: flex; justify-content: center">
                <div style="width: 100%">       
                    @php
                        $categories = Category::latest()->get();

                    @endphp         
                    <table id="payments-table" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Category Name</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>                               
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>              
            </div> 
            
        </div>
    </div>
</x-app-layout>
