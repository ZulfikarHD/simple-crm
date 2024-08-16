<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Buat Pesanan Baru</h1>

        <!-- Card Wrapper -->
        <div class="bg-white shadow-lg rounded-lg p-6">
            <form action="{{ route('orders.store') }}" method="POST">
                @csrf

                <!-- Customer Selection -->
                <div class="mb-4">
                    <label for="customer_id" class="block text-sm font-bold text-gray-700 mb-2">Pilih Pelanggan</label>
                    <select name="customer_id" id="customer_id" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">-- Pilih Pelanggan --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-600">
                        <a href="{{ route('customers.create') }}" class="text-blue-500 hover:underline">Tambah pelanggan baru</a>
                    </p>
                </div>

                <!-- Items/Services Section -->
                <div x-data="orderForm({{ json_encode(old('items', [['inventory_id' => '', 'quantity_used' => 1, 'discount' => 0, 'tax_rate' => 0]])) }})" class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Item / Layanan</label>

                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex items-center space-x-4 mb-2">
                            <select :name="`items[${index}][inventory_id]`" x-model="item.inventory_id" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">-- Pilih Item / Layanan --</option>
                                @foreach($inventoryItems as $inventory)
                                    <option :value="{{ $inventory->id }}">{{ $inventory->item_name }}</option>
                                @endforeach
                            </select>
                            <input :name="`items[${index}][quantity_used]`" type="number" x-model="item.quantity_used" class="w-24 border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" min="1" placeholder="Qty" required>
                            <input :name="`items[${index}][discount]`" type="number" step="0.01" x-model="item.discount" class="w-24 border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Diskon" required>
                            <input :name="`items[${index}][tax_rate]`" type="number" step="0.01" x-model="item.tax_rate" class="w-24 border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Pajak (%)" required>
                            <button type="button" @click="removeItem(index)" class="text-red-500 hover:text-red-700"><i data-lucide="trash" class="w-5 h-5"></i></button>
                        </div>
                        <div x-show="errors[`items.${index}.inventory_id`]">
                            <p class="text-red-500 text-sm mt-2" x-text="errors[`items.${index}.inventory_id`]"></p>
                        </div>
                        <div x-show="errors[`items.${index}.quantity_used`]">
                            <p class="text-red-500 text-sm mt-2" x-text="errors[`items.${index}.quantity_used`]"></p>
                        </div>
                        <div x-show="errors[`items.${index}.discount`]">
                            <p class="text-red-500 text-sm mt-2" x-text="errors[`items.${index}.discount`]"></p>
                        </div>
                        <div x-show="errors[`items.${index}.tax_rate`]">
                            <p class="text-red-500 text-sm mt-2" x-text="errors[`items.${index}.tax_rate`]"></p>
                        </div>
                    </template>

                    <button type="button" @click="addItem()" class="text-blue-500 hover:underline">+ Tambah Item</button>
                </div>

                <!-- Service Date -->
                <div class="mb-4">
                    <label for="service_date" class="block text-sm font-bold text-gray-700 mb-2">Tanggal Layanan</label>
                    <input type="date" name="service_date" id="service_date" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required value="{{ old('service_date') }}">
                    @error('service_date')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Order Notes -->
                <div class="mb-4">
                    <label for="notes" class="block text-sm font-bold text-gray-700 mb-2">Catatan Pesanan</label>
                    <textarea name="notes" id="notes" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('notes') }}</textarea>
                </div>

                <!-- Order Total Section -->
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Total Pesanan</label>
                    <p class="text-xl font-semibold text-gray-900" x-text="formatCurrency(totalCost)"></p>
                </div>

                <!-- Payment Section -->
                <div class="mb-4">
                    <input type="checkbox" id="process_payment" name="process_payment" x-model="processPayment" class="mr-2" {{ old('process_payment') ? 'checked' : '' }}>
                    <label for="process_payment" class="text-sm font-bold text-gray-700">Proses Pembayaran Sekarang</label>
                </div>

                <div x-show="processPayment" class="mb-4">
                    <label for="payment_amount" class="block text-sm font-bold text-gray-700 mb-2">Jumlah Pembayaran</label>
                    <input type="number" step="0.01" name="payment_amount" id="payment_amount" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan jumlah pembayaran" value="{{ old('payment_amount') }}">
                    @error('payment_amount')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-gray-600 mt-2">Sisa: <span x-text="formatCurrency(totalCost - paymentAmount)"></span></p>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Simpan Pesanan
                    </button>
                </div>
            </form>
        </div>
        <!-- End of Card Wrapper -->
    </div>

    <!-- Alpine.js Script -->
    <script>
        function orderForm(existingItems = [{ inventory_id: '', quantity_used: 1, discount: 0, tax_rate: 0 }]) {
            return {
                processPayment: {{ old('process_payment') ? 'true' : 'false' }},
                paymentAmount: {{ old('payment_amount', 0) }},
                items: existingItems,
                errors: @json($errors->toArray()),
                addItem() {
                    this.items.push({ inventory_id: '', quantity_used: 1, discount: 0, tax_rate: 0 });
                },
                removeItem(index) {
                    this.items.splice(index, 1);
                },
                get totalCost() {
                    return this.items.reduce((sum, item) => {
                        let inventory = @json($inventoryItems).find(i => i.id == item.inventory_id);
                        if (inventory) {
                            let basePrice = inventory.unit_price * item.quantity_used;
                            let discount = item.discount;
                            let tax = (basePrice - discount) * (item.tax_rate / 100);
                            return sum + (basePrice - discount + tax);
                        }
                        return sum;
                    }, 0);
                },
                formatCurrency(value) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(value);
                }
            };
        }
    </script>
</x-app-layout>
