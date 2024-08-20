<x-app-layout>
    <div class="container mx-auto p-6 space-y-6">
        <h1 class="text-3xl font-bold text-gray-800">Buat Pesanan Baru - Langkah 2</h1>

        <!-- Order Summary -->
        <div class="bg-white shadow sm:rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Pesanan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Customer Information -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Pelanggan</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $order->customer->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $order->customer->email }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $order->customer->phone }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal Layanan</label>
                    <p class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($order->service_date)->format('d M Y') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Subtotal</label>
                    <p class="mt-1 text-sm text-gray-900">Rp {{ number_format($order->subtotal, 2) }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Total</label>
                    <p class="mt-1 text-sm text-gray-900">Rp {{ number_format($order->total_amount, 2) }}</p>
                </div>
            </div>

            <!-- Collapsible Order Items -->
            <div x-data="{ open: false }" class="mt-6">
                <button @click="open = !open" class="text-blue-500 hover:underline flex items-center">
                    <i :class="open ? 'transform rotate-180' : ''" data-lucide="chevron-down" class="mr-2"></i> Lihat Item dalam Pesanan
                </button>
                <ul x-show="open" class="mt-4 space-y-2">
                    @foreach ($order->inventories as $item)
                        <li class="flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $item->item_name }}</p>
                                <p class="text-sm text-gray-500">Kuantitas: {{ $item->pivot->quantity_used }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-900">Rp {{ number_format($item->pivot->total_price, 2) }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Form Step 2: Payment Details -->
        <form method="POST" action="{{ route('orders.store-payment') }}" class="bg-white shadow sm:rounded-lg p-6 space-y-6" x-data="{
                paymentType: 'full',
                amountPaid: 0,
                percentagePaid: 0,
                totalAmount: '{{ $order->total_amount }}',
                updateAmount() {
                    this.amountPaid = (this.percentagePaid / 100) * this.totalAmount;
                },
                updatePercentage() {
                    this.percentagePaid = (this.amountPaid / this.totalAmount) * 100;
                }
            }">
            @csrf

            <input type="hidden" name="order_id" value="{{ $order->id }}">

            <!-- Payment Type Selection -->
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Jenis Pembayaran</h2>
                <div class="flex items-center space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="payment_type" value="full" x-model="paymentType" checked>
                        <span class="ml-2">Pembayaran Penuh</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="payment_type" value="partial" x-model="paymentType">
                        <span class="ml-2">Pembayaran Parsial</span>
                    </label>
                </div>
            </div>

            <!-- Payment Information -->
            <div>
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Pembayaran</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="amount_paid" class="block text-sm font-medium text-gray-700">Jumlah Dibayar</label>
                        <input type="number" name="amount_paid" id="amount_paid" x-model="amountPaid" @input="updatePercentage" :disabled="paymentType === 'full'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                    </div>
                    <div x-show="paymentType === 'partial'">
                        <label for="percentage_paid" class="block text-sm font-medium text-gray-700">Persentase yang Dibayar (%)</label>
                        <input type="number" x-model="percentagePaid" @input="updateAmount" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                        <select name="payment_method" id="payment_method" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                            <option value="cash">Tunai</option>
                            <option value="credit_card">Kartu Kredit</option>
                            <option value="bank_transfer">Transfer Bank</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Actions Section -->
            <div class="flex justify-end space-x-4">
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 flex items-center">
                    <i data-lucide="check-circle" class="inline-block w-5 h-5 mr-2"></i> Selesaikan Pembayaran
                </button>
                <a href="{{ route('orders.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 flex items-center">
                    <i data-lucide="arrow-left" class="inline-block w-5 h-5 mr-2"></i> Kembali ke Daftar Pesanan
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
