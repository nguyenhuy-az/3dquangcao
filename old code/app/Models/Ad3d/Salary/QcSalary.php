<?php

namespace App\Models\Ad3d\Salary;

use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\SalaryPay\QcSalaryPay;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Work\QcWork;
use Illuminate\Database\Eloquent\Model;

class QcSalary extends Model
{
    protected $table = 'qc_salary';
    protected $fillable = ['salary_id', 'mainMinute', 'plusMinute', 'minusMinute', 'beforePay', 'minusMoney', 'benefitMoney', 'benefitDescription', 'overtimeMoney','kpiMoney', 'salary', 'payStatus', 'created_at', 'work_id', 'workSalary_id', 'salaryBasic_id'];
    protected $primaryKey = 'salary_id';
    public $timestamps = false;

    private $lastId;

    //========== ========== ========== THEM && VA CAP NHAT ========== ========== ==========
    //---------- them bang luong ----------
    public function insert($mainMinute, $plusMinute, $minusMute, $beforePay, $minusMoney, $benefitMoney, $overtimeMoney, $salary, $payStatus, $workId, $workSalaryId = null, $salaryBasicId = null, $benefitDescription = null, $kpiMoney =0)
    {
        $hFunction = new \Hfunction();
        $modelSalary = new QcSalary();
        $modelSalary->mainMinute = $mainMinute;
        $modelSalary->plusMinute = $plusMinute;
        $modelSalary->minusMinute = $minusMute;
        $modelSalary->beforePay = $beforePay; //tong tien ung
        $modelSalary->minusMoney = $minusMoney; // tien da pha
        $modelSalary->benefitMoney = $benefitMoney; // thuong
        $modelSalary->benefitDescription = $benefitDescription;
        $modelSalary->overtimeMoney = $overtimeMoney; // tien p/c tang ca
        $modelSalary->kpiMoney = $kpiMoney; //thuong KPI
        $modelSalary->salary = $salary; //luong chua thanh toan
        $modelSalary->payStatus = $payStatus;
        $modelSalary->work_id = $workId;
        $modelSalary->workSalary_id = $workSalaryId;
        $modelSalary->salaryBasic_id = $salaryBasicId;
        $modelSalary->created_at = $hFunction->createdAt();
        if ($modelSalary->save()) {
            $this->lastId = $modelSalary->salary_id;
            return true;
        } else {
            return false;
        }
    }

    // lay id moi them
    public function insertGetId()
    {
        return $this->lastId;
    }

    public function updatePayStatus($salaryId)
    {
        return QcSalary::where('salary_id', $salaryId)->update(['payStatus' => 1]);
    }

    public function updateBenefitMoney($salaryId, $benefitMoney, $benefitDescription = null)
    {
        return QcSalary::where('salary_id', $salaryId)->update(['benefitMoney' => $benefitMoney, 'benefitDescription' => $benefitDescription]);
    }
    //========== ========== ========== CAC MOI QUAN HE ========== ========== ==========
    //-----------  luong co ban   ------------ phien ban cu
    public function salaryBasic()
    {
        return $this->belongsTo('App\Models\Ad3d\StaffSalaryBasic\QcStaffSalaryBasic', 'salaryBasic_id', 'salaryBasic_id');
    }

//-----------   luong co ban lam tai cty  ------------
    public function staffWorkSalary()
    {
        return $this->belongsTo('App\Models\Ad3d\StaffWorkSalary\QcStaffWorkSalary', 'workSalary_id', 'workSalary_id');
    }

    //----------- lam viec ------------
    public function work()
    {
        return $this->belongsTo('App\Models\Ad3d\Work\QcWork', 'work_id', 'work_id');
    }

    public function infoOfListWorkId($listWorkId)
    {
        return QcSalary::whereIn('work_id', $listWorkId)->orderBy('salary_id', 'DESC')->get();
    }

    public function listIdOfListWorkId($listWorkId)
    {
        return QcSalary::whereIn('work_id', $listWorkId)->orderBy('salary_id', 'DESC')->pluck('salary_id');
    }

    public function checkExistInfoOfWork($workId)
    {
        return QcSalary::where('work_id', $workId)->exists();
    }

    #----------- staff salary pay ------------
    public function salaryPay()
    {
        return $this->hasMany('App\Models\Ad3d\SalaryPay\QcSalaryPay', 'salary_id', 'salary_id');
    }

    public function salaryPayCheckExistUnConfirm($salaryId = null)
    {
        $modelSalaryPay = new QcSalaryPay();
        return $modelSalaryPay->checkExistUnConfirmOfSalary((empty($salaryId)) ? $this->salaryId() : $salaryId);
    }

    public function totalPaid($salaryId = null)
    {
        $modelSalaryPay = new QcSalaryPay();
        return $modelSalaryPay->totalPayOfSalary((empty($salaryId)) ? $this->salaryId() : $salaryId);
    }

    public function infoSalaryPay($salaryId = null)
    {
        $modelSalaryPay = new QcSalaryPay();
        return $modelSalaryPay->infoOfSalary((empty($salaryId)) ? $this->salaryId() : $salaryId);
    }

    //============ =========== ============ LAY THONG TIN ============= =========== ==========
    public function selectInfoOfListCompany($listCompanyId, $dateFilter = null, $payStatus = 3)
    {
        $modelStaff = new QcStaff();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelWork = new QcWork();
        $monthFilter = date('m');
        $yearFilter = date('Y');
        if ($monthFilter < 8 && $yearFilter < 2109) { # du lieu cu phien ban cu --  loc theo staff_id
            $listStaffId = $modelStaff->listIdOfListCompany($listCompanyId);
            $listWorkId = $modelWork->listIdOfListStaffInBeginDate($listStaffId, $dateFilter);
        } else { # du lieu phien ban moi - loc theo thong tin lam viec tai cty (companyStaffWork)
            $listCompanyStaffWorkId = $modelCompanyStaffWork->listIdOfListCompanyAndListStaff($listCompanyId);
            $listWorkId = $modelWork->listIdOfListCompanyStaffWorkBeginDate($listCompanyStaffWorkId, $dateFilter);
        }
        if ($payStatus == 3) {
            return QcSalary::whereIn('work_id', $listWorkId)->orderBy('salary_id', 'DESC')->select('*');
        } else {
            return QcSalary::whereIn('work_id', $listWorkId)->where('payStatus', $payStatus)->orderBy('salary_id', 'DESC')->select('*');
        }
    }

    public function selectInfoByListWork($listWorkId)
    {
        return QcSalary::whereIn('work_id', $listWorkId)->orderBy('salary_id', 'DESC')->select('*');
    }

    public function getInfo($timekeepingId = '', $field = '')
    {
        if (empty($timekeepingId)) {
            return QcSalary::get();
        } else {
            $result = QcSalary::where('salary_id', $timekeepingId)->first();
            if (empty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }

    public function pluck($column, $objectId = null)
    {
        if (empty($objectId)) {
            return $this->$column;
        } else {
            return QcSalary::where('salary_id', $objectId)->pluck($column);
        }
    }

    //----------- GET INFO -------------
    public function salaryId()
    {
        return $this->salary_id;
    }

    public function mainMinute($salaryId = null)
    {
        return $this->pluck('mainMinute', $salaryId);
    }

    public function plusMinute($salaryId = null)
    {
        return $this->pluck('plusMinute', $salaryId);
    }

    public function minusMinute($salaryId = null)
    {
        return $this->pluck('minusMinute', $salaryId);
    }

    public function beforePay($salaryId = null)
    {
        return $this->pluck('beforePay', $salaryId);
    }

    public function minusMoney($salaryId = null)
    {
        return $this->pluck('minusMoney', $salaryId);
    }

    public function benefitMoney($salaryId = null)
    {
        return $this->pluck('benefitMoney', $salaryId);
    }

    public function benefitDescription($salaryId = null)
    {
        return $this->pluck('benefitDescription', $salaryId);
    }

    public function overtimeMoney($salaryId = null)
    {
        return $this->pluck('overtimeMoney', $salaryId);
    }

    public function kpiMoney($salaryId = null)
    {
        return $this->pluck('kpiMoney', $salaryId);
    }

    public function salary($salaryId = null)
    {
        return $this->pluck('salary', $salaryId);
    }

    public function payStatus($salaryId = null)
    {
        return $this->pluck('payStatus', $salaryId);
    }

    public function workId($salaryId = null)
    {
        return $this->pluck('work_id', $salaryId);
    }

    public function salaryBasicId($salaryId = null)
    {
        return $this->pluck('salaryBasic_id', $salaryId);
    }

    public function createdAt($salaryId = null)
    {
        return $this->pluck('created_at', $salaryId);
    }

    public function checkPaid($salaryId = null)
    {
        return ($this->payStatus($salaryId) == 1) ? true : false;
    }
}
