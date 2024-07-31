<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reports\CustomerReport;
use App\Reports\OrderReport;
use App\Reports\InventoryReport;
use App\Reports\InvoiceReport;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function show($reportType)
    {
        switch ($reportType) {
            case 'customers':
                $report = new CustomerReport();
                break;
            case 'orders':
                $report = new OrderReport();
                break;
            case 'inventories':
                $report = new InventoryReport();
                break;
            case 'invoices':
                $report = new InvoiceReport();
                break;
            default:
                abort(404);
        }

        $data = $report->generate();

        return view('reports.show', compact('reportType', 'data'));
    }

    public function download($reportType)
    {
        switch ($reportType) {
            case 'customers':
                $report = new CustomerReport();
                break;
            case 'orders':
                $report = new OrderReport();
                break;
            case 'inventories':
                $report = new InventoryReport();
                break;
            case 'invoices':
                $report = new InvoiceReport();
                break;
            default:
                abort(404);
        }

        return $report->download();
    }
}
