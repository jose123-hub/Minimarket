<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\SaleReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnApprovalController extends Controller
{
    public function index()
    {
        $returns = SaleReturn::with(['sale.cashier', 'user', 'details.product'])
            ->latest()
            ->get();

        return view('admin.returns.index', compact('returns'));
    }

    public function approve(SaleReturn $return)
    {
        if ($return->status !== 'pending') {
            return back()->with('error', 'Only pending returns can be approved.');
        }

        DB::beginTransaction();
        try {
            foreach ($return->details as $detail) {
                Product::where('id', $detail->product_id)->increment('stock', $detail->quantity);
            }

            $return->update(['status' => 'approved']);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Could not approve the return. Please try again.');
        }

        return back()->with('success', "Return #{$return->id} approved — stock has been restored.");
    }

    public function reject(Request $request, SaleReturn $return)
    {
        if ($return->status !== 'pending') {
            return back()->with('error', 'Only pending returns can be rejected.');
        }

        $return->update(['status' => 'rejected']);

        return back()->with('success', "Return #{$return->id} rejected. No stock or stars were changed.");
    }
}