<x-app-layout>
    <div class="container mx-auto p-6 space-y-6">
        <h1 class="text-4xl font-bold text-gray-900 text-center">Dashboard</h1>

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Sales -->
            <div class="bg-white shadow-lg rounded-lg p-6 transition-transform transform hover:scale-105">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                    <i data-lucide="dollar-sign" class="w-6 h-6 mr-2 text-green-500"></i>Total Sales
                </h2>
                <p class="text-3xl font-bold text-green-600">Rp {{ number_format($totalSales, 2) }}</p>
            </div>

            <!-- Active Orders -->
            <div class="bg-white shadow-lg rounded-lg p-6 transition-transform transform hover:scale-105">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                    <i data-lucide="shopping-cart" class="w-6 h-6 mr-2 text-blue-500"></i>Active Orders
                </h2>
                <p class="text-3xl font-bold text-blue-600">{{ $activeOrders }}</p>
            </div>

            <!-- Customer Satisfaction -->
            <div class="bg-white shadow-lg rounded-lg p-6 transition-transform transform hover:scale-105">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                    <i data-lucide="thumbs-up" class="w-6 h-6 mr-2 text-yellow-500"></i>Customer Satisfaction
                </h2>
                <p class="text-3xl font-bold text-yellow-600">{{ $customerSatisfaction }}%</p>
            </div>

            <!-- Low Stock Items -->
            <div class="bg-white shadow-lg rounded-lg p-6 transition-transform transform hover:scale-105">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                    <i data-lucide="alert-triangle" class="w-6 h-6 mr-2 text-red-500"></i>Low Stock Items
                </h2>
                <p class="text-3xl font-bold text-red-600">{{ $lowStockItems }}</p>
            </div>

            <!-- Top Selling Items -->
            <div class="bg-white shadow-lg rounded-lg p-6 transition-transform transform hover:scale-105">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                    <i data-lucide="trending-up" class="w-6 h-6 mr-2 text-purple-500"></i>Top Selling Items
                </h2>
                <ul class="mt-2 text-gray-700">
                    @foreach($topSellingItems as $item)
                        <li class="py-1 border-b border-gray-200">{{ $item->item_name }} - {{ $item->total_sold }} sold</li>
                    @endforeach
                </ul>
            </div>

            <!-- Recurring Customers -->
            <div class="bg-white shadow-lg rounded-lg p-6 transition-transform transform hover:scale-105">
                <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                    <i data-lucide="repeat" class="w-6 h-6 mr-2 text-indigo-500"></i>Recurring Customers
                </h2>
                <p class="text-3xl font-bold text-purple-600">{{ $recurringCustomers->count() }}</p>
            </div>
        </div>

        <!-- Sales and Order Status Charts -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Sales Chart -->
            <div class="bg-white shadow-lg rounded-lg p-6 transition-transform transform hover:scale-105">
                <h2 class="text-xl font-semibold text-gray-800">Sales (Last 7 Days)</h2>
                <div id="sales-chart"></div>
            </div>

            <!-- Order Status Chart -->
            <div class="bg-white shadow-lg rounded-lg p-6 transition-transform transform hover:scale-105">
                <h2 class="text-xl font-semibold text-gray-800">Order Status</h2>
                <div id="order-status-chart"></div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white shadow-lg rounded-lg p-6 transition-transform transform hover:scale-105">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                <i data-lucide="activity" class="w-6 h-6 mr-2 text-gray-600"></i>Recent Activities
            </h2>
            <ul class="mt-4 space-y-2">
                @foreach($recentActivities as $activity)
                    <li class="flex items-center justify-between border-b border-gray-200 pb-2">
                        <span>{{ $activity->description }}</span>
                        <span class="text-sm text-gray-500">{{ $activity->created_at->diffForHumans() }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    @push('js')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Sales Chart
                var salesOptions = {
                    chart: {
                        type: 'line',
                        height: 350
                    },
                    series: [{
                        name: 'Sales',
                        data: @json($salesData)
                    }],
                    xaxis: {
                        categories: @json($salesCategories)
                    }
                };
                var salesChart = new ApexCharts(document.querySelector("#sales-chart"), salesOptions);
                salesChart.render()

                // Order Status Chart
                var ordersOptions = {
                    chart: {
                        type: 'pie',
                        height: 350
                    },
                    series: @json($ordersData),
                    labels: @json($orderStatuses)
                };
                var ordersChart = new ApexCharts(document.querySelector("#order-status-chart"), ordersOptions);
                ordersChart.render()
            });
        </script>
    @endpush
</x-app-layout>
