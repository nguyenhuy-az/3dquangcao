<?php

namespace App\Http\Controllers\Ad3d\Store\Supplies\Supplies;

//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Supplies\QcSupplies;
use Illuminate\Support\Facades\Session;
use Input;
use File;
use DB;
use Request;

class SuppliesController extends Controller
{
    public function index($companyFilterId = null)
    {
        $modelStaff = new QcStaff();
        $modelSupplies = new QcSupplies();
        $modelCompany = new QcCompany();
        $dataCompany = $modelCompany->getInfo();
        $dataAccess = [
            'accessObject' => 'supplies',
            'companyFilterId' => $companyFilterId
        ];
        $dataSupplies = $modelSupplies->selectAllInfo()->paginate(30);
        return view('ad3d.store.supplies.supplies.list', compact('modelStaff', 'modelCompany', 'dataCompany', 'dataSupplies', 'dataAccess'));

    }

    public function view($suppliesId)
    {
        $modelSupplies = new QcSupplies();
        if (!empty($suppliesId)) {
            $dataSupplies = $modelSupplies->getInfo($suppliesId);
            return view('ad3d.store.supplies.supplies.view', compact('dataSupplies'));
        }
    }

    //add
    public function getAdd()
    {
        $modelStaff = new QcStaff();
        $dataAccess = [
            'accessObject' => 'supplies'
        ];
        return view('ad3d.store.supplies.supplies.add', compact('modelStaff','dataAccess'));
    }

    public function postAdd()
    {
        $modelSupplies = new QcSupplies();
        $name = Request::input('txtName');
        $unit = Request::input('txtUnit');
        // kiểm tra tồn tại vật tư
        if ($modelSupplies->existName($name)) {
            Session::put('notifyAdd', "Thêm thất bại <b>'$name'</b> đã tồn tại.");
        } else {
            if ($modelSupplies->insert($name, $unit)) {
                Session::put('notifyAdd', 'Thêm thành công, Nhập thông tin để tiếp tục');
            } else {
                Session::put('notifyAdd', 'Thêm thất bại, Nhập thông tin để tiếp tục');
            }
        }
    }

    //sửa thông tin
    public function getEdit($suppliesId)
    {
        $modelSupplies = new QcSupplies();
        $dataSupplies = $modelSupplies->getInfo($suppliesId);
        if (count($dataSupplies) > 0) {
            return view('ad3d.store.supplies.supplies.edit', compact('dataSupplies'));
        }
    }

    public function postEdit($suppliesId)
    {
        $dataSupplies = new QcSupplies();
        $name = Request::input('txtName');
        $unit = Request::input('txtUnit');
        $notifyContent = null;
        if ($dataSupplies->existEditName($suppliesId, $name)) {
            $notifyContent = "Tên <b>'$name'</b> đã tồn tại.";
        }
        if (!empty($notifyContent)) {
            return $notifyContent;
        } else {
            $dataSupplies->updateInfo($suppliesId, $name,$unit);
        }
    }

    // xóa
    public function deleteSupplies($suppliesId)
    {
        if (!empty($suppliesId)) {
            $dataSupplies = new QcSupplies();
            $dataSupplies->deleteSupplies($suppliesId);
        }
    }
}
