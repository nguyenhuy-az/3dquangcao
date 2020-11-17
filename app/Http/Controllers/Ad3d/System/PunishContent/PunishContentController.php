<?php

namespace App\Http\Controllers\Ad3d\System\PunishContent;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\Payment\QcPayment;
use App\Models\Ad3d\PaymentType\QcPaymentType;
use App\Models\Ad3d\PunishContent\QcPunishContent;
use App\Models\Ad3d\PunishType\QcPunishType;
use App\Models\Ad3d\Staff\QcStaff;
use Illuminate\Support\Facades\Session;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Input;
use Request;

class PunishContentController extends Controller
{
    public function index($punishTypeId = null)
    {
        $modelStaff = new QcStaff();
        $modelPunishType = new QcPunishType();
        $modelPunishContent = new QcPunishContent();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'accessObject' => 'punishContent'
        ];

        if (empty($punishTypeId)) {
            $selectPunishContent = $modelPunishContent->selectInfoAll();
        } else {
            $selectPunishContent = $modelPunishContent->selectInfoByPunishType($punishTypeId);
        }

        $dataPunishContent = $selectPunishContent->paginate(30);
        return view('ad3d.system.punish-content.list', compact('modelStaff', 'modelPunishType', 'dataPunishContent', 'dataAccess', 'punishTypeId'));

    }

    public function view($punishId)
    {
        $modelPunishContent = new QcPunishContent();
        $dataPunishContent = $modelPunishContent->getInfo($punishId);
        if (count($dataPunishContent) > 0) {
            return view('ad3d.system.punish-content.view', compact('dataPunishContent'));
        }
    }

    public function getAdd()
    {
        $modelStaff = new QcStaff();
        $modelPunishType = new QcPunishType();
        $modelPunishContent = new QcPunishContent();
        $dataAccess = [
            'accessObject' => 'punishContent'
        ];
        return view('ad3d.system.punish-content.add', compact('modelStaff', 'modelPunishType', 'modelPunishContent', 'dataAccess'));
    }

    public function postAdd()
    {
        $modelPunishContent = new QcPunishContent();
        $cbPunishTypeId = Request::input('cbPunishType');
        $txtName = Request::input('txtName');
        $txtMoney = Request::input('txtMoney');
        $txtNote = Request::input('txtNote');
        if ($modelPunishContent->existName($txtName)) {
            Session::put('notifyAdd', "Thêm thất bại <b>'$txtName'</b> đã tồn tại.");
        } else {
            if ($modelPunishContent->insert($txtName, $txtMoney, $txtNote, $cbPunishTypeId)) {
                return Session::put('notifyAdd', 'Thêm thành công, chọn thông tin và tiếp tục');
            } else {
                return Session::put('notifyAdd', 'Thêm thất bại, hãy thử lại');
            }
        }

    }

    # cap nhat thong tin
    public function getEdit($punishId)
    {
        $modelStaff = new QcStaff();
        $modelPunishContent = new QcPunishContent();
        $modelPunishType = new QcPunishType();
        $dataPunishContent = $modelPunishContent->getInfo($punishId);
        if (count($dataPunishContent) > 0) {
            return view('ad3d.system.punish-content.edit', compact('modelPunishContent', 'modelStaff', 'modelPunishType', 'dataPunishContent'));
        }

    }

    public function postEdit($punishId)
    {
        $modelPunishContent = new QcPunishContent();
        $cbPunishType = Request::input('cbPunishType');
        $txtName = Request::input('txtName');
        $txtMoney = Request::input('txtMoney');
        $txtNote = Request::input('txtNote');
        if (!$modelPunishContent->updateInfo($punishId, $txtName, $txtMoney, $txtNote, $cbPunishType)) {
            return "Cập nhật thất bại";
        }

    }

    # cap nhat trang thai ap dung
    public function updateApplyStatus($punishId, $applyStatus)
    {
        $modelPunishContent = new QcPunishContent();
        return $modelPunishContent->updateApplyStatus($punishId, $applyStatus);
    }

    # xoa thong tin
    public function deleteInfo($punishId)
    {
        $modelPunishContent = new QcPunishContent();
        $modelPunishContent->deleteInfo($punishId);
    }

}
