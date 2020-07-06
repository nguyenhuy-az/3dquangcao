<?php

namespace App\Models\Ad3d\Work;

use App\Models\Ad3d\Bonus\QcBonus;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\KeepMoney\QcKeepMoney;
use App\Models\Ad3d\LicenseLateWork\QcLicenseLateWork;
use App\Models\Ad3d\LicenseOffWork\QcLicenseOffWork;
use App\Models\Ad3d\MinusMoney\QcMinusMoney;
use App\Models\Ad3d\Salary\QcSalary;
use App\Models\Ad3d\SalaryBeforePay\QcSalaryBeforePay;
use App\Models\Ad3d\SalaryBeforePayRequest\QcSalaryBeforePayRequest;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\StaffWorkSalary\QcStaffWorkSalary;
use App\Models\Ad3d\Timekeeping\QcTimekeeping;
use App\Models\Ad3d\TimekeepingProvisional\QcTimekeepingProvisional;
use Illuminate\Database\Eloquent\Model;

class QcWork extends Model
{
    protected $table = 'qc_works';
    protected $fillable = ['work_id', 'fromDate', 'toDate', 'salaryStatus', 'action', 'created_at', 'staff_id', 'companyStaffWork_id'];
    protected $primaryKey = 'work_id';
    public $timestamps = false;

    private $lastId;

    //========== ========== ========== INSERT && UPDATE ========== ========== ==========
    //---------- them bang cham cong moi ----------
    public function insert($fromDate, $toDate, $companyStaffWorkId)
    {
        $hFunction = new \Hfunction();
        $modelWork = new QcWork();
        $modelWork->fromDate = $fromDate;
        $modelWork->toDate = $toDate;
        $modelWork->companyStaffWork_id = $companyStaffWorkId;
        $modelWork->created_at = $hFunction->createdAt();
        if ($modelWork->save()) {
            $this->lastId = $modelWork->work_id;
            return true;
        } else {
            return false;
        }
    }

    //lay id moi them
    public function insertGetId()
    {
        return $this->lastId;
    }

    public function checkIdNull($workId)
    {
        return (empty($workId)) ? $this->workId() : $workId;
    }


    // ket thuc cong viec lam trong thang
    public function endWork($workId = null)
    {
        $modelWork = new QcWork();
        $workId = $this->checkIdNull($workId);
        $modelWork->disableWord($workId);
    }

    // xuat bang luong bang luong cho nv
    public function makeSalaryOfWork($workId, $benefit, $workStatus)
    {
        $hFunction = new \Hfunction();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelStaffWorkSalary = new QcStaffWorkSalary();
        $modelSalary = new QcSalary();
        $modelBonus = new QcBonus();
        $modelMinusMoney = new QcMinusMoney();
        # kiem tra da tinh luong chưa
        if (!$modelSalary->checkExistInfoOfWork($workId)) { # chưa tinh luong
            #duyet tu dong thong tin thuong
            $modelBonus->autoCheckApplyBonusEndWork($workId);
            #duyet tu dong thong tin phat
            $modelMinusMoney->autoCheckApplyMinusMoneyEndWork($workId);
            # thong tin lam viec
            $dataWork = $this->getInfo($workId);
            $mainMinute = $dataWork->sumMainMinute();
            $plusMinute = $dataWork->sumPlusMinute();
            $minusMinute = $dataWork->sumMinusMinute();
            $totalBeforePay = $dataWork->totalMoneyBeforePay();
            # tong tien thuong duoc ap dung
            $totalBonusMoney = $dataWork->totalMoneyBonusApplied();
            # tong tien phat da duoc ap dung phat
            $totalMinusMoney = $dataWork->totalMoneyMinusApplied();

            $companyStaffWorkId = $dataWork->companyStaffWorkId();
            # phien ban moi - nv lam nhieu cty
            if (!empty($companyStaffWorkId)) {
                $dataStaffWorkSalary = $modelStaffWorkSalary->infoActivityOfWork($companyStaffWorkId);
            } else {
                # truong hop phien ban cu chua cap nhat
                $staffId = $dataWork->staffId();
                $dataStaffWorkSalary = $modelCompanyStaffWork->staffWorkSalaryActivityOfStaff($staffId);
                if ($hFunction->checkCount($dataStaffWorkSalary)) $companyStaffWorkId = $dataStaffWorkSalary->workId();
            }
            if ($hFunction->checkCount($dataStaffWorkSalary)) {
                $workSalaryId = $dataStaffWorkSalary->workSalaryId();
                # phu cap tang ca
                $overtimeHour = $dataStaffWorkSalary->overtimeHour($workSalaryId);
                $overtimeHour = (is_int($overtimeHour)) ? $overtimeHour : $overtimeHour[0];

                $totalUnpaidSalary = (int)($this->totalSalaryBasicOfWorkInMonth($workId) - $totalBeforePay + $totalBonusMoney - $totalMinusMoney);
                $overtimeMoney = ($plusMinute / 60) * $overtimeHour;
                if ($modelSalary->insert($mainMinute, $plusMinute, $minusMinute, $totalBeforePay, $totalMinusMoney, $benefit, $overtimeMoney, $totalUnpaidSalary, 0, $workId, $workSalaryId, null, null, 0, $totalBonusMoney)) {
                    # vo hieu hoa bang cam cong cu
                    $this->endWork($workId);
                    if ($this->confirmExportSalary($workId)) {
                        if ($workStatus == 1) { # tiep tuc lam viec
                            if (!$this->checkCompanyStaffWorkActivity($companyStaffWorkId)) { # khong ton tai thong tin lam viec chua ket thuc
                                #them thong tin lam viec trong thang
                                $currentDate = date('Y-m-d');
                                //$fromDateWork = $hFunction->firstDateOfMonthFromDate($currentDate);
                                $toDateWork = $hFunction->lastDateOfMonthFromDate($currentDate);
                                if (count($companyStaffWorkId) > 0) { # chi them cham cong khi co lam o 1 cty  - phien ban moi
                                    $this->insert($currentDate, $toDateWork, $companyStaffWorkId);
                                }

                            }

                        }
                    }

                }
            }
        }

    }

    # tong luong duoc nhan chua tinh cac chi phi khac
    public function totalSalaryBasicOfWorkInMonth($workId = null)
    {
        $modelStaff = new QcStaff();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelStaffWorkSalary = new QcStaffWorkSalary();
        $workId = $this->checkIdNull($workId);
        $dataWork = $this->getInfo($workId);
        $mainMinute = $this->sumMainMinute($workId);
        $plusMinute = $this->sumPlusMinute($workId);
        $companyStaffWorkId = $dataWork->companyStaffWorkId();
        if (!empty($companyStaffWorkId)) {
            $dataStaffWorkSalary = $modelStaffWorkSalary->infoActivityOfWork($companyStaffWorkId);
            if (!empty($dataStaffWorkSalary)) {
                $overtime = $dataStaffWorkSalary->overtimeHour($dataStaffWorkSalary->workSalaryId());
                $totalSalaryOnHour = $dataStaffWorkSalary->salaryOnHour(); # lương lam trong 1 gio
            } else {
                $overtime = 0;
                $totalSalaryOnHour = 0;
            }


        } else {

            # truong hop phien ban cu chua cap nhat
            $staffId = $this->staffIdOld($workId);
            $dataStaffWorkSalary = $modelCompanyStaffWork->staffWorkSalaryActivityOfStaff($staffId);
            if (count($dataStaffWorkSalary) > 0) {
                $overtime = $dataStaffWorkSalary->overtimeHour($dataStaffWorkSalary->workSalaryId());
                $totalSalaryOnHour = $dataStaffWorkSalary->salaryOnHour(); # lương lam trong 1 gio
            } else { # theo ban luong phien bang cu
                $salaryBasic = 100;// $dataWork->staff->salaryBasicOfStaff($staffId);
                $totalSalaryOnHour = floor($salaryBasic / 208); # lương lam trong 1 gio
                $overtime = 10;
            }
        }

        $overtime = (is_int($overtime)) ? $overtime : $overtime[0];
        $moneyOfMainMinute = ($mainMinute / 60) * $totalSalaryOnHour;  # tong luong trong gio lam chinh
        $moneyOfPlusMinute = ($plusMinute / 60) * 1.5 * $totalSalaryOnHour; # tang ca nhan 1.5  - tong luong cua gio tang ca
        $allowanceOvertime = ($plusMinute / 60) * $overtime; # tien phu cap tang ca
        return (int)($moneyOfMainMinute + $moneyOfPlusMinute + $allowanceOvertime);
    }

    public function confirmExportSalary($workId = null)
    {
        return QcWork::where('work_id', $this->checkIdNull($workId))->update(['salaryStatus' => 1, 'action' => 0]);
    }

    public function disableWord($workId = null)
    {
        return QcWork::where('work_id', $this->checkIdNull($workId))->update(['action' => 0]);
    }

    //========== ========== ========== CAC MOI QUAN HE ========== ========== ==========
    //----------- THONG TIN LAM VIEC TAI CHI NHANH /CTY ------------
    public function companyStaffWork()
    {
        return $this->belongsTo('App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork', 'companyStaffWork_id', 'work_id');
    }

    public function checkCompanyStaffWorkActivity($companyStaffWorkId)
    {
        return QcWork::where('companyStaffWork_id', $companyStaffWorkId)->where('action', 1)->exists();
    }

    # vo hieu hoa cham cong thong tin lam vie
    public function disableOfCompanyStaffWork($companyStaffWorkId)
    {
        return QcWork::where('companyStaffWork_id', $companyStaffWorkId)->update(['action' => 0]);
    }

    # lay 1 bang cham cong dang lam viec
    public function infoActivityOfCompanyStaffWork($companyStaffWorkId)
    {
        return QcWork::where('companyStaffWork_id', $companyStaffWorkId)->where('action', 1)->first();
    }

    # lay nhieu bang cham cong dang lam viec
    public function infoActivityOfListCompanyStaffWork($listCompanyStaffWorkId)
    {
        return QcWork::whereIn('companyStaffWork_id', $listCompanyStaffWorkId)->where('action', 1)->get();
    }

    public function listIdOfListCompanyStaffWork($listCompanyStaffWorkId)
    {
        return QcWork::whereIn('companyStaffWork_id', $listCompanyStaffWorkId)->pluck('work_id');
    }

    public function listIdActivityOfListCompanyStaffWork($listCompanyStaffWorkId)
    {
        return QcWork::whereIn('companyStaffWork_id', $listCompanyStaffWorkId)->where('action', 1)->pluck('work_id');
    }

    //----------- NHAN VIEN ------------
    public function staff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staff_id', 'staff_id');
    }

    public function infoOfListStaff($listStaffId)
    {
        return QcWork::whereIn('staff_id', $listStaffId)->get();
    }

    public function infoActivityOfListStaff($listStaffId)
    {
        return QcWork::where('action', 1)->whereIn('staff_id', $listStaffId)->get();
    }

    # vo hieu hoa thong tiin lam viec cua nv - phien ban cú
    public function disableOfStaff($staffId)
    {
        return QcWork::where('staff_id', $staffId)->update(['action' => 0]);
    }

    public function infoActivityOfStaff($staffId = null, $date = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $dataCompanyStaffWork = $modelCompanyStaffWork->infoActivityOfStaff($staffId);
        if (count($dataCompanyStaffWork) > 0) {
            if (!empty($date)) {
                return QcWork::where(['companyStaffWork_id' => $dataCompanyStaffWork->workId()])->where('fromDate', 'like', "%$date%")->where('action', 1)->first();
            } else {
                return QcWork::where(['companyStaffWork_id' => $dataCompanyStaffWork->workId(), 'action' => 1])->first();
            }
        } else {
            if (!empty($date)) {
                return QcWork::where(['staff_id' => $staffId])->where('fromDate', 'like', "%$date%")->where('action', 1)->first();
            } else {
                return QcWork::where(['staff_id' => $staffId, 'action' => 1])->first();
            }
        }

    }

    public function firstInfoOfStaff($staffId = null, $date = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $dataCompanyStaffWork = $modelCompanyStaffWork->infoActivityOfStaff($staffId);
        if (count($dataCompanyStaffWork) > 0) {
            if (!empty($date)) {
                return QcWork::where(['companyStaffWork_id' => $dataCompanyStaffWork->workId()])->where('fromDate', 'like', "%$date%")->first();
            } else {
                return QcWork::where(['companyStaffWork_id' => $dataCompanyStaffWork->workId()])->first();
            }
        } else {
            if (!empty($date)) {
                return QcWork::where(['staff_id' => $staffId])->where('fromDate', 'like', "%$date%")->first();
            } else {
                return QcWork::where(['staff_id' => $staffId])->first();
            }
        }
        /*
        if (!empty($date)) {
            return QcWork::where(['staff_id' => $staffId])->where('fromDate', 'like', "%$date%")->first();
        } else {
            return QcWork::where(['staff_id' => $staffId])->orderBy('work_id', 'DESC')->first();
        }*/

    }

    public function listIdOfListStaffId($listStaffId)
    {
        return QcWork::whereIn('staff_id', $listStaffId)->orderBy('work_id', 'DESC')->pluck('work_id');
    }

    public function listIdOfListStaffInBeginDate($listStaffId, $dateFilter = null)
    {
        if (empty($dateFilter)) {
            return QcWork::whereIn('staff_id', $listStaffId)->orderBy('work_id', 'DESC')->pluck('work_id');
        } else {
            return QcWork::whereIn('staff_id', $listStaffId)->where('fromDate', 'like', "%$dateFilter%")->orderBy('work_id', 'DESC')->pluck('work_id');
        }

    }

    public function listIdOfListCompanyStaffWorkBeginDate($listCompanyStaffWorkId, $dateFilter = null)
    {
        if (empty($dateFilter)) {
            return QcWork::whereIn('companyStaffWork_id', $listCompanyStaffWorkId)->orderBy('work_id', 'DESC')->pluck('work_id');
        } else {
            return QcWork::whereIn('companyStaffWork_id', $listCompanyStaffWorkId)->where('fromDate', 'like', "%$dateFilter%")->orderBy('work_id', 'DESC')->pluck('work_id');
        }

    }

    public function listIdOfListCompanyStaffId($listCompanyStaffWorkId)
    {
        return QcWork::whereIn('companyStaffWork_id', $listCompanyStaffWorkId)->orderBy('work_id', 'DESC')->pluck('work_id');
    }

    public function arrayIdOfListStaffId($listStaffId = null)
    {
        return QcWork::whereIn('staff_id', $listStaffId)->orderBy('work_id', 'DESC')->pluck('work_id')->toArray();
    }

    public function existStaffActivity($staffId = null)
    {
        return (count($this->infoActivityOfStaff($staffId)) > 0) ? true : false;
    }

    public function staffInfoOfWork($workId = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $dataCompanyStaffWork = $modelCompanyStaffWork->getInfo($this->companyStaffWorkId($this->checkIdNull($workId)));
        if (count($dataCompanyStaffWork) > 0) {
            return $dataCompanyStaffWork->staff;
        } else {
            return $this->staff;
        }
    }

    public function companyIdOfWork($workId = null)
    {
        $modelStaff = new QcStaff();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $workId = $this->checkIdNull($workId);
        $companyStaffWorkId = $this->companyStaffWorkId($workId);
        if (!empty($companyStaffWorkId)) { # du lieu moi
            return $modelCompanyStaffWork->companyId($companyStaffWorkId);
        } else { # du lieu cu
            return $modelStaff->companyId($this->staffId($workId));
        }
    }
    //----------- tinh luong ------------
    # tính tien xang bi tru khi di lam ko du ngay
    public function totalMinusFuelInMonth($workId = null)
    {
        $modelStaffWorkSalary = new QcStaffWorkSalary();
        $companyStaffWorkId = $this->companyStaffWorkId($workId);
        $dataStaffWorkSalary = $modelStaffWorkSalary->infoActivityOfWork($companyStaffWorkId);
        if (count($dataStaffWorkSalary) > 0) {
            $fuel = $dataStaffWorkSalary->fuel(); # phu cap di lai trong thang (26 ngay)
            $mainMinute = $this->sumMainMinute($workId); # lay gio lam chinh
            return (12480 - $mainMinute) * ($fuel / 12480); #so tien bi tru do lam ko du 26 ngay
        } else {
            return 0;
        }
    }

    //----------- lương ------------
    public function salary()
    {
        return $this->hasMany('App\Models\Ad3d\Salary\QcSalary', 'work_id', 'work_id');
    }

    //----------- chi tiết ứng lương ------------
    public function salaryBeforePay()
    {
        return $this->hasMany('App\Models\Ad3d\SalaryBeforePay\QcSalaryBeforePay', 'work_id', 'work_id');
    }

    public function totalMoneyBeforePay($workId = null)
    {
        $modelSalaryBeforePay = new QcSalaryBeforePay();
        return $modelSalaryBeforePay->totalMoneyOfWork($this->checkIdNull($workId));
    }

    # tong tien ung da xac nhan
    public function totalMoneyConfirmedBeforePay($workId = null)
    {
        $modelSalaryBeforePay = new QcSalaryBeforePay();
        return $modelSalaryBeforePay->totalMoneyConfirmedOfWork($this->checkIdNull($workId));
    }

    public function infoBeforePayOfWork($workId = null)
    {
        $modelSalaryBeforePay = new QcSalaryBeforePay();
        return $modelSalaryBeforePay->infoOfWork($this->checkIdNull($workId));
    }

    //----------- chi tiết yêu cầu ứng lương ------------
    public function salaryBeforePayRequest()
    {
        return $this->hasMany('App\Models\Ad3d\SalaryBeforePayRequest\QcSalaryBeforePayRequest', 'work_id', 'work_id');
    }

    public function totalMoneyBeforePayRequest($workId = null)
    {
        $modelSalaryBeforePayRequest = new QcSalaryBeforePayRequest();
        return $modelSalaryBeforePayRequest->totalMoneyConfirmedOfWork($this->checkIdNull($workId));
    }

    public function infoBeforePayRequestOfWork($workId = null)
    {
        $modelSalaryBeforePayRequest = new QcSalaryBeforePayRequest();
        return $modelSalaryBeforePayRequest->infoOfWork($this->checkIdNull($workId));
    }

    public function checkUnconfirmedBeforePayRequest($workId = null)
    {
        $modelSalaryBeforePayRequest = new QcSalaryBeforePayRequest();
        return $modelSalaryBeforePayRequest->existUnconfirmedOfWork($this->checkIdNull($workId));
    }

    //----------- thuong ------------
    public function bonus()
    {
        return $this->hasMany('App\Models\Ad3d\Bonus\QcBonus', 'work_id', 'work_id');
    }

    public function infoBonusOfWork($workId = null)
    {
        $modelBonus = new QcBonus();
        return $modelBonus->infoOfWork($this->checkIdNull($workId));
    }

    public function totalMoneyBonus($workId = null)
    {
        $modelBonus = new QcBonus();
        return $modelBonus->totalMoneyOfWork($this->checkIdNull($workId));
    }

    # tong tien phat duoc ap dung
    public function totalMoneyBonusApplied($workId = null)
    {
        $modelBonus = new QcBonus();
        return $modelBonus->totalMoneyAppliedOfWork($this->checkIdNull($workId));
    }

    //----------- phạt ------------
    public function minusMoney()
    {
        return $this->hasMany('App\Models\Ad3d\MinusMoney\QcMinusMoney', 'work_id', 'work_id');
    }

    public function infoMinusMoneyOfWork($workId = null)
    {
        $modelMinusMoney = new QcMinusMoney();
        return $modelMinusMoney->infoOfWork($this->checkIdNull($workId));
    }

    # tong tien phat tam thoi
    public function totalMoneyMinus($workId = null)
    {
        $modelMinusMoney = new QcMinusMoney();
        return $modelMinusMoney->totalMoneyOfWork($this->checkIdNull($workId));
    }

    # tong tien phat duoc ap dung
    public function totalMoneyMinusApplied($workId = null)
    {
        $modelMinusMoney = new QcMinusMoney();
        return $modelMinusMoney->totalMoneyAppliedOfWork($this->checkIdNull($workId));
    }

    //----------- chấm công tạm ------------
    public function timekeepingProvisional()
    {
        return $this->hasMany('App\Models\Ad3d\TimekeepingProvisional\QcTimekeepingProvisional', 'work_id', 'work_id');
    }

    public function infoTimekeepingProvisional($workId, $orderBy = null)
    {
        $modelTimekeeping = new QcTimekeepingProvisional();
        return $modelTimekeeping->infoOfWork($this->checkIdNull($workId), $orderBy);
    }

    # lay thong tin cham cong theo ngay
    public function timekeepingProvisionalOfDate($workId, $date)
    {
        $modelTimekeepingProvisional = new QcTimekeepingProvisional();
        return $modelTimekeepingProvisional->getInfoOfWorkAndDate($workId, $date);
    }

    // kiem tra thong tin lam viec
    public function checkAutoTimekeepingOfActivityWork()
    {
        $hFunction = new \Hfunction();
        $modelTimekeepingProvisional = new QcTimekeepingProvisional();
        $currentDate = $hFunction->currentDate();// lay ngay hien tai
        $currentDay = (int)$hFunction->currentDay();
        $currentHour = (int)$hFunction->currentHour();// gio hien ta
        $dataWork = QcWork::where('action', 1)->get();
        if (count($dataWork) > 0) {
            foreach ($dataWork as $work) {
                $workId = $work->workId();
                $dateBegin = $work->fromDate();
                $dayBegin = (int)date('d', strtotime($dateBegin));
                $dateEnd = $work->toDate();
                $dayEnd = date('d', strtotime($dateEnd));
                $dateCheck = date('Y-m-d', strtotime($dateBegin)); # chuyen datetime -> date // tranh xet cung ngay
                if ($dateCheck < $currentDate && $currentHour > 8) { # ngay kiem tra nho hon ngay hien tai - KIEM TRA NGAY TRƯƠC DO - va sau 8h ngay hom sau
                    for ($i = $dayBegin; $i < $currentDay; $i++) {
                        $modelTimekeepingProvisional->checkAutoTimekeepingOfWorkAndDate($workId, $dateCheck);
                        $dateCheck = date('Y-m-d', strtotime($hFunction->datetimePlusDay($dateCheck, 1)));
                    }
                }
            }
        }
    }

    //----------- license - off-work ------------
    public function timekeepingOffWork()
    {
        return $this->hasMany('App\Models\Ad3d\LicenseOffWork\QcLicenseOffWork', 'work_id', 'work_id');
    }

    public function infoOffWork($workId, $orderBy = null)
    {
        $modelOffWork = new QcLicenseOffWork();
        return $modelOffWork->infoOfWork($this->checkIdNull($workId), $orderBy);
    }


    //----------- di lam tre ------------
    public function timekeepingLateWork()
    {
        return $this->hasMany('App\Models\Ad3d\LicenseLateWork\QcLicenseLateWork', 'work_id', 'work_id');
    }

    public function infoLateWork($workId, $orderBy = null)
    {
        $modelLateWork = new QcLicenseLateWork();
        return $modelLateWork->infoOfWork($this->checkIdNull($workId), $orderBy);
    }

    //----------- cham cong ------------
    public function timekeeping()
    {
        return $this->hasMany('App\Models\Ad3d\Timekeeping\QcTimekeeping', 'work_id', 'work_id');
    }

    public function infoTimekeeping($workId = null, $orderBy = null)
    {
        $modelTimekeeping = new QcTimekeeping();
        return $modelTimekeeping->infoOfWork($this->checkIdNull($workId), $orderBy);
    }

    public function sumMainMinute($workId = null)
    {
        $modelTimekeeping = new QcTimekeeping();
        return $modelTimekeeping->sumMainMinuteOfWork($this->checkIdNull($workId));
    }

    public function sumPlusMinute($workId = null)
    {
        $modelTimekeeping = new QcTimekeeping();
        return $modelTimekeeping->sumPlusMinuteOfWork($this->checkIdNull($workId));
    }

    public function sumMinusMinute($workId = null)
    {
        $modelTimekeeping = new QcTimekeeping();
        return $modelTimekeeping->sumMinusMinuteOfWork($this->checkIdNull($workId));
    }

    public function sumOffWork($workId = null)
    {
        $modelTimekeeping = new QcTimekeeping();
        return $modelTimekeeping->sumOffWork($this->checkIdNull($workId));
    }

    public function sumOffWorkTrue($workId = null)
    {
        $modelTimekeeping = new QcTimekeeping();
        return $modelTimekeeping->sumOffWorkTrue($this->checkIdNull($workId));
    }

    public function sumOffWorkFalse($workId = null)
    {
        $modelTimekeeping = new QcTimekeeping();
        return $modelTimekeeping->sumOffWorkFalse($this->checkIdNull($workId));
    }

    public function totalCurrentSalary($workId = null)
    {
        return $this->totalSalaryBasicOfWorkInMonth($this->checkIdNull($workId));
        /*$dataWork = $this->getInfo($workId);
        $salaryBasic = $dataWork->staff->salaryBasicOfStaff();
        $priceHours = $salaryBasic / 26 / 8;
        $totalMinutePay = $dataWork->sumMainMinute() + ($this->sumPlusMinute() * 1.5) - $dataWork->sumMinusMinute();
        return (int)(($totalMinutePay / 60) * $priceHours);*/
    }

    public function limitBeforePay($workId = null)
    {
        $workId = $this->checkIdNull($workId);
        $totalSalary = $this->totalCurrentSalary($workId);
        $limitBeforePay = (($totalSalary - $this->totalMoneyBeforePay($workId) - $this->totalMoneyMinus($workId)) * 0.6);
        return (int)$limitBeforePay - ($limitBeforePay % 100000);

    }

    //============ =========== ============ GET INFO ============= =========== ==========
    public function getInfo($workId = '', $field = '')
    {
        if (empty($workId)) {
            return QcWork::where('action', 1)->get();
        } else {
            $result = QcWork::where('work_id', $workId)->first();
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
            return QcWork::where('work_id', $objectId)->pluck($column);
        }
    }

    //----------- GET INFO -------------
    public function workId()
    {
        return $this->work_id;
    }

    public function fromDate($workId = null)
    {
        return $this->pluck('fromDate', $workId);
    }

    public function toDate($workId = null)
    {
        return $this->pluck('toDate', $workId);
    }

    public function salaryStatus($workId = null)
    {
        return $this->pluck('salaryStatus', $workId);
    }

    public function action($workId = null)
    {
        return $this->pluck('action', $workId);
    }


    public function companyStaffWorkId($workId = null)
    {
        return $this->pluck('companyStaffWork_id', $workId);
    }


    public function staffId($workId = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $companyStaffWorkId = $this->companyStaffWorkId($workId);
        if (empty($companyStaffWorkId)) {
            return $this->pluck('staff_id', $workId);
        } else {
            return $modelCompanyStaffWork->staffId($companyStaffWorkId)[0];
        }

    }

    public function staffIdOld($workId = null) # phien ban cu
    {
        return $this->pluck('staff_id', $workId);
    }

    public function createdAt($workId = null)
    {
        return $this->pluck('created_at', $workId);
    }

    //----------- Kiểm tra thông tin -------------
    public function checkEndWorkOfMonth()
    {
        $hFunction = new \Hfunction();
        $hFunction->dateDefaultHCM();
        $currentDate = date('Y-m-d');
        # lay ngay dau thang
        $firstDateOfMonth = $hFunction->firstDateOfMonthFromDate($currentDate);
        //$lastDateOfMonth = $hFunction->lastDateOfMonthFromDate($currentDate); # lay ngay cuoi thang
        # lay nhung bang cham cong dang lam
        $dataWorkActivity = QcWork::where('action', 1)->get();
        if ($hFunction->checkCount($dataWorkActivity)) {
            foreach ($dataWorkActivity as $work) {
                $workId = $work['work_id'];
                $firstDateOfWork = date('Y-m-01', strtotime($work['fromDate']));
                # phien ban moi - nv lam nhieu cty
                if ($firstDateOfMonth > $firstDateOfWork) { // qua thang moi
                    # vo hieu hoa bang cham cong cu
                    $this->disableWord($workId);
                    # xuat bang luong cho NV
                    $this->makeSalaryOfWork($workId, 0, 1);

                }

            }
        }
    }

    public function checkActivity($workId = null)
    {
        return ($this->action($workId) == 0) ? false : true;
    }

    public function checkSalaryStatus($workId = null)
    {
        return ($this->salaryStatus($workId) == 0) ? false : true;
    }

    public function existTimeEndIsNullInTimekeeping($workId = null)
    {
        $modelTimekeeping = new QcTimekeeping();
        return $modelTimekeeping->timeEndIsNullOfWork($this->checkIdNull($workId));
    }
}
