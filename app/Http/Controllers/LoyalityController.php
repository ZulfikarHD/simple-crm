<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoyalityController extends Controller
{
    public function index()
    {
        return view('customers.loyality');
    }
}
