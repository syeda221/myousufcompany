<?php

namespace App\Http\Controllers;

use App\Models\CustomerType;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerTypeController extends Controller
{
    public function index()
    {
        $types = CustomerType::all();
        return view('admin_panel.customer_types.index', compact('types'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:customer_types,name,' . $request->edit_id,
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', $validator->errors()->first());
        }

        // UPDATE
        if ($request->filled('edit_id')) {
            $type = CustomerType::find($request->edit_id);

            if (!$type) {
                return response()->json([
                    'error' => 'Customer Type not found'
                ], 404);
            }

            if ($type->is_static) {
                return response()->json([
                    'error' => 'Cannot update static customer types.'
                ], 422);
            }

            $type->name = $request->name;
            $type->description = $request->description;
            $type->save();

            $message = 'Customer Type Updated Successfully';
        }
        // CREATE
        else {
            $type = new CustomerType();
            $type->name = $request->name;
            $type->description = $request->description;
            $type->is_static = false;
            $type->save();

            $message = 'Customer Type Created Successfully';
        }

        return response()->json([
            'success' => $message,
            'reload' => true
        ]);
    }

    public function destroy($id)
    {
        $type = CustomerType::find($id);

        if (!$type) {
            return response()->json(['error' => 'Customer Type not found'], 404);
        }

        if ($type->is_static) {
            return response()->json(['error' => 'Cannot delete static customer types.'], 422);
        }

        // Check if customers are associated with this type
        $hasCustomers = Customer::where('customer_type', $type->name)->exists();
        if ($hasCustomers) {
            return response()->json(['error' => 'Cannot delete this type because there are customers associated with it.'], 422);
        }

        $type->delete();

        return response()->json([
            'success' => 'Customer Type Deleted Successfully',
            'reload' => route('customer-types.index')
        ]);
    }
}
