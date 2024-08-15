<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Inventory;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('customer')->get();
        return view('order-management.index', compact('orders'));
    }

    public function create()
    {
        $customers = Customer::all();
        $inventories = Inventory::all();
        return view('order-management.create', compact('customers', 'inventories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array',
            'items.*.inventory_id' => 'required|exists:inventories,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price_per_unit' => 'required|numeric|min:0',
        ]);

        foreach ($request->items as $item) {
            $inventory = Inventory::findOrFail($item['inventory_id']);
            if ($item['quantity'] > $inventory->quantity) {
                return redirect()->back()->withErrors(['error' => 'Quantity exceeds available stock for ' . $inventory->item_name]);
            }
        }

        $order = Order::create([
            'customer_id' => $request->customer_id,
            'service_date' => $request->service_date,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        foreach ($request->items as $item) {
            $order->items()->attach($item['inventory_id'], [
                'quantity' => $item['quantity'],
                'price_per_unit' => $item['price_per_unit'],
                'discount' => $item['discount'] ?? 0,
                'tax_rate' => $item['tax_rate'] ?? 0,
                'total_price' => ($item['quantity'] * $item['price_per_unit']) - $item['discount'] + ($item['tax_rate'] / 100),
            ]);
        }

        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
    }

    public function show($id)
    {
        $order = Order::with('customer', 'items')->findOrFail($id);
        return view('order-management.show', compact('order'));
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        $customers = Customer::all();
        $inventories = Inventory::all();
        return view('order-management.edit', compact('order', 'customers', 'inventories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array',
            'items.*.inventory_id' => 'required|exists:inventories,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price_per_unit' => 'required|numeric|min:0',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'customer_id' => $request->customer_id,
            'service_date' => $request->service_date,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        $order->items()->detach();
        foreach ($request->items as $item) {
            $order->items()->attach($item['inventory_id'], [
                'quantity' => $item['quantity'],
                'price_per_unit' => $item['price_per_unit'],
                'discount' => $item['discount'] ?? 0,
                'tax_rate' => $item['tax_rate'] ?? 0,
                'total_price' => ($item['quantity'] * $item['price_per_unit']) - $item['discount'] + ($item['tax_rate'] / 100),
            ]);
        }

        return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }
}
