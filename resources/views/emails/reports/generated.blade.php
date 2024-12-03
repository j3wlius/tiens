<x-mail::message>
# Your Scheduled Report is Ready

Your requested report has been generated and is attached to this email. You can find a summary of the report below:

<x-mail::panel>
Report Type: {{ $report['type'] }}
Generated On: {{ now()->format('F j, Y H:i:s') }}
Period: {{ $report['period'] }}
</x-mail::panel>

## Key Metrics
@if(isset($report['metrics']))
<x-mail::table>
| Metric | Value |
|--------|--------|
@foreach($report['metrics'] as $metric => $value)
| {{ $metric }} | {{ $value }} |
@endforeach
</x-mail::table>
@endif

Please find the detailed report in the attached PDF file.

<x-mail::button :url="route('reports.index')">
View All Reports
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
