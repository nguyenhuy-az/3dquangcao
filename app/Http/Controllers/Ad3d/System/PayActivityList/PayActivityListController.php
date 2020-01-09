<?php

namespace App\Http\Controllers\Ad3d\System\PayActivityList;

use App\Models\Ad3d\PayActivityList\QcPayActivityList;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Input;
use File;
use DB;
use Request;

class PayActivityListController extends Controller
{
    public function index()
    {
        $modelStaff = new QcStaff();
        $modelPayActivityList = new QcPayActivityList();
        $dataAccess = [
            'accessObject' => 'paymentType'
        ];
        $dataPayActivityList = $modelPayActivityList->selectInfo()->paginate(30);
        return view('ad3d.system.pay-activity-list.list', compact('modelStaff', 'dataPayActivityList', 'dataAccess'));

    }

    public function view($payListId)
    {
        $modelPayActivityList = new QcPayActivityList();
        if (!empty($payListId)) {
            $dataPayActivityList = $modelPayActivityList->getInfo($payListId);
            return view('ad3d.system.pay-activity-list.view', compact('dataPayActivityList'));
        }
    }

    //add
    public function getAdd()
    {
        $modelStaff = new QcStaff();
        $dataAccess = [
            'accessObject' => 'paymentType'
        ];
        return view('ad3d.system.pay-activity-list.add', compact('modelStaff','dataAccess'));
    }

    public function postAdd()
    {
        $modelPayActivityList = new QcPayActivityList();
        $name = Request::input('txtName');
        $cbType = Request::input('cbType');
        $txtDescription = Request::input('txtDescription');
        // check exist of name
        if ($modelPayActivityList->existName($name)) {
            Session::put('notifyAdd', "Thêm thất bại <b>'$name'</b> đã tồn tại.");
        } else {
            if ($modelPayActivityList->insert($name, $txtDescription, $cbType)) {
                Session::put('notifyAdd', 'Thêm thành công, Nhập thông tin để tiếp tục');
            } else {
                Session::put('notifyAdd', 'Thêm thất bại, Nhập thông tin để tiếp tục');
            }
        }
    }

    //edit
    public function getEdit($payListId)
    {
        $modelStaff = new QcStaff();
        $modelPayActivityList = new QcPayActivityList();
        $dataPayActivityList = $modelPayActivityList->getInfo($payListId);
        if (count($dataPayActivityList) > 0) {
            return view('ad3d.system.pay-activity-list.edit', compact('modelStaff','dataPayActivityList'));
        }
    }

    public function postEdit($payListId)
    {
        $modelPayActivityList = new QcPayActivityList();
        $name = Request::input('txtName');
        $cbType = Request::input('cbType');
        $txtDescription = Request::input('txtDescription');
        if ($modelPayActivityList->existEditName($payListId, $name)) {
            return "Tên <b>'$name'</b> đã tồn tại.";
        } else {
            $modelPayActivityList->updateInfo($payListId, $name, $txtDescription, $cbType);
        }

    }

    // delete
    public function deletePayList($payListId)
    {
        $modelPayActivityList = new QcPayActivityList();
        $modelPayActivityList->actionDelete($payListId);
    }
}
