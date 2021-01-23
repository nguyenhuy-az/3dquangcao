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

    # mac dinh co ap dung noi quy
    public function getDefaultHasApplyRule()
    {
        return 1;
    }

    # mac dinh khong ap dung noi quy
    public function getDefaultNotApplyRule()
    {
        return 2;
    }

    # mac dinh lam chinh
    public function getDefaultMethodHasMain()
    {
        return 1;
    }

    # mac dinh lam phu
    public function getDefaultMethodNotMain()
    {
        return 1;
    }

    #mac dinh co hoat dong
    public function getDefaultHasAction()
    {
        return 1;
    }

    # mac dinh khong hoat dong
    public function getDefaultNotAction()
    {
        return 0;
    }

    # mac dinh nguoi xac nhan
    public function getDefaultConfirmStaffId()
    {
        return null;
    }
    //========== ========= ========= THEM && CAP NHAT ========== ========= =========
    //---------- them ----------
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

    # thong dang sau cung
    public function lastInfoOfStaff($staffId)
    {
        return QcStaffWorkMethod::where('staff_id', $staffId)->orderBy('method_id', 'DESC')->first();
    }

    # thong dang hoat dong
    public function infoActivityOfStaff($staffId)
    {
        return QcStaffWorkMethod::where('staff_id', $staffId)->where('action', $this->getDefaultHasAction())->first();
    }

    public function checkExistActivityMethodApplyRuleOfStaff($staffId, $method, $applyRule)
    {
        return QcStaffWorkMethod::where(
            [
                'staff_id' => $staffId,
                'method' => $method,
                'applyRule' => $applyRule
            ])->where('action', $this->getDefaultHasAction())->exists();
    }

    public function disableInfoActivity($staffId)
    {
        return QcStaffWorkMethod::where('staff_id', $staffId)->where('action', $this->getDefaultHasAction())->update(['action' => $this->getDefaultNotAction()]);
    }

    //----------  nhan vien xac nhan -----------
    public function confirmStaff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'confirmStaff_id', 'staff_id');
    }

    //========= ========== ========== lay thong  ========== ========== ==========
    public function getInfo($methodId = '', $fietinld = '')
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
            return QcStaffWorkMethod::where('method_id', $objectId)->pluck($column)[0];
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
        if ($method == $this->getDefaultMethodHasMain()) {
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
        if ($applyRule == $this->getDefaultHasApplyRule()) {
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
        $hFunction = new \Hfunction();
        $result = QcStaffWorkMethod::orderBy('method_id', 'DESC')->first();
        return ($hFunction->checkEmpty($result)) ? 0 : $result->method_id;
    }


    // ----------- kiem tra thong tin --------------------
    public function checkOfficialMethod($methodId = null)
    {
        return ($this->method($methodId) == $this->getDefaultMethodHasMain()) ? true : false; # 1 chinh thuc / 2 - khong
    }

    public function checkApplyRule($methodId = null)
    {
        return ($this->applyRule($methodId) == $this->getDefaultHasApplyRule()) ? true : false; # 1 chinh thuc / 2 - khong
    }
}
