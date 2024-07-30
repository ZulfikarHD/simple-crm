<x-app-layout>
	<div class="container mx-auto p-4">
		<h2 class="mb-4 text-2xl font-bold">Edit Invoice</h2>
		<form action="{{ route('invoices.update', $invoice->id) }}" method="POST">
			@csrf
			@method('PUT')
			<div class="mb-4">
				<label for="invoice_number" class="block text-gray-700">Invoice Number</label>
				<input type="text" name="invoice_number" id="invoice_number" class="w-full rounded border border-gray-300 p-2"
					value="{{ $invoice->invoice_number }}" required>
			</div>
			<div class="mb-4">
				<label for="customer_name" class="block text-gray-700">Customer Name</label>
				<input type="text" name="customer_name" id="customer_name" class="w-full rounded border border-gray-300 p-2"
					value="{{ $invoice->customer_name }}" required>
			</div>
			<div class="mb-4">
				<label for="amount" class="block text-gray-700">Amount</label>
				<input type="number" name="amount" id="amount" class="w-full rounded border border-gray-300 p-2"
					value="{{ $invoice->amount }}" required>
			</div>
			<div class="mb-4">
				<label for="status" class="block text-gray-700">Status</label>
				<select name="status" id="status" class="w-full rounded border border-gray-300 p-2" required>
					<option value="Pending" {{ $invoice->status == 'Pending' ? 'selected' : '' }}>Pending</option>
					<option value="Paid" {{ $invoice->status == 'Paid' ? 'selected' : '' }}>Paid</option>
				</select>
			</div>
			<button type
="submit" class="rounded bg-blue-500 px-4 py-2 text-white">Save</button>
		</form>
	</div>
</x-app-layout>
