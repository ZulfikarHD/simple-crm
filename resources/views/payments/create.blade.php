<x-app-layout>
    <div class="container mx-auto p-6 space-y-6">
        <h1 class="text-3xl font-bold text-gray-800">Pembayaran untuk Pesanan #{{ $order->id }}</h1>

        <form method="POST" action="{{ route('payments.store') }}" class="bg-white shadow sm:rounded-lg p-6 space-y-6">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->id }}">

            <div>
                <label for="amount_paid" class="block text-sm font-medium text-gray-700">Jumlah Dibayar</label>
                <input type="number" name="amount_paid" id="amount_paid" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
            </div>

            <div>
                <label for="payment_method" class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                <select name="payment_method" id="payment_method" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                    <option value="cash">Tunai</option>
                    <option value="credit_card">Kartu Kredit</option>
                    <option value="bank_transfer">Transfer Bank</option>
                </select>
            </div>

            <div>
                <label for="payment_type" class="block text-sm font-medium text-gray-700">Jenis Pembayaran</label>
                <select name="payment_type" id="payment_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                    <option value="full">Pembayaran Penuh</option>
                    <option value="partial">Pembayaran Parsial</option>
                </select>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('orders.show', $order->id) }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 flex items-center">
                    <i data-lucide="arrow-left" class="inline-block w-5 h-5 mr-2"></i> Kembali ke Pesanan
                </a>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 flex items-center">
                    <i data-lucide="check-circle" class="inline-block w-5 h-5 mr-2"></i> Simpan Pembayaran
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
