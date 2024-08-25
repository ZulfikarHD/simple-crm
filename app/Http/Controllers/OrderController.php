<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Inventory;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('customer', 'inventory');

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search != '') {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $orders = $query->paginate(3);

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $customers = Customer::all();
        $items = Inventory::all();

        return view('orders.create', compact('customers', 'items'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'required_without:customer_id|string|max:255',
            'customer_email' => 'required_without:customer_id|email|max:255',
            'customer_phone' => 'required_without:customer_id|string|max:20',
            'order_date' => 'required|date',
            'item_id' => 'required|array',
            'item_id.*' => 'required|exists:inventory,id',
            'item_quantity' => 'required|array',
            'item_quantity.*' => 'required|integer|min:1',
            'discount' => 'nullable|numeric|min:0|max:100',
            'tax' => 'nullable|numeric|min:0|max:100',
        ]);

        if (!$validatedData['customer_id']) {
            $customer = Customer::create([
                'name' => $validatedData['customer_name'],
                'email' => $validatedData['customer_email'],
                'phone' => $validatedData['customer_phone'],
            ]);
            $validatedData['customer_id'] = $customer->id;
        }

        $subtotal = 0;
        foreach ($validatedData['item_id'] as $index => $itemId) {
            $item = Inventory::findOrFail($itemId);
            $quantity = $validatedData['item_quantity'][$index];
            $subtotal += $item->unit_price * $quantity;
        }

        $discount = $validatedData['discount'] ?? 0;
        $tax = $validatedData['tax'] ?? 11;
        $totalAfterDiscount = $subtotal - ($subtotal * $discount / 100);
        $totalAmount = $totalAfterDiscount + ($totalAfterDiscount * $tax / 100);

        DB::transaction(function () use ($validatedData, $subtotal, $totalAmount) {
            $order = Order::create([
                'customer_id' => $validatedData['customer_id'],
                'order_date' => $validatedData['order_date'],
                'status' => 'pending',
                'subtotal' => $subtotal,
                'total_amount' => $totalAmount,
            ]);

            Invoice::create([
                'order_id' => $order->id,
                'invoice_number' => 'INV-' . strtoupper(uniqid()),
                'total_amount' => $totalAmount,
                'issue_date' => now(),
                'due_date' => $order->order_date,
                'status' => 'unpaid',
            ]);

            foreach ($validatedData['item_id'] as $index => $itemId) {
                $quantity = $validatedData['item_quantity'][$index];
                $item = Inventory::findOrFail($itemId);
                $order->inventory()->attach($itemId, [
                    'quantity_used' => $quantity,
                    'price_per_unit' => $item->unit_price,
                    'total_price' => $item->unit_price * $quantity,
                ]);

                StockMovement::create([
                    'inventory_id' => $itemId,
                    'quantity' => -$quantity,
                    'movement_type' => 'out',
                    'description' => 'Order created',
                ]);
            }
        });

        if ($request->input('action') === 'save_and_pay') {
            return redirect()->route('payments.create', ['orderId' => $order->id]);
        } else {
            return redirect()->route('orders.index')->with('success', 'Order created successfully.');
        }
    }

    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $customers = Customer::all();
        $inventoryItems = Inventory::all();
        return view('orders.edit', compact('order', 'customers', 'inventoryItems'));
    }

    public function update(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_date' => 'required|date',
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

        $subtotal = 0;
        foreach ($validatedData['item_id'] as $index => $itemId) {
            $item = Inventory::findOrFail($itemId);
            $quantity = $validatedData['item_quantity'][$index];
            $subtotal += $item->unit_price * $quantity;
        }

        $discount = $validatedData['discount'] ?? 0;
        $tax = $validatedData['tax'] ?? 11;
        $totalAfterDiscount = $subtotal - ($subtotal * $discount / 100);
        $totalAmount = $totalAfterDiscount + ($totalAfterDiscount * $tax / 100);

        $order->update([
            'customer_id' => $validatedData['customer_id'],
            'order_date' => $validatedData['order_date'],
            'notes' => $validatedData['notes'],
            'subtotal' => $subtotal,
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);

        $order->inventory()->sync([]);
        foreach ($validatedData['item_id'] as $index => $itemId) {
            $quantity = $validatedData['item_quantity'][$index];
            $item = Inventory::findOrFail($itemId);
            $order->inventory()->attach($itemId, [
                'quantity_used' => $quantity,
                'price_per_unit' => $item->unit_price,
                'total_price' => $item->unit_price * $quantity,
            ]);

            StockMovement::create([
                'inventory_id' => $itemId,
                'quantity' => -$quantity,
                'movement_type' => 'out',
                'description' => 'Order updated',
            ]);
        }

        if ($request->process_payment && $request->payment_amount > 0) {
            $totalPaid = $order->payments->sum('amount_paid') + $request->payment_amount;
            $status = $totalPaid >= $totalAmount ? 'fully_paid' : 'partially_paid';

            $order->payments()->create([
                'amount_paid' => $request->payment_amount,
                'payment_date' => now(),
                'payment_method' => 'cash',
                'status' => $status,
            ]);

            $order->update(['status' => $status]);

            $invoice = $order->invoice;
            $invoice->update([
                'amount_paid' => $totalPaid,
                'status' => $totalPaid >= $invoice->total_amount ? 'paid' : 'partially_paid',
            ]);
        }

        return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->inventory()->detach();
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }
}
