<x-app-layout>
	<div class="container mx-auto p-6">
		<h1 class="mb-6 text-3xl font-bold">Detail Faktur</h1>

		<!-- Informasi Faktur -->
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
							{{ \Carbon\Carbon::parse($invoice->issue_date)->format('d M Y') }}
						</dd>
					</div>
					<div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
						<dt class="text-sm font-medium text-gray-500">
							Tanggal Jatuh Tempo
						</dt>
						<dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
							{{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}
						</dd>
					</div>
					<div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
						<dt class="text-sm font-medium text-gray-500">
							Jumlah
						</dt>
						<dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
							Rp {{ number_format($invoice->amount, 0, ',', '.') }}
						</dd>
					</div>
					<div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
						<dt class="text-sm font-medium text-gray-500">
							Status
						</dt>
						<dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
							@if ($invoice->status == 'paid')
								<span
									class="mr-2 inline-flex items-center justify-center rounded-full bg-green-500 px-2 py-1 text-xs font-bold leading-none text-green-100">Dibayar</span>
							@elseif($invoice->status == 'due')
								<span
									class="mr-2 inline-flex items-center justify-center rounded-full bg-yellow-500 px-2 py-1 text-xs font-bold leading-none text-yellow-100">Jatuh
									Tempo</span>
							@else
								<span
									class="mr-2 inline-flex items-center justify-center rounded-full bg-red-500 px-2 py-1 text-xs font-bold leading-none text-red-100">Tertunda</span>
							@endif
						</dd>
					</div>
				</dl>
			</div>
		</div>

		<!-- Tombol Aksi -->
		<div class="mt-6 flex space-x-4">
			<a href="{{ route('invoices.edit', $invoice->id) }}"
				class="focus:shadow-outline inline-block rounded bg-yellow-500 px-4 py-2 font-bold text-white hover:bg-yellow-700 focus:outline-none">Edit
				Faktur</a>

			<button id="deleteButton"
				class="focus:shadow-outline inline-block rounded bg-red-500 px-4 py-2 font-bold text-white hover:bg-red-700 focus:outline-none">Hapus
				Faktur</button>

			<a href="{{ route('invoices.index') }}"
				class="focus:shadow-outline inline-block rounded bg-gray-500 px-4 py-2 font-bold text-white hover:bg-gray-700 focus:outline-none">Kembali
				ke Daftar Faktur</a>
		</div>

		<!-- Pembayaran -->
		<div class="mt-10">
			<h2 class="mb-4 text-2xl font-bold">Pembayaran</h2>
			<a href="{{ route('payments.create', ['invoice_id' => $invoice->id]) }}"
				class="focus:shadow-outline mb-6 inline-block rounded bg-green-500 px-4 py-2 font-bold text-white hover:bg-green-700 focus:outline-none">Tambah
				Pembayaran</a>
			<div class="overflow-x-auto rounded-lg bg-white shadow">
				<table class="min-w-full bg-white">
					<thead class="bg-green-500 text-white">
						<tr>
							<th class="px-4 py-3 text-left">Tanggal Pembayaran</th>
							<th class="px-4 py-3 text-left">Jumlah</th>
							<th class="px-4 py-3 text-left">Metode Pembayaran</th>
						</tr>
					</thead>
					<tbody class="divide-y divide-gray-200">
						@foreach ($invoice->payments as $payment)
							<tr class="hover:bg-gray-100">
								<td class="px-4 py-3">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
								<td class="px-4 py-3">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
								<td class="px-4 py-3">{{ $payment->payment_method }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>


    @push('sweet-alert')
        <!-- SweetAlert2 Script -->
        <script>
            document.getElementById('deleteButton').addEventListener('click', function(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda tidak akan dapat memulihkan faktur ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit the form using a hidden form
                        let form = document.createElement('form');
                        form.action = "{{ route('invoices.destroy', $invoice->id) }}";
                        form.method = 'POST';
                        form.innerHTML = `
                                @csrf
                                @method('DELETE')
                            `;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>

