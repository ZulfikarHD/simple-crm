<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Invoice;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('invoice.order.customer')->get();
        return view('payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $invoice = Invoice::find($request->invoice_id);
        return view('payments.create', compact('invoice'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string|max:255',
        ]);

        $payment = Payment::create($request->all());

        // Update invoice status after payment
        $this->updateInvoiceStatus($payment->invoice);

        return redirect()->route('payments.index')->with('success', 'Payment recorded successfully.');
    }

    public function show($id)
    {
        $payment = Payment::with('invoice.order.customer')->findOrFail($id);
        return view('payments.show', compact('payment'));
    }

    public function updateInvoiceStatus(Invoice $invoice)
    {
        $totalPaid = $invoice->payments()->sum('amount');
        $invoice->status = $totalPaid >= $invoice->amount ? 'Paid' : ($totalPaid > 0 ? 'Partially Paid' : 'Pending');
        $invoice->save();
    }
}
