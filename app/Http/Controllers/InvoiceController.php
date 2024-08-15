<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Order;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil data dengan paginasi, sorting, dan filtering
        $invoices = Invoice::with('order.customer')
            ->when($request->input('status'), function ($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('issue_date', 'desc')
            ->paginate(10);

        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $orders = Order::all();
        return view('invoices.create', compact('orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|string|max:255',
        ]);

        Invoice::create([
            'invoice_number' => "INV-".$request->order_id.$request->issue_date,
            'order_id' => $request->order_id,
            'issue_date' => $request->issue_date,
            'due_date'  => $request->due_date,
            'amount'    => $request->amount,
            'status'    => $request->status,
        ]);

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }

    public function show($id)
    {
        $invoice = Invoice::with('order.customer', 'payments')->findOrFail($id);

        if (!$invoice) {
            return redirect()->route('invoices.index')->with('error', 'Invoice not found.');
        }

        return view('invoices.show', compact('invoice'));
    }

    public function edit($id)
    {
        $invoice = Invoice::findOrFail($id);
        $orders = Order::all();

        if (!$invoice) {
            return redirect()->route('invoices.index')->with('error', 'Invoice not found.');
        }

        return view('invoices.edit', compact('invoice', 'orders'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|string|max:255',
        ]);

        $invoice = Invoice::findOrFail($id);

        if (!$invoice) {
            return redirect()->route('invoices.index')->with('error', 'Invoice not found.');
        }

        $invoice->update($request->all());

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
    }

    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);

        if (!$invoice) {
            return redirect()->route('invoices.index')->with('error', 'Invoice not found.');
        }

        $invoice->delete();

        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }
}
