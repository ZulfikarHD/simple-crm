<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Dashboard</h1>

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-6">
            <!-- Total Sales -->
            <div class="bg-white p-4 shadow rounded-lg">
                <div class="flex items-center">
                    <i data-lucide="dollar-sign" class="w-6 h-6 text-green-500"></i>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-700">Total Sales</h3>
                        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalSales, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            <!-- Active Orders -->
            <div class="bg-white p-4 shadow rounded-lg">
                <div class="flex items-center">
                    <i data-lucide="shopping-cart" class="w-6 h-6 text-blue-500"></i>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-700">Active Orders</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ $activeOrders }}</p>
                    </div>
                </div>
            </div>
            <!-- Customer Satisfaction -->
            <div class="bg-white p-4 shadow rounded-lg">
                <div class="flex items-center">
                    <i data-lucide="smile" class="w-6 h-6 text-yellow-500"></i>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-700">Customer Satisfaction</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ $customerSatisfaction }}%</p>
                    </div>
                </div>
            </div>
            <!-- Low Stock Items -->
            <div class="bg-white p-4 shadow rounded-lg">
                <div class="flex items-center">
                    <i data-lucide="box" class="w-6 h-6 text-red-500"></i>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-700">Low Stock Items</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ $lowStockItems }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Sales Chart -->
            <div class="bg-white p-4 shadow rounded-lg">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Sales Overview</h3>
                <div id="salesChart"></div>
            </div>
            <!-- Orders Chart -->
            <div class="bg-white p-4 shadow rounded-lg">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Orders by Status</h3>
                <div id="ordersChart"></div>
            </div>
        </div>

        <!-- Recent Activities Section -->
        <div class="bg-white p-4 shadow rounded-lg mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Recent Activities</h3>
            <ul>
                @foreach ($recentActivities as $activity)
                    <li class="mb-2">
                        <span class="text-gray-900">{{ $activity->description }}</span>
                        <span class="text-gray-600">({{ $activity->created_at->diffForHumans() }})</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <a href="{{ route('orders.create-order') }}" class="bg-blue-500 text-white text-center py-2 px-4 rounded-lg shadow hover:bg-blue-700">Create Order</a>
            <a href="{{ route('customers.create') }}" class="bg-green-500 text-white text-center py-2 px-4 rounded-lg shadow hover:bg-green-700">Add Customer</a>
            <a href="{{ route('invoices.create') }}" class="bg-yellow-500 text-white text-center py-2 px-4 rounded-lg shadow hover:bg-yellow-700">Generate Invoice</a>
        </div>
    </div>

    <!-- ApexCharts JS Integration -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script type="module">
        var salesChart = new ApexCharts(document.querySelector("#salesChart"), {
            chart: { type: 'line', height: 350 },
            series: [{ name: 'Sales', data: @json($salesData) }],
            xaxis: { categories: @json($salesCategories) }
        });
        salesChart.render();

        var ordersChart = new ApexCharts(document.querySelector("#ordersChart"), {
            chart: { type: 'pie', height: 350 },
            series: @json($ordersData),
            labels: @json($orderStatuses)
        });
        ordersChart.render();
    </script>
</x-app-layout>
