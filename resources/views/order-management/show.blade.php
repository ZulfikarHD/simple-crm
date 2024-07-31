<x-app-layout>
	<div>
		<h1 class="mb-6 text-3xl font-bold">Detail Pesanan</h1>
		<div class="overflow-hidden bg-white shadow sm:rounded-lg">
			<div class="px-4 py-5 sm:px-6">
				<h3 class="text-lg font-medium leading-6 text-gray-900">
					Informasi Pesanan
				</h3>
			</div>
			<div class="border-t border-gray-200">
				<dl>
					<div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
						<dt class="text-sm font-medium text-gray-500">
							Nama Pelanggan
						</dt>
						<dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
							{{ $order->customer->name }}
						</dd>
					</div>
					<div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
						<dt class="text-sm font-medium text-gray-500">
							Tanggal Layanan
						</dt>
						<dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
							{{ $order->service_date }}
						</dd>
					</div>
					<div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
						<dt class="text-sm font-medium text-gray-500">
							Status
						</dt>
						<dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
							{{ $order->status }}
						</dd>
					</div>
					<div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
						<dt class="text-sm font-medium text-gray-500">
							Catatan
						</dt>
						<dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
							{{ $order->notes }}
						</dd>
					</div>
				</dl>
			</div>
		</div>
	</div>
</x-app-layout>
