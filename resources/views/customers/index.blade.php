<x-app-layout>
    <div class="container mx-auto p-6">
        <!-- Breadcrumb -->
        <nav class="text-sm text-gray-500 mb-6">
            <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a> &gt;
            <span>Database Pelanggan</span>
        </nav>

        <h1 class="mb-6 text-3xl font-bold">Database Pelanggan</h1>

        <!-- Pencarian dan Penyaringan -->
        <div class="flex justify-between items-center mb-6">
            <form class="flex items-center space-x-2">
                <input type="text" placeholder="Cari nama atau email..." class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                <select class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                    <option value="">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Tidak Aktif</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-700">Cari</button>
            </form>
            <div class="flex space-x-2">
                <a href="{{ route('customers.create') }}" class="focus:shadow-outline inline-block rounded bg-green-500 px-4 py-2 font-bold text-white hover:bg-green-700 focus:outline-none">Tambah Pelanggan</a>
                <button class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-700">Ekspor CSV</button>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded bg-green-500 px-4 py-2 text-white">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tabel Pelanggan -->
        <div class="overflow-x-auto bg-white rounded-lg shadow"><div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full">
                <thead class="bg-green-500 text-white">
                    <tr>
                        <th class="py-3 px-4 text-left">Nama</th>
                        <th class="py-3 px-4 text-left">Alamat</th>
                        <th class="py-3 px-4 text-left">Nomor Telepon</th>
                        <th class="py-3 px-4 text-left">Email</th>
                        <th class="py-3 px-4 text-left">Status</th>
                        <th class="py-3 px-4 text-left">Total Orders</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($customers as $customer)
                        <tr class="hover:bg-gray-100">
                            <td class="py-3 px-4">{{ $customer->name }}</td>
                            <td class="py-3 px-4">{{ $customer->address }}</td>
                            <td class="py-3 px-4">{{ $customer->phone_number }}</td>
                            <td class="py-3 px-4">{{ $customer->email }}</td>
                            <td class="py-3 px-4">
                                @if($customer->status == 'active')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4">{{ $customer->orders_count }}</td>
                            <td class="py-3 px-4 text-center flex justify-center space-x-2">
                                <a href="{{ route('customers.show', $customer->id) }}" class="text-blue-500 hover:underline" title="Detail">
                                    <i data-lucide="eye" class="w-5 h-5"></i>
                                </a>
                                <a href="{{ route('customers.edit', $customer->id) }}" class="text-yellow-500 hover:underline" title="Edit">
                                    <i data-lucide="edit" class="w-5 h-5"></i>
                                </a>
                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline" title="Hapus">
                                        <i data-lucide="trash" class="w-5 h-5"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $customers->links() }}
        </div>

        </div>

        <!-- Paginasi -->
        <div class="mt-6">
            {{ $customers->links() }}
        </div>

        <!-- Informasi Data -->
        <div class="text-sm text-gray-500 mt-4">
            Menampilkan {{ $customers->count() }} dari {{ $customers->total() }} pelanggan
        </div>
    </div>
</x-app-layout>
