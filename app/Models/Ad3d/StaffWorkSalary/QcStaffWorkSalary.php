<?php

namespace App\Models\Ad3d\StaffWorkSalary;

use Illuminate\Database\Eloquent\Model;

class QcStaffWorkSalary extends Model
{
    protected $table = 'qc_staff_work_salary';
    protected $fillable = ['workSalary_id', 'totalSalary', 'salary', 'responsibility', 'usePhone', 'insurance', 'fuel', 'dateOff', 'overtimeHour', 'action', 'created_at', 'work_id'];
    protected $primaryKey = 'workSalary_id';
    public $timestamps = false;

    private $lastId;

    //========== ========== ========== them moi && cap nhat ========== ========== ==========
    //---------- them moi ----------
    public function insert($totalSalary, $salary, $responsibility, $usePhone, $insurance, $fuel, $dateOff, $overtimeHour, $workId)
    {
        $hFunction = new \Hfunction();
        $modelSalary = new QcStaffWorkSalary();
        $modelSalary->totalSalary = $totalSalary;
        $modelSalary->salary = $salary;
        $modelSalary->responsibility = $responsibility;
        $modelSalary->usePhone = $usePhone;
        $modelSalary->insurance = str_replace(',', '.', $insurance);
        $modelSalary->fuel = $fuel;
        $modelSalary->dateOff = $dateOff;
        $modelSalary->overtimeHour = $overtimeHour;
        $modelSalary->work_id = $workId;
        $modelSalary->created_at = $hFunction->createdAt();
        if ($modelSalary->save()) {
            $this->lastId = $modelSalary->workSalary_id;
            return true;
        } else {
            return false;
        }
    }

    // ma moi them
    public function insertGetId()
    {
        return $this->lastId;
    }

    public function checkIdNull($workSalaryId)
    {
        return (empty($workSalaryId)) ? $this->workSalaryId() : $workSalaryId;
    }

    // vo hieu bang luong
    public function disableOfWork($workId)
    {
        return QcStaffWorkSalary::where('work_id', $workId)->update(['action' => 0]);
    }

    public function disableStaffWorkSalary($workSalaryId = null)
    {
        return QcStaffWorkSalary::where('workSalary_id', $this->checkIdNull($workSalaryId))->update(['action' => 0]);
    }
    //========== ========== ========== moi quan he cac bang ========== ========== ==========
    //----------- cty noi lam viec ------------
    public function companyStaffWork()
    {
        return $this->belongsTo('App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork', 'work_id', 'work_id');
    }

    public function checkExistsActivityOfWork($workId)
    {
        return QcStaffWorkSalary::where(['work_id' => $workId, 'action' => 1])->exists();
    }

    public function workSalaryOfWork($workId, $date = null)
    {
        $hFunction = new \Hfunction();
        $lastDate = $hFunction->lastDateOfMonthFromDate(date('Y-m-d', strtotime($date)));
        $salary = null;
        if (!empty($date)) {
            $salary = QcStaffWorkSalary::where(['work_id' => $workId])->where('created_at', '<', $lastDate)->orderBy('created_at', 'DESC')->pluck('salary')[0];
        }
        if (empty($salary)) {
            return QcStaffWorkSalary::where(['work_id' => $workId, 'action' => 1])->pluck('salary')[0];
        } else {
            return $salary;
        }
    }

    public function infoActivityOfWork($workId = null)
    {
        return QcStaffWorkSalary::where(['work_id' => $workId, 'action' => 1])->first();//return value
    }

    public function  allInfoOfWork($workId)
    {
        return QcStaffWorkSalary::where(['work_id' => $workId])->orderBy('created_at', 'DESC')->get();
    }

    public function totalSalaryBasic($workSalaryId = null) # tong tien luong co ban dc lanh
    {
        /*$salaryBasic = $this->salary($workSalaryId);# luong co ban
        $responsibility = $this->responsibility($workSalaryId);# phu cap trach nhiem /26 ngay
        $usePhone = $this->usePhone($workSalaryId);# phu cap su dung dien thoai
        $insurance = $this->insurance($workSalaryId);# phu cap bao hiem %
        $fuel = $this->fuel($workSalaryId);# phu cap xang di lai
        if ($insurance == 0) {
            $moneyInsurance = 0;
        } else {
            $moneyInsurance = ($salaryBasic * $insurance) / 100;
        }
        return $salaryBasic + $usePhone + $responsibility + $fuel + $moneyInsurance;*/
        return $this->totalSalary($workSalaryId);
    }

    public function salaryOnHour($workSalaryId = null) # tien luong tin tren 1 gio
    {
        return $this->totalSalaryBasic($workSalaryId) / 208;
    }

    public function totalSalaryOnHour($workSalaryId = null) # tien luong tin tren 1 gio
    {
        return $this->totalSalaryInMonthOfWork($this->workId($workSalaryId)) / 208;
    }

    public function totalMoneyInsurance($workSalaryId = null)
    {
        return ($this->salary($workSalaryId) * $this->insurance($workSalaryId)) / 100;
    }

    public function salaryOneDateOff($workSalaryId = null) # tien phu cap trong 1 ngay
    {
        return $this->totalSalary($workSalaryId) / 26;
    }

    public function totalSalaryDateOff($workSalaryId = null) # tong tien phu cap ngay nghi
    {
        return $this->dateOff($workSalaryId) * $this->salaryOneDateOff($workSalaryId);
    }

    public function totalMoneyOvertimeHour($workSalaryId, $hour) # tong tien phu cap tang ca trong thang
    {
        return $this->overtimeHour($workSalaryId) * $hour;
    }


    // ------------------------ of work ---------------------
    public function totalSalaryBasicOfWork($workId) # tong tien luong co ban dc lanh
    {
        $dataSalary = $this->infoActivityOfWork($workId);
        return $this->totalSalaryBasic($dataSalary->workSalaryId());
    }

    public function totalSalaryDateOffOfWork($workId) # tong tien phu cap ngay nghi
    {
        $dataSalary = $this->infoActivityOfWork($workId);
        return $dataSalary->dateOff() * $this->salaryOneDateOff($workId);
    }

    public function totalMoneyOvertimeHourOfWork($workId, $hour) # tong tien phu cap tang ca trong thang
    {
        $dataSalary = $this->infoActivityOfWork($workId);
        return $dataSalary->overtimeHour() * $hour;
    }

    public function totalSalaryInMonthOfWork($workId) # tien luong tin tren 1  thang
    {
        return $this->totalSalaryBasicOfWork($workId) + $this->totalSalaryDateOff($workId);
    }

//----------- tinh luong ------------
    public function salaryWork()
    {
        return $this->hasMany('App\Models\Ad3d\Salary\QcSalary', 'workSalary_id', 'workSalary_id');
    }


//============ =========== ============ LAY THONG TIN ============= =========== ==========

    public function getInfo($workSalaryId = '', $field = '')
    {
        if (empty($workSalaryId)) {
            return QcStaffWorkSalary::where('action', 1)->get();
        } else {
            $result = QcStaffWorkSalary::where('workSalary_id', $workSalaryId)->first();
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
            return QcStaffWorkSalary::where('workSalary_id', $objectId)->pluck($column);
        }
    }

//----------- GET INFO -------------
    public function workSalaryId()
    {
        return $this->workSalary_id;
    }

    public function totalSalary($workSalaryId = null)
    {
        return $this->pluck('totalSalary', $workSalaryId);
    }

    public function salary($workSalaryId = null)
    {
        return $this->pluck('salary', $workSalaryId);
    }

    public function responsibility($workSalaryId = null)
    {
        return $this->pluck('responsibility', $workSalaryId);
    }

    public function usePhone($workSalaryId = null)
    {
        return $this->pluck('usePhone', $workSalaryId);
    }

    public function insurance($workSalaryId = null)
    {
        return $this->pluck('insurance', $workSalaryId);
    }

    public function fuel($workSalaryId = null)
    {
        return $this->pluck('fuel', $workSalaryId);
    }

    public function dateOff($workSalaryId = null)
    {
        return $this->pluck('dateOff', $workSalaryId);
    }

    public function overtimeHour($workSalaryId = null)
    {
        return $this->pluck('overtimeHour', $workSalaryId);
    }

    public function action($workSalaryId = null)
    {
        return $this->pluck('action', $workSalaryId);
    }

    public function workId($workSalaryId = null)
    {
        return $this->pluck('work_id', $workSalaryId);
    }

    public function createdAt($workSalaryId = null)
    {
        return $this->pluck('created_at', $workSalaryId);
    }
}
