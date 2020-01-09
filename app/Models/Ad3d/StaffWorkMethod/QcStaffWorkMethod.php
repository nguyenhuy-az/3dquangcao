<?php

namespace App\Models\Ad3d\StaffWorkMethod;

use Illuminate\Database\Eloquent\Model;

class QcStaffWorkMethod extends Model
{
    protected $table = 'qc_staff_work_method';
    protected $fillable = ['method_id', 'method', 'applyRule', 'action', 'created_at', 'staff_id', 'confirmStaff_id'];
    protected $primaryKey = 'method_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= THEM && CAP NHAT ========== ========= =========
    //---------- th�m ----------
    public function insert($method, $applyRule, $staffId, $confirmStaffId)
    {
        $hFunction = new \Hfunction();
        $modelStaffWorkRule = new QcStaffWorkMethod();
        $modelStaffWorkRule->method = $method;
        $modelStaffWorkRule->applyRule = $applyRule;
        $modelStaffWorkRule->staff_id = $staffId;
        $modelStaffWorkRule->confirmStaff_id = $confirmStaffId;
        $modelStaffWorkRule->created_at = $hFunction->createdAt();
        if ($modelStaffWorkRule->save()) {
            $this->lastId = $modelStaffWorkRule->method_id;
            return true;
        } else {
            return false;
        }
    }

    public function insertGetId()
    {
        return $this->lastId;
    }

    //========== ========= ========= quan he cac bang ========== ========= ==========
    //----------  nhan vien ap dung -----------
    public function staff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staff_id', 'staff_id');
    }

    public function infoActivityOfStaff($staffId)
    {
        return QcStaffWorkMethod::where('staff_id', $staffId)->where('action', 1)->first();
    }

    public function checkExistActivityMethodApplyRuleOfStaff($staffId, $method, $applyRule)
    {
        return QcStaffWorkMethod::where(['staff_id' => $staffId, 'method' => $method, 'applyRule' => $applyRule])->where('action', 1)->exists();
    }

    public function disableInfoActivity($staffId)
    {
        return QcStaffWorkMethod::where(['staff_id' => $staffId])->where('action', 1)->update(['action' => 0]);
    }

    //----------  nhan vien xac nhan -----------
    public function confirmStaff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'confirmStaff_id', 'staff_id');
    }

    //========= ========== ========== lay thong tin ========== ========== ==========
    public function getInfo($methodId = '', $field = '')
    {
        if (empty($methodId)) {
            return QcStaffWorkMethod::get();
        } else {
            $result = QcStaffWorkMethod::where('method_id', $methodId)->first();
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
            return QcStaffWorkMethod::where('method_id', $objectId)->pluck($column);
        }
    }

    public function methodId()
    {
        return $this->method_id;
    }

    public function method($methodId = null)
    {
        return $this->pluck('method', $methodId);
    }

    public function methodLabel($method)
    {
        if ($method == 1) {
            return 'Chính thưc';
        } else {
            return 'Không chính thức';
        }
    }

    public function applyRule($methodId = null)
    {
        return $this->pluck('applyRule', $methodId);
    }

    public function applyRuleLabel($applyRule)
    {
        if ($applyRule == 1) {
            return 'Áp dụng';
        } else {
            return 'Không áp dụng';
        }
    }

    public function createdAt($methodId = null)
    {
        return $this->pluck('created_at', $methodId);
    }

    public function confirmStaffId($methodId = null)
    {
        return $this->pluck('confirmStaff_id', $methodId);
    }

    public function staffId($methodId = null)
    {
        return $this->pluck('staff_id', $methodId);
    }

    // lay id cuoi
    public function lastId()
    {
        $result = QcStaffWorkMethod::orderBy('method_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->method_id;
    }

    // ----------- kiem tra thong tin --------------------
    public function checkOfficialMethod($methodId=null)
    {
        return ($this->method($methodId) == 1) ? true : false; # 1 chinh thuc / 2 - khong
    }

    public function checkApplyRule($methodId=null)
    {
        return ($this->applyRule($methodId) == 1) ? true : false; # 1 chinh thuc / 2 - khong
    }
}
