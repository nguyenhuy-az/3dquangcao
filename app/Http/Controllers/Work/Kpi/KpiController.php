<?php

namespace App\Http\Controllers\Work\Kpi;

use App\Models\Ad3d\Kpi\QcKpi;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use File;
use Input;
use Request;

class KpiController extends Controller
{
    //đề suất ứng
    public function getRegister($kpiSelectedId = null)
    {
        $modelStaff = new QcStaff();
        $modelKpi = new QcKpi();
        $dataStaff = $modelStaff->loginStaffInfo();
        if (count($dataStaff) > 0) {
            $dataKpi = $modelKpi->selectActivityInfo()->get();
            if (!empty($kpiSelectedId)) {
                $dataKpiSelected = $modelKpi->getInfo($kpiSelectedId);
            } else {
                $dataKpiSelected = null;
            }
            return view('work.kpi.register', compact('modelStaff','dataKpi', 'dataKpiSelected'));

        } else {
            return redirect()->route('qc.work.login.get');
        }

    }

    public function postRegister()
    {
        $modelStaff = new QcStaff();
        /*$modelSalaryBeforePayRequest = new QcSalaryBeforePayRequest();
        $workId = Request::input('txtWork');
        $txtMoneyRequest = Request::input('txtMoneyRequest');
        $modelSalaryBeforePayRequest->insert($txtMoneyRequest, $workId);*/

    }

    /* public function deleteBeforePayRequest($requestId)
     {
         $modelSalaryBeforePayRequest = new QcSalaryBeforePayRequest();
         $modelSalaryBeforePayRequest->deleteInfo($requestId);
     }*/
}
