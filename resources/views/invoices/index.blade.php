<x-app-layout>
	<div class="container mx-auto p-6">
		<h1 class="mb-6 text-4xl font-extrabold text-gray-800">Daftar Faktur</h1>

		<!-- Action Buttons & Filters -->
		<div class="mb-6 flex items-center justify-between">
			<a href="{{ route('invoices.create') }}"
				class="focus:shadow-outline mb-6 inline-block rounded-lg bg-green-600 px-6 py-3 font-bold text-white hover:bg-green-700 transition duration-200">Tambah
				Faktur</a>
			<div class="flex items-center space-x-4">
				<input type="text" name="search" placeholder="Cari Pelanggan..."
					class="rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent" id="searchInvoice">
				<select name="status" class="rounded-lg border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
					<option value="">Semua Status</option>
					<option value="paid">Dibayar</option>
					<option value="due">Jatuh Tempo</option>
					<option value="pending">Tertunda</option>
				</select>
			</div>
		</div>

		@if (session('success'))
			<div class="mb-4 rounded-lg bg-green-500 px-4 py-2 text-white">
				{{ session('success') }}
			</div>
		@endif

		<!-- Invoices Table -->
		<div class="overflow-x-auto rounded-lg bg-white shadow-lg">
			<table class="min-w-full" id="invoiceTable">
				<thead class="bg-green-600 text-white">
					<tr>
						<th class="cursor-pointer px-4 py-3 text-left" onclick="sortTable(0)">Nama Pelanggan</th>
						<th class="cursor-pointer px-4 py-3 text-left" onclick="sortTable(1)">Tanggal Penerbitan</th>
						<th class="cursor-pointer px-4 py-3 text-left" onclick="sortTable(2)">Tanggal Jatuh Tempo</th>
						<th class="cursor-pointer px-4 py-3 text-left" onclick="sortTable(3)">Jumlah</th>
						<th class="cursor-pointer px-4 py-3 text-left" onclick="sortTable(4)">Status</th>
						<th class="px-4 py-3 text-center">Aksi</th>
					</tr>
				</thead>
				<tbody class="divide-y divide-gray-200">
					@foreach ($invoices as $invoice)
						<tr class="hover:bg-gray-100 transition duration-150">
							<td class="px-4 py-3">{{ $invoice->order->customer->name }}</td>
							<td class="px-4 py-3">{{ \Carbon\Carbon::parse($invoice->issue_date)->format('d M Y') }}</td>
							<td class="px-4 py-3">{{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</td>
							<td class="px-4 py-3">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
							<td class="px-4 py-3">
								@if ($invoice->status == 'paid')
									<span class="rounded bg-green-200 px-2.5 py-0.5 text-xs font-semibold text-green-800">Dibayar</span>
								@elseif($invoice->status == 'due')
									<span class="rounded bg-yellow-200 px-2.5 py-0.5 text-xs font-semibold text-yellow-800">Jatuh Tempo</span>
								@else
									<span class="rounded bg-red-200 px-2.5 py-0.5 text-xs font-semibold text-red-800">Tertunda</span>
								@endif
							</td>
							<td class="px-4 py-3 text-center">
								<div x-data="{ open: false }" class="inline-block text-left">
									<button @click="open = !open"
										class="inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition duration-200">
										Aksi
										<svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
											stroke="currentColor">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
										</svg>
									</button>

									<div x-show="open" @click.away="open = false"
										class="absolute z-10 mt-2 w-56 origin-top-left rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
										<div class="py-1">
											<a href="{{ route('invoices.show', $invoice->id) }}"
												class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition duration-200">Lihat Detail</a>
											<a href="{{ route('invoices.edit', $invoice->id) }}"
												class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition duration-200">Edit</a>
											<form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" class="inline-block">
												@csrf
												@method('DELETE')
												<button type="button" class="block w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-gray-100 transition duration-200"
													onclick="confirmDelete('{{ $invoice->id }}')">Hapus</button>
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
			{{ $invoices->links() }}
		</div>

		<!-- Data Information -->
		<div class="mt-4 text-sm text-gray-500">
			Menampilkan {{ $invoices->count() }} dari {{ $invoices->total() }} faktur
		</div>
	</div>

	<!-- SweetAlert2 Script -->
	@push('sweet-alert')
		<script>
			function confirmDelete(invoiceId) {
				Swal.fire({
					title: 'Yakin ingin menghapus?',
					text: "Tindakan ini tidak dapat diurungkan!",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#d33',
					cancelButtonColor: '#3085d6',
					confirmButtonText: 'Ya, hapus!'
				}).then((result) => {
					if (result.isConfirmed) {
						document.getElementById(`delete-form-${invoiceId}`).submit();
					}
				});
			}

			// Implement sorting for table columns
			function sortTable(columnIndex) {
				// Sorting logic here
			}

			// Implement filtering/searching
			document.getElementById('searchInvoice').addEventListener('input', function() {
				// Filtering logic here
			});
		</script>
	@endpush
</x-app-layout>
