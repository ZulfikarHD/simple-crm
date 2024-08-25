<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Tambah Faktur</h1>
        <form action="{{ route('invoices.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="order_id">
                    Pesanan <span class="text-red-500">*</span>
                </label>
                <select name="order_id" id="order_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <option value="">Pilih Pesanan</option>
                    @foreach($orders as $order)
                        <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                            {{ $order->customer->name }} - {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}
                        </option>
                    @endforeach
                </select>
                @error('order_id')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="issue_date">
                    Tanggal Penerbitan <span class="text-red-500">*</span>
                </label>
                <input type="date" name="issue_date" id="issue_date" value="{{ old('issue_date') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                @error('issue_date')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="due_date">
                    Tanggal Jatuh Tempo <span class="text-red-500">*</span>
                </label>
                <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                @error('due_date')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="amount">
                    Jumlah <span class="text-red-500">*</span>
                </label>
                <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                @error('amount')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="status">
                    Status <span class="text-red-500">*</span>
                </label>
                <select name="status" id="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <option value="">Pilih Status</option>
                    <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Dibayar</option>
                    <option value="due" {{ old('status') == 'due' ? 'selected' : '' }}>Jatuh Tempo</option>
                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Tertunda</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
