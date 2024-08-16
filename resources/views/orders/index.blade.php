<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Kelola Pesanan</h1>

        <!-- Card Wrapper -->
        <div class="bg-white shadow-lg rounded-lg p-6">
            <!-- Filters and Sorting -->
            <div class="mb-6">
                <form class="flex items-center space-x-4">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pelanggan..." class="w-1/3 border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">

                    <select name="status" class="w-1/4 border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Sedang Diproses</option>
                        <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>

                    <select name="sort_by" class="w-1/4 border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Tanggal Pesanan</option>
                        <option value="service_date" {{ request('sort_by') == 'service_date' ? 'selected' : '' }}>Tanggal Layanan</option>
                    </select>

                    <select name="sort_direction" class="w-1/4 border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>Naik</option>
                        <option value="desc" {{ request('sort_direction') == 'desc' ? 'selected' : '' }}>Turun</option>
                    </select>

                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Terapkan</button>
                </form>
            </div>

            <!-- Orders Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-2 px-4 text-left">Nama Pelanggan</th>
                            <th class="py-2 px-4 text-left">Tanggal Pesanan</th>
                            <th class="py-2 px-4 text-left">Tanggal Layanan</th>
                            <th class="py-2 px-4 text-left">Status</th>
                            <th class="py-2 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($orders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="py-2 px-4">{{ $order->customer->name }}</td>
                                <td class="py-2 px-4">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}</td>
                                <td class="py-2 px-4">{{ \Carbon\Carbon::parse($order->service_date)->format('d M Y') }}</td>
                                <td class="py-2 px-4">
                                    @if($order->status == 'completed')
                                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Selesai</span>
                                    @elseif($order->status == 'processing')
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded">Sedang Diproses</span>
                                    @else
                                        <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">Dibatalkan</span>
                                    @endif
                                </td>
                                <td class="py-2 px-4 text-center">
                                    <div x-data="{ open: false }" class="relative inline-block text-left">
                                        <button @click="open = !open" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" id="options-menu" aria-expanded="true" aria-haspopup="true">
                                            Aksi
                                            <svg :class="{ 'rotate-180': open }" class="ml-2 h-5 w-5 transition-transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>

                                        <div x-show="open" @click.away="open = false" class="origin-top-left absolute mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10">
                                            <div class="py-1">
                                                <a href="{{ route('orders.show', $order->id) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100">Lihat Detail</a>
                                                <a href="{{ route('orders.edit', $order->id) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100">Edit</a>
                                                <form action="{{ route('orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus pesanan ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 block w-full text-left px-4 py-2 text-sm hover:bg-gray-100">Hapus</button>
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

            <!-- Pagination -->
            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        </div>
        <!-- End of Card Wrapper -->
    </div>
</x-app-layout>
