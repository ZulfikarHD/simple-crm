<x-app-layout>
	<div class="container mx-auto p-6">
		<h1 class="mb-6 text-4xl font-extrabold text-gray-800">Daftar Inventaris</h1>

		<!-- Tombol Tambah Item -->
		<a href="{{ route('inventory.create') }}"
			class="focus:shadow-outline mb-6 inline-block rounded-lg bg-green-600 px-6 py-3 font-bold text-white hover:bg-green-700 transition duration-200">Tambah
			Item</a>

		<!-- Pesan Sukses -->
		@if (session('success'))
			<div class="mb-4 rounded-lg bg-green-500 px-4 py-2 text-white">
				{{ session('success') }}
			</div>
		@endif

		<!-- Filter dan Sorting -->
		<div class="mb-6 flex items-center justify-between">
			<form class="flex items-center space-x-2">
				<input type="text" name="search" placeholder="Cari nama item..."
					class="rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">

				<select name="sort_by" class="rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
					<option value="item_name">Nama Item</option>
					<option value="quantity">Jumlah</option>
					<option value="unit_price">Harga Satuan</option>
				</select>

				<select name="sort_direction" class="rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
					<option value="asc">Naik</option>
					<option value="desc">Turun</option>
				</select>

				<button type="submit" class="rounded-lg bg-green-600 px-4 py-2 text-white hover:bg-green-700 transition duration-200">Terapkan</button>
			</form>
		</div>

		<!-- Tabel Inventaris -->
		<div class="overflow-x-auto rounded-lg bg-white shadow-lg">
			<table class="min-w-full">
				<thead class="bg-green-600 text-white">
					<tr>
						<th class="px-4 py-3 text-left">Nama Item</th>
						<th class="px-4 py-3 text-left">Stock</th>
						<th class="px-4 py-3 text-left">Harga Satuan</th>
						<th class="px-4 py-3 text-center">Aksi</th>
					</tr>
				</thead>
				<tbody class="divide-y divide-gray-200">
					@foreach ($inventories as $inventory)
						<tr class="hover:bg-gray-100">
							<td class="px-4 py-3">{{ $inventory->item_name }}</td>
							<td class="px-4 py-3">{{ number_format($inventory->quantity) }}</td>
							<td class="px-4 py-3">Rp {{ number_format($inventory->unit_price, 0, ',', '.') }}</td>
							<td class="px-4 py-3 text-center">
								<div x-data="{ open: false }" class="inline-block text-left">
									<button @click="open = !open"
										class="inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
										Aksi
										<svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
											stroke="currentColor" aria-hidden="true">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
										</svg>
									</button>

									<div x-show="open" @click.away="open = false" x-transition
										class="absolute z-10 mt-2 w-56 origin-top-left rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
										role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
										<div class="py-1" role="none">
											<a href="{{ route('inventory.show', $inventory->id) }}"
												class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Lihat Detail</a>
											<a href="{{ route('inventory.edit', $inventory->id) }}"
												class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit</a>
											<button @click.prevent="confirmDelete({{ $inventory->id }})"
												class="block w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-gray-100">Hapus</button>
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
			{{ $inventories->links() }}
		</div>
	</div>

	<!-- SweetAlert2 -->
	@push('sweet-alert')
		<script>
			function confirmDelete(inventoryId) {
				Swal.fire({
					title: 'Apakah Anda yakin?',
					text: "Anda tidak akan dapat memulihkan item ini!",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#d33',
					cancelButtonColor: '#3085d6',
					confirmButtonText: 'Ya, hapus!',
					cancelButtonText: 'Batal'
				}).then((result) => {
					if (result.isConfirmed) {
						// Submit the form to delete the inventory item
						let form = document.createElement('form');
						form.action = `/inventory/${inventoryId}`;
						form.method = 'POST';
						form.innerHTML = `
                        @csrf
                        @method('DELETE')
                    `;
						document.body.appendChild(form);
						form.submit();
					}
				});
			}
		</script>
	@endpush
</x-app-layout>
