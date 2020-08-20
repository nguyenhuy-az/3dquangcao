<?php

namespace App\Models\Ad3d\SalaryBeforePay;

use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Work\QcWork;
use Illuminate\Database\Eloquent\Model;

class QcSalaryBeforePay extends Model
{
    protected $table = 'qc_salary_before_pay';
    protected $fillable = ['pay_id', 'money', 'datePay', 'description', 'confirmStatus', 'confirmDate', '', 'created_at', 'work_id', 'staffPay_id'];
    protected $primaryKey = 'pay_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== INSERT && UPDATE ========== ========== ==========
    #---------- Insert ----------
    public function insert($money, $datePay, $description, $workId, $staffPayId)
    {
        $hFunction = new \Hfunction();
        $modelSalaryBeforePay = new QcSalaryBeforePay();
        $modelSalaryBeforePay->money = $money;
        $modelSalaryBeforePay->datePay = $datePay;
        $modelSalaryBeforePay->description = $description;
        $modelSalaryBeforePay->work_id = $workId;
        $modelSalaryBeforePay->staffPay_id = $staffPayId;
        $modelSalaryBeforePay->created_at = $hFunction->createdAt();
        if ($modelSalaryBeforePay->save()) {
            $this->lastId = $modelSalaryBeforePay->pay_id;
            return true;
        } else {
            return false;
        }
    }

    public function updateInfo($payId, $money, $datePay, $description)
    {
        return QcSalaryBeforePay::where('pay_id', $payId)->update(['money' => $money, 'datePay' => $datePay, 'description' => $description]);
    }

    public function updateConfirmStatus($payId)
    {
        $hFunction = new \Hfunction();
        return QcSalaryBeforePay::where('pay_id', $payId)->update(['confirmStatus' => 1, 'confirmDate' => $hFunction->carbonNow()]);
    }

    public function deleteInfo($payId = null)
    {
        $payId = (empty($payId)) ? $this->payId() : $payId;
        return QcSalaryBeforePay::where('pay_id', $payId)->delete();
    }

    //---------- nhan vien cho ung -----------
    public function staff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staffPay_id', 'staff_id');
    }

    public function infoOfStaffAndDate($staffId, $date = null)
    {
        if (!empty($date)) {
            return QcSalaryBeforePay::where('staffPay_id', $staffId)->orderBy('pay_id', 'DESC')->where('datePay', 'like', "%$date%")->get();
        } else {
            return QcSalaryBeforePay::where('staffPay_id', $staffId)->orderBy('pay_id', 'DESC')->get();
        }

    }

    //kiển tra người nhập
    public function checkStaffInput($staffId, $payId = null)
    {
        $payId = (empty($payId)) ? $this->payId() : $payId;
        return (QcSalaryBeforePay::where('staffPay_id', $staffId)->where('pay_id', $payId)->count() > 0) ? true : false;
    }

    # tong tien cua 1 nv cho úng
    public function totalMoneyOfStaffAndDate($staffId, $date)
    {
        if (!empty($date)) {
            return QcSalaryBeforePay::where('staffPay_id', $staffId)->where('datePay', 'like', "%$date%")->sum('money');
        } else {
            return QcSalaryBeforePay::where('staffPay_id', $staffId)->sum('money');
        }

    }

    # tong tien cua 1 nv cho úng da xac nhan
    public function totalMoneyConfirmedOfStaffAndDate($staffId, $date)
    {
        if (!empty($date)) {
            return QcSalaryBeforePay::where('staffPay_id', $staffId)->where('confirmStatus', 1)->where('datePay', 'like', "%$date%")->sum('money');
        } else {
            return QcSalaryBeforePay::where('staffPay_id', $staffId)->where('confirmStatus', 1)->sum('money');
        }

    }

    # tong tien cua nhieu nv cho úng
    public function totalMoneyOfListStaffAndDate($listStaffId, $date)
    {
        if (!empty($date)) {
            return QcSalaryBeforePay::whereIn('staffPay_id', $listStaffId)->where('datePay', 'like', "%$date%")->sum('money');
        } else {
            return QcSalaryBeforePay::whereIn('staffPay_id', $listStaffId)->sum('money');
        }
    }

    # tong tien cua nhieu nv cho úng da xac nhan
    public function totalMoneyConfirmedOfListStaffAndDate($listStaffId, $date)
    {
        if (!empty($date)) {
            return QcSalaryBeforePay::whereIn('staffPay_id', $listStaffId)->where('confirmStatus', 1)->where('datePay', 'like', "%$date%")->sum('money');
        } else {
            return QcSalaryBeforePay::whereIn('staffPay_id', $listStaffId)->where('confirmStatus', 1)->sum('money');
        }
    }

    //----------- lam viec ------------
    public function work()
    {
        return $this->belongsTo('App\Models\Ad3d\Work\QcWork', 'work_id', 'work_id');
    }

    public function totalMoneyOfWork($workId)
    {
        return QcSalaryBeforePay::where('work_id', $workId)->sum('money');
    }

    # tong tien ung da xac nhan
    public function totalMoneyConfirmedOfWork($workId)
    {
        return QcSalaryBeforePay::where('work_id', $workId)->where('confirmStatus', 1)->sum('money');
    }

    public function infoOfWork($workId)
    {
        return QcSalaryBeforePay::where('work_id', $workId)->orderBy('pay_id', 'DESC')->get();
    }

    # thong tin ung chua xac nhan
    public function infoUnConfirmOfWork($workId)
    {
        return QcSalaryBeforePay::where('work_id', $workId)->where('confirmStatus', 0)->orderBy('pay_id', 'DESC')->get();
    }

    #============ =========== ============ KIEM TRA THONG TIN ============= =========== ==========
    public function checkConfirm($payId = null)
    {
        return ($this->confirmStatus($payId) == 1) ? true : false;
    }

    #============ =========== ============ LAY THONG TIN ============= =========== ==========
    public function getInfo($payId = '', $field = '')
    {
        if (empty($payId)) {
            return QcSalaryBeforePay::get();
        } else {
            $result = QcSalaryBeforePay::where('pay_id', $payId)->first();
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
            return QcSalaryBeforePay::where('pay_id', $objectId)->pluck($column);
        }
    }

    public function payId()
    {
        return $this->pay_id;
    }

    public function money($payId = null)
    {
        return $this->pluck('money', $payId);
    }

    public function datePay($payId = null)
    {
        return $this->pluck('datePay', $payId);
    }

    public function description($payId = null)
    {
        return $this->pluck('description', $payId);
    }

    public function confirmStatus($payId = null)
    {
        return $this->pluck('confirmStatus', $payId);
    }

    public function confirmDate($payId = null)
    {
        return $this->pluck('confirmDate', $payId);
    }

    public function createdAt($payId = null)
    {
        return $this->pluck('created_at', $payId);
    }

    public function workId($payId = null)
    {
        return $this->pluck('work_id', $payId);
    }

    public function staffPayId($payId = null)
    {
        return $this->pluck('staffPay_id', $payId);
    }

    #============ =========== ============ STATISTICAL ============= =========== ==========
    public function totalSalaryBeforeOfCompany($listCompanyId, $dateFilter = null)
    {
        $modelStaff = new QcStaff();
        $modelWork = new QcWork();
        $listWorkId = $modelWork->listIdOfListStaffId($modelStaff->listIdOfListCompanyAndName($listCompanyId, null));
        if (empty($dateFilter)) {
            return QcSalaryBeforePay::whereIn('work_id', $listWorkId)->sum('money');
        } else {
            return QcSalaryBeforePay::whereIn('work_id', $listWorkId)->where('datePay', 'like', "%$dateFilter%")->sum('money');
        }
    }

    public function totalSalaryBeforeOfCompanyStaffDate($listCompanyId, $staffId, $dateFilter = null)
    {
        $modelStaff = new QcStaff();
        $modelWork = new QcWork();
        $listWorkId = $modelWork->listIdOfListStaffId($modelStaff->listIdOfListCompanyAndName($listCompanyId, null));
        if (empty($dateFilter)) {
            return QcSalaryBeforePay::whereIn('work_id', $listWorkId)->where('staffPay_id', $staffId)->sum('money');
        } else {
            return QcSalaryBeforePay::whereIn('work_id', $listWorkId)->where('staffPay_id', $staffId)->where('datePay', 'like', "%$dateFilter%")->sum('money');
        }
    }
}
