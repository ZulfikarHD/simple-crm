<x-app-layout>
	<div class="container mx-auto space-y-6 p-6">
		<div class="flex items-center justify-between">
			<h1 class="text-3xl font-bold text-gray-800">Daftar Pesanan</h1>
			<form method="GET" action="{{ route('orders.index') }}" class="flex space-x-4">
				<input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Pesanan..."
					class="w-full rounded-lg border px-4 py-2 focus:ring-2 focus:ring-blue-500 md:w-auto">
				<select name="status" class="w-full rounded-lg border px-4 py-2 focus:ring-2 focus:ring-blue-500 md:w-auto">
					<option value="">Filter Status</option>
					<option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
					<option value="partially_paid" {{ request('status') == 'partially_paid' ? 'selected' : '' }}>Partially Paid
					</option>
					<option value="fully_paid" {{ request('status') == 'fully_paid' ? 'selected' : '' }}>Fully Paid</option>
				</select>
				<button type="submit" class="rounded-lg bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">Filter</button>
			</form>
		</div>

		<!-- Summary Chart -->
		<div class="bg-white p-6 shadow sm:rounded-lg">
			<h2 class="mb-4 text-lg font-semibold text-gray-800">Ringkasan Pesanan</h2>
			<div id="ordersChart"></div>
		</div>

		<div class="overflow-hidden bg-white shadow sm:rounded-lg">
			<table class="min-w-full divide-y divide-gray-200">
				<thead class="bg-gray-50">
					<tr>
						<th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">ID
							Pesanan</th>
						<th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
							Pelanggan</th>
						<th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Tanggal
							Layanan</th>
						<th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status
						</th>
						<th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Total
						</th>
						<th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Aksi
						</th>
					</tr>
				</thead>
				<tbody class="divide-y divide-gray-200 bg-white">
					@foreach ($orders as $order)
						<tr>
							<td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">{{ $order->id }}</td>
							<td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
								{{ $order->customer->name }}
								<div x-data="{ open: false }">
									<button @click="open = !open" class="mt-2 flex items-center text-sm text-blue-500 hover:underline">
										<i data-lucide="chevron-down" class="inline-block h-4 w-4"></i> Lihat Item
									</button>
									<ul x-show="open" x-transition:enter="transition ease-out duration-300"
										x-transition:enter-start="opacity-0 transform scale-95"
										x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200"
										x-transition:leave-start="opacity-100 transform scale-100"
										x-transition:leave-end="opacity-0 transform scale-95"
										class="mt-2 list-inside list-disc text-sm text-gray-500">
										@foreach ($order->inventories as $inventory)
											<li>{{ $inventory->item_name }} ({{ $inventory->pivot->quantity_used }} unit)</li>
										@endforeach
									</ul>
								</div>
							</td>
							<td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
								{{ \Carbon\Carbon::parse($order->service_date)->format('d M Y') }}
							</td>
							<td class="whitespace-nowrap px-6 py-4 text-sm">
								<span
									class="{{ $order->status == 'pending' ? 'bg-red-100 text-red-800' : ($order->status == 'partially_paid' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }} inline-flex rounded-full px-2 text-xs font-semibold leading-5">
									{{ ucfirst($order->status) }}
								</span>
							</td>
							<td class="whitespace-nowrap px-6 py-4 text-right text-sm text-gray-500">
								Rp {{ number_format($order->total_amount, 2) }}
							</td>
							<td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
								<div class="flex justify-end space-x-4">
									<a href="{{ route('orders.show', $order->id) }}" class="text-blue-500 hover:text-blue-700">
										<i data-lucide="eye" class="h-5 w-5"></i>
									</a>
									<a href="{{ route('orders.edit', $order->id) }}" class="text-yellow-500 hover:text-yellow-700">
										<i data-lucide="edit" class="h-5 w-5"></i>
									</a>
									<button type="button" class="text-red-500 hover:text-red-700" onclick="confirmDelete({{ $order->id }});">
										<i data-lucide="trash" class="h-5 w-5"></i>
									</button>
									<form id="delete-form-{{ $order->id }}" action="{{ route('orders.destroy', $order->id) }}" method="POST"
										style="display: none;">
										@csrf
										@method('DELETE')
									</form>
								</div>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>

		<!-- Pagination -->
		<div class="mt-4">
			{{ $orders->appends(request()->input())->links() }}
		</div>
	</div>

	<!-- ApexCharts Script -->
	@push('scripts')
		<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
		<script>
			document.addEventListener('DOMContentLoaded', function() {
				var options = {
					chart: {
						type: 'pie',
						height: '100%'
					},
					series: [
						{{ $orders->where('status', 'pending')->count() }},
						{{ $orders->where('status', 'partially_paid')->count() }},
						{{ $orders->where('status', 'fully_paid')->count() }}
					],
					labels: ['Pending', 'Partially Paid', 'Fully Paid'],
					colors: ['#f87171', '#facc15', '#4ade80'],
					responsive: [{
						breakpoint: 480,
						options: {
							chart: {
								width: 300
							},
							legend: {
								position: 'bottom'
							}
						}
					}]
				};

				var chart = new ApexCharts(document.querySelector("#ordersChart"), options);
				chart.render();
			});
		</script>
	@endpush

	<!-- SweetAlert2 Script -->
	@push('sweet-alert')
		<script>
			function confirmDelete(orderId) {
				Swal.fire({
					title: 'Konfirmasi Hapus',
					text: "Anda yakin ingin menghapus pesanan ini?",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonText: 'Ya, hapus!',
					cancelButtonText: 'Batal'
				}).then((result) => {
					if (result.isConfirmed) {
						document.getElementById('delete-form-' + orderId).submit();
						Swal.fire('Dihapus!', 'Pesanan telah dihapus.', 'success');
					}
				});
			}
		</script>
	@endpush
</x-app-layout>
