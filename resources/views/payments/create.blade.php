<x-app-layout>
    <div class="container mx-auto p-6 space-y-6">
        <h1 class="text-4xl font-extrabold text-gray-900">Pembayaran untuk Pesanan #{{ $order->id }}</h1>

        <!-- Invoice Summary -->
        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Ringkasan Invoice</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="border p-4 rounded-lg shadow-sm">
                    <label class="block text-sm font-medium text-gray-700">Nomor Invoice</label>
                    <p class="mt-1 text-lg text-gray-900 font-semibold">{{ $order->invoice->invoice_number }}</p>
                </div>
                <div class="border p-4 rounded-lg shadow-sm">
                    <label class="block text-sm font-medium text-gray-700">Status Invoice</label>
                    <p class="mt-1 text-sm">
                        <span class="px-3 inline-flex text-xs leading-5 font-semibold rounded-full {{ $order->invoice->status === 'paid' ? 'bg-green-200 text-green-800' : ($order->invoice->status === 'partially_paid' ? 'bg-yellow-200 text-yellow-800' : 'bg-red-200 text-red-800') }}">
                            {{ ucfirst($order->invoice->status) }}
                        </span>
                    </p>
                </div>
                <div class="border p-4 rounded-lg shadow-sm">
                    <label class="block text-sm font-medium text-gray-700">Total Invoice</label>
                    <p class="mt-1 text-lg text-gray-900 font-semibold">Rp {{ number_format($order->invoice->total_amount, 2) }}</p>
                </div>
                <div class="border p-4 rounded-lg shadow-sm">
                    <label class="block text-sm font-medium text-gray-700">Total Dibayar</label>
                    <p class="mt-1 text-lg text-gray-900 font-semibold">Rp {{ number_format($order->invoice->amount_paid, 2) }}</p>
                </div>
                <div class="border p-4 rounded-lg shadow-sm">
                    <label class="block text-sm font-medium text-gray-700">Sisa Tagihan</label>
                    <p class="mt-1 text-lg text-gray-900 font-semibold">Rp {{ number_format($order->invoice->total_amount - $order->invoice->amount_paid, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Payment Form with Dynamic Partial/Full Payment -->
        <form method="POST" action="{{ route('payments.store') }}" class="bg-white shadow-lg rounded-lg p-6 space-y-6">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->id }}">

            <div x-data="{
                paymentType: 'full',
                totalAmount: {{ $order->invoice->total_amount - $order->invoice->amount_paid }},
                partialAmount: 0,
                percentage: 0,
                updatePercentage() {
                    this.percentage = (this.partialAmount / this.totalAmount) * 100;
                },
                updatePartialAmount() {
                    this.partialAmount = (this.percentage / 100) * this.totalAmount;
                }
            }">
                <label for="payment_type" class="block text-sm font-medium text-gray-700">Jenis Pembayaran</label>
                <select name="payment_type" id="payment_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" x-model="paymentType" required>
                    <option value="full">Pembayaran Penuh</option>
                    <option value="partial">Pembayaran Parsial</option>
                </select>

                <!-- Full Payment -->
                <div x-show="paymentType === 'full'" class="mt-4">
                    <label for="payment_amount_full" class="block text-sm font-medium text-gray-700">Jumlah Pembayaran Penuh</label>
                    <input type="number" name="payment_amount_full" id="payment_amount_full" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" :value="totalAmount" readonly>
                </div>

                <!-- Partial Payment -->
                <div x-show="paymentType === 'partial'" x-cloak class="mt-4">
                    <label for="partial_payment_amount" class="block text-sm font-medium text-gray-700">Jumlah Pembayaran Parsial</label>
                    <input type="number" step="0.01" name="payment_amount" id="partial_payment_amount" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" x-model="partialAmount" @input="updatePercentage" placeholder="Masukkan jumlah pembayaran">

                    <label for="payment_percentage" class="block text-sm font-medium text-gray-700 mt-4">Persentase Pembayaran (%)</label>
                    <input type="number" step="0.01" id="payment_percentage" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" x-model="percentage" @input="updatePartialAmount" placeholder="Masukkan persentase pembayaran">
                </div>
            </div>

            <div class="mt-6">
                <label for="payment_method" class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                <select name="payment_method" id="payment_method" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                    <option value="cash">Tunai</option>
                    <option value="credit_card">Kartu Kredit</option>
                    <option value="bank_transfer">Transfer Bank</option>
                </select>
            </div>

            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ route('orders.show', $order->id) }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 flex items-center">
                    <i data-lucide="arrow-left" class="inline-block w-5 h-5 mr-2"></i> Kembali ke Pesanan
                </a>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center">
                    <i data-lucide="check-circle" class="inline-block w-5 h-5 mr-2"></i> Simpan Pembayaran
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
