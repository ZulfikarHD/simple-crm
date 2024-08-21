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

    /**
     * Show the payment form for a specific order.
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create($orderId)
    {
        $order = Order::with('invoice')->findOrFail($orderId);

        return view('payments.create', compact('order'));
    }

    // Store payment details and update order status
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'payment_type' => 'required|in:partial,full',
            'payment_amount_full' => 'nullable|numeric|min:0'
        ]);

        // Use a database transaction to ensure data integrity
        DB::transaction(function () use ($validatedData) {
            // Retrieve the order and associated invoice
            $order = Order::with('invoice')->findOrFail($validatedData['order_id']);
            $invoice = $order->invoice;

            // Calculate the new total paid including this payment
            if ($validatedData['payment_type'] === 'full') {
                $totalPaid = $validatedData['payment_amount_full'] - $order->payments->sum('amount_paid');
            } else {
                $totalPaid = $order->payments->sum('amount_paid') + $validatedData['payment_amount'];
            }

            // Create the payment record
            Payment::create([
                'order_id' => $order->id,
                'amount_paid' => $totalPaid,
                'payment_method' => $validatedData['payment_method'],
                'payment_date'   => now(),
            ]);

            // Update the invoice
            $invoice->amount_paid += $totalPaid;
            if ($invoice->amount_paid >= $invoice->total_amount) {
                $invoice->status = 'paid';
            } elseif ($invoice->amount_paid > 0) {
                $invoice->status = 'partially_paid';
            }
            $invoice->save();

            // Update the order status
            if ($totalPaid >= $order->total_amount) {
                $order->status = 'fully_paid';
            } elseif ($totalPaid > 0) {
                $order->status = 'partially_paid';
            }
            $order->save();
        });

        return redirect()->route('orders.index')->with('success', 'Pembayaran berhasil disimpan, status pesanan diperbarui, dan invoice diperbarui.');
    }

    // Show payment history for a specific order
    public function show($orderId)
    {
        $order = Order::with('payments')->findOrFail($orderId);
        return view('payment.show', compact('order'));
    }


    /**
     * Update the specified payment in storage.
     *
     * @param Request $request
     * @param Payment $payment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Payment $payment)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'amount_paid' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
        ]);

        // Use a database transaction to ensure data integrity
        DB::transaction(function () use ($validatedData, $payment) {
            // Retrieve the associated order and invoice
            $order = $payment->order;
            $invoice = $order->invoice;

            // Calculate the old payment amount and the difference
            $oldPaymentAmount = $payment->amount_paid;
            $paymentDifference = $validatedData['amount_paid'] - $oldPaymentAmount;

            // Update the payment record
            $payment->update([
                'amount_paid' => $validatedData['amount_paid'],
                'payment_method' => $validatedData['payment_method'],
                'payment_date' => now(), // Optionally, update the payment date
            ]);

            // Update the total paid amount on the invoice
            $invoice->amount_paid += $paymentDifference;

            // Update the invoice status
            if ($invoice->amount_paid >= $invoice->total_amount) {
                $invoice->status = 'paid';
            } elseif ($invoice->amount_paid > 0) {
                $invoice->status = 'partially_paid';
            } else {
                $invoice->status = 'unpaid';
            }
            $invoice->save();

            // Update the order status based on the total paid amount
            if ($invoice->amount_paid >= $order->total_amount) {
                $order->status = 'fully_paid';
            } elseif ($invoice->amount_paid > 0) {
                $order->status = 'partially_paid';
            } else {
                $order->status = 'pending';
            }
            $order->save();
        });

        return redirect()->route('payments.index')->with('success', 'Payment updated successfully.');
    }
}
