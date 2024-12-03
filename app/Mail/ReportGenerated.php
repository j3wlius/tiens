<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class ReportGenerated extends Mailable
{
    use Queueable, SerializesModels;

    protected $report;

    public function __construct($report)
    {
        $this->report = $report;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Scheduled Report is Ready',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reports.generated',
            with: [
                'report' => $this->report,
            ],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->report['pdf'], 'report.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
