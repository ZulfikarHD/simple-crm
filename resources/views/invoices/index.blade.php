<x-app-layout>
	<div class="container mx-auto p-4">
		<h2 class="mb-4 text-2xl font-bold">Daftar Faktur</h2>
		<a href="{{ route('invoices.create') }}" class="rounded bg-blue-500 px-4 py-2 text-white">Buat Faktur Baru</a>
		<div class="mt-4">
			@if ($message = Session::get('success'))
				<div class="mb-4 rounded bg-green-500 p-2 text-white">
					{{ $message }}
				</div>
			@endif
			<table class="min-w-full bg-white">
				<thead>
					<tr>
						<th class="py-2">Nomor Faktur</th>
						<th class="py-2">Nama Pelanggan</th>
						<th class="py-2">Jumlah</th>
						<th class="py-2">Status</th>
						<th class="py-2">Aksi</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($invoices as $invoice)
						<tr>
							<td class="px-4 py-2 text-center">{{ $invoice->invoice_number }}</td>
							<td class="px-4 py-2 text-center">{{ $invoice->order->customer->name }}</td>
							<td class="px-4 py-2 text-end">{{ $invoice->amount }}</td>
							<td class="px-4 py-2 text-center">{{ $invoice->status }}</td>
							<td class="px-4 py-2 text-center">
								<div class="flex gap-2 justify-center">
									<a href="{{ route('invoices.edit', $invoice->id) }}" class="rounded bg-yellow-500 p-2 text-white">
										<i data-lucide="edit" class="w-4 h-4"></i>
									</a>
									<form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" class="inline-block">
										@csrf
										@method('DELETE')
										<button type="submit" class="rounded bg-red-500 p-2 text-white">
											<i data-lucide="trash" class="w-4 h-4"></i>
										</button>
									</form>
								</div>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</x-app-layout>
