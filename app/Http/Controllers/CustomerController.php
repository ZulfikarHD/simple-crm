<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerModel;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = CustomerModel::all();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone_number' => 'required|string|max:15',
            'email' => 'required|string|email|max:255|unique:customers',
        ]);

        CustomerModel::create($request->all());

        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil dibuat.');
    }

    public function edit(CustomerModel $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, CustomerModel $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone_number' => 'required|string|max:15',
            'email' => 'required|string|email|max:255|unique:customers,email,'.$customer->id,
        ]);

        $customer->update($request->all());

        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil diperbarui.');
    }

    public function destroy(CustomerModel $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil dihapus.');
    }
}
