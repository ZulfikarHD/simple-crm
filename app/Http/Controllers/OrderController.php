<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Inventory;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\StockMovement;
use DB;
use Illuminate\Http\Request;

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
        $orders = $query->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new order with customer and item data.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $customers = Customer::all();
        $items = Inventory::all();

        return view('orders.create', compact('customers', 'items'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'required_without:customer_id|string|max:255',
            'customer_email' => 'required_without:customer_id|email|max:255',
            'customer_phone' => 'required_without:customer_id|string|max:20',
            'service_date' => 'required|date',
            'item_id' => 'required|array',
            'item_id.*' => 'required|exists:inventory,id',
            'item_quantity' => 'required|array',
            'item_quantity.*' => 'required|integer|min:1',
            'discount' => 'nullable|numeric|min:0|max:100',
            'tax' => 'nullable|numeric|min:0|max:100',
        ]);

        // Create or find customer
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

        // Create the order
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
            'issue_date' => now(),
            'due_date' => $order->service_date,
            'status' => 'unpaid', // Initial status
        ]);

        // Attach items to the order and create stock movements
        foreach ($validatedData['item_id'] as $index => $itemId) {
            $quantity = $validatedData['item_quantity'][$index];
            $item = Inventory::findOrFail($itemId);

            // Attach inventory to order
            $order->inventory()->attach($itemId, [
                'quantity_used' => $quantity,
                'price_per_unit' => $item->unit_price,
                'total_price' => $item->unit_price * $quantity,
            ]);

            // Create stock movement
            StockMovement::create([
                'inventory_id' => $itemId,
                'order_id' => $order->id,
                'quantity_change' => -$quantity, // Decrease stock
                'movement_type' => 'outbound', // Adjust as needed
            ]);
        }

        // Redirect based on user action
        if ($request->input('action') === 'save_and_pay') {
            return redirect()->route('payments.create', ['orderId' => $order->id]);
        } else {
            return redirect()->route('orders.index')->with('success', 'Pesanan berhasil disimpan.');
        }
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
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_date' => 'required|date',
            'item_id' => 'required|array|min:1',
            'item_id.*' => 'required|exists:inventory,id',
            'item_quantity' => 'required|array|min:1',
            'item_quantity.*' => 'required|integer|min:1',
            'discount' => 'nullable|numeric|min:0|max:100',
            'tax' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string',
            'process_payment' => 'sometimes|boolean',
            'payment_amount' => 'nullable|numeric|min:0',
        ]);

        // Calculate the total order amount
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

        // Update the order
        $order->update([
            'customer_id' => $validatedData['customer_id'],
            'service_date' => $validatedData['service_date'],
            'notes' => $validatedData['notes'],
            'subtotal' => $subtotal,
            'total_amount' => $totalAmount,
            'status' => 'pending', // Reset status
        ]);

        // Sync items with the order
        $order->inventories()->sync([]);
        foreach ($validatedData['item_id'] as $index => $itemId) {
            $quantity = $validatedData['item_quantity'][$index];
            $item = Inventory::findOrFail($itemId);
            $order->inventories()->attach($itemId, [
                'quantity_used' => $quantity,
                'price_per_unit' => $item->unit_price,
                'total_price' => $item->unit_price * $quantity,
            ]);
        }

        // Handle payment processing if requested
        if ($request->process_payment && $request->payment_amount > 0) {
            return redirect()->route('payments.create', ['orderId' => $order->id]);
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
