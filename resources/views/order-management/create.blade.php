<x-app-layout>
	<div class="container mx-auto p-4">
		<h2 class="mb-4 text-2xl font-bold">Buat Pesanan Baru</h2>
		<form action="{{ route('order.store') }}" method="POST">
			@csrf
			<div class="mb-4">
				<label for="customer_id" class="block text-gray-700">Nama Pelanggan</label>
				<select name="customer_id" id="customer_id" class="w-full rounded border border-gray-300 p-2" required>
					@foreach ($customers as $customer)
						<option value="{{ $customer->id }}">{{ $customer->name }}</option>
					@endforeach
				</select>
			</div>
			<div class="mb-4">
				<label for="service_date" class="block text-gray-700">Tanggal Layanan</label>
				<input type="date" name="service_date" id="service_date" class="w-full rounded border border-gray-300 p-2"
					required>
			</div>
			<div class="mb-4">
				<label for="status" class="block text-gray-700">Status</label>
				<select name="status" id="status" class="w-full rounded border border-gray-300 p-2" required>
					<option value="Pending">Pending</option>
					<option value="Completed">Completed</option>
				</select>
			</div>
			<div class="mb-4">
				<label for="total_amount" class="block text-gray-700">Jumlah Total</label>
				<input type="number" name="total_amount" id="total_amount" class="w-full rounded border border-gray-300 p-2"
					required>
			</div>
			<button type="submit" class="rounded bg-blue-500 px-4 py-2 text-white">Simpan</button>
		</form>
	</div>
</x-app-layout>
