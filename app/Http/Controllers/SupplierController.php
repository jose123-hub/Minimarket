<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required',
            'ruc'          => 'required|unique:suppliers,ruc',
        ]);

        $request->validate([
              'company_name' => 'required|string|max:255',
              'ruc'          => 'required|digits:11|unique:suppliers,ruc',
              'contact_name' => 'nullable|string|max:255',
              'phone'        => 'nullable|string|max:20',
              'email'        => 'nullable|email|max:255',
              'address'      => 'nullable|string|max:255',
              'status'       => 'required|in:active,inactive',
           ]);
        return redirect('/admin/suppliers')->with('success', 'Supplier created successfully.');
    }

    public function edit(Supplier $supplier)
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
    $request->validate([
        'company_name' => 'required|string|max:255',
        'ruc'          => 'required|digits:11|unique:suppliers,ruc,' . $supplier->id,
        'contact_name' => 'nullable|string|max:255',
        'phone'        => 'nullable|string|max:20',
        'email'        => 'nullable|email|max:255',
        'address'      => 'nullable|string|max:255',
        'status'       => 'required|in:active,inactive',
    ]);

    $supplier->update([
        'company_name' => $request->company_name,
        'ruc'          => $request->ruc,
        'contact_name' => $request->contact_name,
        'phone'        => $request->phone,
        'email'        => $request->email,
        'address'      => $request->address,
        'status'       => $request->status,
    ]);

    return redirect()
        ->route('suppliers.index')
        ->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
    $supplier->loadCount(['products', 'purchaseOrders']);

    if ($supplier->products_count > 0 || $supplier->purchase_orders_count > 0) {
        $supplier->update([
            'status' => 'inactive',
        ]);

        return redirect('/admin/suppliers')
            ->with('success', 'Supplier has related records, so it was marked as inactive.');
    }

    $supplier->delete();

    return redirect('/admin/suppliers')
        ->with('success', 'Supplier deleted successfully.');
    }
}