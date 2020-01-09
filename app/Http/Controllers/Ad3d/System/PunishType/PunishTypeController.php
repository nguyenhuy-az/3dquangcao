<?php

namespace App\Http\Controllers\Ad3d\System\PunishType;

use App\Models\Ad3d\PaymentType\QcPaymentType;
use App\Models\Ad3d\PunishType\QcPunishType;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Input;
use File;
use DB;
use Request;

class PunishTypeController extends Controller
{
    public function index()
    {
        $modelStaff = new QcStaff();
        $dataAccess = [
            'accessObject' => 'punishType'
        ];
        $dataPunishType = QcPunishType::orderBy('name', 'ASC')->select('*')->paginate(30);
        return view('ad3d.system.punish-type.list', compact('modelStaff', 'dataPunishType', 'dataAccess'));

    }

    public function view($typeId)
    {
        $modelPunishType = new QcPunishType();
        if (!empty($typeId)) {
            $dataPunishType = $modelPunishType->getInfo($typeId);
            return view('ad3d.system.punish-type.view', compact('dataPunishType'));
        }
    }

    //add
    public function getAdd()
    {
        $modelStaff = new QcStaff();
        $dataAccess = [
            'accessObject' => 'punishType'
        ];
        return view('ad3d.system.punish-type.add', compact('modelStaff','dataAccess'));
    }

    public function postAdd()
    {
        $modelPunishType = new QcPunishType();
        $name = Request::input('txtName');
        // check exist of name
        if ($modelPunishType->existName($name)) {
            Session::put('notifyAdd', "Thêm thất bại <b>'$name'</b> đã tồn tại.");
        } else {
            if ($modelPunishType->insert($name)) {
                Session::put('notifyAdd', 'Thêm thành công, Nhập thông tin để tiếp tục');
            } else {
                Session::put('notifyAdd', 'Thêm thất bại, Nhập thông tin để tiếp tục');
            }
        }
    }

    //edit
    public function getEdit($typeId)
    {
        $modelStaff = new QcStaff();
        $modelPunishType = new QcPunishType();
        $dataPunishType = $modelPunishType->getInfo($typeId);
        if (count($dataPunishType) > 0) {
            return view('ad3d.system.punish-type.edit', compact('modelStaff','dataPunishType'));
        }
    }

    public function postEdit($typeId)
    {
        $modelPunishType = new QcPunishType();
        $name = Request::input('txtName');
        if ($modelPunishType->existEditName($typeId, $name)) {
            return "Tên <b>'$name'</b> đã tồn tại.";
        } else {
            $modelPunishType->updateInfo($typeId, $name);
        }

    }

    // delete
    public function deleteInfo($typeId)
    {
        if (!empty($typeId)) {
            $modelPunishType = new QcPunishType();
            $modelPunishType->deleteInfo($typeId);
        }
    }
}
