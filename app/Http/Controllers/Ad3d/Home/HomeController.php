<?php

namespace App\Http\Controllers\Ad3d\Home;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Statistical\QcStatistical;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $modelStatistical = new QcStatistical();
        $modelStaff = new QcStaff();
        return view('ad3d.panel', compact('modelStatistical', 'modelStaff'));
    }
}
