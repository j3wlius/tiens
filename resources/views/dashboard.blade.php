@php
    use App\Models\Category;

@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>


    <style>
      /* Card Styles */
      .card.bg-primary { color: white; }
      .card.bg-success { color: white; }
      .card.bg-warning { color: white; }
      .card.bg-danger { color: white; }

   </style>

   <script>
       
   </script>

    <div class="p-2 h-[90vh] overflow-y-auto">
        <div class="bg-white p-2 h-[90vh] overflow-hidden sm:rounded-lg">
            <div class="flex justify-between">
                <div>
                    <p class="text-2xl font-bold">Dashboard</p>
                </div>              
            </div>

            <div class="container my-4">
               <div class="row gy-4">

                     <!-- Total Distribution No. -->
                     <div class="col-md-4">
                        <div class="card bg-primary">
                           <div class="card-body text-center">
                                 <h5 class="card-title">Total Distribution No.</h5>
                                 <p class="card-text display-6">600</p>
                           </div>
                        </div>
                     </div>

                     <!-- Total Staff Paid -->
                     <div class="col-md-4">
                        <div class="card bg-success">
                           <div class="card-body text-center">
                                 <h5 class="card-title">Total Staff Paid</h5>
                                 <p class="card-text display-6">400</p>
                           </div>
                        </div>
                     </div>

                     <!-- Total Staff Unpaid -->
                     <div class="col-md-4">
                        <div class="card bg-warning">
                           <div class="card-body text-center">
                                 <h5 class="card-title">Total Staff Unpaid</h5>
                                 <p class="card-text display-6">100</p>
                           </div>
                        </div>
                     </div>

                     <!-- Total Net Pay -->
                     <div class="col-md-4">
                        <div class="card bg-danger">
                           <div class="card-body text-center">
                                 <h5 class="card-title">Total Net Pay</h5>
                                 <p class="card-text display-6">10,000</p>
                           </div>
                        </div>
                     </div>

                     <!-- Total Paid Net Pay -->
                     <div class="col-md-4">
                        <div class="card bg-primary">
                           <div class="card-body text-center">
                                 <h5 class="card-title">Total Paid Net Pay</h5>
                                 <p class="card-text display-6">6,000</p>
                           </div>
                        </div>
                     </div>

                     <!-- Total Unpaid Net Pay -->
                     <div class="col-md-4">
                        <div class="card bg-warning">
                           <div class="card-body text-center">
                                 <h5 class="card-title">Total Unpaid Net Pay</h5>
                                 <p class="card-text display-6">5,000</p>
                           </div>
                        </div>
                     </div>

               </div>

               <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
             

               <!-- Charts -->
               {{-- <div class="row mt-4">
                     <div class="col-md-6">
                        <canvas id="barChart" width="400" height="200"></canvas>
                     </div>
                     <div class="col-md-6">
                        <canvas id="pieChart" style="width: 200px !important; height: 150px !important"></canvas>
                     </div>
               </div> --}}

               {{-- <div class="row mt-4">
                  <div class="col-md-6 d-flex justify-content-center">
                      <div class="chart-container">
                          <canvas id="barChart"></canvas>
                      </div>
                  </div>
                  <div class="col-md-6 d-flex justify-content-center">
                      <div class="chart-container">
                          <canvas id="pieChart"></canvas>
                      </div>
                  </div>
               </div> --}}

               <style>
                  /* Larger Canvas Size */
                  .chart-container {
                      position: relative;
                      max-width: 600px; /* Adjust for larger medium size */
                      max-height: 400px; /* Adjust for larger medium size */
                      margin: auto;
                  }
              </style>
              
              <div class="row mt-5">
               <div class="col-md-6">
                  <canvas id="barChart" width="400" height="200"></canvas>
               </div>
                  <div class="col-md-6 d-flex justify-content-center">
                      <div class="chart-container">
                          <canvas id="pieChart"></canvas>
                      </div>
                  </div>
              </div>
              
              

               <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
               <script>
                  // Placeholder data for bar chart
                  var barChartCtx = document.getElementById('barChart').getContext('2d');
                  var barChart = new Chart(barChartCtx, {
                        type: 'bar',
                        data: {
                           labels: ['Distribution', 'Staff Paid', 'Staff Unpaid', 'Net Pay', 'Paid Net Pay', 'Unpaid Net Pay'],
                           datasets: [{
                              label: 'Count',
                              data: [600, 400, 100, 10000, 6000, 5000],
                              backgroundColor: 'rgba(75, 192, 192, 0.6)',
                              borderColor: 'rgba(75, 192, 192, 1)',
                              borderWidth: 1
                           }]
                        },
                        options: {
                           responsive: true,
                           plugins: {
                              legend: { display: false }
                           }
                        }
                  });

                  // Placeholder data for pie chart
                  var pieChartCtx = document.getElementById('pieChart').getContext('2d');
                  var pieChart = new Chart(pieChartCtx, {
                        type: 'pie',
                        data: {
                           labels: ['Paid Net Pay', 'Unpaid Net Pay'],
                           datasets: [{
                              label: 'Amount',
                              data: [6000, 5000],
                              backgroundColor: ['#4CAF50', '#FF9800']
                           }]
                        },
                        options: {
                           responsive: true
                        }
                  });
               </script>




            </div>

    
</body>
</html>

             
            
        </div>
    </div>
</x-app-layout>
