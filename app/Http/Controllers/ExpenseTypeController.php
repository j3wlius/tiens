<?php

namespace App\Http\Controllers;

use App\Models\ExpenseType;
use Illuminate\Http\Request;

class ExpenseTypeController extends Controller
{
    public function index()
    {
        $expenseTypes = ExpenseType::latest()->get();
        return view('expense-types.index', compact('expenseTypes'));
    }

    public function create()
    {
        return view('expense-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:expense_types',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        ExpenseType::create($validated);

        return redirect()->route('expense-types.index')
            ->with('success', 'Expense type created successfully.');
    }

    public function edit(ExpenseType $expenseType)
    {
        return view('expense-types.edit', compact('expenseType'));
    }

    public function update(Request $request, ExpenseType $expenseType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:expense_types,name,' . $expenseType->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $expenseType->update($validated);

        return redirect()->route('expense-types.index')
            ->with('success', 'Expense type updated successfully.');
    }

    public function destroy(ExpenseType $expenseType)
    {
        // Check if the expense type is being used
        if ($expenseType->expenses()->exists()) {
            return redirect()->route('expense-types.index')
                ->with('error', 'Cannot delete expense type that is being used by expenses.');
        }

        $expenseType->delete();

        return redirect()->route('expense-types.index')
            ->with('success', 'Expense type deleted successfully.');
    }

    public function toggle(ExpenseType $expenseType)
    {
        $expenseType->update([
            'is_active' => !$expenseType->is_active
        ]);

        return redirect()->route('expense-types.index')
            ->with('success', 'Expense type status updated successfully.');
    }
}
