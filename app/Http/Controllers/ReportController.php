<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function expenseSummary(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        // Get total expenses
        $totalExpenses = Expense::whereBetween('date', [$startDate, $endDate])
            ->sum('amount');

        // Get expenses by type
        $expensesByType = Expense::whereBetween('date', [$startDate, $endDate])
            ->select('expense_type', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
            ->groupBy('expense_type')
            ->get();

        // Get daily expenses for chart
        $dailyExpenses = Expense::whereBetween('date', [$startDate, $endDate])
            ->select(DB::raw('DATE(date) as date'), DB::raw('SUM(amount) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('reports.expense-summary', compact(
            'totalExpenses',
            'expensesByType',
            'dailyExpenses',
            'startDate',
            'endDate'
        ));
    }

    public function distributorSummary()
    {
        // Get total distributors count
        $totalDistributors = Distributor::count();

        // Get distributors by region
        $distributorsByRegion = Distributor::select('residence', DB::raw('COUNT(*) as count'))
            ->groupBy('residence')
            ->get();

        // Get recent distributors
        $recentDistributors = Distributor::latest()
            ->take(5)
            ->get();

        return view('reports.distributor-summary', compact(
            'totalDistributors',
            'distributorsByRegion',
            'recentDistributors'
        ));
    }

    public function monthlyExpenseReport(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);

        $expenses = Expense::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date')
            ->get();

        $totalAmount = $expenses->sum('amount');
        $averageAmount = $expenses->avg('amount');
        $maxExpense = $expenses->max('amount');
        $minExpense = $expenses->min('amount');

        // Group expenses by type
        $expensesByType = $expenses->groupBy('expense_type')
            ->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'total' => $group->sum('amount'),
                    'average' => $group->avg('amount')
                ];
            });

        // Daily totals for chart
        $dailyTotals = $expenses->groupBy(function($expense) {
            return $expense->date->format('Y-m-d');
        })->map(function ($group) {
            return $group->sum('amount');
        });

        return view('reports.monthly-expense', compact(
            'expenses',
            'totalAmount',
            'averageAmount',
            'maxExpense',
            'minExpense',
            'expensesByType',
            'dailyTotals',
            'year',
            'month'
        ));
    }

    public function exportExpenseReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:csv,pdf',
        ]);

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        $expenses = Expense::with('creator')
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->get();

        if ($request->format === 'csv') {
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="expense-report.csv"',
            ];

            $callback = function() use ($expenses) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Date', 'Type', 'Received By', 'Description', 'Amount', 'Created By']);

                foreach ($expenses as $expense) {
                    fputcsv($file, [
                        $expense->date->format('Y-m-d'),
                        $expense->expense_type,
                        $expense->received_by,
                        $expense->description,
                        $expense->amount,
                        $expense->creator->name,
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        // For PDF format, you'll need to install a PDF package like dompdf
        // Implementation will depend on the package you choose
    }
}
