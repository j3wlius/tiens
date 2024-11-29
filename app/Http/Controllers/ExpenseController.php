<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with(['creator', 'expenseType'])->latest()->get();
        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        $expenseTypes = ExpenseType::active()->orderBy('name')->get();
        return view('expenses.create', compact('expenseTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'expense_type_id' => 'required|exists:expense_types,id',
            'received_by' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0'
        ]);

        $validated['created_by'] = Auth::id();

        Expense::create($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense created successfully.');
    }

    public function edit(Expense $expense)
    {
        $expenseTypes = ExpenseType::active()->orderBy('name')->get();
        return view('expenses.edit', compact('expense', 'expenseTypes'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'expense_type_id' => 'required|exists:expense_types,id',
            'received_by' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0'
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }
}
