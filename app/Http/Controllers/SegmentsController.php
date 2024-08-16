<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SegmentsController extends Controller
{
    public function index()
    {
        return view('customers.segments');
    }
}
