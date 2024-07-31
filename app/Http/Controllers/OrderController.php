<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderModel;
use App\Models\CustomerModel;

class OrderController extends Controller
{
    public function index()
    {
        $orders = OrderModel::with('customer')->get();
        return view('order-management.index', compact('orders'));
    }

    public function create()
    {
        $customers = CustomerModel::all();
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

        OrderModel::create($request->all());

        return redirect()->route('order.index')->with('success', 'Pesanan berhasil dibuat.');
    }

    public function edit(OrderModel $order)
    {
        $customers = CustomerModel::all();
        return view('order-management.edit', compact('order', 'customers'));
    }

    public function update(Request $request, OrderModel $order)
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

    public function destroy(OrderModel $order)
    {
        $order->delete();

        return redirect()->route('order.index')->with('success', 'Pesanan berhasil dihapus.');
    }
}
