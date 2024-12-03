<?php

namespace App\Jobs;

use App\Models\ScheduledReport;
use App\Services\ReportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReportGenerated;

class GenerateScheduledReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $scheduledReport;

    public function __construct(ScheduledReport $scheduledReport)
    {
        $this->scheduledReport = $scheduledReport;
    }

    public function handle(ReportService $reportService): void
    {
        // todo: generate the report based on type
        $report = match($this->scheduledReport->type) {
            'expense_summary' => $reportService->generateExpenseSummary($this->scheduledReport->filters),
            'monthly_expense' => $reportService->generateMonthlyExpense($this->scheduledReport->filters),
            'distributor_summary' => $reportService->generateDistributorSummary($this->scheduledReport->filters),
            default => throw new \Exception('Invalid report type'),
        };

        // todo: send email to all recipients
        foreach ($this->scheduledReport->recipients as $recipient) {
            Mail::to($recipient)->send(new ReportGenerated($report));
        }

        // todo: update the scheduled report timestamps
        $this->scheduledReport->update([
            'last_sent_at' => now(),
            'next_scheduled_at' => $this->calculateNextScheduledTime(),
        ]);
    }

    protected function calculateNextScheduledTime(): \Carbon\Carbon
    {
        $now = now();
        
        return match($this->scheduledReport->frequency) {
            'daily' => $now->addDay(),
            'weekly' => $now->addWeek(),
            'monthly' => $now->addMonth(),
            'quarterly' => $now->addQuarter(),
            'yearly' => $now->addYear(),
            default => throw new \Exception('Invalid frequency'),
        };
    }
}
