<?php

namespace App\Models\Ad3d\ProductRepairAllocation;

use Illuminate\Database\Eloquent\Model;

class QcProductRepairAllocation extends Model
{
    protected $table = 'qc_product_repair_allocation';
    protected $fillable = ['allocation_id', 'role', 'action', 'allocation_id', 'staff_id', 'created_at'];
    protected $primaryKey = 'allocation_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- them moi ----------
    public function insert($role, $allocationId, $staffId)
    {
        $hFunction = new \Hfunction();
        $modelProductRepairAllocation = new QcProductRepairAllocation();
        $modelProductRepairAllocation->role = $role;
        $modelProductRepairAllocation->allocation_id = $allocationId;
        $modelProductRepairAllocation->staff_id = $staffId;
        $modelProductRepairAllocation->created_at = $hFunction->createdAt();
        if ($modelProductRepairAllocation->save()) {
            $this->lastId = $modelProductRepairAllocation->allocation_id;
            return true;
        } else {
            return false;
        }
    }

    // lay id moi them
    public function insertGetId()
    {
        return $this->lastId;
    }

    // ket thuc cong viec
    public function confirmFinish($allocationId)
    {
        return QcProductRepairAllocation::where('allocation_id', $allocationId)->update(['action' => 0]);
    }
    //========== ========= ========= RELATION ========== ========= ==========
    //---------- nhân viên nhân -----------
    public function staff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staff_id', 'staff_id');
    }

    //---------- phieu sua chua -----------
    public function productRepair()
    {
        return $this->belongsTo('App\Models\Ad3d\ProductRepair\QcProductRepair', 'repair_id', 'repair_id');
    }

    //========= ========== ========== lay thong tin ========== ========== ==========
    public function getInfo($allocationId = '', $field = '')
    {
        if (empty($allocationId)) {
            return QcProductRepairAllocation::get();
        } else {
            $result = QcProductRepairAllocation::where('allocation_id', $allocationId)->first();
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
            return QcProductRepairAllocation::where('allocation_id', $objectId)->pluck($column);
        }
    }

    public function allocationId()
    {
        return $this->allocation_id;
    }

    public function role($allocationId = null)
    {
        return $this->pluck('role', $allocationId);
    }
    
    public function repairId($allocationId = null)
    {
        return $this->pluck('repair_id', $allocationId);
    }

    public function staffId($allocationId = null)
    {
        return $this->pluck('staff_id', $allocationId);
    }

    public function action($allocationId = null)
    {
        return $this->pluck('action', $allocationId);
    }

    public function createdAt($allocationId = null)
    {
        return $this->pluck('created_at', $allocationId);
    }

// tong mau tin
    public function totalRecords()
    {
        return QcProductRepairAllocation::count();
    }

// id cuoi
    public function lastId()
    {
        $result = QcProductRepairAllocation::orderBy('allocation_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->allocation_id;
    }

    // kiem tra thong tin
    #con hoat dong
    public function checkActivity($allocationId = null)
    {
        return ($this->action($allocationId) == 1) ? true : false;
    }
}
