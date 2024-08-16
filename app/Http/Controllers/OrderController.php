<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Payment;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // Get filters and sorting from the request
        $search = $request->input('search');
        $status = $request->input('status');
        $sort_by = $request->input('sort_by', 'created_at');
        $sort_direction = $request->input('sort_direction', 'asc');

        // Query orders with filters, sorting, and pagination
        $orders = Order::with('customer')
            ->when($search, function ($query, $search) {
                return $query->whereHas('customer', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy($sort_by, $sort_direction)
            ->paginate(10);

        return view('orders.index', compact('orders', 'search', 'status', 'sort_by', 'sort_direction'));
    }

    public function create()
    {
        $customers = Customer::all();
        $inventoryItems = Inventory::all();
        return view('orders.create', compact('customers', 'inventoryItems'));
    }

    public function store(Request $request)
    {
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

            if(isset($item['discount'])){
                $discount = $item['discount'];
            } else {
                $discount = 0;
            }

            if(isset($item['tax_rate'])){
                $taxRate = $item['tax_rate'];
            } else {
                $taxRate = 0;
            }


            $inventoryItem = Inventory::find($item['inventory_id']);

            $priceBeforeTax = $inventoryItem->unit_price * $item['quantity_used'];
            $totalPrice = $priceBeforeTax - $discount + ($priceBeforeTax * ($taxRate / 100));

            $order->inventories()->attach($item['inventory_id'], [
                'quantity_used' => $item['quantity_used'],
                'price_per_unit' => $inventoryItem->unit_price,
                'discount' => $discount ?? 0,
                'tax_rate' => $taxRate ?? 0,
                'total_price' => $totalPrice,
            ]);

            $inventoryItem->decrement('quantity', $item['quantity_used']);
        }

        // Handle Payment
        if ($request->process_payment && $request->payment_amount > 0) {
            $order->payments()->create([
                'amount' => $request->payment_amount,
                'payment_date' => now(),
                'payment_method' => 'cash', // Or get from form
            ]);
        }

        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
    }

    public function show($id)
    {
        $order = Order::with('customer', 'inventories')->findOrFail($id);
        return view('orders.show', compact('order'));
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        $customers = Customer::all();
        $inventoryItems = Inventory::all();
        return view('orders.edit', compact('order', 'customers', 'inventoryItems'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.inventory_id' => 'required|exists:inventories,id',
            'items.*.quantity_used' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'payment_amount' => 'nullable|numeric|min:0',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'customer_id' => $request->customer_id,
            'service_date' => $request->service_date,
            'notes' => $request->notes,
            'status' => $request->process_payment && $request->payment_amount > 0 ? 'partially_paid' : 'pending',
        ]);

        // Detach existing items and reattach updated items
        $order->inventories()->detach();
        foreach ($request->items as $item) {
            $inventoryItem = Inventory::find($item['inventory_id']);

            $priceBeforeTax = $inventoryItem->unit_price * $item['quantity_used'];
            $totalPrice = $priceBeforeTax - $item['discount'] + ($priceBeforeTax * ($item['tax_rate'] / 100));

            $order->inventories()->attach($item['inventory_id'], [
                'quantity_used' => $item['quantity_used'],
                'price_per_unit' => $inventoryItem->unit_price,
                'discount' => $item['discount'] ?? 0,
                'tax_rate' => $item['tax_rate'] ?? 0,
                'total_price' => $totalPrice,
            ]);

            $inventoryItem->decrement('quantity', $item['quantity_used']);
        }

        // Handle Payment (If any update is required)
        if ($request->process_payment && $request->payment_amount > 0) {
            $order->payments()->create([
                'amount' => $request->payment_amount,
                'payment_date' => now(),
                'payment_method' => 'cash',
            ]);
        }

        return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->inventories()->detach(); // Detach associated inventories
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }
}
