{{-- Main layout for creating a new order --}}
<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="mb-6 text-3xl font-bold text-gray-800">Buat Pesanan Baru</h1>

        {{-- Card Wrapper for the order form --}}
        <div class="rounded-lg bg-white p-6 shadow-lg">
            <div>
                {{-- Display validation errors if any --}}
                @if ($errors->any())
                    <div class="mb-4">
                        <div class="font-medium text-red-600">
                            {{ __('Whoops! Something went wrong.') }}
                        </div>

                        <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            {{-- Order form --}}
            <form id="order-form" action="{{ route('orders.store') }}" method="POST">
                @csrf

                {{-- Customer Selection --}}
                <div class="mb-4">
                    <label for="customer_id" class="mb-2 block text-sm font-bold text-gray-700">Pilih Pelanggan</label>
                    <div class="relative">
                        <select name="customer_id" id="customer_id"
                            class="w-full rounded border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none" required>
                            <option value="">-- Pilih Pelanggan --</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <i data-lucide="chevron-down"></i>
                        </div>
                    </div>
                    @error('customer_id')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-600">
                        <a href="{{ route('customers.create') }}" class="text-blue-500 hover:underline">Tambah pelanggan baru</a>
                    </p>
                </div>

                {{-- Service Date --}}
                <div class="mb-4">
                    <label for="service_date" class="mb-2 block text-sm font-bold text-gray-700">Tanggal Layanan</label>
                    <div class="relative">
                        <input type="date" name="service_date" id="service_date" value="{{ old('service_date') }}"
                            class="w-full rounded border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <i data-lucide="calendar"></i>
                        </div>
                    </div>
                </div>

                {{-- Description --}}
                <div class="mb-4">
                    <label for="notes" class="mb-2 block text-sm font-bold text-gray-700">Deskripsi</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="w-full rounded border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Tambahkan deskripsi (opsional)">{{ old('notes') }}</textarea>
                </div>

                {{-- Items/Services Section --}}
                <div x-data="{
                        items: {{ json_encode(old('items', [['inventory_id' => '', 'quantity_used' => 1, 'discount' => 0, 'tax_rate' => 0]])) }},
                        addItem() {
                            this.items.push({ inventory_id: '', quantity_used: 1, discount: 0, tax_rate: 0 });
                        },
                        removeItem(index) {
                            this.items.splice(index, 1);
                        }
                    }" class="mb-4">
                    <label class="mb-2 block text-sm font-bold text-gray-700">Item / Jasa</label>

                    <template x-for="(item, index) in items" :key="index">
                        <div class="mb-4 flex items-center space-x-4">
                            {{-- Inventory Item Selection --}}
                            <div class="relative w-1/3">
                                <select x-model="item.inventory_id" :name="`items[${index}][inventory_id]`" required
                                    class="w-full rounded border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none">
                                    <option value="">-- Pilih Item --</option>
                                    @foreach ($inventoryItems as $inventory)
                                        <option :value="{{ $inventory->id }}">
                                            {{ $inventory->name }} (Stok: {{ $inventory->quantity }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <i data-lucide="chevron-down"></i>
                                </div>
                            </div>

                            {{-- Quantity Input --}}
                            <input type="number" x-model="item.quantity_used" :name="`items[${index}][quantity_used]`" min="1" required
                                class="w-1/6 rounded border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Qty">

                            {{-- Discount Input --}}
                            <input type="number" x-model="item.discount" :name="`items[${index}][discount]`" min="0" step="0.01"
                                class="w-1/6 rounded border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Diskon">

                            {{-- Tax Rate Input --}}
                            <input type="number" x-model="item.tax_rate" :name="`items[${index}][tax_rate]`" min="0" step="0.01" max="100"
                                class="w-1/6 rounded border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Pajak (%)">

                            {{-- Remove Item Button --}}
                            <button type="button" @click="removeItem(index)" class="text-red-500 hover:underline flex items-center">
                                <i data-lucide="trash"></i>
                            </button>
                        </div>
                    </template>

                    {{-- Add Item Button --}}
                    <button type="button" @click="addItem()" class="text-blue-500 hover:underline flex items-center">
                        <i data-lucide="plus-circle" class="mr-1"></i> Tambah Item
                    </button>
                </div>

                {{-- Payment Section --}}
                <div class="mb-4">
                    <label for="payment_amount" class="mb-2 block text-sm font-bold text-gray-700">Jumlah Pembayaran</label>
                    <input type="number" name="payment_amount" id="payment_amount" min="0" step="0.01"
                        class="w-full rounded border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Masukkan jumlah pembayaran (opsional)">
                </div>

                <div class="mb-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="process_payment" value="1"
                            class="form-checkbox rounded text-blue-500 focus:ring focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Proses Pembayaran Sekarang</span>
                    </label>
                </div>

                {{-- Submit Button --}}
                <div>
                    <button type="button" id="submit-button"
                        class="rounded bg-blue-500 px-4 py-2 font-bold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Simpan Pesanan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- SweetAlert2 Confirmation Script --}}
    @push('sweet-alert')
    <script>
        document.getElementById('submit-button').addEventListener('click', function(event) {
            event.preventDefault();

            const customerName = document.querySelector('#customer_id option:checked').textContent.trim();
            const serviceDate = document.getElementById('service_date').value;
            const paymentAmount = document.getElementById('payment_amount').value || '0';
            const processPayment = document.querySelector('input[name="process_payment"]:checked') ? 'Yes' : 'No';
            const items = [...document.querySelectorAll('[x-data] select[name^="items"] option:checked')].map(option => option.textContent.trim()).join(', ');

            Swal.fire({
                title: 'Konfirmasi Pesanan',
                html: `
                    <p><strong>Pelanggan:</strong> ${customerName}</p>
                    <p><strong>Tanggal Layanan:</strong> ${serviceDate}</p>
                    <p><strong>Item:</strong> ${items}</p>
                    <p><strong>Jumlah Pembayaran:</strong> Rp ${paymentAmount}</p>
                    <p><strong>Proses Pembayaran Sekarang:</strong> ${processPayment}</p>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Konfirmasi',
                cancelButtonText: 'Batal',
                focusConfirm: false,
                preConfirm: () => {
                    document.getElementById('order-form').submit();
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
