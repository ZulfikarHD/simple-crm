<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Inventory;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use DB;
use Illuminate\Http\Request;

/**
 * Class OrderController
 * Handles the management of orders, including creating, updating, and deleting orders,
 * as well as processing payments and generating invoices.
 */
class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Order::with('customer', 'inventories');

        // Filter orders by status if provided
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search for orders by customer name if provided
        if ($request->has('search') && $request->search != '') {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Paginate the results
        $orders = $query->paginate(3);

        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new order.
     *
     * @return void
     */
    public function create() {}

    /**
     * Show the form for creating a new order with customer and item data.
     *
     * @return \Illuminate\View\View
     */
    public function createOrder()
    {
        $customers = Customer::all();
        $items = Inventory::all();

        return view('orders.create-order', compact('customers', 'items'));
    }

    /**
     * Store a newly created order in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeOrder(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'required_without:customer_id|string|max:255',
            'customer_email' => 'required_without:customer_id|email|max:255',
            'customer_phone' => 'required_without:customer_id|string|max:20',
            'service_date' => 'required|date',
            'item_id' => 'required|array',
            'item_id.*' => 'required|exists:inventories,id', // Validate each item against the 'inventories' table
            'item_quantity' => 'required|array',
            'item_quantity.*' => 'required|integer|min:1',
            'discount' => 'nullable|numeric|min:0|max:100',
            'tax' => 'nullable|numeric|min:0|max:100',
        ]);

        // If customer_id is not provided, create a new customer
        if (!$validatedData['customer_id']) {
            $customer = Customer::create([
                'name' => $validatedData['customer_name'],
                'email' => $validatedData['customer_email'],
                'phone' => $validatedData['customer_phone'],
            ]);
            $validatedData['customer_id'] = $customer->id;
        }

        // Calculate subtotal and total amount
        $subtotal = 0;
        foreach ($validatedData['item_id'] as $index => $itemId) {
            $item = Inventory::findOrFail($itemId);
            $quantity = $validatedData['item_quantity'][$index];
            $subtotal += $item->unit_price * $quantity;
        }

        $discount = $validatedData['discount'] ?? 0;
        $tax = $validatedData['tax'] ?? 11; // Default tax if not provided
        $totalAfterDiscount = $subtotal - ($subtotal * $discount / 100);
        $totalAmount = $totalAfterDiscount + ($totalAfterDiscount * $tax / 100);

        // Create the order without saving payment
        $order = Order::create([
            'customer_id' => $validatedData['customer_id'],
            'service_date' => $validatedData['service_date'],
            'status' => 'pending', // Initially set to pending
            'subtotal' => $subtotal,
            'total_amount' => $totalAmount,
        ]);

        // Create an invoice for the order
        Invoice::create([
            'order_id' => $order->id,
            'invoice_number' => 'INV-' . strtoupper(uniqid()), // Unique invoice number
            'total_amount' => $totalAmount,
            'issue_date'   => now(),
            'due_date'     => $order->service_date,
            'status' => 'unpaid', // Initial status
        ]);

        // Attach items to the order
        foreach ($validatedData['item_id'] as $index => $itemId) {
            $quantity = $validatedData['item_quantity'][$index];
            $item = Inventory::findOrFail($itemId);
            $order->inventories()->attach($itemId, [
                'quantity_used' => $quantity,
                'price_per_unit' => $item->unit_price,
                'total_price' => $item->unit_price * $quantity,
            ]);
        }

        // Redirect based on user action
        if ($request->input('action') === 'save_and_pay') {
            // Save the order ID in the session to pass it to the payment step
            session(['order_id' => $order->id]);
            return redirect()->route('orders.create-payment');
        } else {
            return redirect()->route('orders.index')->with('success', 'Pesanan berhasil disimpan.');
        }
    }

    /**
     * Show the payment form for a specific order.
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function createPayment(Request $request)
    {
        $orderId = session('order_id');
        if (!$orderId) {
            return redirect()->route('orders.index')->with('error', 'Tidak ada pesanan untuk pembayaran.');
        }

        $order = Order::findOrFail($orderId);
        return view('orders.create-payment', compact('order'));
    }

    /**
     * Store a newly created payment in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storePayment(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'payment_type' => 'required|in:partial,full',
            'payment_amount_full' => 'nullable|numeric|min:0'
        ]);

        // Use a database transaction to ensure data integrity
        DB::transaction(function () use ($validatedData) {
            // Retrieve the order and associated invoice
            $order = Order::with('invoice')->findOrFail($validatedData['order_id']);
            $invoice = $order->invoice;

            // Calculate the new total paid including this payment
            if($validatedData['payment_type'] === 'full'){
                $totalPaid = $validatedData['payment_amount_full'] - $order->payments->sum('amount');
            } else {
                $totalPaid = $order->payments->sum('amount') + $validatedData['payment_amount'];
            }

            // Create the payment record
            Payment::create([
                'order_id' => $order->id,
                'amount_paid' => $totalPaid,
                'payment_method' => $validatedData['payment_method'],
                'payment_date'   => now(),
            ]);

            // Update the invoice
            $invoice->amount += $validatedData['payment_amount'];
            if ($invoice->amount >= $invoice->total_amount) {
                $invoice->status = 'paid';
            } elseif ($invoice->amount > 0) {
                $invoice->status = 'partially_paid';
            }
            $invoice->save();

            // Update the order status
            if ($totalPaid >= $order->total_amount) {
                $order->status = 'fully_paid';
            } elseif ($totalPaid > 0) {
                $order->status = 'partially_paid';
            }
            $order->save();
        });

        return redirect()->route('orders.index')->with('success', 'Pembayaran berhasil disimpan, status pesanan diperbarui, dan invoice diperbarui.');
    }

    /**
     * Display the specified order.
     *
     * @param Order $order
     * @return \Illuminate\View\View
     */
    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order.
     *
     * @param Order $order
     * @return \Illuminate\View\View
     */
    public function edit(Order $order)
    {
        $customers = Customer::all();
        $inventoryItems = Inventory::all();
        return view('orders.edit', compact('order', 'customers', 'inventoryItems'));
    }

    /**
     * Update the specified order in storage.
     *
     * @param Request $request
     * @param Order $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Order $order)
    {
        // Validate the request
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.inventory_id' => 'required|exists:inventories,id',
            'items.*.quantity_used' => 'required|integer|min:1',
            'items.*.discount' => 'nullable|numeric|min:0',
            'items.*.tax_rate' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string',
            'payment_amount' => 'nullable|numeric|min:0',
            'process_payment' => 'sometimes|boolean',
        ]);

        // Calculate the total order amount
        $totalOrderAmount = 0;
        foreach ($request->items as $item) {
            $inventoryItem = Inventory::find($item['inventory_id']);
            $priceBeforeTax = $inventoryItem->unit_price * $item['quantity_used'];
            $totalPrice = $priceBeforeTax - ($item['discount'] ?? 0) + ($priceBeforeTax * (($item['tax_rate'] ?? 0) / 100));
            $totalOrderAmount += $totalPrice;
        }

        // Determine the order status based on the payment amount
        $status = 'pending';
        if ($request->process_payment) {
            if ($request->payment_amount >= $totalOrderAmount) {
                $status = 'fully_paid';
            } elseif ($request->payment_amount > 0) {
                $status = 'partially_paid';
            }
        }

        // Update the order
        $order->update([
            'customer_id' => $request->customer_id,
            'service_date' => $request->service_date,
            'notes' => $request->notes,
            'status' => $status,
            'total_amount' => $totalOrderAmount,
        ]);

        // Sync items with the order
        $order->inventories()->detach();
        foreach ($request->items as $item) {
            $inventoryItem = Inventory::find($item['inventory_id']);
            $priceBeforeTax = $inventoryItem->unit_price * $item['quantity_used'];
            $totalPrice = $priceBeforeTax - ($item['discount'] ?? 0) + ($priceBeforeTax * (($item['tax_rate'] ?? 0) / 100));

            $order->inventories()->attach($inventoryItem->id, [
                'quantity_used' => $item['quantity_used'],
                'price_per_unit' => $inventoryItem->unit_price,
                'discount' => $item['discount'] ?? 0,
                'tax_rate' => $item['tax_rate'] ?? 0,
                'total_price' => $totalPrice,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Update or add payment if it was made
        if ($request->process_payment && $request->payment_amount > 0) {
            $order->payments()->create([
                'amount' => $request->payment_amount,
                'payment_date' => now(),
                'payment_method' => 'cash', // Adjust as needed
                'status' => $status == 'fully_paid' ? 'completed' : 'partial',
            ]);
        }

        return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    }

    /**
     * Remove the specified order from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->inventories()->detach();
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }
}
