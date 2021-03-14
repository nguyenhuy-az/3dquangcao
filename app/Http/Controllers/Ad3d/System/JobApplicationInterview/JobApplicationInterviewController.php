<?php

namespace App\Http\Controllers\Ad3d\System\JobApplicationInterview;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\JobApplicationInterview\QcJobApplicationInterview;
use App\Models\Ad3d\Rank\QcRank;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Input;
use File;
use DB;
use Request;

class JobApplicationInterviewController extends Controller
{
    public function index($companyFilterId = null, $confirmStatusFilter = 100)
    {
        $modelStaff = new QcStaff();
        $modelJobApplicationInterview = new QcJobApplicationInterview();
        $modelCompany = new QcCompany();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $dataCompanyLogin = $modelStaff->companyLogin();
        $companyLoginId = $dataCompanyLogin->companyId();
        $companyFilterId = ($companyFilterId == 'null') ? null : $companyFilterId;
        $dataAccess = [
            'accessObject' => 'recruitment',
            'subObject' => 'jobApplicationInterview'
        ];

        if ($companyFilterId == null || $companyFilterId == 0) {
            $companyFilterId = $companyLoginId;
        }
        # lay thong tin cong ty cung he thong
        $dataListCompany = $modelCompany->getInfoSameSystemOfCompany($companyLoginId);
        # danh sach ho so
        $selectJobApplicationInterview = $modelJobApplicationInterview->selectInfoByCompany($companyFilterId, $confirmStatusFilter);
        $dataJobApplicationInterview = $selectJobApplicationInterview->paginate(30);
        return view('ad3d.system.recruitment.job-application-interview.list', compact('modelStaff', 'dataJobApplicationInterview', 'dataListCompany', 'dataAccess', 'companyFilterId', 'confirmStatusFilter'));
    }

    # thong tin ho so
    public function getInfo($interviewId)
    {
        $modelStaff = new QcStaff();
        $modelRank = new QcRank();
        $modelJobApplicationInterview = new QcJobApplicationInterview();
        $dataAccess = [
            'accessObject' => 'recruitment',
            'subObject' => 'jobApplicationInterview'
        ];
        $dataJobApplicationInterview = $modelJobApplicationInterview->getInfo($interviewId);
        return view('ad3d.system.recruitment.job-application-interview.info', compact('modelRank', 'modelStaff', 'dataJobApplicationInterview', 'dataAccess'));
    }

    # xac nhan ho so
    public function postConfirm($interviewId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelJobApplicationInterview = new QcJobApplicationInterview();
        $agreeStatus = Request::input('cbAgreeStatus');
        $dataJobApplicationInterview = $modelJobApplicationInterview->getInfo($interviewId);
        if ($hFunction->checkCount($dataJobApplicationInterview)) {
            if ($agreeStatus == $modelJobApplicationInterview->getDefaultHasAgree()) { # dong y tuyen dung
                $totalSalary = Request::input('txtSalary');
                $salary = $hFunction->convertCurrencyToInt($totalSalary);
                $departmentRank = Request::input('cbDepartmentRank');
                $cbDay = Request::input('cbDay');
                $cbMonth = Request::input('cbMonth');
                $cbYear = Request::input('cbYear');
                $cbHours = Request::input('cbHours');
                $cbMinute = Request::input('cbMinute');
                $currentDate = date('Y-m-d H:j');
                # ngay bat dau lam viec
                $workBeginDate = $hFunction->convertStringToDatetime("$cbMonth/$cbDay/$cbYear $cbHours:$cbMinute");
                if ($workBeginDate < $currentDate) {
                    Session::put('confirmJobApplicationInterviewNotify', 'NGÀY BẮT ĐẦU LÀM PHẢI LỚN HƠN NGÀY HIỆN TẠI');
                } else {
                    # xac nhan dong y
                    if ($modelJobApplicationInterview->confirmInterviewAgree($interviewId, $modelStaff->loginStaffId(), $salary, $workBeginDate, $departmentRank)) {
                        Session::put('confirmJobApplicationInterviewNotify', 'TÍNH NĂNG ĐANG BẢO TRÌ, HÃY THỬ LẠI SAU');
                    }
                }

            } else { # khong dong y
                $modelJobApplicationInterview->confirmInterviewDisagree($interviewId, $modelStaff->loginStaffId());
            }
        } else {
            Session::put('confirmJobApplicationInterviewNotify', 'KHÔNG CÓ THÔNG TIN PHỎNG VẤN, HÃY THỬ LẠI SAU');
        }
        /*if ($agreeStatus == 1) { # dong y
            # lay ngay hen phong van



        } else {
            # xac nhan khong dong y
            if (!$modelJobApplication->confirmDisagree($jobApplicationId, $modelStaff->loginStaffId())) {
                Session::put('confirmJobApplicationNotify', 'Tính năng đang bảo trì, Hãy quay lại sau');
            }
        }*/

    }
    //xóa
    /*public function deleteStaff($staffId = null)
    {
        $modelStaff = new QcStaff();
        if (!empty($staffId)) {
            return $modelStaff->actionDelete($staffId);
        }
    }*/
}
