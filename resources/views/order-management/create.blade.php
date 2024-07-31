<x-app-layout>

	<div>
		<h1 class="mb-6 text-3xl font-bold">Tambah Pesanan</h1>
		<form action="{{ route('orders.store') }}" method="POST">
			@csrf
			<div class="mb-4">
				<label class="mb-2 block text-sm font-bold text-gray-700" for="customer_id">
					Pelanggan
				</label>
				<select name="customer_id" id="customer_id"
					class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"
					required>
					<option value="">Pilih Pelanggan</option>
					@foreach ($customers as $customer)
						<option value="{{ $customer->id }}">{{ $customer->name }}</option>
					@endforeach
				</select>
			</div>
			<div class="mb-4">
				<label class="mb-2 block text-sm font-bold text-gray-700" for="service_date">
					Tanggal Layanan
				</label>
				<input type="date" name="service_date" id="service_date"
					class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"
					required>
			</div>
			<div class="mb-4">
				<label class="mb-2 block text-sm font-bold text-gray-700" for="status">
					Status
				</label>
				<input type="text" name="status" id="status"
					class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"
					required>
			</div>
			<div class="mb-4">
				<label class="mb-2 block text-sm font-bold text-gray-700" for="notes">
					Catatan
				</label>
				<textarea name="notes" id="notes"
				 class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"></textarea>
			</div>
			<div class="flex items-center justify-between">
				<button type="submit"
					class="focus:shadow-outline rounded bg-blue-500 px-4 py-2 font-bold text-white hover:bg-blue-700 focus:outline-none">
					Simpan
				</button>
			</div>
		</form>
	</div>
</x-app-layout>
