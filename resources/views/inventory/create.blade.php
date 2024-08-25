<x-app-layout>
	<div class="container mx-auto p-6">
		<h1 class="mb-6 text-4xl font-extrabold text-gray-900">Tambah Item Inventaris</h1>
		<form action="{{ route('inventory.store') }}" method="POST" class="bg-white shadow-lg rounded-lg p-6">
			@csrf
			<div class="mb-4">
				<label class="mb-2 block text-sm font-semibold text-gray-700" for="item_name">
					Nama Item
				</label>
				<input type="text" name="item_name" id="item_name"
					class="focus:shadow-outline w-full appearance-none rounded-lg border border-gray-300 px-4 py-2 leading-tight text-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
					required>
			</div>
			<div class="mb-4">
				<label class="mb-2 block text-sm font-semibold text-gray-700" for="quantity">
					Stock
				</label>
				<input type="number" name="quantity" id="quantity"
					class="focus:shadow-outline w-full appearance-none rounded-lg border border-gray-300 px-4 py-2 leading-tight text-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
					required>
			</div>
			<div class="mb-4">
				<label class="mb-2 block text-sm font-semibold text-gray-700" for="unit_price">
					Harga Satuan
				</label>
				<input type="number" step="0.01" name="unit_price" id="unit_price"
					class="focus:shadow-outline w-full appearance-none rounded-lg border border-gray-300 px-4 py-2 leading-tight text-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
					required>
			</div>
			<div class="flex items-center justify-between">
				<button type="submit"
					class="focus:shadow-outline rounded-lg bg-blue-600 px-6 py-2 font-bold text-white hover:bg-blue-700 focus:outline-none">
					Simpan
				</button>
			</div>
		</form>
	</div>
</x-app-layout>
