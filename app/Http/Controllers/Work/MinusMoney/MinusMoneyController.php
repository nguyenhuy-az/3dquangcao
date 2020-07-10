<?php

namespace App\Http\Controllers\Work\MinusMoney;

use App\Models\Ad3d\MinusMoney\QcMinusMoney;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\StaffNotify\QcStaffNotify;
use App\Models\Ad3d\Work\QcWork;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use File;
use Input;
use Request;

class MinusMoneyController extends Controller
{    //phạt
    public function index($monthFilter = 0, $yearFilter = 0)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelStaffNotify = new QcStaffNotify();
        $modelWork = new QcWork();
        $dataStaff = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'object' => 'minusMoney'
        ];
        if ($hFunction->checkCount($dataStaff)) {
            $staffLoginId = $dataStaff->staffId();
            $dateFilter = null;
            if ($monthFilter == 0 && $yearFilter == 0) { //khong chon thoi gian xem
                $monthFilter = date('m');
                $yearFilter = date('Y');
                $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
            } elseif ($monthFilter == 100 && $yearFilter == null) { //xam tat ca cac thang va khong chon nam
                $yearFilter = date('Y');
                $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
            } elseif ($monthFilter > 0 && $monthFilter < 100 && $yearFilter == 100) { //co chon thang va khong chon nam
                $monthFilter = 100;
                $dateFilter = null;
            } elseif ($monthFilter > 0 && $monthFilter < 100 && $yearFilter > 100) { //co chon thang va chon nam
                $dateFilter = date('Y-m', strtotime("1-$monthFilter-$yearFilter"));
            } elseif ($monthFilter == 100 && $yearFilter == 100) { //xem tất cả
                $dateFilter = null;
            } elseif ($monthFilter == 100 && $yearFilter > 100) { //xem tất cả
                $dateFilter = date('Y', strtotime("1-1-$yearFilter"));
            } else {
                $dateFilter = date('Y-m');
                $monthFilter = date('m');
                $yearFilter = date('Y');
            }
            $dataWork = $modelWork->firstInfoOfStaff($staffLoginId, $dateFilter);
            if ($hFunction->checkCount($dataWork)) {
                $dataMinusMoney = $dataWork->infoMinusMoneyOfWork();
                $totalMinusMoney = $dataWork->totalMoneyMinus();
            } else {
                $dataMinusMoney = null;
                $totalMinusMoney = 0;
            }
            # cap nhat thong tin thong bao moi
            $modelStaffNotify->updateViewedAllOfStaffAndMinusMoney($staffLoginId);
            return view('work.bonus-minus.minus-money.list', compact('dataAccess', 'modelStaff', 'dataMinusMoney', 'totalMinusMoney', 'monthFilter', 'yearFilter'));
        } else {
            return redirect()->route('qc.work.login.get');
        }
    }

    #------------ ------- phan hoi -------------- --------------
    // xem anh phan hoi
    public function getViewImage($minusId)
    {
        $modelMinusMoney = new QcMinusMoney();
        $dataMinusMoney = $modelMinusMoney->getInfo($minusId);
        return view('work.bonus-minus.minus-money.view-image', compact('dataAccess', 'dataMinusMoney'));
    }

    public function getFeedback($minusId)
    {
        $modelMinusMoney = new QcMinusMoney();
        $dataMinusMoney = $modelMinusMoney->getInfo($minusId);
        return view('work.bonus-minus.minus-money.feedback', compact('dataAccess', 'modelStaff', 'dataMinusMoney'));
    }

    public function postFeedback()
    {
        $hFunction = new \Hfunction();
        $modelMinusMoney = new QcMinusMoney();
        $txtMinusId = Request::input('txtMinusId');
        $txtFeedbackContent = Request::input('txtFeedbackContent');
        $txtFeedbackImage = Request::file('txtFeedbackImage');
        //$dataMinusMoney = $modelMinusMoney->getInfo($txtMinusId);
        if (!empty($txtFeedbackImage)) {
            $name_img = stripslashes($_FILES['txtFeedbackImage']['name']);
            $name_img = $hFunction->getTimeCode() . '.' . $hFunction->getTypeImg($name_img);
            $source_img = $_FILES['txtFeedbackImage']['tmp_name'];
            if (!$modelMinusMoney->uploadImage($source_img, $name_img, 500)) {
                $name_img = null;
            }
        } else {
            $name_img = null;
        }
        $modelMinusMoney->updateFeedback($txtMinusId, $txtFeedbackContent, $name_img);

    }

    # huy phan hoi
    public function cancelFeedback($minusMoneyId)
    {
        $modelMinusMoney = new QcMinusMoney();
        $modelMinusMoney->cancelFeedback($minusMoneyId);
    }

}
