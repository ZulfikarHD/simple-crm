<x-app-layout>
	<div>
		<h1 class="mb-6 text-3xl font-bold">Database Pelanggan</h1>
		<a href="{{ route('customers.create') }}"
			class="focus:shadow-outline mb-6 inline-block rounded bg-green-500 px-4 py-2 font-bold text-white hover:bg-green-700 focus:outline-none">Tambah
			Pelanggan</a>
		@if (session('success'))
			<div class="mb-4 rounded bg-green-500 px-4 py-2 text-white">
				{{ session('success') }}
			</div>
		@endif
		<table class="min-w-full bg-white">
			<thead>
				<tr>
					<th class="py-2">Nama</th>
					<th class="py-2">Alamat</th>
					<th class="py-2">Nomor Telepon</th>
					<th class="py-2">Email</th>
					<th class="py-2">Aksi</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($customers as $customer)
					<tr>
						<td class="py-2">{{ $customer->name }}</td>
						<td class="py-2">{{ $customer->address }}</td>
						<td class="py-2">{{ $customer->phone_number }}</td>
						<td class="py-2">{{ $customer->email }}</td>
						<td class="py-2">
							<a href="{{ route('customers.show', $customer->id) }}" class="text-blue-500">Detail</a>
							<a href="{{ route('customers.edit', $customer->id) }}" class="text-yellow-500">Edit</a>
							<form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="inline-block">
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
