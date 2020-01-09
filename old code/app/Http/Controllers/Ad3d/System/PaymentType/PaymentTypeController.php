<?php

namespace App\Http\Controllers\Ad3d\System\PaymentType;

use App\Models\Ad3d\PaymentType\QcPaymentType;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Input;
use File;
use DB;
use Request;

class PaymentTypeController extends Controller
{
    public function index()
    {
        $modelStaff = new QcStaff();
        $dataAccess = [
            'accessObject' => 'paymentType'
        ];
        $dataPaymentType = QcPaymentType::where('action', 1)->orderBy('name', 'ASC')->select('*')->paginate(30);
        return view('ad3d.system.payment-type.list', compact('modelStaff', 'dataPaymentType', 'dataAccess'));

    }

    public function view($typeId)
    {
        $modelPaymentType = new QcPaymentType();
        if (!empty($typeId)) {
            $dataPaymentType = $modelPaymentType->getInfo($typeId);
            return view('ad3d.system.payment-type.view', compact('dataPaymentType'));
        }
    }

    //add
    public function getAdd()
    {
        $dataAccess = [
            'accessObject' => 'paymentType'
        ];
        return view('ad3d.system.payment-type.add', compact('dataAccess'));
    }

    public function postAdd()
    {
        $modelPaymentType = new QcPaymentType();
        $name = Request::input('txtName');
        // check exist of name
        if ($modelPaymentType->existName($name)) {
            Session::put('notifyAdd', "Thêm thất bại <b>'$name'</b> đã tồn tại.");
        } else {
            if ($modelPaymentType->insert($name)) {
                Session::put('notifyAdd', 'Thêm thành công, Nhập thông tin để tiếp tục');
            } else {
                Session::put('notifyAdd', 'Thêm thất bại, Nhập thông tin để tiếp tục');
            }
        }
    }

    //edit
    public function getEdit($typeId)
    {
        $modelPaymentType = new QcPaymentType();
        $dataPaymentType = $modelPaymentType->getInfo($typeId);
        if (count($dataPaymentType) > 0) {
            return view('ad3d.system.payment-type.edit', compact('dataPaymentType'));
        }
    }

    public function postEdit($typeId)
    {
        $modelPaymentType = new QcPaymentType();
        $name = Request::input('txtName');
        if ($modelPaymentType->existEditName($typeId, $name)) {
            return "Tên <b>'$name'</b> đã tồn tại.";
        } else {
            $modelPaymentType->updateInfo($typeId, $name);
        }

    }

    // delete
    public function deleteCompany($typeId)
    {
        /*
        if (!empty($typeId)) {
            $modelPaymentType = new QcPaymentType();
            $modelPaymentType->actionDelete($typeId);
        }
        */
    }
}
