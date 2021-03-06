<?php

namespace App\Http\Controllers\Ad3d\System\Rules;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\Rule\QcRules;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Input;
use File;
use DB;
use Request;

class RulesController extends Controller
{
    public function index()
    {
        $modelStaff = new QcStaff();
        $dataAccess = [
            'accessObject' => 'rules'
        ];
        $dataCompanyLogin = $modelStaff->companyLogin();
        $dataRules = $dataCompanyLogin->rulesGetInfoOfCompany();
        return view('ad3d.system.rules.list', compact('modelStaff', 'dataRules', 'dataAccess'));

    }

    # add
    public function getAdd($parentId = null)
    {
        $hFunction = new \Hfunction();
        $modelCompany = new QcCompany();
        $modelStaff = new QcStaff();
        $dataAccess = [
            'accessObject' => 'rules'
        ];
        if ($hFunction->checkEmpty($parentId)) {
            $dataRulesSelected = $hFunction->getDefaultNull();
        } else {
            $dataRulesSelected = $modelCompany->getInfo($parentId)->rulesGetInfoOfCompany();
        }
        return view('ad3d.system.rules.add', compact('modelStaff', 'dataAccess', 'dataRulesSelected'));
    }

    public function postAdd()
    {
        $modelStaff = new QcStaff();
        $modelRules = new QcRules();
        $txtTitle = Request::input('txtTitle');
        $txtContent = Request::input('txtRuleContent');
        $dataCompanyLogin = $modelStaff->companyLogin();
        if ($modelRules->insert($txtTitle, $txtContent, $dataCompanyLogin->companyId())) {
            # Session::put('notifyAdd', 'Thêm thành công, Nhập thông tin để tiếp tục');
            return redirect()->route('qc.ad3d.system.rules.get');
        } else {
            Session::put('notifyAdd', 'Thêm thất bại, Nhập thông tin để tiếp tục');
            return redirect()->back();
        }

        # check exist of name
        /*if ($modelRules->existTitle($txtTitle)) {
            Session::put('notifyAdd', "Thêm thất bại <b>'$txtTitle'</b> đã tồn tại.");
        } else {

        }*/

    }

    # edit
    public function getEdit($rulesId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelRules = new QcRules();
        $dataRules = $modelRules->getInfo($rulesId);
        if ($hFunction->checkCount($dataRules)) {
            return view('ad3d.system.rules.edit', compact('modelStaff', 'dataRules'));
        }
    }

    public function postEdit($rulesId)
    {
        $hFunction = new \Hfunction();
        $modelCompany = new QcCompany();
        $modelRules = new QcRules();
        $txtTitle = Request::input('txtTitle');
        $txtContent = Request::input('txtRuleContent');
        $dataRules = $modelRules->getInfo($rulesId);
        if ($hFunction->checkCount($dataRules)) {
            $dataCompany = $dataRules->company;
            # cong ty me
            if ($dataCompany->checkParent()) {
                $checkAllStatus = Request::input('checkAllStatus');
                if ($checkAllStatus) {
                    # danh sach cua cung he thong
                    $listDataCompany = $modelCompany->getInfoSameSystemOfCompany($dataCompany->companyId());
                    if ($hFunction->checkCount($listDataCompany)) {
                        foreach ($listDataCompany as $company) {
                            $rule = $company->rulesGetInfoOfCompany();
                            # ton tai noi quy
                            if ($hFunction->checkCount($rule)) {
                                $modelRules->updateInfo($rule->rulesId(), $txtTitle, $txtContent);
                            } else {
                                # khong ton tai thi them moi
                                $modelRules->insert($txtTitle, $txtContent, $company->companyId());
                            }
                        }
                    }
                } else {
                    $modelRules->updateInfo($rulesId, $txtTitle, $txtContent);
                }
            } else {
                $modelRules->updateInfo($rulesId, $txtTitle, $txtContent);
            }
        } else {
            return "Hệ thống đang bảo trì";
        }
    }

    # delete
    public function deleteDelete($rulesId)
    {
        $modelRules = new QcRules();
        $modelRules->actionDelete($rulesId);
    }
}
