<?php

namespace App\Models\Ad3d\KeepMoneyPay;

use Illuminate\Database\Eloquent\Model;

class QcKeepMoneyPay extends Model
{
    protected $table = 'qc_keep_money_pay';
    protected $fillable = ['pay_id', 'money', 'payDate', 'confirmStatus', 'created_at', 'keep_id', 'staff_id'];
    protected $primaryKey = 'pay_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== INSERT && UPDATE ========== ========== ==========
    #---------- Insert ----------
    public function insert($money, $payDate, $keepId, $staffPayId)
    {
        $hFunction = new \Hfunction();
        $modelKeepMoneyPay = new QcKeepMoneyPay();
        $modelKeepMoneyPay->money = $money;
        $modelKeepMoneyPay->payDate = $payDate;
        $modelKeepMoneyPay->keep_id = $keepId;
        $modelKeepMoneyPay->staff_id = $staffPayId;
        $modelKeepMoneyPay->created_at = $hFunction->createdAt();
        if ($modelKeepMoneyPay->save()) {
            $this->lastId = $modelKeepMoneyPay->pay_id;
            return true;
        } else {
            return false;
        }
    }

    //---------- nhan vien xac nhan -----------
    public function staff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staff_id', 'staff_id');
    }

    public function infoOfStaffAndDate($staffId, $date = null)
    {
        if (!empty($date)) {
            return QcKeepMoneyPay::where('staff_id', $staffId)->where('payDate', 'like', "%$date%")->orderBy('payDate', 'DESC')->get();
        } else {
            return QcKeepMoneyPay::where('staff_id', $staffId)->orderBy('payDate', 'DESC')->get();
        }
    }

    public function infoConfirmedOfStaffAndDate($staffId, $date = null)
    {
        if (!empty($date)) {
            return QcKeepMoneyPay::where('staff_id', $staffId)->where('confirmStatus', 1)->where('payDate', 'like', "%$date%")->orderBy('payDate', 'DESC')->get();
        } else {
            return QcKeepMoneyPay::where('staff_id', $staffId)->where('confirmStatus', 1)->orderBy('payDate', 'DESC')->get();
        }
    }

    public function totalMoneyOfStaffAndDate($staffId, $date)
    {
        if (!empty($date)) {
            return QcKeepMoneyPay::where('staff_id', $staffId)->where('payDate', 'like', "%$date%")->sum('money');
        } else {
            return QcKeepMoneyPay::where('staff_id', $staffId)->sum('money');
        }

    }

    public function totalMoneyConfirmedOfStaffAndDate($staffId, $date)
    {
        if (!empty($date)) {
            return QcKeepMoneyPay::where('staff_id', $staffId)->where('confirmStatus', 1)->where('payDate', 'like', "%$date%")->sum('money');
        } else {
            return QcKeepMoneyPay::where('staff_id', $staffId)->where('confirmStatus', 1)->sum('money');
        }

    }

    public function totalMoneyOfListStaffAndDate($listStaffId, $date)
    {
        if (!empty($date)) {
            return QcKeepMoneyPay::whereIn('staff_id', $listStaffId)->where('payDate', 'like', "%$date%")->sum('money');
        } else {
            return QcKeepMoneyPay::whereIn('staff_id', $listStaffId)->sum('money');
        }

    }

    public function totalMoneyConfirmedOfListStaffAndDate($listStaffId, $date)
    {
        if (!empty($date)) {
            return QcKeepMoneyPay::whereIn('staff_id', $listStaffId)->where('confirmStatus', 1)->where('payDate', 'like', "%$date%")->sum('money');
        } else {
            return QcKeepMoneyPay::whereIn('staff_id', $listStaffId)->where('confirmStatus', 1)->sum('money');
        }

    }

    //----------- giu tien ------------
    public function keepMoney()
    {
        return $this->belongsTo('App\Models\Ad3d\KeepMoney\QcKeepMoney', 'keep_id', 'keep_id');
    }

    # thong tin thanh toan chua xac nhan cua nhieu lan giu
    public function getInfoUnConfirmOfListKeepId($listKeepId)
    {
        return QcKeepMoneyPay::whereIn('keep_id', $listKeepId)->where('confirmStatus', 0)->get();
    }

    # thong tin thanh toan chua xac nhan cua 1 bang luonglan giu
    public function getInfoUnConfirmOfKeep($keepId)
    {
        return QcKeepMoneyPay::where('keep_id', $keepId)->where('confirmStatus', 0)->get();
    }

    public function confirmReceiveOfKeep($keepId)
    {
        return QcKeepMoneyPay::where('keep_id', $keepId)->where('confirmStatus', 0)->update(['confirmStatus' => 1]);
    }


    public function infoOfKeep($keepId)
    {
        return QcKeepMoneyPay::where('keep_id', $keepId)->get();
    }

    public function totalPayOfKeep($keepId)
    {
        return QcKeepMoneyPay::where('keep_id', $keepId)->sum('money');
    }

    public function totalPayConfirmedOfKeep($keepId)
    {
        return QcKeepMoneyPay::where('keep_id', $keepId)->where('confirmStatus', 1)->sum('money');
    }

    public function checkExistUnConfirmOfKeep($keepId)
    {
        return QcKeepMoneyPay::where('keep_id', $keepId)->where('confirmStatus', 0)->exists('*');
    }

    #============ =========== ============ GET INFO ============= =========== ==========
    public function getListId()
    {
        return QcKeepMoneyPay::pluck('keep_id');
    }

    public function getInfo($payId = '', $field = '')
    {
        if (empty($payId)) {
            return QcKeepMoneyPay::get();
        } else {
            $result = QcKeepMoneyPay::where('pay_id', $payId)->first();
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
            return QcKeepMoneyPay::where('pay_id', $objectId)->pluck($column);
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

    public function payDate($payId = null)
    {
        return $this->pluck('payDate', $payId);
    }

    public function confirmStatus($payId = null)
    {
        return $this->pluck('confirmStatus', $payId);
    }

    public function keepId($payId = null)
    {
        return $this->pluck('keep_id', $payId);
    }

    public function staffId($payId = null)
    {
        return $this->pluck('staff_id', $payId);
    }

    public function createdAt($payId = null)
    {
        return $this->pluck('created_at', $payId);
    }

    public function checkConfirmed($payId = null)
    {
        return ($this->confirmStatus($payId) == 0) ? false : true;
    }

    #============ =========== ============ STATISTICAL ============= =========== ==========

    /*public function totalSalaryPaidOfCompany($listCompanyId, $dateFilter = null)
    {
        $modelStaff = new QcStaff();
        $modelWork = new QcWork();
        $modelSalary = new QcSalary();

        $listSalaryId = $modelSalary->listIdOfListWorkId($modelWork->listIdOfListStaffId($modelStaff->listIdOfListCompanyAndName($listCompanyId, null)));
        if (empty($dateFilter)) {
            return QcKeepMoneyPay::whereIn('keep_id', $listSalaryId)->sum('money');
        } else {
            return QcKeepMoneyPay::whereIn('keep_id', $listSalaryId)->where('payDate', 'like', "%$dateFilter%")->sum('money');
        }
    }

    public function totalSalaryPaidOfCompanyStaffDate($listCompanyId, $staffId, $dateFilter = null)
    {
        $modelStaff = new QcStaff();
        $modelWork = new QcWork();
        $modelSalary = new QcSalary();
        $listSalaryId = $modelSalary->listIdOfListWorkId($modelWork->listIdOfListStaffId($modelStaff->listIdOfListCompanyAndName($listCompanyId, null)));
        if (empty($dateFilter)) {
            return QcKeepMoneyPay::whereIn('keep_id', $listSalaryId)->where('staff_id', $staffId)->sum('money');
        } else {
            return QcKeepMoneyPay::whereIn('keep_id', $listSalaryId)->where('staff_id', $staffId)->where('payDate', 'like', "%$dateFilter%")->sum('money');
        }
    }*/
}
