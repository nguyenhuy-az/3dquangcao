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
    # mac dinh co xac nhan
    public function getDefaultHasConfirm()
    {
        return 1;
    }

    # mac dinh co xac nhan
    public function getDefaultNotConfirm()
    {
        return 0;
    }

    #---------- Insert ----------
    public function checkIdNull($id)
    {
        $hFunction = new \Hfunction();
        return ($hFunction->checkEmpty($id)) ? $this->payId() : $id;
    }

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
            $newPayId = $modelSalaryPay->pay_id;
            $this->lastId = $newPayId;
            # xu ly sau khi thanh toan
            $this->handleAfterPay($newPayId);
            return true;
        } else {
            return false;
        }
    }

    # xu ly say khi thanh toan
    public function handleAfterPay($payId)
    {
        $dataSalaryPay = $this->getInfo($payId);
        $dataWork = $dataSalaryPay->salary->work;
        $dataStaff = $dataWork->staffInfoOfWork();
        # khong con lam
        if (!$dataStaff->checkWorkStatus()) {
            # tu dong xac nhan da nhan luong
            $this->confirmReceive($payId);
        }
    }

    public function deleteSalaryPay($payId)
    {
        return QcSalaryPay::where('pay_id', $payId)->delete();
    }

    # xac nhan da nhan tien
    public function confirmReceive($payId = null)
    {
        return QcSalaryPay::where('pay_id', $this->checkIdNull($payId))->update(['confirmStatus' => $this->getDefaultHasConfirm()]);
    }

    # ---------- nhan vien xac nhan -----------
    public function staff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staff_id', 'staff_id');
    }

    public function infoOfStaffAndDate($staffId, $date = null)
    {
        $hFunction = new \Hfunction();
        if (!$hFunction->checkEmpty($date)) {
            return QcSalaryPay::where('staff_id', $staffId)->where('datePay', 'like', "%$date%")->orderBy('datePay', 'DESC')->get();
        } else {
            return QcSalaryPay::where('staff_id', $staffId)->orderBy('datePay', 'DESC')->get();
        }
    }

    public function infoConfirmedOfStaffAndDate($staffId, $date = null)
    {
        $hFunction = new \Hfunction();
        if (!$hFunction->checkEmpty($date)) {
            return QcSalaryPay::where('staff_id', $staffId)->where('confirmStatus', $this->getDefaultHasConfirm())->where('datePay', 'like', "%$date%")->orderBy('datePay', 'DESC')->get();
        } else {
            return QcSalaryPay::where('staff_id', $staffId)->where('confirmStatus', $this->getDefaultNotConfirm())->orderBy('datePay', 'DESC')->get();
        }
    }

    public function totalMoneyOfStaffAndDate($staffId, $date)
    {
        $hFunction = new \Hfunction();
        if (!$hFunction->checkEmpty($date)) {
            return QcSalaryPay::where('staff_id', $staffId)->where('datePay', 'like', "%$date%")->sum('money');
        } else {
            return QcSalaryPay::where('staff_id', $staffId)->sum('money');
        }

    }

    public function totalMoneyConfirmedOfStaffAndDate($staffId, $date)
    {
        $hFunction = new \Hfunction();
        if (!$hFunction->checkEmpty($date)) {
            return QcSalaryPay::where('staff_id', $staffId)->where('confirmStatus', $this->getDefaultHasConfirm())->where('datePay', 'like', "%$date%")->sum('money');
        } else {
            return QcSalaryPay::where('staff_id', $staffId)->where('confirmStatus', $this->getDefaultHasConfirm())->sum('money');
        }

    }

    public function totalMoneyOfListStaffAndDate($listStaffId, $date)
    {
        $hFunction = new \Hfunction();
        if (!$hFunction->checkEmpty($date)) {
            return QcSalaryPay::whereIn('staff_id', $listStaffId)->where('datePay', 'like', "%$date%")->sum('money');
        } else {
            return QcSalaryPay::whereIn('staff_id', $listStaffId)->sum('money');
        }

    }

    public function totalMoneyConfirmedOfListStaffAndDate($listStaffId, $date)
    {
        $hFunction = new \Hfunction();
        if (!$hFunction->checkEmpty($date)) {
            return QcSalaryPay::whereIn('staff_id', $listStaffId)->where('confirmStatus', $this->getDefaultHasConfirm())->where('datePay', 'like', "%$date%")->sum('money');
        } else {
            return QcSalaryPay::whereIn('staff_id', $listStaffId)->where('confirmStatus', $this->getDefaultHasConfirm())->sum('money');
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
        return QcSalaryPay::whereIn('salary_id', $listSalaryId)->where('confirmStatus', $this->getDefaultNotConfirm())->get();
    }

    # thong tin thanh toan chua xac nhan cua 1 bang luong
    public function getInfoUnConfirmOfSalary($salaryId)
    {
        return QcSalaryPay::where('salary_id', $salaryId)->where('confirmStatus', $this->getDefaultNotConfirm())->get();
    }

    public function confirmReceiveOfSalary($salaryId)
    {
        return QcSalaryPay::where('salary_id', $salaryId)->where('confirmStatus', $this->getDefaultNotConfirm())->update(['confirmStatus' => $this->getDefaultHasConfirm()]);
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
        return QcSalaryPay::where('salary_id', $salaryId)->where('confirmStatus', $this->getDefaultHasConfirm())->sum('money');
    }

    public function checkExistUnConfirmOfSalary($salaryId)
    {
        return QcSalaryPay::where('salary_id', $salaryId)->where('confirmStatus', $this->getDefaultNotConfirm())->exists('*');
    }

    #============ =========== ============ GET INFO ============= =========== ==========
    public function getInfo($payId = null, $field = null)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($payId)) {
            return QcSalaryPay::get();
        } else {
            $result = QcSalaryPay::where('pay_id', $payId)->first();
            if ($hFunction->checkEmpty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }

    public function pluck($column, $objectId = null)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($objectId)) {
            return $this->$column;
        } else {
            return QcSalaryPay::where('pay_id', $objectId)->pluck($column)[0];
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
        return ($this->confirmStatus($payId) == $this->getDefaultNotConfirm()) ? false : true;
    }

    #============ =========== ============ STATISTICAL ============= =========== ==========

    public function totalSalaryPaidOfCompany($listCompanyId, $dateFilter = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelWork = new QcWork();
        $modelSalary = new QcSalary();
        $listSalaryId = $modelSalary->listIdOfListWorkId($modelWork->listIdOfListStaffId($modelStaff->listIdOfListCompanyAndName($listCompanyId, null)));
        if ($hFunction->checkEmpty($dateFilter)) {
            return QcSalaryPay::whereIn('salary_id', $listSalaryId)->sum('money');
        } else {
            return QcSalaryPay::whereIn('salary_id', $listSalaryId)->where('datePay', 'like', "%$dateFilter%")->sum('money');
        }
    }

    public function totalSalaryPaidOfCompanyStaffDate($listCompanyId, $staffId, $dateFilter = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelWork = new QcWork();
        $modelSalary = new QcSalary();
        $listSalaryId = $modelSalary->listIdOfListWorkId($modelWork->listIdOfListStaffId($modelStaff->listIdOfListCompanyAndName($listCompanyId, null)));
        if ($hFunction->checkEmpty($dateFilter)) {
            return QcSalaryPay::whereIn('salary_id', $listSalaryId)->where('staff_id', $staffId)->sum('money');
        } else {
            return QcSalaryPay::whereIn('salary_id', $listSalaryId)->where('staff_id', $staffId)->where('datePay', 'like', "%$dateFilter%")->sum('money');
        }
    }
}
