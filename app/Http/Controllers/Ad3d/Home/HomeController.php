<?php

namespace App\Http\Controllers\Ad3d\Home;

use App\Models\Ad3d\Staff\QcStaff;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $modelStaff = new QcStaff();
        return view('ad3d.panel', compact('modelStaff'));
    }
}
