<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InventoryReportController extends Controller
{
    public function index()
    {
        return view('reports.inventory');
    }
}
