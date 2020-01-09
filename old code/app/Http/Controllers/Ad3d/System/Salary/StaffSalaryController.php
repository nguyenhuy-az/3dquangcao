<?php

namespace App\Http\Controllers\Ad3d\System\Salary;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\StaffSalaryBasic\QcStaffSalaryBasic;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Input;
use File;
use DB;
use Request;

class StaffSalaryController extends Controller
{
    public function index($companyFilterId = null)
    {
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'accessObject' => 'salary'
        ];
        $dataCompany = $modelCompany->getInfo();
        if (empty($companyFilterId)) {
            if (!$dataStaffLogin->checkRootManage()) {
                $companyFilterId = $dataStaffLogin->companyId();
            }
        }
        $listStaffIdActivity = $modelStaff->listStaffIdActivity();
        if (empty($companyFilterId)) {
            //$dataStaffSalaryBasic = QcStaffSalaryBasic::whereIn('staff_id', $listStaffIdActivity)->where('action', 1)->orderBy('salary_basic_id', 'ASC')->select('*')->paginate(30);
        } else {
            //$listStaffId = $modelStaff->listIdOfCompany($companyFilterId);
            //$dataStaffSalaryBasic = QcStaffSalaryBasic::whereIn('staff_id', $listStaffIdActivity)->where('action', 1)->whereIn('staff_id', $listStaffId)->orderBy('salary_basic_id', 'ASC')->select('*')->paginate(30);
        }
        return view('ad3d.system.salary.list', compact('modelStaff', 'dataCompany', 'dataAccess', 'dataStaffSalaryBasic', 'companyFilterId'));

    }
    public function indexOld($companyFilterId = null)
    {
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'accessObject' => 'salary'
        ];
        $dataCompany = $modelCompany->getInfo();
        if (empty($companyFilterId)) {
            if (!$dataStaffLogin->checkRootManage()) {
                $companyFilterId = $dataStaffLogin->companyId();
            }
        }
        $listStaffIdActivity = $modelStaff->listStaffIdActivity();
        if (empty($companyFilterId)) {
            $dataStaffSalaryBasic = QcStaffSalaryBasic::whereIn('staff_id', $listStaffIdActivity)->where('action', 1)->orderBy('salary_basic_id', 'ASC')->select('*')->paginate(30);
        } else {
            $listStaffId = $modelStaff->listIdOfCompany($companyFilterId);
            $dataStaffSalaryBasic = QcStaffSalaryBasic::whereIn('staff_id', $listStaffIdActivity)->where('action', 1)->whereIn('staff_id', $listStaffId)->orderBy('salary_basic_id', 'ASC')->select('*')->paginate(30);
        }
        //return view('ad3d.system.salary.list', compact('modelStaff', 'dataCompany', 'dataAccess', 'dataStaffSalaryBasic', 'companyFilterId'));

    }

    public function view($staffId)
    {
        $modelStaff = new QcStaff();
        $modelStaffSalaryBasic = new QcStaffSalaryBasic();
        $dataStaffSalaryBasic = $modelStaffSalaryBasic->allInfoOfStaff($staffId);
        $dataStaff = $modelStaff->getInfo($staffId);
        return view('ad3d.system.salary.view', compact('dataStaffSalaryBasic', 'dataStaff'));
    }

    //add
    public function getEdit($salaryBasicId = null)
    {
        $modelStaffSalaryBasic = new QcStaffSalaryBasic();
        $dataStaffSalaryBasic = $modelStaffSalaryBasic->getInfo($salaryBasicId);
        return view('ad3d.system.salary.edit', compact('dataStaffSalaryBasic'));
    }

    public function postEdit($salaryBasicId)
    {
        $modelStaffSalaryBasic = new QcStaffSalaryBasic();
        $newSalary = Request::input('txtNewSalary');
        $dataOldSalaryBasic = $modelStaffSalaryBasic->getInfo($salaryBasicId);
        if (count($dataOldSalaryBasic) > 0) {
            # co thay doi luong
            if ($dataOldSalaryBasic->salary() != $newSalary && $newSalary > 0) {
                if ($modelStaffSalaryBasic->disableOfStaff($dataOldSalaryBasic->staffId())) {
                    $modelStaffSalaryBasic->insert($newSalary, $dataOldSalaryBasic->staffId());
                }
            }
        }
    }
}
