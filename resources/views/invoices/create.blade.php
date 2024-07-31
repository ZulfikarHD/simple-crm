<x-app-layout>
	<div class="container mx-auto p-4">
		<h2 class="mb-4 text-2xl font-bold">Buat Faktur Baru</h2>
		<form action="{{ route('invoices.store') }}" method="POST">
			@csrf
			<div class="mb-4">
				<label for="order_id" class="block text-gray-700">Nomor Pesanan</label>
				<select name="order_id" id="order_id" class="w-full rounded border border-gray-300 p-2" required>
					@foreach ($orders as $order)
						<option value="{{ $order->id }}">{{ $order->id }} - {{ $order->customer->name }}</option>
					@endforeach
				</select>
			</div>
			<div class="mb-4">
				<label for="issue_date" class="block text-gray-700">Tanggal Terbit</label>
				<input type="date" name="issue_date" id="issue_date" class="w-full rounded border border-gray-300 p-2" required>
			</div>
			<div class="mb-4">
				<label for="due_date" class="block text-gray-700">Tanggal Jatuh Tempo</label>
				<input type="date" name="due_date" id="due_date" class="w-full rounded border border-gray-300 p-2" required>
			</div>
			<div class="mb-4">
				<label for="amount" class="block text-gray-700">Jumlah</label>
				<input type="number" name="amount" id="amount" class="w-full rounded border border-gray-300 p-2" required>
			</div>
			<div class="mb-4">
				<label for="status" class="block text-gray-700">Status</label>
				<select name="status" id="status" class="w-full rounded border border-gray-300 p-2" required>
					<option value="Pending">Pending</option>
					<option value="Paid">Paid</option>
				</select>
			</div>
			<button type="submit" class="rounded bg-blue-500 px-4 py-2 text-white">Simpan</button>
		</form>
	</div>
</x-app-layout>
