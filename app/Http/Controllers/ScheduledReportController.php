<?php

namespace App\Http\Controllers;

use App\Models\ScheduledReport;
use Illuminate\Http\Request;

class ScheduledReportController extends Controller
{
    public function index()
    {
        $reports = ScheduledReport::latest()->paginate(10);
        return view('reports.scheduled.index', compact('reports'));
    }

    public function create()
    {
        return view('reports.scheduled.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:expense_summary,monthly_expense,distributor_summary',
            'frequency' => 'required|string|in:daily,weekly,monthly,quarterly,yearly',
            'recipients' => 'required|array',
            'recipients.*' => 'email',
            'filters' => 'nullable|array',
        ]);

        $validated['next_scheduled_at'] = now()->addDay();
        
        ScheduledReport::create($validated);

        return redirect()->route('scheduled-reports.index')
            ->with('success', 'Scheduled report created successfully.');
    }

    public function edit(ScheduledReport $scheduledReport)
    {
        return view('reports.scheduled.edit', compact('scheduledReport'));
    }

    public function update(Request $request, ScheduledReport $scheduledReport)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:expense_summary,monthly_expense,distributor_summary',
            'frequency' => 'required|string|in:daily,weekly,monthly,quarterly,yearly',
            'recipients' => 'required|array',
            'recipients.*' => 'email',
            'filters' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $scheduledReport->update($validated);

        return redirect()->route('scheduled-reports.index')
            ->with('success', 'Scheduled report updated successfully.');
    }

    public function destroy(ScheduledReport $scheduledReport)
    {
        $scheduledReport->delete();

        return redirect()->route('scheduled-reports.index')
            ->with('success', 'Scheduled report deleted successfully.');
    }

    public function toggle(ScheduledReport $scheduledReport)
    {
        $scheduledReport->update([
            'is_active' => !$scheduledReport->is_active
        ]);

        return redirect()->route('scheduled-reports.index')
            ->with('success', 'Report schedule status updated successfully.');
    }
}
