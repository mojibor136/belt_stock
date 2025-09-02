<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('transport', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $customers = $query->orderBy('id', 'desc')->get();

        return view('customer.index', compact('customers'));
    }

    public function getCustomerData($id)
    {
        $customer = Customer::find($id);

        if($customer) {
            return response()->json($customer);
        }

        return response()->json(['error' => 'Customer not found'], 404);
    }

    public function create()
    {
        return view('customer.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'phone'     => 'required|string|max:20',
            'amount'    => 'required|numeric|min:0',
            'email'     => 'nullable|email|max:255',
            'address'   => 'required|string|max:500',
            'transport' => 'nullable|string|max:255',
        ]);

        try {
            $exists = Customer::where('name', $request->name)
                ->where('phone', $request->phone)
                ->where('address', $request->address)
                ->where('email', $request->email)
                ->exists();

            if ($exists) {
                return back()->withInput()->with('error', 'Customer already exists with same Name, Phone & Address!');
            }

            Customer::create([
                'name'      => $request->name,
                'phone'     => $request->phone,
                'amount'    => $request->amount,
                'email'     => $request->email,
                'address'   => $request->address,
                'transport' => $request->transport,
                'status'    => 'Debit',
            ]);

            return redirect()->route('customer.index')->with('success', 'Customer created successfully!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Something went wrong! Please try again.');
        }
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customer.edit', compact('customer'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id'        => 'required|exists:customers,id',
            'name'      => 'required|string|max:255',
            'phone'     => 'required|string|max:20',
            'amount'    => 'required|numeric|min:0',
            'email'     => 'nullable|email|max:255',
            'address'   => 'required|string|max:500',
            'transport' => 'nullable|string|max:255',
        ]);

        try {
            $customer = Customer::findOrFail($request->id);

            $exists = Customer::where('name', $request->name)
                ->where('phone', $request->phone)
                ->where('address', $request->address)
                ->where('email', $request->email)
                ->where('id', '!=', $request->id)
                ->exists();

            if ($exists) {
                return back()->withInput()->with('error', 'Another customer already exists with same Name, Phone & Address!');
            }

            $customer->update([
                'name'      => $request->name,
                'phone'     => $request->phone,
                'amount'    => $request->amount,
                'email'     => $request->email,
                'address'   => $request->address,
                'transport' => $request->transport,
            ]);

            return redirect()->route('customer.index')->with('success', 'Customer updated successfully!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update customer. Please try again.');
        }
    }

    public function destroy($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->delete();
            return redirect()->route('customer.index')->with('success', 'Customer deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete customer. Please try again.');
        }
    }

    public function customerSales()
    {
        return view('customer.customerSales');
    }
}
