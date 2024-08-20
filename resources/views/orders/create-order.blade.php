<x-app-layout>
	<div class="container mx-auto space-y-6 p-6">
		@if ($errors->any())
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		<h1 class="text-3xl font-bold text-gray-800">Buat Pesanan Baru</h1>

		<!-- Form Step 1: Customer and Order Details -->
		<form method="POST" action="{{ route('orders.store-order') }}" class="space-y-6 bg-white p-6 shadow sm:rounded-lg">
			@csrf

			<!-- Customer Information -->
			<div>
				<h2 class="mb-4 text-lg font-semibold text-gray-800">Informasi Pelanggan</h2>
				<div class="grid grid-cols-1 gap-4 md:grid-cols-2">
					<div>
						<label for="customer_search" class="block text-sm font-medium text-gray-700">Pilih Pelanggan</label>
						<select id="customer_search" name="customer_id"
							class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
							<option value="">Cari Pelanggan...</option>
							@foreach ($customers as $customer)
								<option value="{{ $customer->id }}" data-email="{{ $customer->email }}" data-phone="{{ $customer->phone }}">
									{{ $customer->name }}
								</option>
							@endforeach
						</select>
					</div>
				</div>

				<!-- Customer Details (Auto-filled or Manual Entry) -->
				<div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
					<div>
						<label for="customer_name" class="block text-sm font-medium text-gray-700">Nama Pelanggan</label>
						<input type="text" name="customer_name" id="customer_name"
							class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
							required>
					</div>
					<div>
						<label for="customer_email" class="block text-sm font-medium text-gray-700">Email</label>
						<input type="email" name="customer_email" id="customer_email"
							class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
							required>
					</div>
					<div>
						<label for="customer_phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
						<input type="text" name="customer_phone" id="customer_phone"
							class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
							required>
					</div>
				</div>
			</div>

			<!-- Order Information -->
			<div>
				<h2 class="mb-4 text-lg font-semibold text-gray-800">Informasi Pesanan</h2>
				<div class="grid grid-cols-1 gap-4 md:grid-cols-2">
					<div>
						<label for="service_date" class="block text-sm font-medium text-gray-700">Tanggal Layanan</label>
						<input type="date" name="service_date" id="service_date"
							class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
							required>
					</div>
				</div>

				<!-- Order Items -->
				<div x-data="{
	    items: [{ id: '', quantity: 1, price: 0 }],
	    discount: 0,
	    tax: 10, // Default tax value
	    subtotal() {
	        return this.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
	    },
	    total() {
	        const discounted = this.subtotal() - (this.subtotal() * this.discount / 100);
	        return discounted + (discounted * this.tax / 100);
	    }
	}" class="mt-4 space-y-4">
					<template x-for="(item, index) in items" :key="index">
						<div class="flex items-center justify-between rounded-lg bg-gray-50 p-4">
							<div class="flex-1">
								<div class="grid grid-cols-2 gap-4">
									<div>
										<label for="item" class="block text-sm font-medium text-gray-700">Pilih Item</label>
										<select x-model="item.id" name="item_id[]"
											class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
											required>
											<option value="">Pilih Item</option>
											@foreach ($items as $inventoryItem)
												<option value="{{ $inventoryItem->id }}" data-price="{{ $inventoryItem->price }}">
													{{ $inventoryItem->item_name }}
												</option>
											@endforeach
										</select>

									</div>
									<div>
										<label for="item_quantity" class="block text-sm font-medium text-gray-700">Kuantitas</label>
										<input type="number" x-model="item.quantity" name="item_quantity[]"
											class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
											required>
									</div>
								</div>
							</div>
							<div class="flex items-center space-x-2 text-right">
								<button type="button" @click="items.splice(index, 1)" class="text-red-500 hover:text-red-700">
									<i data-lucide="trash" class="h-5 w-5"></i>
								</button>
							</div>
						</div>
					</template>
					<button type="button" @click="items.push({ id: '', quantity: 1, price: 0 })"
						class="rounded-lg bg-green-500 px-4 py-2 text-white hover:bg-green-600">
						<i data-lucide="plus" class="mr-2 inline-block h-5 w-5"></i> Tambah Item
					</button>

					<!-- Discount and Tax Inputs -->
					<div class="mt-4">
						<div class="grid grid-cols-1 gap-4 md:grid-cols-2">
							<div>
								<label for="discount" class="block text-sm font-medium text-gray-700">Diskon (%)</label>
								<input type="number" x-model="discount"
									class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
							</div>
							<div>
								<label for="tax" class="block text-sm font-medium text-gray-700">Pajak (%)</label>
								<input type="number" x-model="tax"
									class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
							</div>
						</div>
					</div>

					<!-- Subtotal and Total Calculation -->
					<div class="mt-4">
						<h3 class="text-lg font-medium text-gray-800">Subtotal: Rp <span x-text="subtotal().toFixed(2)"></span></h3>
						<h3 class="text-lg font-medium text-gray-800">Total: Rp <span x-text="total().toFixed(2)"></span></h3>
					</div>
				</div>
			</div>

            <!-- Actions Section -->
            <div class="flex justify-end space-x-4">
                <input type="hidden" id="action-input" name="action" value="save_and_pay">
                <button type="submit" onclick="document.getElementById('action-input').value='save_and_pay';" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 flex items-center">
                    <i data-lucide="arrow-right" class="inline-block w-5 h-5 mr-2"></i> Lanjutkan ke Pembayaran
                </button>
                <button type="submit" onclick="document.getElementById('action-input').value='save';" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 flex items-center">
                    <i data-lucide="save" class="inline-block w-5 h-5 mr-2"></i> Simpan Pesanan
                </button>
            </div>
		</form>
	</div>

	<!-- SweetAlert2 Script -->
	@push('sweet-alert')
		<script>
			document.addEventListener('DOMContentLoaded', function() {
				const form = document.querySelector('form');
				const customerSearch = document.getElementById('customer_search');
				const customerName = document.getElementById('customer_name');
				const customerEmail = document.getElementById('customer_email');
				const customerPhone = document.getElementById('customer_phone');

				customerSearch.addEventListener('change', function() {
					const selectedOption = this.options[this.selectedIndex];
					customerName.value = selectedOption.text;
					customerEmail.value = selectedOption.getAttribute('data-email');
					customerPhone.value = selectedOption.getAttribute('data-phone');
				});

				form.addEventListener('submit', function(event) {
					event.preventDefault();

					Swal.fire({
						title: 'Konfirmasi Pesanan',
						text: "Anda yakin ingin melanjutkan?",
						icon: 'warning',
						showCancelButton: true,
						confirmButtonText: 'Ya, lanjutkan!',
						cancelButtonText: 'Batal'
					}).then((result) => {
						if (result.isConfirmed) {
							form.submit();
						}
					});
				});
			});
		</script>
	@endpush
</x-app-layout>
