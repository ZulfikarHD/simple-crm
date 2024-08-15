<x-app-layout>
	<div class="container mx-auto p-6">
		<h1 class="mb-6 text-3xl font-bold">Edit Item Inventaris</h1>
		<form action="{{ route('inventory.update', $inventory->id) }}" method="POST" onsubmit="return confirmSave(event)">
			@csrf
			@method('PUT')

			<!-- Nama Item -->
			<div class="mb-4">
				<label class="mb-2 block text-sm font-bold text-gray-700" for="item_name">
					Nama Item
				</label>
				<input type="text" name="item_name" id="item_name"
					class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"
					value="{{ old('item_name', $inventory->item_name) }}" required>
				@error('item_name')
					<p class="mt-2 text-xs text-red-500">{{ $message }}</p>
				@enderror
			</div>

			<!-- Jumlah -->
			<div class="mb-4">
				<label class="mb-2 block text-sm font-bold text-gray-700" for="quantity">
					Stock
				</label>
				<input type="number" name="quantity" id="quantity"
					class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"
					value="{{ old('quantity', $inventory->quantity) }}" required min="0">
				@error('quantity')
					<p class="mt-2 text-xs text-red-500">{{ $message }}</p>
				@enderror
			</div>

			<!-- Harga Satuan -->
			<div class="mb-4">
				<label class="mb-2 block text-sm font-bold text-gray-700" for="unit_price">
					Harga Satuan
				</label>
				<input type="number" step="0.01" name="unit_price" id="unit_price"
					class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"
					value="{{ old('unit_price', $inventory->unit_price) }}" required min="0">
				@error('unit_price')
					<p class="mt-2 text-xs text-red-500">{{ $message }}</p>
				@enderror
			</div>

			<!-- Tombol Simpan -->
			<div class="flex items-center justify-between">
				<button type="submit"
					class="focus:shadow-outline rounded bg-blue-500 px-4 py-2 font-bold text-white hover:bg-blue-700 focus:outline-none">
					Simpan
				</button>
			</div>
		</form>
	</div>

	<!-- SweetAlert2 -->
	@push('sweet-alert')
		<script>
			function confirmSave(event) {
				event.preventDefault();
				Swal.fire({
					title: 'Konfirmasi Penyimpanan',
					text: "Apakah Anda yakin ingin menyimpan perubahan?",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Ya, simpan!',
					cancelButtonText: 'Batal'
				}).then((result) => {
					if (result.isConfirmed) {
						event.target.submit();
					}
				});
			}
		</script>
	@endpush
</x-app-layout>
