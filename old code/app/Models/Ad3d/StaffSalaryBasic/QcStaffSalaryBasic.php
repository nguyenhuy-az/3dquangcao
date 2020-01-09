<?php

namespace App\Models\Ad3d\StaffSalaryBasic;

use Illuminate\Database\Eloquent\Model;
use DB;

class QcStaffSalaryBasic extends Model
{
    protected $table = 'qc_staff_salary_basics';
    protected $fillable = ['salary_basic_id', 'salary','action', 'created_at', 'staff_id'];
    protected $primaryKey = 'salary_basic_id';
    public $timestamps = false;

    private $lastId;

    //========== ========== ========== them moi && cap nhat ========== ========== ==========
    //---------- them moi ----------
    public function insert($salary, $staffId)
    {
        $hFunction = new \Hfunction();
        $modelSalary = new QcStaffSalaryBasic();
        $modelSalary->salary = $salary;
        $modelSalary->staff_id = $staffId;
        $modelSalary->created_at = $hFunction->createdAt();
        if ($modelSalary->save()) {
            $this->lastId = $modelSalary->salary_basic_id;
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

    // vo hieu bang luong
    public function disableOfStaff($staffId)
    {
        return QcStaffSalaryBasic::where('staff_id', $staffId)->update(['action' => 0]);
    }

    //========== ========== ========== moi quan he cac bang ========== ========== ==========
    //----------- nhan vien ------------
    public function staff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staff_id', 'staff_id');
    }

    public function salaryOfStaff($staffId, $date = null)
    {
        $hFunction = new \Hfunction();
        $salary = null;
        if (!empty($date)) {
            $lastDate = $hFunction->lastDateOfMonthFromDate(date('Y-m-d', strtotime($date)));
            $salary =  QcStaffSalaryBasic::where(['staff_id' => $staffId])->where('created_at', '<', $lastDate)->orderBy('created_at', 'DESC')->first()->salary();
        }
        if (empty($salary)) {
            return  QcStaffSalaryBasic::where(['staff_id' => $staffId, 'action' => 1])->pluck('salary')[0];
        } else {
            return $salary;
        }
    }

    public function infoActivityOfStaff($staffId = null)
    {
        return QcStaffSalaryBasic::where(['staff_id' => $staffId, 'action' => 1])->first();//return value
    }

    public function  allInfoOfStaff($staffId)
    {
        return QcStaffSalaryBasic::where(['staff_id' => $staffId])->orderBy('created_at', 'DESC')->get();
    }

    //----------- tinh luong ------------
    public function qcSalary()
    {
        return $this->hasMany('App\Models\Ad3d\Salary\QcSalary', 'salaryBasic_id', 'salary_basic_id');
    }


    //============ =========== ============ GET INFO ============= =========== ==========
    public function getInfo($salaryBasicId = '', $field = '')
    {
        if (empty($salaryBasicId)) {
            return QcStaffSalaryBasic::where('action', 1)->get();
        } else {
            $result = QcStaffSalaryBasic::where('salary_basic_id', $salaryBasicId)->first();
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
            return QcStaffSalaryBasic::where('salary_basic_id', $objectId)->pluck($column);
        }
    }

    //----------- GET INFO -------------
    public function salaryBasicId()
    {
        return $this->salary_basic_id;
    }

    public function salary($salaryBasicId = null)
    {
        return $this->pluck('salary', $salaryBasicId);
    }

    public function sales($salaryBasicId = null)
    {
        return $this->pluck('sales', $salaryBasicId);
    }

    public function action($salaryBasicId = null)
    {
        return $this->pluck('action', $salaryBasicId);
    }

    public function staffId($salaryBasicId = null)
    {
        return $this->pluck('staff_id', $salaryBasicId);
    }

    public function createdAt($salaryBasicId = null)
    {
        return $this->pluck('created_at', $salaryBasicId);
    }
}
