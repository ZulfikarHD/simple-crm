<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Inventory;
use App\Models\Customer;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('customer', 'inventories');

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
        $inventoryItems = Inventory::all();
        return view('orders.create', compact('customers', 'inventoryItems'));
    }

    public function store(Request $request)
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
        ]);

        // Create the order
        $order = Order::create([
            'customer_id' => $request->customer_id,
            'service_date' => $request->service_date,
            'notes' => $request->notes,
            'status' => $request->process_payment && $request->payment_amount > 0 ? 'partially_paid' : 'pending',
        ]);

        // Attach items to the order and update inventory
        foreach ($request->items as $item) {
            $discount = $item['discount'] ?? 0;
            $taxRate = $item['tax_rate'] ?? 0;

            // Verify inventory item exists before proceeding
            $inventoryItem = Inventory::find($item['inventory_id']);
            if (!$inventoryItem) {
                // If the inventory item is not found, rollback the transaction and show an error
                return back()->withErrors(['inventory' => 'One or more selected inventory items are invalid or no longer available.']);
            }

            $priceBeforeTax = $inventoryItem->unit_price * $item['quantity_used'];
            $totalPrice = $priceBeforeTax - $discount + ($priceBeforeTax * ($taxRate / 100));

            // Attach inventory item to the order
            $order->inventories()->attach($inventoryItem->id, [
                'quantity_used' => $item['quantity_used'],
                'price_per_unit' => $inventoryItem->unit_price,
                'discount' => $discount,
                'tax_rate' => $taxRate,
                'total_price' => $totalPrice,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Optionally update inventory quantity if needed
            // $inventoryItem->decrement('quantity', $item['quantity_used']);
        }

        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
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

            // Optionally update inventory quantity if needed
            // $inventoryItem->decrement('quantity', $item['quantity_used']);
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


    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->inventories()->detach();
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }
}
