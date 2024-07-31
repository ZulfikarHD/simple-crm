<x-app-layout>
	<div>
		<h1 class="mb-6 text-3xl font-bold">Daftar Faktur</h1>
		<a href="{{ route('invoices.create') }}"
			class="focus:shadow-outline mb-6 inline-block rounded bg-green-500 px-4 py-2 font-bold text-white hover:bg-green-700 focus:outline-none">Tambah
			Faktur</a>
		@if (session('success'))
			<div class="mb-4 rounded bg-green-500 px-4 py-2 text-white">
				{{ session('success') }}
			</div>
		@endif
		<table class="min-w-full bg-white">
			<thead>
				<tr>
					<th class="py-2">Nama Pelanggan</th>
					<th class="py-2">Tanggal Penerbitan</th>
					<th class="py-2">Tanggal Jatuh Tempo</th>
					<th class="py-2">Jumlah</th>
					<th class="py-2">Status</th>
					<th class="py-2">Aksi</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($invoices as $invoice)
					<tr>
						<td class="py-2">{{ $invoice->order->customer->name }}</td>
						<td class="py-2">{{ $invoice->issue_date }}</td>
						<td class="py-2">{{ $invoice->due_date }}</td>
						<td class="py-2">{{ $invoice->amount }}</td>
						<td class="py-2">{{ $invoice->status }}</td>
						<td class="py-2">
							<a href="{{ route('invoices.show', $invoice->id) }}" class="text-blue-500">Detail</a>
							<a href="{{ route('invoices.edit', $invoice->id) }}" class="text-yellow-500">Edit</a>
							<form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" class="inline-block">
								@csrf
								@method('DELETE')
								<button type="submit" class="text-red-500">Hapus</button>
							</form>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</x-app-layout>
