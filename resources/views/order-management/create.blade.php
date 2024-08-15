<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="mb-6 text-3xl font-bold">Tambah Pesanan</h1>
        <form action="{{ route('orders.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Pelanggan -->
                <div class="mb-4">
                    <label class="mb-2 block text-sm font-bold text-gray-700" for="customer_id">
                        Pelanggan <span class="text-red-500">*</span>
                    </label>
                    <select name="customer_id" id="customer_id"
                        class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"
                        required>
                        <option value="">Pilih Pelanggan</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Layanan -->
                <div class="mb-4">
                    <label class="mb-2 block text-sm font-bold text-gray-700" for="service_date">
                        Tanggal Layanan <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="service_date" id="service_date" value="{{ old('service_date') }}"
                        class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"
                        required>
                    @error('service_date')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <label class="mb-2 block text-sm font-bold text-gray-700" for="status">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status"
                        class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none"
                        required>
                        <option value="">Pilih Status</option>
                        <option value="processing" {{ old('status') == 'processing' ? 'selected' : '' }}>Sedang Diproses</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="canceled" {{ old('status') == 'canceled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Catatan -->
                <div class="mb-4 md:col-span-2">
                    <label class="mb-2 block text-sm font-bold text-gray-700" for="notes">
                        Catatan
                    </label>
                    <textarea name="notes" id="notes"
                        class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 leading-tight text-gray-700 shadow focus:outline-none">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Tombol Simpan -->
            <div class="flex items-center justify-between mt-6">
                <button type="submit"
                    class="focus:shadow-outline rounded bg-blue-500 px-4 py-2 font-bold text-white hover:bg-blue-700 focus:outline-none">
                    Simpan
                </button>
                <a href="{{ route('orders.index') }}" class="text-gray-500 hover:text-gray-700">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
