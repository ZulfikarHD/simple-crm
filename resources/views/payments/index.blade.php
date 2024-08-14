				<x-app-layout>
					<div>
						<h1 class="mb-6 text-3xl font-bold">Daftar Pembayaran</h1>
						<a href="{{ route('payments.create') }}"
							class="focus:shadow-outline mb-6 inline-block rounded bg-green-500 px-4 py-2 font-bold text-white hover:bg-green-700 focus:outline-none">Tambah
							Pembayaran</a>
						@if (session('success'))
							<div class="mb-4 rounded bg-green-500 px-4 py-2 text-white">
								{{ session('success') }}
							</div>
						@endif
						<table class="min-w-full bg-white">
							<thead>
								<tr>
									<th class="py-2">Nama Pelanggan</th>
									<th class="py-2">Tanggal Pembayaran</th>
									<th class="py-2">Jumlah</th>
									<th class="py-2">Metode Pembayaran</th>
									<th class="py-2">Aksi</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($payments as $payment)
									<tr>
										<td class="py-2">{{ $payment->invoice->order->customer->name }}</td>
										<td class="py-2">{{ $payment->payment_date }}</td>
										<td class="py-2">{{ $payment->amount }}</td>
										<td class="py-2">{{ $payment->payment_method }}</td>
										<td class="py-2">
											<a href="{{ route('payments.show', $payment->id) }}" class="text-blue-500" title="Detail">
												<i data-lucide="eye" class="w-4 h-4"></i>
											</a>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</x-app-layout>
