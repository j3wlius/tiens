<?php

namespace App\Console\Commands;

use App\Jobs\GenerateScheduledReport;
use App\Models\ScheduledReport;
use Illuminate\Console\Command;

class ProcessScheduledReports extends Command
{
    protected $signature = 'reports:process-scheduled';
    protected $description = 'Process all scheduled reports that are due';

    public function handle()
    {
        $dueReports = ScheduledReport::query()
            ->where('is_active', true)
            ->where('next_scheduled_at', '<=', now())
            ->get();

        $count = 0;
        foreach ($dueReports as $report) {
            GenerateScheduledReport::dispatch($report);
            $count++;
        }

        $this->info("Dispatched {$count} scheduled reports for processing.");
    }
}
