<?php
namespace App\Reports;

use App\Models\Inventory;
use Illuminate\Support\Facades\Response;

class InventoryReport
{
    public function generate()
    {
        return Inventory::all();
    }

    public function download()
    {
        $inventories = $this->generate();
        $csvData = $this->toCsv($inventories);

        $filename = 'inventory_report_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return Response::make($csvData, 200, $headers);
    }

    private function toCsv($inventories)
    {
        $csvData = "ID,Item Name,Quantity,Unit Price\n";

        foreach ($inventories as $inventory) {
            $csvData .= "{$inventory->id},{$inventory->item_name},{$inventory->quantity},{$inventory->unit_price}\n";
        }

        return $csvData;
    }
}
