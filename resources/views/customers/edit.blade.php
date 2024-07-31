<x-app-layout>
	<div class="container mx-auto p-4">
		<h2 class="mb-4 text-2xl font-bold">Edit Pelanggan</h2>
		<form action="{{ route('customers.update', $customer->id) }}" method="POST">
			@csrf
			@method('PUT')
			<div class="mb-4">
				<label for="name" class="block text-gray-700">Nama</label>
				<input type="text" name="name" id="name" class="w-full rounded border border-gray-300 p-2"
					value="{{ $customer->name }}" required>
			</div>
			<div class="mb-4">
				<label for="address" class="block text-gray-700">Alamat</label>
				<input type="text" name="address" id="address" class="w-full rounded border border-gray-300 p-2"
					value="{{ $customer->address }}">
			</div>
			<div class="mb-4">
				<label for="phone_number" class="block text-gray-700">Nomor Telepon</label>
				<input type="text" name="phone_number" id="phone_number" class="w-full rounded border border-gray-300 p-2"
					value="{{ $customer->phone_number }}" required>
			</div>
			<div class="mb-4">
				<label for="email" class="block text-gray-700">Email</label>
				<input type="email" name="email" id="email" class="w-full rounded border border-gray-300 p-2"
					value="{{ $customer->email }}" required>
			</div>
			<button type="submit" class="rounded bg-blue-500 px-4 py-2 text-white">Simpan</button>
		</form>
	</div>
</x-app-layout>
