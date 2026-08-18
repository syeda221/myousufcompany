<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        $categories = ExpenseCategory::all();
        return view('admin_panel.expense_categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:expense_categories,name,' . $request->edit_id . ',id',
            'code' => 'nullable|string|max:50',
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

        if ($request->filled('edit_id')) {
            $category = ExpenseCategory::findOrFail($request->edit_id);
            $category->update([
                'name' => $request->name,
                'code' => $request->code,
                'description' => $request->description,
            ]);

            return response()->json([
                'success' => 'Expense Category Updated Successfully',
                'reload'  => true
            ]);
        }

        $newCategory = ExpenseCategory::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'category' => $newCategory,
                'message' => 'Expense Category Created Successfully'
            ]);
        }

        return response()->json([
            'success'  => 'Expense Category Created Successfully',
            'redirect' => route('expense_categories.index')
        ]);
    }

    public function delete($id)
    {
        $category = ExpenseCategory::find($id);
        if ($category) {
            $category->delete();
            $msg = [
                'success' => 'Expense Category Deleted Successfully',
                'reload' =>  route('expense_categories.index'),
            ];
        } else {
            $msg = ['error' => 'Expense Category Not Found'];
        }
        return response()->json($msg);
    }
}
