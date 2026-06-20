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

        Supplier::create([
            'company_name' => $request->company_name,
            'ruc'          => $request->ruc,
            'contact_name' => $request->contact_name,
            'phone'        => $request->phone,
            'email'        => $request->email,
            'address'      => $request->address,
            'status'       => $request->status ?? 'active',
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
            'company_name' => 'required',
            'ruc'          => 'required|unique:suppliers,ruc,' . $supplier->id,
        ]);

        $supplier->update([
            'company_name' => $request->company_name,
            'ruc'          => $request->ruc,
            'contact_name' => $request->contact_name,
            'phone'        => $request->phone,
            'email'        => $request->email,
            'address'      => $request->address,
            'status'       => $request->status ?? 'active',
        ]);

        return redirect('/admin/suppliers')->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect('/admin/suppliers')->with('success', 'Supplier deleted successfully.');
    }
}