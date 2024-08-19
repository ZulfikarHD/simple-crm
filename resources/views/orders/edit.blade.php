<x-app-layout>
    <div class="container mx-auto p-6 space-y-6">
        <!-- Header Section -->
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">Edit Pesanan #{{ $order->id }}</h1>
            <a href="{{ route('orders.show', $order->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                <i data-lucide="arrow-left" class="inline-block w-5 h-5 mr-2"></i> Kembali ke Detail Pesanan
            </a>
        </div>

        <!-- Order Edit Form -->
        <form method="POST" action="{{ route('orders.update', $order->id) }}" class="bg-white shadow sm:rounded-lg p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Customer Information -->
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Pelanggan</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="customer_name" class="block text-sm font-medium text-gray-700">Nama Pelanggan</label>
                        <input type="text" name="customer_name" id="customer_name" value="{{ $order->customer->name }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="customer_email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="customer_email" id="customer_email" value="{{ $order->customer->email }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="customer_phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                        <input type="text" name="customer_phone" id="customer_phone" value="{{ $order->customer->phone }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                </div>
            </div>

            <!-- Order Information -->
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Pesanan</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="service_date" class="block text-sm font-medium text-gray-700">Tanggal Layanan</label>
                        <input type="date" name="service_date" id="service_date" value="{{ $order->service_date }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="partially_paid" {{ $order->status == 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                            <option value="fully_paid" {{ $order->status == 'fully_paid' ? 'selected' : '' }}>Fully Paid</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div>
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
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 flex items-center">
                    <i data-lucide="save" class="inline-block w-5 h-5 mr-2"></i> Simpan Perubahan
                </button>
                <button type="button" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 flex items-center" onclick="confirmDelete({{ $order->id }});">
                    <i data-lucide="trash" class="inline-block w-5 h-5 mr-2"></i> Hapus Pesanan
                </button>
                <form id="delete-form-{{ $order->id }}" action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </form>
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
