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
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'accessObject' => 'punishContent'
        ];

        if (empty($punishTypeId)) {
            $dataPunishContent = QcPunishContent::orderBy('name', 'ASC')->select('*')->paginate(30);
        } else {
            $dataPunishContent = QcPunishContent::where('type_id', $punishTypeId)->orderBy('name', 'ASC')->select('*')->paginate(30);
        }

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
        $modelStaff = new QcStaff();
        $modelPunishContent = new QcPunishContent();
        $cbPunishTypeId = Request::input('cbPunishType');
        $txtPunishName = Request::input('txtPunishName');
        $txtName = Request::input('txtName');
        $txtMoney = Request::input('txtMoney');
        $txtNote = Request::input('txtNote');

        if ($modelPunishContent->existPunishCode($txtPunishName)) {
            Session::put('notifyAdd', "Thêm thất bại, Mã <b>'$txtPunishName'</b> đã tồn tại.");
        }else{
            if ($modelPunishContent->existName($txtName)) {
                Session::put('notifyAdd', "Thêm thất bại <b>'$txtName'</b> đã tồn tại.");
            } else {
                if ($modelPunishContent->insert($txtPunishName, $txtName, $txtMoney, $txtNote, $cbPunishTypeId)) {
                    return Session::put('notifyAdd', 'Thêm thành công, chọn thông tin và tiếp tục');
                } else {
                    return Session::put('notifyAdd', 'Thêm thất bại, hãy thử lại');
                }
            }
        }

    }

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

    public function deleteInfo($punishId)
    {
        $modelPunishContent = new QcPunishContent();
        $modelPunishContent->deleteInfo($punishId);
    }

}
