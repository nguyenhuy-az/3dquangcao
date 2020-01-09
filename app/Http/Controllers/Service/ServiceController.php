<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServiceController extends Controller
{
    public function repair()
    {
        return view('service.repair');
    }

    public function advisory()
    {
        return view('service.advisory-design');
    }
}
