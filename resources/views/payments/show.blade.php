<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-6">Detail Pembayaran #{{ $payment->id }}</h1>

        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-5">
                <h3 class="text-xl leading-6 font-semibold text-gray-800">
                    Informasi Pembayaran
                </h3>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-6 py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-600">
                            Nomor Faktur
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            #{{ $payment->invoice->id }}
                        </dd>
                    </div>
                    <div class="bg-white px-6 py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-600">
                            Tanggal Pembayaran
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $payment->payment_date->format('d M Y') }}
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-6 py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-600">
                            Jumlah Pembayaran
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            Rp {{ number_format($payment->amount_paid, 0, ',', '.') }}
                        </dd>
                    </div>
                    <div class="bg-white px-6 py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-600">
                            Metode Pembayaran
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ ucfirst($payment->payment_method) }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <a href="{{ route('payments.index') }}" class="rounded-lg bg-blue-600 px-4 py-2 font-bold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                Kembali ke Daftar Pembayaran
            </a>
        </div>
    </div>
</x-app-layout>
