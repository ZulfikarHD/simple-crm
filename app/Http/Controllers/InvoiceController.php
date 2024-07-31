<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceModel;
use App\Models\OrderModel;

class InvoiceController extends Controller
{

    public function index()
    {
        $invoices = InvoiceModel::with('order')->get();
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $orders = OrderModel::all();
        return view('invoices.create', compact('orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date',
            'amount' => 'required|numeric',
        ]);

        InvoiceModel::updateOrCreate(
            ['invoice_number' => "SRV/" . date('Ymd') . "/" . InvoiceModel::with('order')->count() + 1],
            [
                'order_id' => $request->order_id,
                'issue_date' => $request->issue_date,
                'due_date' => $request->due_date,
                'amount' => $request->amount,
            ]
        );

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }

    public function edit(InvoiceModel $invoice)
    {
        $orders = OrderModel::all();
        return view('invoices.edit', compact('invoice', 'orders'));
    }

    public function update(Request $request, InvoiceModel $invoice)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date',
            'amount' => 'required|numeric',
        ]);

        $invoice->update($request->all());

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
    }

    public function destroy(InvoiceModel $invoice)
    {
        $invoice->delete();

        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }
}
