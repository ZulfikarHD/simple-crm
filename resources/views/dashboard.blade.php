<x-app-layout>
    <div class="container mx-auto p-6">
        <!-- Dashboard Overview Section -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <div class="p-4 bg-white rounded-lg shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="text-xl font-bold">Total Customers</div>
                    <div class="text-3xl font-bold">{{ $totalCustomers }}</div>
                </div>
            </div>
            <div class="p-4 bg-white rounded-lg shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="text-xl font-bold">Pending Orders</div>
                    <div class="text-3xl font-bold">{{ $pendingOrders }}</div>
                </div>
            </div>
            <div class="p-4 bg-white rounded-lg shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="text-xl font-bold">Completed Orders</div>
                    <div class="text-3xl font-bold">{{ $completedOrders }}</div>
                </div>
            </div>
            <div class="p-4 bg-white rounded-lg shadow-lg">
                <div class="flex items-center justify-between">
                    <div class="text-xl font-bold">Total Revenue</div>
                    <div class="text-3xl font-bold">{{ $totalRevenue }}</div>
                </div>
            </div>
        </div>

        <!-- Search and Add Customer Section -->
        <div class="flex justify-between items-center mb-6">
            <form class="flex items-center">
                <input type="text" placeholder="Search by name or email..." class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                <button type="submit" class="ml-4 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-700">Search</button>
            </form>
            <a href="{{ route('customers.create') }}" class="focus:shadow-outline inline-block rounded bg-green-500 px-4 py-2 font-bold text-white hover:bg-green-700 focus:outline-none">Tambah Pelanggan</a>
        </div>

        <!-- Success Toast Notification -->
        @if (session('success'))
            <script>
                Toastify({
                    text: "{{ session('success') }}",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#4CAF50",
                }).showToast();
            </script>
        @endif

        <!-- Customers Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead class="bg-green-500 text-white">
                    <tr>
                        <th class="py-3 px-4 text-left">Nama</th>
                        <th class="py-3 px-4 text-left">Alamat</th>
                        <th class="py-3 px-4 text-left">Nomor Telepon</th>
                        <th class="py-3 px-4 text-left">Email</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $customer)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="py-3 px-4">{{ $customer->name }}</td>
                            <td class="py-3 px-4">{{ $customer->address }}</td>
                            <td class="py-3 px-4">{{ $customer->phone_number }}</td>
                            <td class="py-3 px-4">{{ $customer->email }}</td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('customers.show', $customer->id) }}" class="text-blue-500 hover:underline" title="Detail">
                                    <i data-lucide="eye" class="w-4 h-4 inline"></i>
                                </a>
                                <a href="{{ route('customers.edit', $customer->id) }}" class="text-yellow-500 hover:underline" title="Edit">
                                    <i data-lucide="edit" class="w-4 h-4 inline"></i>
                                </a>
                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline" title="Hapus">
                                        <i data-lucide="trash" class="w-4 h-4 inline"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Recent Activities Section -->
        <div class="mt-8">
            <h2 class="text-2xl font-bold mb-4">Recent Activities</h2>
            <ul class="bg-white rounded-lg shadow-md p-4">
                @foreach ($recentActivities as $activity)
                    <li class="py-2 border-b last:border-b-0">{{ $activity->description }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</x-app-layout>
