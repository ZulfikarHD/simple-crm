<x-app-layout>
	<div>
		<h1 class="mb-6 text-3xl font-bold">Daftar Pesanan</h1>
		<a href="{{ route('orders.create') }}"
			class="focus:shadow-outline mb-6 inline-block rounded bg-green-500 px-4 py-2 font-bold text-white hover:bg-green-700 focus:outline-none">Tambah
			Pesanan</a>
		@if (session('success'))
			<div class="mb-4 rounded bg-green-500 px-4 py-2 text-white">
				{{ session('success') }}
			</div>
		@endif
		<table class="min-w-full bg-white">
			<thead>
				<tr>
					<th class="py-2">Nama Pelanggan</th>
					<th class="py-2">Tanggal Layanan</th>
					<th class="py-2">Status</th>
					<th class="py-2">Aksi</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($orders as $order)
					<tr>
						<td class="py-2">{{ $order->customer->name }}</td>
						<td class="py-2">{{ $order->service_date }}</td>
						<td class="py-2">{{ $order->status }}</td>
						<td class="py-2">
							<a href="{{ route('orders.show', $order->id) }}" class="text-blue-500" title="Detail">
								<i data-lucide="eye" class="w-4 h-4"></i>
							</a>
							<a href="{{ route('orders.edit', $order->id) }}" class="text-yellow-500" title="Edit">
								<i data-lucide="edit" class="w-4 h-4"></i>
							</a>
							<form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="inline-block">
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
