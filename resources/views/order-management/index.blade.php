<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="mb-6 text-3xl font-bold">Daftar Pesanan</h1>
        <a href="{{ route('orders.create') }}" class="focus:shadow-outline mb-6 inline-block rounded bg-green-500 px-4 py-2 font-bold text-white hover:bg-green-700 focus:outline-none">Tambah Pesanan</a>

        @if (session('success'))
            <div class="mb-4 rounded bg-green-500 px-4 py-2 text-white">
                {{ session('success') }}
            </div>
        @endif

        <!-- Pencarian dan Penyaringan -->
        <div class="flex justify-between items-center mb-6">
            <form class="flex items-center space-x-2">
                <input type="text" placeholder="Cari nama pelanggan..." class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                <select class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                    <option value="">Semua Status</option>
                    <option value="completed">Selesai</option>
                    <option value="processing">Sedang Diproses</option>
                    <option value="canceled">Dibatalkan</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-700">Cari</button>
            </form>
        </div>

        <!-- Tabel Pesanan -->
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full">
                <thead class="bg-green-500 text-white">
                    <tr>
                        <th class="py-3 px-4 text-left">Nama Pelanggan</th>
                        <th class="py-3 px-4 text-left">Tanggal Layanan</th>
                        <th class="py-3 px-4 text-left">Jumlah Layanan</th>
                        <th class="py-3 px-4 text-left">Total Biaya</th>
                        <th class="py-3 px-4 text-left">Status</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($orders as $order)
                        <tr class="hover:bg-gray-100">
                            <td class="py-3 px-4">{{ $order->customer->name }}</td>
                            <td class="py-3 px-4">{{ \Carbon\Carbon::parse($order->service_date)->format('d M Y') }}</td>
                            <td class="py-3 px-4">{{ $order->services_count }}</td>
                            <td class="py-3 px-4">Rp {{ number_format($order->total_cost, 0, ',', '.') }}</td>
                            <td class="py-3 px-4">
                                @if($order->status == 'completed')
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Selesai</span>
                                @elseif($order->status == 'processing')
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded">Sedang Diproses</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">Dibatalkan</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div x-data="{ open: false }" class="inline-block text-left">
                                    <button @click="open = !open" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="options-menu" aria-expanded="true" aria-haspopup="true">
                                        Aksi
                                        <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>

                                    <div x-show="open" @click.away="open = false" class="origin-top-left absolute mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                        <div class="py-1" role="none">
                                            <a href="{{ route('orders.show', $order->id) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem">Lihat Detail</a>
                                            <a href="{{ route('orders.edit', $order->id) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem">Edit</a>
                                            <form @submit.prevent="if (confirm('Apakah Anda yakin ingin menghapus pesanan ini?')) { $el.submit() }" action="{{ route('orders.destroy', $order->id) }}" method="POST" role="none">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100" role="menuitem">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginasi -->
        <div class="mt-6">
            {{ $orders->links() }}
        </div>

        <!-- Informasi Data -->
        <div class="text-sm text-gray-500 mt-4">
            Menampilkan {{ $orders->count() }} dari {{ $orders->total() }} pesanan
        </div>
    </div>
</x-app-layout>
