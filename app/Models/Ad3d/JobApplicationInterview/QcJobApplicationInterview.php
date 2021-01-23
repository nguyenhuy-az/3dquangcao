<?php

namespace App\Models\Ad3d\JobApplicationInterview;

use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\JobApplication\QcJobApplication;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\StaffWorkDepartment\QcStaffWorkDepartment;
use App\Models\Ad3d\StaffWorkMethod\QcStaffWorkMethod;
use App\Models\Ad3d\StaffWorkSalary\QcStaffWorkSalary;
use App\Models\Ad3d\Work\QcWork;
use App\Models\Ad3d\WorkSkill\QcWorkSkill;
use Illuminate\Database\Eloquent\Model;

class QcJobApplicationInterview extends Model
{
    protected $table = 'qc_job_application_interview';
    protected $fillable = ['interview_id', 'interviewConfirm', 'interviewDate', 'agreeStatus', 'action', 'confirmDate', 'created_at', 'staff_id', 'jobApplication_id'];
    protected $primaryKey = 'interview_id';
    public $timestamps = false;

    private $lastId;

    #mac dinh da xac nhan
    public function getDefaultHasConfirm()
    {
        return 1;
    }

    # mac dinh chua xac nhan
    public function getDefaultNotConfirm()
    {
        return 0;
    }

    #mac dinh tat ca trang thai xac nhan
    public function getDefaultAllConfirm()
    {
        return 100;
    }

    # mac dinh dong ý
    public function getDefaultHasAgree()
    {
        return 1;
    }

    # mac dinh khong dong y
    public function getDefaultNotAgree()
    {
        return 0;
    }

    #mac dinh dang hoat dong
    public function getDefaultHasAction()
    {
        return 1;
    }

    # mac dinh khong hoat dong
    public function getDefaultNotAction()
    {
        return 0;
    }

    # mac dinh ngay xac nhan
    public function getDefaultConfirmDate()
    {
        $hFunction = new \Hfunction();
        return $hFunction->getDefaultNull();
    }

    # mac dinh nguoi xac nhan
    public function getDefaultStaffConfirm()
    {
        $hFunction = new \Hfunction();
        return $hFunction->getDefaultNull();
    }
    #========== ========== ========== INSERT && UPDATE ========== ========== ==========
    #---------- Insert ----------
    public function insert($interviewDate, $jobApplicationId)
    {
        $hFunction = new \Hfunction();
        $modelJobApplicationInterview = new QcJobApplicationInterview();
        $modelJobApplicationInterview->interviewDate = $interviewDate;
        $modelJobApplicationInterview->jobApplication_id = $jobApplicationId;
        $modelJobApplicationInterview->created_at = $hFunction->createdAt();
        if ($modelJobApplicationInterview->save()) {
            $this->lastId = $modelJobApplicationInterview->interview_id;
            return true;
        } else {
            return false;
        }
    }

    # get new id
    public function insertGetId()
    {
        return $this->lastId;
    }

    # delete
    public function deleteInfo($interviewId = null)
    {
        return QcJobApplicationInterview::where('interview_id', $interviewId)->delete();
    }

    # xac nhan phong van khong dat
    public function confirmInterviewDisagree($interviewId, $confirmStaffId)
    {
        $hFunction = new \Hfunction();
        return QcJobApplicationInterview::where('interview_id', $interviewId)->update(
            [
                'interviewConfirm' => $this->getDefaultHasConfirm(),
                'confirmDate' => $hFunction->carbonNow(),
                'staff_id' => $confirmStaffId,
            ]
        );
    }

    # xac nhan phong van dat - duoc tuyen dung
    public function confirmInterviewAgree($interviewId, $confirmStaffId, $totalSalary, $workBeginDate, $departmentWorkRank)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $modelStaffWorkSalary = new QcStaffWorkSalary();
        $modelStaffWorkMethod = new QcStaffWorkMethod();
        $modelWorkSkill = new QcWorkSkill();
        $modelWork = new QcWork();
        if (QcJobApplicationInterview::where('interview_id', $interviewId)->update(
            [
                'interviewConfirm' => $this->getDefaultHasConfirm(),
                'agreeStatus' => $this->getDefaultHasAgree(),
                'confirmDate' => $hFunction->carbonNow(),
                'staff_id' => $confirmStaffId,
            ]
        )
        ) {

            # thong tin tren ho sơ
            $dataJobApplicationInterview = $this->getInfo($interviewId);
            $dataJobApplication = $dataJobApplicationInterview->jobApplication;
            $firstName = $dataJobApplication->firstName();
            $lastName = $dataJobApplication->lastName();
            $identityCard = $dataJobApplication->identityCard();
            $birthday = $dataJobApplication->birthday();
            $gender = $dataJobApplication->gender();
            $image = $dataJobApplication->image();
            $identityFront = $dataJobApplication->identityFront();
            $identityBack = $dataJobApplication->identityBack();
            $phone = $dataJobApplication->phone();
            $address = $dataJobApplication->address();
            $email = $dataJobApplication->email();
            $companyId = $dataJobApplication->companyId();
            $departmentId = $dataJobApplication->departmentId();
            # thong tin ky nag lam viec o bo phan
            $dataJobApplicationWork = $dataJobApplication->jobApplicationWorkGetInfo();
            # thong tin them nhan su
            $add_image = $hFunction->getDefaultNull();
            $add_identityFront = $hFunction->getDefaultNull();
            $add_identityBack = $hFunction->getDefaultNull();
            $add_account = $identityCard;
            $add_level = $modelCompanyStaffWork->getDefaultLevel(); # mac dinh binh thuong
            $add_rankId = $departmentWorkRank; # cap bac lam viec tai 1 bo phan
            $add_totalSalary = $totalSalary;
            $add_usePhone = $modelStaffWorkSalary->getDefaultUsePhone();
            $add_salary = $add_totalSalary - $add_usePhone; // tru tien dien thoai
            #copy anh dai dien
            if (copy($dataJobApplication->rootPathSmallImage() . '/' . $image, $modelStaff->rootPathSmallImage() . '/' . $image)) {
                if (copy($dataJobApplication->rootPathFullImage() . '/' . $image, $modelStaff->rootPathFullImage() . '/' . $image)) {
                    $add_image = $image;
                }
            }
            #copy anh CMND mat truoc
            if (copy($dataJobApplication->rootPathSmallImage() . '/' . $identityFront, $modelStaff->rootPathSmallImage() . '/' . $identityFront)) {
                if (copy($dataJobApplication->rootPathFullImage() . '/' . $identityFront, $modelStaff->rootPathFullImage() . '/' . $identityFront)) {
                    $add_identityFront = $identityFront;
                }
            }
            #copy anh CMND mat sau
            if (copy($dataJobApplication->rootPathSmallImage() . '/' . $identityBack, $modelStaff->rootPathSmallImage() . '/' . $identityBack)) {
                if (copy($dataJobApplication->rootPathFullImage() . '/' . $identityBack, $modelStaff->rootPathFullImage() . '/' . $identityBack)) {
                    $add_identityBack = $identityBack;
                }
            }
            # then nhan vien
            #mac dinh
            $bankAccount = $modelStaff->getDefaultBankAccount();
            $bankName = $modelStaff->getDefaultBankName();
            if ($modelStaff->insert($firstName, $lastName, $identityCard, $add_account, $birthday, $gender, $add_image, $add_identityFront, $add_identityBack, $email, $address, $phone, $add_level, $bankAccount, $bankName)) {
                $newStaffId = $modelStaff->insertGetId();
                #them vao cong ty lam viec
                if ($modelCompanyStaffWork->insert($workBeginDate, $add_level, $newStaffId, $confirmStaffId, $companyId)) {
                    $newWorkId = $modelCompanyStaffWork->insertGetId();
                    # them vi tri lam viec
                    $modelStaffWorkDepartment->insert($newWorkId, $departmentId, $add_rankId, $workBeginDate);

                    # them luong cho nv
                    $responsibility = $modelStaffWorkSalary->getDefaultResponsibility();
                    $insurance = $modelStaffWorkSalary->getDefaultInsurance();
                    $fuel = $modelStaffWorkSalary->getDefaultFuel();
                    $dateOff = $modelStaffWorkSalary->getDefaultDateOff();
                    $overTimeHour = $modelStaffWorkSalary->getDefaultOverTimeHour();
                    $modelStaffWorkSalary->insert($add_totalSalary, $add_salary, $responsibility, $add_usePhone, $insurance, $fuel, $dateOff, $overTimeHour, $newWorkId);

                    # them bang cham cong theo thang
                    $toDateWork = $hFunction->lastDateOfMonthFromDate($workBeginDate);
                    $modelWork->insert($workBeginDate, $toDateWork, $newWorkId);
                    
                    # them ky nang lam viec
                    if ($hFunction->checkCount($dataJobApplicationWork)) {
                        foreach ($dataJobApplicationWork as $jobApplicationWork) {
                            $level = $jobApplicationWork->skillStatus();
                            $departmentWorkId = $jobApplicationWork->workId();
                            $modelWorkSkill->insert($level, $departmentWorkId, $newWorkId);
                        }
                    }
                }

                # them phương thuc lam viec
                # mac dinh phuong thuc lam viec
                $add_workMethod = $modelStaffWorkMethod->getDefaultMethodNotMain(); # - thu viec
                $add_applyRule = $modelStaffWorkMethod->getDefaultHasApplyRule(); # ap dung noi quy
                $modelStaffWorkMethod->insert($add_workMethod, $add_applyRule, $newStaffId, $confirmStaffId);
                return true;
            } else {
                return false;
            }
        }

    }

    #========== ========== ========== RELATION ========== ========== ==========
    #----------- nguoi phong van------------
    public function staff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staff_id', 'staff_id');
    }

    #----------- thong tin ho so ------------
    public function jobApplication()
    {
        return $this->belongsTo('App\Models\Ad3d\JobApplication\QcJobApplication', 'jobApplication_id', 'jobApplication_id');
    }

    public function lastInfoOfJobApplication($jobApplicationId)
    {
        return QcJobApplicationInterview::where('jobApplication_id', $jobApplicationId)->orderBy('interview_id', 'DESC')->first();
    }

    #============ =========== ============ GET INFO ============= =========== ==========
    # lay ho so phong van theo cong ty va trang thai xac nhan
    public function selectInfoByCompany($companyId, $confirmStatus = 100)
    {
        $modelJobApplication = new QcJobApplication();
        $listJobApplicationId = $modelJobApplication->listIdByCompany($companyId);
        if ($confirmStatus == $this->getDefaultAllConfirm()) { # tat ca thong tin
            return QcJobApplicationInterview::whereIn('jobApplication_id', $listJobApplicationId)->orderBy('interview_id', 'DESC')->select();
        } else {
            return QcJobApplicationInterview::whereIn('jobApplication_id', $listJobApplicationId)->where('interviewConfirm', $confirmStatus)->orderBy('interview_id', 'DESC')->select();
        }
    }

    #chon tat ca danh sach
    public function selectInfoAll()
    {
        return QcJobApplicationInterview::select('*');
    }

    public function getInfo($interviewId = '', $field = '')
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($interviewId)) {
            return QcJobApplicationInterview::get();
        } else {
            $result = QcJobApplicationInterview::where('interview_id', $interviewId)->first();
            if ($hFunction->checkEmpty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }

    public function pluck($column, $id = null)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($id)) {
            return $this->$column;
        } else {
            return QcJobApplicationInterview::where('interview_id', $id)->pluck($column)[0];
        }
    }

    public function interviewId()
    {
        return $this->interview_id;
    }

    public function interviewDate($interviewId = null)
    {
        return $this->pluck('interviewDate', $interviewId);
    }

    public function interviewConfirm($interviewId = null)
    {
        return $this->pluck('interviewConfirm', $interviewId);
    }

    public function agreeStatus($interviewId = null)
    {
        return $this->pluck('agreeStatus', $interviewId);
    }

    public function staffId($interviewId = null)
    {
        return $this->pluck('staff_id', $interviewId);
    }

    public function jobApplicationId($interviewId)
    {
        return $this->pluck('jobApplication_id', $interviewId);
    }

    public function createdAt($interviewId = null)
    {
        return $this->pluck('created_at', $interviewId);
    }

    # total record
    public function totalRecords()
    {
        return QcJobApplicationInterview::count();
    }

    # kiem tra co duoc phong van hay chua
    public function checkInterviewConfirm($interviewId = null)
    {
        return ($this->interviewConfirm($interviewId) == $this->getDefaultHasConfirm()) ? true : false;
    }

    # kiem tra duoc dong y hay khong
    public function checkAgreeStatus($interviewId = null)
    {
        return ($this->agreeStatus($interviewId) == $this->getDefaultHasAgree()) ? true : false;
    }

    # tong ho so chua phong van
    public function totalUnconfirmed()
    {
        return QcJobApplicationInterview::where('interviewConfirm', $this->getDefaultNotConfirm())->count();
    }

}
