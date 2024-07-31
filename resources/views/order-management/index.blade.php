<x-app-layout>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">Daftar Pesanan</h2>
        <a href="{{ route('order.create') }}" class="bg-blue-500 text-white py-2 px-4 rounded">Buat Pesanan Baru</a>
        <div class="mt-4">
            @if ($message = Session::get('success'))
                <div class="bg-green-500 text-white p-2 rounded mb-4">
                    {{ $message }}
                </div>
            @endif
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2">Nomor Pesanan</th>
                        <th class="py-2">Nama Pelanggan</th>
                        <th class="py-2">Tanggal Service</th>
                        <th class="py-2">Status</th>
                        <th class="py-2">Jumlah Pembayaran</th>
                        <th class="py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td class="py-2 px-4 text-center">{{ $order->id }}</td>
                            <td class="py-2 px-4 text-center">{{ $order->customer->name }}</td>
                            <td class="py-2 px-4 text-center">{{ $order->service_date }}</td>
                            <td class="py-2 px-4 text-center">{{ $order->status }}</td>
                            <td class="py-2 px-4 text-center">{{ $order->total_amount }}</td>
                            <td class="py-2 px-4 text-center">
                                <div class="flex gap-2 justify-center">
                                    <a href="{{ route('order.edit', $order->id) }}" class="bg-yellow-500 text-white px-2 py-1.5 rounded">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('order.destroy', $order->id) }}" method="POST" >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white  px-2 py-1.5  rounded">
                                            <i data-lucide="trash" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
