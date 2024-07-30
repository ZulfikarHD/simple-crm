<x-app-layout>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">Invoices</h2>
        <a href="{{ route('invoice.create') }}" class="bg-blue-500 text-white py-2 px-4 rounded">Create Invoice</a>
        <div class="mt-4">
            @if ($message = Session::get('success'))
                <div class="bg-green-500 text-white p-2 rounded mb-4">
                    {{ $message }}
                </div>
            @endif
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2">Invoice Number</th>
                        <th class="py-2">Customer Name</th>
                        <th class="py-2">Amount</th>
                        <th class="py-2">Status</th>
                        <th class="py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoices as $invoice)
                        <tr>
                            <td class="py-2">{{ $invoice->invoice_number }}</td>
                            <td class="py-2">{{ $invoice->customer_name }}</td>
                            <td class="py-2">{{ $invoice->amount }}</td>
                            <td class="py-2">{{ $invoice->status }}</td>
                            <td class="py-2">
                                <a href="{{ route('invoices.edit', $invoice->id) }}" class="bg-yellow-500 text-white py-1 px-2 rounded">Edit</a>
                                <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white py-1 px-2 rounded">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
