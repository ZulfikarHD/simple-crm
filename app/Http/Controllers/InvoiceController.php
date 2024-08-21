<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Order;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with('order.customer');

        // Apply search filter
        if ($request->has('search') && $request->search != '') {
            $query->whereHas('order.customer', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Apply status filter
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Apply sorting
        if ($request->has('sort_by') && $request->sort_by != '') {
            $sortDirection = $request->sort_direction ?? 'asc';
            $query->orderBy($request->sort_by, $sortDirection);
        } else {
            $query->orderBy('issue_date', 'desc'); // Default sorting
        }

        $invoices = $query->paginate(10);

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
            'due_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'status' => 'required|string|max:255',
        ]);

        Invoice::create($request->all());

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }

    public function show($id)
    {
        $invoice = Invoice::with('order.customer', 'payments')->findOrFail($id);
        return view('invoices.show', compact('invoice'));
    }

    public function edit($id)
    {
        $invoice = Invoice::findOrFail($id);
        $orders = Order::all();
        return view('invoices.edit', compact('invoice', 'orders'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date',
            'total_amount' => 'required|numeric',
            'status' => 'required|string|max:255',
        ]);

        $invoice = Invoice::findOrFail($id);
        $invoice->update($request->all());

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
    }

    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }
}
