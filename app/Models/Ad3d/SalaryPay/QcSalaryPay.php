<?php

namespace App\Models\Ad3d\SalaryPay;

use App\Models\Ad3d\Salary\QcSalary;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Work\QcWork;
use Illuminate\Database\Eloquent\Model;

class QcSalaryPay extends Model
{
    protected $table = 'qc_salary_pays';
    protected $fillable = ['pay_id', 'money', 'datePay', 'confirmStatus', 'created_at', 'salary_id', 'staff_id'];
    protected $primaryKey = 'pay_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== INSERT && UPDATE ========== ========== ==========
    #---------- Insert ----------
    public function insert($money, $datePay, $salaryId, $staffPayId)
    {
        $hFunction = new \Hfunction();
        $modelSalaryPay = new QcSalaryPay();
        $modelSalaryPay->money = $money;
        $modelSalaryPay->datePay = $datePay;
        $modelSalaryPay->salary_id = $salaryId;
        $modelSalaryPay->staff_id = $staffPayId;
        $modelSalaryPay->created_at = $hFunction->createdAt();
        if ($modelSalaryPay->save()) {
            $this->lastId = $modelSalaryPay->pay_id;
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
            return QcSalaryPay::where('staff_id', $staffId)->where('datePay', 'like', "%$date%")->orderBy('datePay', 'DESC')->get();
        } else {
            return QcSalaryPay::where('staff_id', $staffId)->orderBy('datePay', 'DESC')->get();
        }
    }

    public function infoConfirmedOfStaffAndDate($staffId, $date = null)
    {
        if (!empty($date)) {
            return QcSalaryPay::where('staff_id', $staffId)->where('confirmStatus', 1)->where('datePay', 'like', "%$date%")->orderBy('datePay', 'DESC')->get();
        } else {
            return QcSalaryPay::where('staff_id', $staffId)->where('confirmStatus', 1)->orderBy('datePay', 'DESC')->get();
        }
    }

    public function totalMoneyOfStaffAndDate($staffId, $date)
    {
        if (!empty($date)) {
            return QcSalaryPay::where('staff_id', $staffId)->where('datePay', 'like', "%$date%")->sum('money');
        } else {
            return QcSalaryPay::where('staff_id', $staffId)->sum('money');
        }

    }

    public function totalMoneyConfirmedOfStaffAndDate($staffId, $date)
    {
        if (!empty($date)) {
            return QcSalaryPay::where('staff_id', $staffId)->where('confirmStatus', 1)->where('datePay', 'like', "%$date%")->sum('money');
        } else {
            return QcSalaryPay::where('staff_id', $staffId)->where('confirmStatus', 1)->sum('money');
        }

    }

    public function totalMoneyOfListStaffAndDate($listStaffId, $date)
    {
        if (!empty($date)) {
            return QcSalaryPay::whereIn('staff_id', $listStaffId)->where('datePay', 'like', "%$date%")->sum('money');
        } else {
            return QcSalaryPay::whereIn('staff_id', $listStaffId)->sum('money');
        }

    }

    public function totalMoneyConfirmedOfListStaffAndDate($listStaffId, $date)
    {
        if (!empty($date)) {
            return QcSalaryPay::whereIn('staff_id', $listStaffId)->where('confirmStatus', 1)->where('datePay', 'like', "%$date%")->sum('money');
        } else {
            return QcSalaryPay::whereIn('staff_id', $listStaffId)->where('confirmStatus', 1)->sum('money');
        }

    }

    //----------- luong ------------
    public function salary()
    {
        return $this->belongsTo('App\Models\Ad3d\Salary\QcSalary', 'salary_id', 'salary_id');
    }

    # thong tin thanh toan chua xac nhan cua nhieu bang luong
    public function getInfoUnConfirmOfListSalaryId($listSalaryId)
    {
        return QcSalaryPay::whereIn('salary_id', $listSalaryId)->where('confirmStatus', 0)->get();
    }

    # thong tin thanh toan chua xac nhan cua 1 bang luong
    public function getInfoUnConfirmOfSalary($salaryId)
    {
        return QcSalaryPay::where('salary_id', $salaryId)->where('confirmStatus', 0)->get();
    }

    public function confirmReceiveOfSalary($salaryId)
    {
        return QcSalaryPay::where('salary_id', $salaryId)->where('confirmStatus', 0)->update(['confirmStatus' => 1]);
    }


    public function infoOfSalary($salaryId)
    {
        return QcSalaryPay::where('salary_id', $salaryId)->get();
    }

    public function totalPayOfSalary($salaryId)
    {
        return QcSalaryPay::where('salary_id', $salaryId)->sum('money');
    }

    public function totalPayConfirmedOfSalary($salaryId)
    {
        return QcSalaryPay::where('salary_id', $salaryId)->where('confirmStatus', 1)->sum('money');
    }

    public function checkExistUnConfirmOfSalary($salaryId)
    {
        return QcSalaryPay::where('salary_id', $salaryId)->where('confirmStatus', 0)->exists('*');
    }

    #============ =========== ============ GET INFO ============= =========== ==========
    public function getInfo($payId = '', $field = '')
    {
        if (empty($payId)) {
            return QcSalaryPay::get();
        } else {
            $result = QcSalaryPay::where('pay_id', $payId)->first();
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
            return QcSalaryPay::where('pay_id', $objectId)->pluck($column);
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

    public function confirmStatus($payId = null)
    {
        return $this->pluck('confirmStatus', $payId);
    }

    public function salaryId($payId = null)
    {
        return $this->pluck('salary_id', $payId);
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

    public function totalSalaryPaidOfCompany($listCompanyId, $dateFilter = null)
    {
        $modelStaff = new QcStaff();
        $modelWork = new QcWork();
        $modelSalary = new QcSalary();

        $listSalaryId = $modelSalary->listIdOfListWorkId($modelWork->listIdOfListStaffId($modelStaff->listIdOfListCompanyAndName($listCompanyId, null)));
        if (empty($dateFilter)) {
            return QcSalaryPay::whereIn('salary_id', $listSalaryId)->sum('money');
        } else {
            return QcSalaryPay::whereIn('salary_id', $listSalaryId)->where('datePay', 'like', "%$dateFilter%")->sum('money');
        }
    }

    public function totalSalaryPaidOfCompanyStaffDate($listCompanyId, $staffId, $dateFilter = null)
    {
        $modelStaff = new QcStaff();
        $modelWork = new QcWork();
        $modelSalary = new QcSalary();
        $listSalaryId = $modelSalary->listIdOfListWorkId($modelWork->listIdOfListStaffId($modelStaff->listIdOfListCompanyAndName($listCompanyId, null)));
        if (empty($dateFilter)) {
            return QcSalaryPay::whereIn('salary_id', $listSalaryId)->where('staff_id', $staffId)->sum('money');
        } else {
            return QcSalaryPay::whereIn('salary_id', $listSalaryId)->where('staff_id', $staffId)->where('datePay', 'like', "%$dateFilter%")->sum('money');
        }
    }
}
