<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-4xl font-extrabold text-gray-800 mb-6">Detail Item Inventaris</h1>

        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4">
                <h3 class="text-xl font-semibold text-gray-900">
                    Informasi Item
                </h3>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-6 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">
                            Nama Item
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $inventory->item_name }}
                        </dd>
                    </div>
                    <div class="bg-white px-6 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">
                            Stock
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ number_format($inventory->quantity) }}
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500">
                            Harga Satuan
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            Rp {{ number_format($inventory->unit_price, 0, ',', '.') }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="mt-6 flex justify-between">
            <a href="{{ route('inventory.edit', $inventory->id) }}" class="focus:shadow-outline rounded-lg bg-yellow-600 px-6 py-3 font-bold text-white hover:bg-yellow-700 transition duration-200">
                Edit Item
            </a>
            <a href="{{ route('inventory.index') }}" class="focus:shadow-outline rounded-lg bg-gray-600 px-6 py-3 font-bold text-white hover:bg-gray-700 transition duration-200">
                Kembali ke Daftar Inventaris
            </a>
        </div>
    </div>
</x-app-layout>
