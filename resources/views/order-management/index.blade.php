<x-app-layout>
    <div class="container mx-auto p-4">
        <!-- Judul dan Tombol -->
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Kelola Pesanan</h1>
            <a href="{{ route('orders.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                Buat Pesanan Baru
            </a>
        </div>

        <!-- Tabel Pesanan -->
        <div class="bg-white shadow-md rounded overflow-hidden">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">ID Pesanan</th>
                        <th class="py-2 px-4 border-b">Pelanggan</th>
                        <th class="py-2 px-4 border-b">Total Item</th>
                        <th class="py-2 px-4 border-b">Total Harga</th>
                        <th class="py-2 px-4 border-b">Status</th>
                        <th class="py-2 px-4 border-b">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $order->id }}</td>
                        <td class="py-2 px-4 border-b">{{ $order->customer->name }}</td>
                        <td class="py-2 px-4 border-b">{{ $order->items_count }}</td>
                        <td class="py-2 px-4 border-b">{{ number_format($order->total_price, 2) }}</td>
                        <td class="py-2 px-4 border-b">
                            <span class="px-2 py-1 rounded-full text-white {{ $order->status_color }}">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td class="py-2 px-4 border-b">
                            <a href="{{ route('orders.show', $order->id) }}" class="text-blue-500 hover:underline">Lihat</a>
                            <a href="{{ route('orders.edit', $order->id) }}" class="text-yellow-500 hover:underline ml-2">Edit</a>
                            <button @click="confirmDelete({{ $order->id }})" class="text-red-500 hover:underline ml-2">
                                Hapus
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>

    @push('sweet-alert')
    <script>
        function confirmDelete(orderId) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Pesanan ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete('/orders/' + orderId)
                        .then(response => {
                            Swal.fire(
                                'Dihapus!',
                                'Pesanan telah dihapus.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        })
                        .catch(error => {
                            Swal.fire(
                                'Gagal!',
                                'Terjadi kesalahan saat menghapus pesanan.',
                                'error'
                            );
                        });
                }
            });
        }
    </script>
    @endpush
</x-app-layout>
