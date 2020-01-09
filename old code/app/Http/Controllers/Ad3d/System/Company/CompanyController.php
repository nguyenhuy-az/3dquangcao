<?php

namespace App\Http\Controllers\Ad3d\System\Company;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Input;
use File;
use DB;
use Request;

class CompanyController extends Controller
{
    public function index()
    {
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $dataAccess = [
            'accessObject' => 'company'
        ];
        $dataCompany = QcCompany::where('action', 1)->orderBy('name', 'ASC')->select('*')->paginate(30);
        return view('ad3d.system.company.list', compact('modelStaff', 'modelCompany', 'dataCompany', 'dataAccess'));

    }

    public function view($companyId)
    {
        $modelCompany = new QcCompany();
        if (!empty($companyId)) {
            $dataCompany = $modelCompany->getInfo($companyId);
            return view('ad3d.system.company.view', compact('dataCompany'));
        }
    }

    //add
    public function getAdd()
    {
        $modelStaff = new QcStaff();
        $dataAccess = [
            'accessObject' => 'company'
        ];
        return view('ad3d.system.company.add', compact('modelStaff','dataAccess'));
    }

    public function postAdd()
    {
        $hFunction = new \Hfunction();
        $modelCompany = new QcCompany();
        $name = Request::input('txtName');
        $companyCode = Request::input('txtCompanyCode');
        $txtNameCode = Request::input('txtNameCode');
        $address = Request::input('txtAddress');
        $phone = Request::input('txtPhone');
        $email = Request::input('txtEmail');
        $website = Request::input('txtWebsite');
        $logo = Request::file('txtLogo');;
        $companyType = Request::input('cbCompanyType');;// chi nhanh
        // check exist of name
        if ($modelCompany->existName($name)) {
            Session::put('notifyAdd', "Thêm thất bại <b>'$name'</b> đã tồn tại.");
        } else {
            if (count($logo) > 0) {
                $imageName = $logo->getClientOriginalName();
                $imageName = "$companyCode-" . $hFunction->getTimeCode() . "." . $hFunction->getTypeImg($imageName);
                $source_img = $_FILES['txtLogo']['tmp_name'];
                if (!$modelCompany->uploadImage($source_img, $imageName, 500)) {
                    $imageName = null;
                }
            }
            if ($modelCompany->insert($companyCode, $name, $txtNameCode, $address, $phone, $email, $website,$companyType, $imageName)) {
                Session::put('notifyAdd', 'Thêm thành công, Nhập thông tin để tiếp tục');
            } else {
                Session::put('notifyAdd', 'Thêm thất bại, Nhập thông tin để tiếp tục');
            }
        }
    }

    //edit
    public function getEdit($companyId)
    {
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $dataCompany = $modelCompany->getInfo($companyId);
        if (count($dataCompany) > 0) {
            return view('ad3d.system.company.edit', compact('modelStaff','dataCompany'));
        }
    }

    public function postEdit($companyId)
    {
        $hFunction = new \Hfunction();
        $modelCompany = new QcCompany();
        $name = Request::input('txtName');
        $companyCode = Request::input('txtCompanyCode');
        $txtNameCode = Request::input('txtNameCode');
        $address = Request::input('txtAddress');
        $phone = Request::input('txtPhone');
        $email = Request::input('txtEmail');
        $website = Request::input('txtWebsite');

        $txtNewLogo = Request::file('txtNewLogo');;
        $companyType = Request::input('cbCompanyType');;// chi nhanh

        $notifyContent = null;
        if ($modelCompany->existEditName($companyId, $name)) {
            $notifyContent = "Tên <b>'$name'</b> đã tồn tại.";
        }
        if ($modelCompany->existEditCompanyCode($companyId, $companyCode)) {
            if (empty($notifyContent)) {
                $notifyContent = "Mã số thuế <b>'$companyCode'</b> đã tồn tại.";
            } else {
                $notifyContent = $notifyContent . "<br/> Mã số thuế <b>'$companyCode'</b> đã tồn tại.";
            }
        }
        if ($modelCompany->existEditNameCode($companyId, $txtNameCode)) {
            if (empty($notifyContent)) {
                $notifyContent = "Mã Cty <b>'$txtNameCode'</b> đã tồn tại.";
            } else {
                $notifyContent = $notifyContent . "<br/> Mã Cty <b>'$txtNameCode'</b> đã tồn tại.";
            }
        }
        if (!empty($notifyContent)) {
            return $notifyContent;
        } else {
            $oldLogo = $modelCompany->logo($companyId);
            if (count($txtNewLogo) > 0) {
                $imageName = $txtNewLogo->getClientOriginalName();
                $imageName = "$companyCode-" . $hFunction->getTimeCode() . "." . $hFunction->getTypeImg($imageName);
                $source_img = $_FILES['txtNewLogo']['tmp_name'];
                if (!$modelCompany->uploadImage($source_img, $imageName, 500)) {
                    $imageName = $oldLogo;
                }
            }else{
                $imageName = $oldLogo;
            }
            $modelCompany->updateInfo($companyId, $companyCode, $name, $txtNameCode, $address, $phone, $email, $website,$companyType, $imageName);
        }
    }

    // delete
    public function deleteCompany($companyId)
    {
        if (!empty($companyId)) {
            $modelCompany = new QcCompany();
            $modelCompany->actionDelete($companyId);
        }
    }
}
