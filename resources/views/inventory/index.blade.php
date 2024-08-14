<x-app-layout>
    <div>
        <h1 class="text-3xl font-bold mb-6">Daftar Inventaris</h1>
        <a href="{{ route('inventory.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mb-6 inline-block">Tambah Item</a>
        @if(session('success'))
            <div class="bg-green-500 text-white py-2 px-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2">Nama Item</th>
                    <th class="py-2">Jumlah</th>
                    <th class="py-2">Harga Satuan</th>
                    <th class="py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inventories as $inventory)
                <tr>
                    <td class="py-2">{{ $inventory->item_name }}</td>
                    <td class="py-2">{{ $inventory->quantity }}</td>
                    <td class="py-2">{{ $inventory->unit_price }}</td>
                    <td class="py-2">
                        <a href="{{ route('inventory.show', $inventory->id) }}" class="text-blue-500" title="Detail">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </a>
                        <a href="{{ route('inventory.edit', $inventory->id) }}" class="text-yellow-500" title="Edit">
                            <i data-lucide="edit" class="w-4 h-4"></i>
                        </a>
                        <form action="{{ route('inventory.destroy', $inventory->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500" title="Hapus">
                                <i data-lucide="trash" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
