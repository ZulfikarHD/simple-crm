<?php

namespace App\Reports;

use App\Models\Customer;
use Illuminate\Support\Facades\Response;

class CustomerReport
{
    public function generate()
    {
        return Customer::all();
    }

    public function download()
    {
        $customers = $this->generate();
        $csvData = $this->toCsv($customers);

        $filename = 'customer_report_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return Response::make($csvData, 200, $headers);
    }

    private function toCsv($customers)
    {
        $csvData = "ID,Name,Address,Phone Number,Email\n";

        foreach ($customers as $customer) {
            $csvData .= "{$customer->id},{$customer->name},{$customer->address},{$customer->phone_number},{$customer->email}\n";
        }

        return $csvData;
    }
}
