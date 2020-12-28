<?php

namespace App\Models\Ad3d\Salary;

use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\KeepMoney\QcKeepMoney;
use App\Models\Ad3d\SalaryPay\QcSalaryPay;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Work\QcWork;
use Illuminate\Database\Eloquent\Model;

class QcSalary extends Model
{
    protected $table = 'qc_salary';
    protected $fillable = ['salary_id', 'mainMinute', 'plusMinute', 'minusMinute', 'beforePay', 'bonusMoney', 'minusMoney', 'benefitMoney', 'benefitDescription', 'overtimeMoney', 'kpiMoney', 'salary', 'payStatus', 'created_at', 'work_id', 'workSalary_id', 'salaryBasic_id'];
    protected $primaryKey = 'salary_id';
    public $timestamps = false;

    private $lastId;

    //========== ========== ========== THEM && VA CAP NHAT ========== ========== ==========
    # mac dinh tien cong them
    public function getDefaultBenefitMoney()
    {
        return 0;
    }

    # mac dinh tien tru
    public function getDefaultMinusMoney()
    {
        return 0;
    }

    # mac dinh tien thuong
    public function getDefaultBonusMoney()
    {
        return 0;
    }

    #mac tinh da thanh toan
    public function getDefaultHasPay()
    {
        return 1;
    }

    #mac tinh chua thanh toan
    public function getDefaultNotPay()
    {
        return 0;
    }
    //---------- them bang luong ----------
    /*
     *  salary = tien luong co ban lam viec + thuong -tien ung - phat (khong bao gom tien giu va cong them)
     * */
    public function insert($mainMinute, $plusMinute, $minusMute, $beforePay, $minusMoney, $benefitMoney, $overtimeMoney, $salary, $payStatus, $workId, $workSalaryId = null, $benefitDescription = null, $kpiMoney = 0, $bonusMoney = 0)
    {
        $hFunction = new \Hfunction();
        $modelSalary = new QcSalary();
        $modelSalary->mainMinute = $mainMinute;
        $modelSalary->plusMinute = $plusMinute;
        $modelSalary->minusMinute = $minusMute;
        #tong tien ung
        $modelSalary->beforePay = $beforePay;
        $modelSalary->bonusMoney = $bonusMoney;
        # tien da pha
        $modelSalary->minusMoney = $minusMoney;
        # thuong
        $modelSalary->benefitMoney = $benefitMoney;
        $modelSalary->benefitDescription = $benefitDescription;
        # tien p/c tang ca
        $modelSalary->overtimeMoney = $overtimeMoney;
        #thuong KPI
        $modelSalary->kpiMoney = $kpiMoney;
        #luong chua thanh toan
        $modelSalary->salary = $salary;
        $modelSalary->payStatus = $payStatus;
        $modelSalary->work_id = $workId;
        $modelSalary->workSalary_id = $workSalaryId;
        $modelSalary->salaryBasic_id = null;// phien ban cu - xoa
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

    public function checkIdNull($salaryId = null)
    {
        return (empty($salaryId)) ? $this->salaryId() : $salaryId;
    }

    public function updatePayStatus($salaryId)
    {
        return QcSalary::where('salary_id', $salaryId)->update(['payStatus' => $this->getDefaultHasPay()]);
    }

    public function updateFinishPay($salaryId)
    {
        return QcSalary::where('salary_id', $salaryId)->update(['payStatus' => $this->getDefaultHasPay()]);
    }

    public function updateUnFinishPay($salaryId)
    {
        return QcSalary::where('salary_id', $salaryId)->update(['payStatus' => $this->getDefaultNotPay()]);
    }

    public function updateBenefitMoney($salaryId, $benefitMoney, $benefitDescription = null)
    {
        return QcSalary::where('salary_id', $salaryId)->update(['benefitMoney' => $benefitMoney, 'benefitDescription' => $benefitDescription]);
    }
    //========== ========== ========== CAC MOI QUAN HE ========== ========== ==========
    //----------- GIU TIEN ------------
    public function keepMoney()
    {
        return $this->hasMany('App\Models\Ad3d\KeepMoney\QcKeepMoney', 'salary_id', 'salary_id');
    }

    # tong tien giu  tren 1 bang cham cong
    public function totalKeepMoney($salaryId = null)
    {
        $modelKeepMoney = new QcKeepMoney();
        return $modelKeepMoney->totalMoneyOfSalary($this->checkIdNull($salaryId));
    }

    //-----------  luong co ban   ------------ phien ban cu
    # phien ban cu - xoa
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

    # thong tin bang luong cua bang cham cong
    public function infoOfListWorkId($listWorkId)
    {
        return QcSalary::whereIn('work_id', $listWorkId)->orderBy('salary_id', 'DESC')->get();
    }

    # danh sach ma bang luong tu 1 danh sach bang cham cong
    public function listIdOfListWorkId($listWorkId)
    {
        return QcSalary::whereIn('work_id', $listWorkId)->orderBy('salary_id', 'DESC')->pluck('salary_id');
    }

    # kiem tra ton tai bang cham cong da xuat bang luong
    public function checkExistInfoOfWork($workId)
    {
        return QcSalary::where('work_id', $workId)->exists();
    }

    #----------- staff salary pay ------------
    public function salaryPay()
    {
        return $this->hasMany('App\Models\Ad3d\SalaryPay\QcSalaryPay', 'salary_id', 'salary_id');
    }

    # ton tai thanh toan luong chua xac nhan
    public function salaryPayCheckExistUnConfirm($salaryId = null)
    {
        $modelSalaryPay = new QcSalaryPay();
        return $modelSalaryPay->checkExistUnConfirmOfSalary($this->checkIdNull($salaryId));
    }

    # tong tien da thanh toan
    public function totalPaid($salaryId = null)
    {
        $modelSalaryPay = new QcSalaryPay();
        return $modelSalaryPay->totalPayOfSalary($this->checkIdNull($salaryId));
    }

    # tong  tien thanh toan da xac nhan
    public function totalPayConfirmed($salaryId = null)
    {
        $modelSalaryPay = new QcSalaryPay();
        return $modelSalaryPay->totalPayConfirmedOfSalary($this->checkIdNull($salaryId));
    }

    #lay thong tin thanh toan
    public function infoSalaryPay($salaryId = null)
    {
        $modelSalaryPay = new QcSalaryPay();
        return $modelSalaryPay->infoOfSalary($this->checkIdNull($salaryId));
    }

    //============ =========== ============ LAY THONG TIN ============= =========== ==========
    # chon thong tin bang luong theo danh sach ma cty
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

    public function bonusMoney($salaryId = null)
    {
        return $this->pluck('bonusMoney', $salaryId);
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

    public function workSalaryId($salaryId = null)
    {
        return $this->pluck('work_id', $salaryId);
    }

    # phien ban cu - xoa
    public function salaryBasicId($salaryId = null)
    {
        return $this->pluck('salaryBasic_id', $salaryId);
    }

    public function createdAt($salaryId = null)
    {
        return $this->pluck('created_at', $salaryId);
    }

    # kiem tra bang luong da duoc thanh toan
    public function checkPaid($salaryId = null)
    {
        return ($this->payStatus($salaryId) == $this->getDefaultHasPay()) ? true : false;
    }

    # kiem tra bang luong co duoc cong tien them hay chua
    public function checkExistBenefit($salaryId = null)
    {
        return ($this->benefitMoney($salaryId) > 0) ? true : false;
    }

    #luong co ban cua 1 bang luong
    # tinh theo bang luong khi xuat bang luong
    public function totalSalaryBasic($salaryId = null)
    {
        $dataSalary = $this->getInfo($this->checkIdNull($salaryId));
        $mainMinute = $dataSalary->mainMinute();
        $plusMinute = $dataSalary->plusMinute();
        $dataStaffWorkSalary = $dataSalary->staffWorkSalary;
        if (!empty($dataStaffWorkSalary)) {
            $overtime = $dataStaffWorkSalary->overtimeHour($dataStaffWorkSalary->workSalaryId());
            $totalSalaryOnHour = $dataStaffWorkSalary->salaryOnHour(); # lương lam trong 1 gio
        } else {
            $overtime = 0;
            $totalSalaryOnHour = 0;
        }

        $overtime = (is_int($overtime)) ? $overtime : $overtime[0];
        $moneyOfMainMinute = ($mainMinute / 60) * $totalSalaryOnHour;  # tong luong trong gio lam chinh
        $moneyOfPlusMinute = ($plusMinute / 60) * 1.5 * $totalSalaryOnHour; # tang ca nhan 1.5  - tong luong cua gio tang ca
        $allowanceOvertime = ($plusMinute / 60) * $overtime; # tien phu cap tang ca
        return (int)($moneyOfMainMinute + $moneyOfPlusMinute + $allowanceOvertime);
    }
    # kiem tra co du dieu kien giu tien hay khong
    /*
     * chi duoc giu tien khi luong co ban duong va chua thanh toan
     * */
    public function checkLicenseKeepMoney($salaryId = null)
    {
        $result = true;
        $dataSalary = $this->getInfo($this->checkIdNull($salaryId));
        if ($dataSalary->checkPaid()) {
            $result = false;
        } else {
            if ($this->getLimitKeepMoney($salaryId) <= 0) $result = false;
        }
        return $result;
    }

    # gioi han tien giu tren mot bang luong
    public function getLimitKeepMoney($salaryId = null)
    {
        $dataSalary = $this->getInfo($this->checkIdNull($salaryId));
        $dataWork = $dataSalary->work;
        # tong luong co ban
        $totalSalaryBasic = $dataWork->totalSalaryBasicOfWorkInMonth($dataWork->workId());
        # tong tien da giu
        $totalKeepMoney = $dataSalary->totalKeepMoney();
        return $totalSalaryBasic - $totalKeepMoney;
    }

    # tong luong nhan cua 1 bang luong
    /*
     * khong tinh tien vat tu chua thanh toan
     * tong luong co ban + tien cong them + tien thuong
     * */
    public function totalSalaryReceive($salaryId = null)
    {
        $dataSalary = $this->getInfo($this->checkIdNull($salaryId));
        # cong them
        $benefitMoney = $dataSalary->benefitMoney();
        # tien thuong da ap dung
        $bonusMoney = $dataSalary->bonusMoney();
        $dataWork = $dataSalary->work;
        # tong luong co ban
        $totalSalaryBasic = $dataWork->totalSalaryBasicOfWorkInMonth($dataWork->workId());
        return $totalSalaryBasic + $benefitMoney + $bonusMoney;
    }
    # tong tien luong chua thanh toan cua 1 bang luong
    /*
     * khong tinh tien vat tu chua thanh toan
     *
     * */
    public function totalSalaryUnpaid($salaryId = null)
    {
        $dataSalary = $this->getInfo($this->checkIdNull($salaryId));
        # thong tin lam viec
        $dataWork = $dataSalary->work;
        # tien ung
        $totalMoneyConfirmedBeforePay = $dataWork->totalMoneyConfirmedBeforePay();
        # tien phat
        $minusMoney = $dataSalary->minusMoney();
        # tong tien nhan trong thang
        $totalSalaryReceive = $dataSalary->totalSalaryReceive();
        # tong tien giu trong thang
        $totalKeepMoney = $dataSalary->totalKeepMoney();


        # tien da thanh toan
        $totalPaid = $dataSalary->totalPaid();

        return $totalSalaryReceive - $totalMoneyConfirmedBeforePay - $totalKeepMoney - $totalPaid - $minusMoney;
    }
}
