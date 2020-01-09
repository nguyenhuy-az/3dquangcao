<?php

namespace App\Http\Controllers\Price;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PriceController extends Controller
{
    public function banner()
    {
        return view('price.material');
    }

    public function material()
    {
        return view('price.material');
    }
}
