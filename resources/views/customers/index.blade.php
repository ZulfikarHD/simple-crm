<x-app-layout>
	<div class="container mx-auto p-4">
		<h2 class="mb-4 text-2xl font-bold">Daftar Pelanggan</h2>
		<a href="{{ route('customers.create') }}" class="rounded bg-blue-500 px-4 py-2 text-white">Tambah Pelanggan</a>
		<div class="mt-4">
			@if ($message = Session::get('success'))
				<div class="mb-4 rounded bg-green-500 p-2 text-white">
					{{ $message }}
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
								<a href="{{ route('customers.edit', $customer->id) }}"
									class="rounded bg-yellow-500 px-2 py-1 text-white">Edit</a>
								<form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="inline-block">
									@csrf
									@method('DELETE')
									<button type="submit" class="rounded bg-red-500 px-2 py-1 text-white">Hapus</button>
								</form>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</x-app-layout>
