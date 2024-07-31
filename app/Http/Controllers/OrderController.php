<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Customer;

class OrderController extends Controller
{public function index()
    {
        $orders = Order::with('customer')->get();
        return view('order-management.index', compact('orders'));
    }

    public function create()
    {
        $customers = Customer::all();
        return view('order-management.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_date' => 'required|date',
            'status' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        Order::create($request->all());

        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
    }

    public function show($id)
    {
        $order = Order::with('customer')->findOrFail($id);
        return view('order-management.show', compact('order'));
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        $customers = Customer::all();
        return view('order-management.edit', compact('order', 'customers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_date' => 'required|date',
            'status' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $order = Order::findOrFail($id);
        $order->update($request->all());

        return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }
}
