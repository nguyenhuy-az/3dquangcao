<?php

namespace App\Models\Ad3d\JobApplication;

use App\Models\Ad3d\JobApplicationInterview\QcJobApplicationInterview;
use App\Models\Ad3d\JobApplicationWork\QcJobApplicationWork;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class QcJobApplication extends Model
{
    protected $table = 'qc_job_application';
    protected $fillable = ['jobApplication_id', 'nameCode', 'firstName', 'lastName', 'identityCard', 'birthday', 'gender', 'image', 'identityFront', 'identityBack', 'email', 'address'
        , 'phone', 'introduce', 'salaryOffer', 'confirmStatus', 'confirmNote', 'confirmDate', 'agreeStatus', 'action', 'created_at', 'confirmStaff_id', 'company_id', 'department'];
    protected $primaryKey = 'jobApplication_id';
    public $timestamps = false;

    private $lastId;


    # mac dinh email
    public function getDefaultEmail()
    {
        return null;
    }

    # mac dinh mo ta ho so
    public function getDefaultIntroduce()
    {
        return null;
    }

    # mac dinh da xac nhan
    public function getDefaultHasConfirm()
    {
        return 1;
    }

    # mac dinh chua xac nhan
    public function getDefaultNotConfirm()
    {
        return 0;
    }

    # mac dinh tat ca trang thai xac nhan
    public function getDefaultAllConfirm()
    {
        return 100;
    }

    # mac dinh ghi chu xac nhan
    public function getDefaultConfirmNote()
    {
        return null;
    }

    # mac dinh ngay xac nhan
    public function getDefaultConfirmDate()
    {
        return null;
    }

    #mac dinh co dong y
    public function getDefaultHasAgree()
    {
        return 1;
    }

    # mac dinh khong dong y
    public function getDefaultNotAgree()
    {
        return 0;
    }

    # mac dinh trang thai dong y
    public function getDefaultAllAgree()
    {
        return 100;
    }

    # mac dinh dang hoat dong
    public function getDefaultHasAction()
    {
        return 1;
    }

    # mac dinh khong hoat dong
    public function getDefaultNotAction()
    {
        return 0;
    }

    # mac dinh tat ca trang thai hoat dong
    public function getDefaultAllAction()
    {
        return 100;
    }

    # mac dinh nguoi xac nhan
    public function getDefaultConfirmStaffId()
    {
        return null;
    }

    public function insert($firstName, $lastName, $identityCard, $birthday, $gender, $image, $identityFront, $identityBack, $email, $address, $phone, $introduce, $salaryOffer, $companyId, $departmentId)
    {
        $hFunction = new \Hfunction();
        $modelJobApplication = new QcJobApplication();
        //create code
        $nameCode = $hFunction->getTimeCode();        // insert
        $modelJobApplication->nameCode = $nameCode;
        $modelJobApplication->firstName = $hFunction->convertValidHTML($firstName);
        $modelJobApplication->lastName = $hFunction->convertValidHTML($lastName);
        $modelJobApplication->identityCard = $identityCard;
        $modelJobApplication->introduce = $introduce;
        $modelJobApplication->salaryOffer = $salaryOffer;
        $modelJobApplication->birthday = $birthday;
        $modelJobApplication->gender = $gender;
        $modelJobApplication->image = $image;
        $modelJobApplication->identityFront = $identityFront;
        $modelJobApplication->identityBack = $identityBack;
        $modelJobApplication->email = $email;
        $modelJobApplication->address = $hFunction->convertValidHTML($address);
        $modelJobApplication->phone = $phone;
        $modelJobApplication->company_id = $companyId;
        $modelJobApplication->department_id = $departmentId;
        $modelJobApplication->action = $this->getDefaultHasAction();
        $modelJobApplication->created_at = $hFunction->createdAt();
        if ($modelJobApplication->save()) {
            $this->lastId = $modelJobApplication->jobApplication_id;
            return true;
        } else {
            return false;
        }
    }

    // lấy id mới thêm
    public function insertGetId()
    {
        return $this->lastId;
    }

    public function checkIdNull($jobApplicationId)
    {
        $hFunction = new \Hfunction();
        return ($hFunction->checkNull($jobApplicationId)) ? $this->jobApplicationId() : $jobApplicationId;
    }

    # up hinh anh
    public function rootPathFullImage()
    {
        return 'public/images/job-application/full';
    }

    public function rootPathSmallImage()
    {
        return 'public/images/job-application/small';
    }

    # upload image
    public function uploadImage($file, $imageName, $size = 500)
    {
        $hFunction = new \Hfunction();
        $pathSmallImage = $this->rootPathSmallImage();
        $pathFullImage = $this->rootPathFullImage();
        if (!is_dir($pathFullImage)) mkdir($pathFullImage);
        if (!is_dir($pathSmallImage)) mkdir($pathSmallImage);
        return $hFunction->uploadSaveByFileName($file, $imageName, $pathSmallImage . '/', $pathFullImage . '/', $size);
    }

    # drop image
    public function dropImage($imageName)
    {
        if (is_file($this->rootPathSmallImage() . '/' . $imageName)) unlink($this->rootPathSmallImage() . '/' . $imageName);
        if (is_file($this->rootPathFullImage() . '/' . $imageName)) unlink($this->rootPathFullImage() . '/' . $imageName);
    }

    public function pathSmallImage($image)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkNull($image)) {
            return $hFunction->getDefaultNull();
        } else {
            return asset($this->rootPathSmallImage() . '/' . $image);
        }
    }

    public function pathFullImage($image)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($image)) {
            return $hFunction->getDefaultNull();
        } else {
            return asset($this->rootPathFullImage() . '/' . $image);
        }
    }

    public function pathDefaultImage()
    {
        return asset('public/images/icons/people.jpeg');
    }

    # lay duong dan anh avatar
    public function pathAvatar($image)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($image)) {
            return $this->pathDefaultImage();
        } else {
            return $this->pathFullImage($image);
        }
    }

    # ======= ======= cong ty ========= =======
    public function company()
    {
        return $this->belongsTo('App\Models\Ad3d\Company\QcCompany', 'company_id', 'company_id');
    }

    # lay danh sach ma ho cua cong ty
    public function listIdByCompany($companyId)
    {
        return QcJobApplication::where('company_id', $companyId)->pluck('jobApplication_id');
    }

    # lay tong so luong ho so tuyen dung chua duyet cua 1 cong ty
    public function totalUnconfirmedOfCompany($companyId)
    {
        return QcJobApplication::where('company_id', $companyId)->where('confirmStatus', $this->getDefaultNotConfirm())->count();
    }

    #----------- nguoi duyet ho so------------
    public function staff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staff_id', 'staff_id');
    }

    # --------- ------------- bo phan ------------ --------
    public function department()
    {
        return $this->belongsTo('App\Models\Ad3d\Department\QcDepartment', 'department_id', 'department_id');
    }

    # --------- ------------- ky nang lam viec ------------ --------
    public function jobApplicationWork()
    {
        return $this->belongsTo('App\Models\Ad3d\JobApplicationWork\QcJobApplicationWork', 'jobApplication_id', 'jobApplication_id');
    }

    public function jobApplicationWorkGetInfo($jobApplicationId = null)
    {
        $modelJobApplicationWork = new QcJobApplicationWork();
        return $modelJobApplicationWork->getInfoOfJobApplication($this->checkIdNull($jobApplicationId));
    }

    # --------- ------------- Thong tin phong van ------------ --------
    public function jobApplicationInterview()
    {
        return $this->hasMany('App\Models\Ad3d\JobApplicationInterview\QcJobApplicationInterview', 'jobApplication_id', 'jobApplication_id');
    }

    # lay thong tin hen phong van sau cung
    public function jobApplicationInterviewLastInfo($jobApplicationId = null)
    {
        $modelJobApplicationInterview = new QcJobApplicationInterview();
        return $modelJobApplicationInterview->lastInfoOfJobApplication($this->checkIdNull($jobApplicationId));
    }
    # ======== ======= lay thong tin ======== =========
    # lay tong so luong ho so tuyen dung chua duyet
    public function totalUnconfirmed()
    {
        return QcJobApplication::where('confirmStatus', $this->getDefaultNotConfirm())->count();
    }

    # lay thong tin theo so dien thoai cua cong
    public function infoByPhoneAndCompany($phone, $companyId)
    {
        return QcJobApplication::where('phone', $phone)->where('company_id', $companyId)->first();
    }

    public function getInfo($id = null, $field = null)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($id)) {
            return QcJobApplication::select('*')->get();
        } else {
            $result = QcJobApplication::where('jobApplication_id', $id)->first();
            if ($hFunction->checkEmpty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }

    public function getInfoActivity()
    {
        return QcJobApplication::where('action', $this->getDefaultHasAction())->get();
    }

    //danh sach ho so  dang hoat dong
    public function listIdActivity()
    {
        return QcJobApplication::where('action', $this->getDefaultHasAction())->pluck('jobApplication_id');
    }

    public function jobApplicationId()
    {
        return $this->jobApplication_id;
    }

    # lay mot gia tri
    public function pluck($column, $objectId = null)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($objectId)) {
            return $this->$column;
        } else {
            return QcJobApplication::where('jobApplication_id', $objectId)->pluck($column)[0];
        }
    }

    public function fullName($jobApplicationId = null)
    {
        return $this->firstName($jobApplicationId) . ' ' . $this->lastName();
    }

    public function firstName($jobApplicationId = null)
    {
        $hFunction = new \Hfunction();
        return $hFunction->htmlEntities($this->pluck('firstName', $jobApplicationId));
    }

    public function lastName($jobApplicationId = null)
    {
        $hFunction = new \Hfunction();
        return $hFunction->htmlEntities($this->pluck('lastName', $jobApplicationId));
    }

    public function identityCard($jobApplicationId = null)
    {
        return $this->pluck('identityCard', $jobApplicationId);
    }

    public function nameCode($jobApplicationId = null)
    {
        return $this->pluck('nameCode', $jobApplicationId);
    }

    public function birthday($jobApplicationId = null)
    {

        return $this->pluck('birthday', $jobApplicationId);
    }

    public function email($jobApplicationId = null)
    {

        return $this->pluck('email', $jobApplicationId);
    }

    public function phone($jobApplicationId = null)
    {

        return $this->pluck('phone', $jobApplicationId);
    }

    public function address($jobApplicationId = null)
    {
        $hFunction = new \Hfunction();
        return $hFunction->htmlEntities($this->pluck('address', $jobApplicationId));
    }

    public function introduce($jobApplicationId = null)
    {
        return $this->pluck('introduce', $jobApplicationId);
    }

    public function createdAt($jobApplicationId = null)
    {
        return $this->pluck('created_at', $jobApplicationId);
    }


    public function image($jobApplicationId = null)
    {
        return $this->pluck('image', $jobApplicationId);
    }

    public function identityBack($jobApplicationId = null)
    {
        return $this->pluck('identityBack', $jobApplicationId);
    }

    public function identityFront($jobApplicationId = null)
    {
        return $this->pluck('identityFront', $jobApplicationId);
    }

    public function action($jobApplicationId = null)
    {
        return $this->pluck('action', $jobApplicationId);
    }

    public function confirmStatus($jobApplicationId = null)
    {
        return $this->pluck('confirmStatus', $jobApplicationId);
    }

    public function confirmNote($jobApplicationId = null)
    {
        return $this->pluck('confirmNote', $jobApplicationId);
    }

    public function agreeStatus($jobApplicationId = null)
    {
        return $this->pluck('agreeStatus', $jobApplicationId);
    }


    public function confirmDate($jobApplicationId = null)
    {
        return $this->pluck('confirmDate', $jobApplicationId);
    }


    public function gender($jobApplicationId = null)
    {
        return $this->pluck('gender', $jobApplicationId);
    }

    public function salaryOffer($jobApplicationId = null)
    {
        return $this->pluck('salaryOffer', $jobApplicationId);
    }

    public function confirmStaffId($jobApplicationId = null)
    {
        return $this->pluck('confirmStaff_id', $jobApplicationId);
    }

    public function companyId($jobApplicationId = null)
    {
        return $this->pluck('company_id', $jobApplicationId);
    }

    public function departmentId($jobApplicationId = null)
    {
        return $this->pluck('department_id', $jobApplicationId);
    }

    // total records
    public function totalRecords()
    {
        return QcJobApplication::count();
    }

    // last id
    public function lastId()
    {
        $hFunction = new \Hfunction();
        $result = QcJobApplication::orderBy('jobApplication_id', 'DESC')->first();
        return ($hFunction->checkEmpty($result)) ? 0 : $result->jobApplication_id;
    }

    # ======== ======== kiem tra thong tin ========= ==========

    #lay ho so tuyen dung theo cty va trang thai duyet
    public function selectInfoByCompany($companyId, $confirmStatus = 100)
    {
        if ($confirmStatus == $this->getDefaultAllConfirm()) { # tat ca thong tin
            return QcJobApplication::where('company_id', $companyId)->orderBy('jobApplication_id', 'DESC')->select();
        } else {
            return QcJobApplication::where('company_id', $companyId)->where('confirmStatus', $confirmStatus)->orderBy('jobApplication_id', 'DESC')->select();
        }
    }

    # dang nhap ho so
    public function loginJobApplication($companyId, $phone)
    {
        $hFunction = new \Hfunction();
        $getInfo = QcJobApplication::where('company_id', $companyId)->where('phone', $phone)->first();
        if ($hFunction->checkCount($getInfo)) { // login success
            Session::put('loginJobApplication', $getInfo);
            return true;
        } else {
            return false;
        }
    }

    // thong tin ho so dang nhap
    public function loginJobApplicationInfo()
    {
        if (Session::has('loginJobApplication')) {//da dang nhap
            return Session::get('loginJobApplication');
        } else {
            return null;
        }
    }

    // kiem tra dang nhap ho so
    public function checkLoginJobApplication()
    {
        if (Session::has('loginJobApplication')) return true; else return false;
    }

    # kiem tra hs da xac nhan hay chua
    public function checkConfirmStatus($jobApplicationId = null)
    {
        return ($this->confirmStatus($jobApplicationId) == $this->getDefaultHasConfirm()) ? true : false;
    }

    # hs con hoat dong hay khong
    public function checkActivity($jobApplicationId = null)
    {
        return ($this->action($jobApplicationId) == $this->getDefaultHasAction()) ? true : false;
    }

    # kiem tra so dien thoai da dang ky o cty
    public function checkExistPhoneOfCompany($phone, $companyId)
    {
        return QcJobApplication::where('phone', $phone)->where('company_id', $companyId)->exists();
    }

    # kiem tra co duoc dong y chap nhan hay khong
    public function checkAgreeStatus($jobApplicationId = null)
    {
        return ($this->agreeStatus($jobApplicationId) == $this->getDefaultHasAgree()) ? true : false;
    }

    # xac nhan khong dong y ho so
    public function confirmDisagree($jobApplicationId, $confirmStaffId)
    {
        $hFunction = new \Hfunction();
        return QcJobApplication::where('jobApplication_id', $jobApplicationId)->update(
            [
                'confirmStatus' => $this->getDefaultHasConfirm(),
                'confirmStaff_id' => $confirmStaffId,
                'confirmDate' => $hFunction->carbonNow()
            ]);
    }

    # xac nhan dong y va hen phong van
    public function confirmAgreeInterview($jobApplicationId, $confirmStaffId, $interviewDate)
    {
        $hFunction = new \Hfunction();
        $modelJobApplicationInterview = new QcJobApplicationInterview();
        if (QcJobApplication::where('jobApplication_id', $jobApplicationId)->update(
            [
                'confirmStatus' => $this->getDefaultHasConfirm(),
                'agreeStatus' => $this->getDefaultHasAgree(),
                'confirmStaff_id' => $confirmStaffId,
                'confirmDate' => $hFunction->carbonNow()
            ])
        ) {
            return $modelJobApplicationInterview->insert($interviewDate, $jobApplicationId);
        } else {
            return false;
        }
    }
}
