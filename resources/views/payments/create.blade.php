<x-app-layout>
	<div>
		<h1 class="mb-6 text-3xl font-bold">Tambah Pembayaran</h1>
		<form action="{{ route('payments.store') }}" method="POST">
			@csrf
			<div class="mb-4">
				<label class="mb-2 block text-sm font-bold text-gray-700" for="invoice_id">
					Faktur
				</label>
				<select name="invoice_id" id="invoice_id"
					class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"
					required>
					<option value="">Pilih Faktur</option>
					@foreach ($invoices as $invoice)
						<option value="{{ $invoice->id }}">{{ $invoice->order->customer->name }} - {{ $invoice->issue_date }}</option>
					@endforeach
				</select>
			</div>
			<div class="mb-4">
				<label class="mb-2 block text-sm font-bold text-gray-700" for="payment_date">
					Tanggal Pembayaran
				</label>
				<input type="date" name="payment_date" id="payment_date"
					class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"
					required>
			</div>
			<div class="mb-4">
				<label class="mb-2 block text-sm font-bold text-gray-700" for="amount">
					Jumlah
				</label>
				<input type="number" step="0.01" name="amount" id="amount"
					class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"
					required>
			</div>
			<div class="mb-4">
				<label class="mb-2 block text-sm font-bold text-gray-700" for="payment_method">
					Metode Pembayaran
				</label>
				<input type="text" name="payment_method" id="payment_method"
					class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"
					required>
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
