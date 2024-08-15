<x-app-layout>
	<div class="container mx-auto p-6">
		<h1 class="mb-6 text-3xl font-bold">Daftar Faktur</h1>

		<!-- Tombol Tambah Faktur -->
		<a href="{{ route('invoices.create') }}"
			class="focus:shadow-outline mb-6 inline-block rounded bg-green-500 px-4 py-2 font-bold text-white hover:bg-green-700 focus:outline-none">Tambah
			Faktur</a>

		@if (session('success'))
			<div class="mb-4 rounded bg-green-500 px-4 py-2 text-white">
				{{ session('success') }}
			</div>
		@endif

		<!-- Filter dan Sorting -->
		<div class="mb-6 flex items-center justify-between">
			<form class="flex items-center space-x-2">
				<input type="text" name="customer" value="{{ request('customer') }}" placeholder="Cari nama pelanggan..."
					class="rounded-lg border px-4 py-2 focus:ring-2 focus:ring-green-500">

				<select name="status" class="rounded-lg border px-4 py-2 focus:ring-2 focus:ring-green-500">
					<option value="">Semua Status</option>
					<option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Dibayar</option>
					<option value="due" {{ request('status') == 'due' ? 'selected' : '' }}>Jatuh Tempo</option>
					<option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Tertunda</option>
				</select>

				<select name="sort_by" class="rounded-lg border px-4 py-2 focus:ring-2 focus:ring-green-500">
					<option value="issue_date" {{ request('sort_by') == 'issue_date' ? 'selected' : '' }}>Tanggal Penerbitan</option>
					<option value="due_date" {{ request('sort_by') == 'due_date' ? 'selected' : '' }}>Tanggal Jatuh Tempo</option>
					<option value="amount" {{ request('sort_by') == 'amount' ? 'selected' : '' }}>Jumlah</option>
				</select>

				<select name="sort_direction" class="rounded-lg border px-4 py-2 focus:ring-2 focus:ring-green-500">
					<option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>Naik</option>
					<option value="desc" {{ request('sort_direction') == 'desc' ? 'selected' : '' }}>Turun</option>
				</select>

				<button type="submit" class="rounded-lg bg-green-500 px-4 py-2 text-white hover:bg-green-700">Terapkan</button>
			</form>
		</div>

		<!-- Tabel Faktur -->
		<div class="overflow-x-auto rounded-lg bg-white shadow">
			<table class="min-w-full">
				<thead class="bg-green-500 text-white">
					<tr>
						<th class="px-4 py-3 text-left">Nama Pelanggan</th>
						<th class="px-4 py-3 text-left">Tanggal Penerbitan</th>
						<th class="px-4 py-3 text-left">Tanggal Jatuh Tempo</th>
						<th class="px-4 py-3 text-left">Jumlah</th>
						<th class="px-4 py-3 text-left">Status</th>
						<th class="px-4 py-3 text-center">Aksi</th>
					</tr>
				</thead>
				<tbody class="divide-y divide-gray-200">
					@foreach ($invoices as $invoice)
						<tr class="hover:bg-gray-100">
							<td class="px-4 py-3">{{ $invoice->order->customer->name }}</td>
							<td class="px-4 py-3">{{ \Carbon\Carbon::parse($invoice->issue_date)->format('d M Y') }}</td>
							<td class="px-4 py-3">{{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</td>
							<td class="px-4 py-3">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
							<td class="px-4 py-3">
								@if ($invoice->status == 'paid')
									<span class="rounded bg-green-100 px-2.5 py-0.5 text-xs font-semibold text-green-800">Dibayar</span>
								@elseif($invoice->status == 'due')
									<span class="rounded bg-yellow-100 px-2.5 py-0.5 text-xs font-semibold text-yellow-800">Jatuh Tempo</span>
								@else
									<span class="rounded bg-red-100 px-2.5 py-0.5 text-xs font-semibold text-red-800">Tertunda</span>
								@endif
							</td>
							<td class="px-4 py-3 text-center">
								<div x-data="{ open: false }" class="inline-block text-left">
									<button @click="open = !open"
										class="inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
										id="options-menu" aria-expanded="true" aria-haspopup="true">
										Aksi
										<svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
											stroke="currentColor" aria-hidden="true">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
										</svg>
									</button>

									<div x-show="open" @click.away="open = false"
										class="absolute z-10 mt-2 w-56 origin-top-left rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
										role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
										<div class="py-1" role="none">
											<a href="{{ route('invoices.show', $invoice->id) }}"
												class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Lihat Detail</a>
											<a href="{{ route('invoices.edit', $invoice->id) }}"
												class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit</a>
											<button @click.prevent="confirmDelete({{ $invoice->id }})"
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
			{{ $invoices->links() }}
		</div>

		<!-- Informasi Data -->
		<div class="mt-4 text-sm text-gray-500">
			Menampilkan {{ $invoices->count() }} dari {{ $invoices->total() }} faktur
		</div>
	</div>

	<!-- SweetAlert2 -->
	@push('sweet-alert')
		<script>
			function confirmDelete(invoiceId) {
				Swal.fire({
					title: 'Apakah Anda yakin?',
					text: "Anda tidak akan dapat memulihkan faktur ini!",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#d33',
					cancelButtonColor: '#3085d6',
					confirmButtonText: 'Ya, hapus!',
					cancelButtonText: 'Batal'
				}).then((result) => {
					if (result.isConfirmed) {
						// Submit the form to delete the invoice
						let form = document.createElement('form');
						form.action = `/invoices/${invoiceId}`;
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
