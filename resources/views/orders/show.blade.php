<x-app-layout>
    <div class="container mx-auto p-6 space-y-6">
        <h1 class="text-3xl font-bold text-gray-800">Detail Pesanan</h1>

        <!-- Order Summary -->
        <div class="bg-white shadow sm:rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Pesanan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Pelanggan</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $order->customer->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $order->customer->email }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $order->customer->phone }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal Layanan</label>
                    <p class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($order->service_date)->format('d M Y') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Subtotal</label>
                    <p class="mt-1 text-sm text-gray-900">Rp {{ number_format($order->subtotal, 2) }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Total</label>
                    <p class="mt-1 text-sm text-gray-900">Rp {{ number_format($order->total_amount, 2) }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $order->status == 'pending' ? 'bg-red-100 text-red-800' : ($order->status == 'partially_paid' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white shadow sm:rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Item dalam Pesanan</h2>
            <ul class="space-y-2">
                @foreach ($order->inventories as $item)
                    <li class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $item->item_name }}</p>
                            <p class="text-sm text-gray-500">Kuantitas: {{ $item->pivot->quantity_used }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-900">Rp {{ number_format($item->pivot->total_price, 2) }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Actions Section -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('orders.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 flex items-center">
                <i data-lucide="arrow-left" class="inline-block w-5 h-5 mr-2"></i> Kembali ke Daftar Pesanan
            </a>
            <a href="{{ route('orders.edit', $order->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 flex items-center">
                <i data-lucide="edit" class="inline-block w-5 h-5 mr-2"></i> Edit Pesanan
            </a>
        </div>
    </div>
</x-app-layout>
