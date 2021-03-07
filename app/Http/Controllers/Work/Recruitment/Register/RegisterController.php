<?php

namespace App\Http\Controllers\Work\Recruitment\Register;

//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\Department\QcDepartment;
use App\Models\Ad3d\JobApplication\QcJobApplication;
use App\Models\Ad3d\JobApplicationWork\QcJobApplicationWork;
use Illuminate\Support\Facades\Session;
use Input;
use File;
use DB;
use Request;

class RegisterController extends Controller
{
    public function index()
    {
        $hFunction = new \Hfunction();
        $dataAccess = [
            'object' => null
        ];

    }

    # lay form dang ky
    public function getAdd($companyId, $phoneNumber = null, $departmentSelectedId = null)
    {
        $hFunction = new \Hfunction();
        $modelCompany = new QcCompany();
        $modelDepartment = new QcDepartment();
        $dataDepartmentWork = null;
        if ($hFunction->checkEmpty($phoneNumber)) {
            return redirect()->route('qc.work.recruitment.login.get', $companyId);
        } else {
            if ($hFunction->checkEmpty($departmentSelectedId)) {
                # lay mac dinh la bo phan thi cong
                $dataDepartmentSelected = null;// $modelDepartment->getInfo($modelDepartment->constructionDepartmentId());
            } else {
                $dataDepartmentSelected = $modelDepartment->getInfo($departmentSelectedId);
            }
            # danh sach bo phan cua he thong
            $dataDepartment = $modelDepartment->selectInfoAllActivity()->get();
            if ($hFunction->checkCount($dataDepartmentSelected)) $dataDepartmentWork = $dataDepartmentSelected->departmentWorkGetInfo();
            # thong tin cong ty
            $dataCompany = $modelCompany->getInfo($companyId);
            return view('work.recruitment.register.add', compact('dataDepartment', 'dataCompany', 'dataDepartmentSelected', 'dataDepartmentWork', 'phoneNumber'));
        }
    }

    public function postAdd($companyId)
    {
        $hFunction = new \Hfunction();
        $modelJobApplication = new QcJobApplication();
        $modelJobApplicationWork = new QcJobApplicationWork();
        $departmentId = Request::input('cbDepartment');
        $departmentWork = Request::input('txtDepartmentWork');
        $firstName = Request::input('txtFirstName');
        $lastName = Request::input('txtLastName');
        $identityCard = Request::input('txtIdentityCard');
        $birthDay = Request::input('txtBirthday');
        $gender = Request::input('cbGender');
        $phone = Request::input('txtPhone');
        $address = Request::input('txtAddress');
        $email = Request::input('txtEmail');
        $introduce = Request::input('txtIntroduce');
        $totalSalary = Request::input('txtTotalSalary');
        $salary = $hFunction->convertCurrencyToInt($totalSalary);
        $image = Request::file('txtImage');
        $identityCardFront = Request::file('txtIdentityCardFront');
        $identityCardBack = Request::file('txtIdentityCardBack');
        $name_img = null;
        $name_img_front = null;
        $name_img_back = null;
        $dataJobApplication = $modelJobApplication->infoByPhoneAndCompany($phone, $companyId);
        if ($hFunction->checkCount($dataJobApplication)) {
            return view('work.recruitment.info.index', compact('dataJobApplication'));
        } else {
            if ($hFunction->checkCount($image)) {
                $name_img = stripslashes($_FILES['txtImage']['name']);
                $name_img = 'avatar' . $hFunction->getTimeCode() . '.' . $hFunction->getTypeImg($name_img);
                $source_img = $_FILES['txtImage']['tmp_name'];
                if (!$modelJobApplication->uploadImage($source_img, $name_img)) $name_img = null;
            }
            if ($hFunction->checkCount($identityCardFront)) {
                $name_img_front = stripslashes($_FILES['txtIdentityCardFront']['name']);
                $name_img_front = 'identity_front' . $hFunction->getTimeCode() . '.' . $hFunction->getTypeImg($name_img_front);
                $source_img = $_FILES['txtIdentityCardFront']['tmp_name'];
                if (!$modelJobApplication->uploadImage($source_img, $name_img_front)) $name_img_front = null;
            }
            if ($hFunction->checkCount($identityCardBack)) {
                $name_img_back = stripslashes($_FILES['txtIdentityCardBack']['name']);
                $name_img_back = 'identity_back' . $hFunction->getTimeCode() . '.' . $hFunction->getTypeImg($name_img_back);
                $source_img = $_FILES['txtIdentityCardBack']['tmp_name'];
                if (!$modelJobApplication->uploadImage($source_img, $name_img_back)) $name_img_back = null;
            }
            if ($modelJobApplication->insert($firstName, $lastName, $identityCard, $birthDay, $gender, $name_img, $name_img_front, $name_img_back, $email, $address, $phone, $introduce, $salary, $companyId, $departmentId)) {
                $jobApplicationId = $modelJobApplication->insertGetId();
                foreach ($departmentWork as $departmentWorkId) {
                    $skillValue = Request::input('chkSkill_' . $departmentWorkId);
                    $modelJobApplicationWork->insert($skillValue, $departmentWorkId, $jobApplicationId);
                }
                $dataJobApplication = $modelJobApplication->getInfo($jobApplicationId);
                return view('work.recruitment.register.notify-success', compact('dataJobApplication'));
            } else {
                Session::put('notifyRecruitmentAdd', "THÔNG TIN ĐANG CẬP NHẬT, HÃY THỬ LẠI SAU 24h");
                return redirect()->back();
            }
        }


    }
}
