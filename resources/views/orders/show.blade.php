<x-app-layout>
    <div class="container mx-auto p-6 space-y-6">
        <h1 class="text-3xl font-bold text-gray-800">Detail Pesanan #{{ $order->id }}</h1>

        <!-- Order Summary -->
        <div class="bg-white shadow sm:rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Pesanan</h2>
            <!-- Display customer info, service date, order status, etc. -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Customer and order information here... -->
            </div>
        </div>

        <!-- Invoice Summary -->
        <div class="bg-white shadow sm:rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Invoice</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nomor Invoice</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $order->invoice->invoice_number }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status Invoice</label>
                    <p class="mt-1 text-sm">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $order->invoice->status === 'paid' ? 'bg-green-100 text-green-800' : ($order->invoice->status === 'partially_paid' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($order->invoice->status) }}
                        </span>
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Total Invoice</label>
                    <p class="mt-1 text-sm text-gray-900">Rp {{ number_format($order->invoice->total_amount, 2) }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Total Dibayar</label>
                    <p class="mt-1 text-sm text-gray-900">Rp {{ number_format($order->invoice->amount_paid, 2) }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Sisa Tagihan</label>
                    <p class="mt-1 text-sm text-gray-900">Rp {{ number_format($order->invoice->total_amount - $order->invoice->amount_paid, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Payment History -->
        <div class="bg-white shadow sm:rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Pembayaran</h2>
            <ul class="space-y-2">
                @foreach ($order->payments as $payment)
                    <li class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-900">Rp {{ number_format($payment->amount_paid, 2) }}</p>
                            <p class="text-sm text-gray-500">{{ ucfirst($payment->payment_method) }} - {{ $payment->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Actions Section -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('orders.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 flex items-center">
                <i data-lucide="arrow-left" class="inline-block w-5 h-5 mr-2"></i> Kembali ke Daftar Pesanan
            </a>
            <a href="{{ route('orders.edit', $order->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 flex items-center">
                <i data-lucide="edit" class="inline-block w-5 h-5 mr-2"></i> Edit Pesanan
            </a>
            @if ($order->invoice->status !== 'paid')
                <a href="{{ route('orders.create-payment', $order->id) }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 flex items-center">
                    <i data-lucide="dollar-sign" class="inline-block w-5 h-5 mr-2"></i> Buat Pembayaran
                </a>
            @endif
        </div>
    </div>
</x-app-layout>
