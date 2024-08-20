<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('order.customer', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('id', 'like', "%{$search}%")
                ->orWhere('order_id', 'like', "%{$search}%");
        }

        // Filter by payment method
        if ($request->filled('filterMethod')) {
            $query->where('payment_method', $request->input('filterMethod'));
        }

        // Paginate results
        $payments = $query->with('order.customer')->paginate(10);

        return view('payments.index', compact('payments'));
    }


    // Show payment form for a specific order
    public function create($orderId)
    {
        $order = Order::with('payments')->findOrFail($orderId);
        return view('payments.create', compact('order'));
    }

    // Store payment details and update order status
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount_paid' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'payment_type' => 'required|in:partial,full',
        ]);

        DB::transaction(function () use ($validatedData) {
            $order = Order::findOrFail($validatedData['order_id']);
            $totalPaid = $order->payments->sum('amount_paid') + $validatedData['amount_paid'];

            // Save payment record
            Payment::create([
                'order_id' => $validatedData['order_id'],
                'amount_paid' => $validatedData['amount_paid'],
                'payment_method' => $validatedData['payment_method'],
            ]);

            // Update order status based on payment
            if ($totalPaid >= $order->total_amount) {
                $order->status = 'fully_paid';
            } elseif ($totalPaid > 0) {
                $order->status = 'partially_paid';
            }

            $order->save();
        });

        return redirect()->route('orders.show', $validatedData['order_id'])->with('success', 'Pembayaran berhasil disimpan.');
    }

    // Show payment history for a specific order
    public function show($orderId)
    {
        $order = Order::with('payments')->findOrFail($orderId);
        return view('payment.show', compact('order'));
    }
}
