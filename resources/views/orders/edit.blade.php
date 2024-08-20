<x-app-layout>
    <div class="container mx-auto p-6 space-y-6">
        <h1 class="text-3xl font-bold text-gray-800">Edit Pesanan</h1>

        <!-- Edit Order Form -->
        <form method="POST" action="{{ route('orders.update', $order->id) }}" class="bg-white shadow sm:rounded-lg p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Customer Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="customer_name" class="block text-sm font-medium text-gray-700">Nama Pelanggan</label>
                    <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name', $order->customer->name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                </div>
                <div>
                    <label for="customer_email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="customer_email" id="customer_email" value="{{ old('customer_email', $order->customer->email) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                </div>
                <div>
                    <label for="customer_phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                    <input type="text" name="customer_phone" id="customer_phone" value="{{ old('customer_phone', $order->customer->phone) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                </div>
                <div>
                    <label for="service_date" class="block text-sm font-medium text-gray-700">Tanggal Layanan</label>
                    <input type="date" name="service_date" id="service_date" value="{{ old('service_date', $order->service_date) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                </div>
            </div>

            <!-- Order Items -->
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Item dalam Pesanan</h2>
                @foreach ($order->inventories as $inventory)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Item</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $inventory->item_name }}</p>
                        </div>
                        <div>
                            <label for="item_quantity_{{ $inventory->id }}" class="block text-sm font-medium text-gray-700">Kuantitas</label>
                            <input type="number" name="item_quantity[{{ $inventory->id }}]" id="item_quantity_{{ $inventory->id }}" value="{{ old('item_quantity.' . $inventory->id, $inventory->pivot->quantity_used) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Harga Total</label>
                            <p class="mt-1 text-sm text-gray-900">Rp {{ number_format($inventory->pivot->total_price, 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Actions Section -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('orders.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 flex items-center">
                    <i data-lucide="arrow-left" class="inline-block w-5 h-5 mr-2"></i> Kembali ke Daftar Pesanan
                </a>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 flex items-center">
                    <i data-lucide="save" class="inline-block w-5 h-5 mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
