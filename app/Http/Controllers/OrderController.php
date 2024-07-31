<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Customer;

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
        return view('order-management.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_date' => 'required|date',
            'status' => 'required|string',
            'total_amount' => 'required|numeric',
        ]);

        Order::create($request->all());

        return redirect()->route('order.index')->with('success', 'Pesanan berhasil dibuat.');
    }

    public function edit(Order $order)
    {
        $customers = Customer::all();
        return view('order-management.edit', compact('order', 'customers'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_date' => 'required|date',
            'status' => 'required|string',
            'total_amount' => 'required|numeric',
        ]);

        $order->update($request->all());

        return redirect()->route('order.index')->with('success', 'Pesanan berhasil diperbarui.');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('order.index')->with('success', 'Pesanan berhasil dihapus.');
    }
}
