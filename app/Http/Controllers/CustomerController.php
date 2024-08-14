<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        // Pencarian
        $search = $request->input('search');
        $status = $request->input('status');

        // Pengambilan data dengan paginasi, pencarian, dan penyaringan
        $customers = Customer::when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->paginate(10);

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
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'email' => 'required|email|unique:customers,email',
            'social_media_profile' => 'nullable|string|max:255',
            'feedback' => 'nullable|string',
            'loyalty_points' => 'nullable|integer|min:0',
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customers.show', compact('customer'));
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'email' => 'required|email|unique:customers,email,'.$id,
            'social_media_profile' => 'nullable|string|max:255',
            'feedback' => 'nullable|string',
            'loyalty_points' => 'nullable|integer|min:0',
        ]);

        $customer = Customer::findOrFail($id);
        $customer->update($request->all());

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);

        // Validasi sebelum menghapus (misalnya, cek jika ada order terkait)
        $hasOrders = DB::table('orders')->where('customer_id', $id)->exists();

        if ($hasOrders) {
            return redirect()->route('customers.index')->with('error', 'Cannot delete customer with existing orders.');
        }

        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }

    public function exportCSV()
    {
        $customers = Customer::all();
        $filename = "customers_" . date('Ymd') . ".csv";
        $handle = fopen($filename, 'w+');
        fputcsv($handle, ['ID', 'Name', 'Address', 'Phone Number', 'Email', 'Loyalty Points', 'Social Media Profile']);

        foreach ($customers as $customer) {
            fputcsv($handle, [$customer->id, $customer->name, $customer->address, $customer->phone_number, $customer->email, $customer->loyalty_points, $customer->social_media_profile]);
        }

        fclose($handle);

        return response()->download($filename)->deleteFileAfterSend(true);
    }
}
