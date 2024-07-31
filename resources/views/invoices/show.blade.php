<x-app-layout>
	<div>
		<h1 class="mb-6 text-3xl font-bold">Detail Faktur</h1>
		<div class="overflow-hidden bg-white shadow sm:rounded-lg">
			<div class="px-4 py-5 sm:px-6">
				<h3 class="text-lg font-medium leading-6 text-gray-900">
					Informasi Faktur
				</h3>
			</div>
			<div class="border-t border-gray-200">
				<dl>
					<div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
						<dt class="text-sm font-medium text-gray-500">
							Nama Pelanggan
						</dt>
						<dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
							{{ $invoice->order->customer->name }}
						</dd>
					</div>
					<div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
						<dt class="text-sm font-medium text-gray-500">
							Tanggal Penerbitan
						</dt>
						<dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
							{{ $invoice->issue_date }}
						</dd>
					</div>
					<div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
						<dt class="text-sm font-medium text-gray-500">
							Tanggal Jatuh Tempo
						</dt>
						<dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
							{{ $invoice->due_date }}
						</dd>
					</div>
					<div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
						<dt class="text-sm font-medium text-gray-500">
							Jumlah
						</dt>
						<dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
							{{ $invoice->amount }}
						</dd>
					</div>
					<div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
						<dt class="text-sm font-medium text-gray-500">
							Status
						</dt>
						<dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
							{{ $invoice->status }}
						</dd>
					</div>
				</dl>
			</div>
		</div>
		<div class="mt-6">
			<h2 class="mb-4 text-2xl font-bold">Pembayaran</h2>
			<a href="{{ route('payments.create', ['invoice_id' => $invoice->id]) }}"
				class="focus:shadow-outline mb-6 inline-block rounded bg-green-500 px-4 py-2 font-bold text-white hover:bg-green-700 focus:outline-none">Tambah
				Pembayaran</a>
			<table class="min-w-full bg-white">
				<thead>
					<tr>
						<th class="py-2">Tanggal Pembayaran</th>
						<th class="py-2">Jumlah</th>
						<th class="py-2">Metode Pembayaran</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($invoice->payments as $payment)
						<tr>
							<td class="py-2">{{ $payment->payment_date }}</td>
							<td class="py-2">{{ $payment->amount }}</td>
							<td class="py-2">{{ $payment->payment_method }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</x-app-layout>
