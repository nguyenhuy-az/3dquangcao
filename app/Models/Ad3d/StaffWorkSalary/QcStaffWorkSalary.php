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

    # mac dinh dang hoat dong
    public function getDefaultHasAction()
    {
        return 1;
    }

    # mac dinh dang hoat dong
    public function getDefaultNotAction()
    {
        return 0;
    }

    # mac dinh luong co ban cua 1 gio
    public function getDefaultSalaryOnHour()
    {
        return 0;
    }

    # mac dinh tien gio tang ca - trong 1 gio
    public function getDefaultOverTimeHour()
    {
        return 10000;
    }

    # mac dinh so ngay nghi
    public function getDefaultDateOff()
    {
        return 1;
    }

    # mac dinh tien xang
    public function getDefaultFuel()
    {
        return 0;
    }

    # mac dinh tien bao hiem
    public function getDefaultInsurance()
    {
        return 0;
    }

    # mac dinh phan tram bao hiem
    public function getDefaultInsurancePercent()
    {
        return 21.5;
    }

    # mac dinh tien su dung dien thoai
    public function getDefaultUsePhone()
    {
        return 100000;
    }

    # mac dinh tien trach nhiem
    public function getDefaultResponsibility()
    {
        return 0;
    }
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

    public function checkIdNull($id)
    {
        $hFunction = new \Hfunction();
        return ($hFunction->checkEmpty($id)) ? $this->workSalaryId() : $id;
    }

    //============ =========== ============ LAY THONG TIN ============= =========== ==========

    public function getInfo($workSalaryId = '', $field = '')
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($workSalaryId)) {
            return QcStaffWorkSalary::where('action', $this->getDefaultHasAction())->get();
        } else {
            $result = QcStaffWorkSalary::where('workSalary_id', $workSalaryId)->first();
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
            return QcStaffWorkSalary::where('workSalary_id', $objectId)->pluck($column)[0];
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

    // vo hieu bang luong
    public function disableOfWork($workId)
    {
        return QcStaffWorkSalary::where('work_id', $workId)->update(['action' => $this->getDefaultNotAction()]);
    }

    public function disableStaffWorkSalary($workSalaryId = null)
    {
        return QcStaffWorkSalary::where('workSalary_id', $this->checkIdNull($workSalaryId))->update(['action' => $this->getDefaultNotAction()]);
    }
    //========== ========== ========== moi quan he cac bang ========== ========== ==========
    //----------- cty noi lam viec ------------
    public function companyStaffWork()
    {
        return $this->belongsTo('App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork', 'work_id', 'work_id');
    }

    public function checkExistsActivityOfWork($workId)
    {
        return QcStaffWorkSalary::where(['work_id' => $workId, 'action' => $this->getDefaultHasAction()])->exists();
    }

    public function workSalaryOfWork($workId, $date = null)
    {
        $hFunction = new \Hfunction();
        $lastDate = $hFunction->lastDateOfMonthFromDate(date('Y-m-d', strtotime($date)));
        $salary = $hFunction->getDefaultNull();
        if (!$hFunction->checkEmpty($date)) {
            $salary = QcStaffWorkSalary::where('work_id', $workId)->where('created_at', '<', $lastDate)->orderBy('created_at', 'DESC')->pluck('salary')[0];
        }
        if ($hFunction->checkEmpty($salary)) {
            return QcStaffWorkSalary::where(['work_id' => $workId, 'action' => $this->getDefaultHasAction()])->pluck('salary')[0];
        } else {
            return $salary;
        }
    }

    public function infoActivityOfWork($workId = null)
    {
        return QcStaffWorkSalary::where(['work_id' => $workId, 'action' => $this->getDefaultHasAction()])->first();//return value
    }

    public function  allInfoOfWork($workId)
    {
        return QcStaffWorkSalary::where('work_id', $workId)->orderBy('created_at', 'DESC')->get();
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

    # tien luong tin tren 1 gio
    public function salaryOnHour($workSalaryId = null)
    {
        return $this->totalSalaryBasic($workSalaryId) / 208;
    }

    # tong tien luong tinh tren 1 gio
    public function totalSalaryOnHour($workSalaryId = null)
    {
        return $this->totalSalaryInMonthOfWork($this->workId($workSalaryId)) / 208;
    }

    # tong tien bao hiem
    public function totalMoneyInsurance($workSalaryId = null)
    {
        return ($this->salary($workSalaryId) * $this->insurance($workSalaryId)) / 100;
    }

    # tien luong 1 ngay
    public function salaryOneDateOff($workSalaryId = null)
    {
        return $this->totalSalary($workSalaryId) / 26;
    }

    # tong tien phu cap ngay nghi
    public function totalSalaryDateOff($workSalaryId = null)
    {
        return $this->dateOff($workSalaryId) * $this->salaryOneDateOff($workSalaryId);
    }

    # tong tien phu cap tang ca trong thang
    public function totalMoneyOvertimeHour($workSalaryId, $hour)
    {
        return $this->overtimeHour($workSalaryId) * $hour;
    }


    // ------------------------ of work ---------------------
    # tong tien luong co ban dc lanh
    public function totalSalaryBasicOfWork($workId)
    {
        $dataSalary = $this->infoActivityOfWork($workId);
        return $this->totalSalaryBasic($dataSalary->workSalaryId());
    }

    # tong tien phu cap ngay nghi
    public function totalSalaryDateOffOfWork($workId)
    {
        $dataSalary = $this->infoActivityOfWork($workId);
        return $dataSalary->dateOff() * $this->salaryOneDateOff($workId);
    }

    # tong tien phu cap tang ca trong thang
    public function totalMoneyOvertimeHourOfWork($workId, $hour)
    {
        $dataSalary = $this->infoActivityOfWork($workId);
        return $dataSalary->overtimeHour() * $hour;
    }

    # tien luong tin tren 1  thang
    public function totalSalaryInMonthOfWork($workId)
    {
        return $this->totalSalaryBasicOfWork($workId) + $this->totalSalaryDateOff($workId);
    }

    //----------- tinh luong ------------
    public function salaryWork()
    {
        return $this->hasMany('App\Models\Ad3d\Salary\QcSalary', 'workSalary_id', 'workSalary_id');
    }


}
