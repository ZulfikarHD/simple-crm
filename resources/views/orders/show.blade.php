<x-app-layout>
    <div class="container mx-auto p-6 space-y-6">
        <!-- Header Section -->
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">Detail Pesanan #{{ $order->id }}</h1>
            <a href="{{ route('orders.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                <i data-lucide="arrow-left" class="inline-block w-5 h-5 mr-2"></i> Kembali ke Daftar Pesanan
            </a>
        </div>

        <!-- Order Information -->
        <div class="bg-white shadow sm:rounded-lg p-6 space-y-4">
            <div class="flex flex-col md:flex-row md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Informasi Pelanggan</h2>
                    <p class="text-gray-600 mt-1">{{ $order->customer->name }}</p>
                    <p class="text-gray-600">{{ $order->customer->email }}</p>
                    <p class="text-gray-600">{{ $order->customer->phone }}</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <h2 class="text-lg font-semibold text-gray-800">Informasi Pesanan</h2>
                    <p class="text-gray-600 mt-1">Tanggal Layanan: {{ \Carbon\Carbon::parse($order->service_date)->format('d M Y') }}</p>
                    <p class="text-gray-600">Status:
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $order->status == 'pending' ? 'bg-red-100 text-red-800' : ($order->status == 'partially_paid' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>
                    <p class="text-gray-600">Total: Rp {{ number_format($order->total_amount, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white shadow sm:rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Item dalam Pesanan</h2>
            <div class="space-y-4">
                @foreach ($order->inventories as $inventory)
                    <div class="p-4 bg-gray-50 rounded-lg flex justify-between items-center">
                        <div>
                            <h3 class="text-sm font-medium text-gray-800">{{ $inventory->item_name }}</h3>
                            <p class="text-sm text-gray-600">{{ $inventory->pivot->quantity_used }} unit</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-800">Rp {{ number_format($inventory->pivot->price_per_unit, 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('orders.edit', $order->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 flex items-center">
                <i data-lucide="edit" class="inline-block w-5 h-5 mr-2"></i> Edit Pesanan
            </a>
            <button type="button" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 flex items-center" onclick="confirmDelete({{ $order->id }});">
                <i data-lucide="trash" class="inline-block w-5 h-5 mr-2"></i> Hapus Pesanan
            </button>
            <form id="delete-form-{{ $order->id }}" action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>

    <!-- SweetAlert2 Script -->
    @push('sweet-alert')
    <script>
        function confirmDelete(orderId) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: "Anda yakin ingin menghapus pesanan ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + orderId).submit();
                    Swal.fire('Dihapus!', 'Pesanan telah dihapus.', 'success');
                }
            });
        }
    </script>
    @endpush
</x-app-layout>
