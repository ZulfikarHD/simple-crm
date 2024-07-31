<?php
namespace App\Reports;

use App\Models\Invoice;
use Illuminate\Support\Facades\Response;

class InvoiceReport
{
    public function generate()
    {
        return Invoice::with('order.customer')->get();
    }

    public function download()
    {
        $invoices = $this->generate();
        $csvData = $this->toCsv($invoices);

        $filename = 'invoice_report_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return Response::make($csvData, 200, $headers);
    }

    private function toCsv($invoices)
    {
        $csvData = "ID,Customer,Issue Date,Due Date,Amount,Status\n";

        foreach ($invoices as $invoice) {
            $csvData .= "{$invoice->id},{$invoice->order->customer->name},{$invoice->issue_date},{$invoice->due_date},{$invoice->amount},{$invoice->status}\n";
        }

        return $csvData;
    }
}
