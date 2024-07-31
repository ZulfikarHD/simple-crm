<x-app-layout>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">Edit Faktur</h2>
        <form action="{{ route('invoices.update', $invoice->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="order_id" class="block text-gray-700">Nomor Pesanan</label>
                <select name="order_id" id="order_id" class="w-full border border-gray-300 p-2 rounded" required>
                    @foreach($orders as $order)
                        <option value="{{ $order->id }}" {{ $invoice->order_id == $order->id ? 'selected' : '' }}>
                            {{ $order->id }} - {{ $order->customer->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="issue_date" class="block text-gray-700">Tanggal Terbit</label>
                <input type="date" name="issue_date" id="issue_date" class="w-full border border-gray-300 p-2 rounded" value="{{ $invoice->issue_date }}" required>
            </div>
            <div class="mb-4">
                <label for="due_date" class="block text-gray-700">Tanggal Jatuh Tempo</label>
                <input type="date" name="due_date" id="due_date" class="w-full border border-gray-300 p-2 rounded" value="{{ $invoice->due_date }}" required>
            </div>
            <div class="mb-4">
                <label for="amount" class="block text-gray-700">Jumlah</label>
                <input type="number" name="amount" id="amount" class="w-full border border-gray-300 p-2 rounded" value="{{ $invoice->amount }}" required>
            </div>
            <div class="mb-4">
                <label for="status" class="block text-gray-700">Status</label>
                <select name="status" id="status" class="w-full border border-gray-300 p-2 rounded" required>
                    <option value="Pending" {{ $invoice->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Paid" {{ $invoice->status == 'Paid' ? 'selected' : '' }}>Paid</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Simpan</button>
        </form>
    </div>
</x-app-layout>
