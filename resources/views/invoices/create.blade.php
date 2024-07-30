<x-app-layout>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">Create Invoice</h2>
        <form action="{{ route('invoice.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="invoice_number" class="block text-gray-700">Invoice Number</label>
                <input type="text" name="invoice_number" id="invoice_number" class="w-full border border-gray-300 p-2 rounded" required>
            </div>
            <div class="mb-4">
                <label for="customer_name" class="block text-gray-700">Customer Name</label>
                <input type="text" name="customer_name" id="customer_name" class="w-full border border-gray-300 p-2 rounded" required>
            </div>
            <div class="mb-4">
                <label for="amount" class="block text-gray-700">Amount</label>
                <input type="number" name="amount" id="amount" class="w-full border border-gray-300 p-2 rounded" required>
            </div>
            <div class="mb-4">
                <label for="status" class="block text-gray-700">Status</label>
                <select name="status" id="status" class="w-full border border-gray-300 p-2 rounded" required>
                    <option value="Pending">Pending</option>
                    <option value="Paid">Paid</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Save</button>
        </form>
    </div>
</x-app-layout>
