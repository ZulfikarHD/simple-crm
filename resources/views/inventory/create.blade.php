<x-app-layout>
	<div>
		<h1 class="mb-6 text-3xl font-bold">Tambah Item Inventaris</h1>
		<form action="{{ route('inventory.store') }}" method="POST">
			@csrf
			<div class="mb-4">
				<label class="mb-2 block text-sm font-bold text-gray-700" for="item_name">
					Nama Item
				</label>
				<input type="text" name="item_name" id="item_name"
					class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"
					required>
			</div>
			<div class="mb-4">
				<label class="mb-2 block text-sm font-bold text-gray-700" for="quantity">
					Stock
				</label>
				<input type="number" name="quantity" id="quantity"
					class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"
					required>
			</div>
			<div class="mb-4">
				<label class="mb-2 block text-sm font-bold text-gray-700" for="unit_price">
					Harga Satuan
				</label>
				<input type="number" step="0.01" name="unit_price" id="unit_price"
					class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"
					required>
			</div>
			<div class="flex items-center justify-between">
				<button type="submit"
					class="focus:shadow-outline rounded bg-blue-500 px-4 py-2 font-bold text-white hover:bg-blue-700 focus:outline-none">
					Simpan
				</button>
			</div>
		</form>
	</div>
</x-app-layout>
