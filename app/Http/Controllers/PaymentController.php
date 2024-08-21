<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Menampilkan daftar pembayaran dengan opsi pencarian dan filter metode pembayaran.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Payment::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('order.customer', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('id', 'like', "%{$search}%")
                ->orWhere('order_id', 'like', "%{$search}%");
        }

        if ($request->filled('filterMethod')) {
            $query->where('payment_method', $request->input('filterMethod'));
        }

        $payments = $query->with('order.customer')->paginate(10);

        return view('payments.index', compact('payments'));
    }

    /**
     * Menampilkan form untuk membuat pembayaran baru untuk order tertentu.
     *
     * @param int $orderId
     * @return \Illuminate\View\View
     */
    public function create($orderId)
    {
        $order = Order::with('invoice')->findOrFail($orderId);

        return view('payments.create', compact('order'));
    }

    /**
     * Menyimpan pembayaran baru ke dalam database.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'payment_type' => 'required|in:partial,full',
            'payment_amount_full' => 'nullable|numeric|min:0'
        ]);

        DB::transaction(function () use ($validatedData) {
            $order = Order::with('invoice')->findOrFail($validatedData['order_id']);
            $invoice = $order->invoice;

            $totalPaid = $validatedData['payment_type'] === 'full'
                ? $invoice->total_amount - $invoice->amount_paid
                : $order->payments->sum('amount_paid') + $validatedData['payment_amount'];

            Payment::create([
                'order_id' => $order->id,
                'amount_paid' => $totalPaid,
                'payment_method' => $validatedData['payment_method'],
                'payment_date' => now(),
            ]);

            $invoice->amount_paid += $totalPaid;
            $invoice->status = $invoice->amount_paid >= $invoice->total_amount
                ? 'paid'
                : ($invoice->amount_paid > 0 ? 'partially_paid' : 'unpaid');
            $invoice->save();

            $order->status = $totalPaid >= $order->total_amount
                ? 'fully_paid'
                : ($totalPaid > 0 ? 'partially_paid' : 'pending');
            $order->save();
        });

        return redirect()->route('orders.index')->with('success', 'Payment successfully saved, order status updated, and invoice updated.');
    }

    /**
     * Menampilkan detail pembayaran untuk order tertentu.
     *
     * @param int $orderId
     * @return \Illuminate\View\View
     */
    public function show($orderId)
    {
        $order = Order::with('payments')->findOrFail($orderId);
        return view('payment.show', compact('order'));
    }

    /**
     * Memperbarui pembayaran yang sudah ada di dalam database.
     *
     * @param Request $request
     * @param Payment $payment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Payment $payment)
    {
        $validatedData = $request->validate([
            'amount_paid' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
        ]);

        DB::transaction(function () use ($validatedData, $payment) {
            $order = $payment->order;
            $invoice = $order->invoice;

            $oldPaymentAmount = $payment->amount_paid;
            $paymentDifference = $validatedData['amount_paid'] - $oldPaymentAmount;

            $payment->update([
                'amount_paid' => $validatedData['amount_paid'],
                'payment_method' => $validatedData['payment_method'],
                'payment_date' => now(),
            ]);

            $invoice->amount_paid += $paymentDifference;
            $invoice->status = $invoice->amount_paid >= $invoice->total_amount
                ? 'paid'
                : ($invoice->amount_paid > 0 ? 'partially_paid' : 'unpaid');
            $invoice->save();

            $order->status = $invoice->amount_paid >= $order->total_amount
                ? 'fully_paid'
                : ($invoice->amount_paid > 0 ? 'partially_paid' : 'pending');
            $order->save();
        });

        return redirect()->route('payments.index')->with('success', 'Payment updated successfully.');
    }
}
