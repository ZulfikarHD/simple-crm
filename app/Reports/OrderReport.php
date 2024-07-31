<?php
namespace App\Reports;

use App\Models\Order;
use Illuminate\Support\Facades\Response;

class OrderReport
{
    public function generate()
    {
        return Order::with('customer')->get();
    }

    public function download()
    {
        $orders = $this->generate();
        $csvData = $this->toCsv($orders);

        $filename = 'order_report_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return Response::make($csvData, 200, $headers);
    }

    private function toCsv($orders)
    {
        $csvData = "ID,Customer,Service Date,Status,Total Amount\n";

        foreach ($orders as $order) {
            $csvData .= "{$order->id},{$order->customer->name},{$order->service_date},{$order->status},{$order->total_amount}\n";
        }

        return $csvData;
    }
}
