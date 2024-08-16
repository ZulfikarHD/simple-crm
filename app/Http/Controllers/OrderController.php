<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Inventory;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('customer');

        // Apply search filter
        if ($request->has('search') && $request->search != '') {
            $query->whereHas('customer', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Apply sorting
        if ($request->has('sort_by') && $request->sort_by != '') {
            $sortDirection = $request->sort_direction ?? 'asc';
            $query->orderBy($request->sort_by, $sortDirection);
        } else {
            $query->orderBy('created_at', 'desc'); // Default sorting
        }

        $orders = $query->paginate(10);

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
            'service_date' => 'required|date',
            'status' => 'required|string|max:255',
            'items' => 'required|array',
            'items.*.inventory_id' => 'required|exists:inventories,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price_per_unit' => 'required|numeric|min:0',
        ]);

        $order = Order::create($request->only(['customer_id', 'service_date', 'status']));

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
            'service_date' => 'required|date',
            'status' => 'required|string|max:255',
            'items' => 'required|array',
            'items.*.inventory_id' => 'required|exists:inventories,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price_per_unit' => 'required|numeric|min:0',
        ]);

        $order = Order::findOrFail($id);
        $order->update($request->only(['customer_id', 'service_date', 'status']));

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
