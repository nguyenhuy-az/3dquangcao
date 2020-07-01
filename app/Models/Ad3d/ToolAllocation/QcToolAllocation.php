<?php

namespace App\Models\Ad3d\ToolAllocation;

use App\Models\Ad3d\ToolAllocationDetail\QcToolAllocationDetail;
use Illuminate\Database\Eloquent\Model;

class QcToolAllocation extends Model
{
    protected $table = 'qc_tool_allocation';
    protected $fillable = ['allocation_id', 'confirmStatus', 'confirmDate', 'allocationDate', 'created_at', 'allocationStaff_id', 'receiveStaff_id'];
    protected $primaryKey = 'allocation_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- thêm ----------
    public function insert($allocationDate, $allocationStaffId, $receiveStaffId, $confirmStatus = 0)
    {
        $hFunction = new \Hfunction();
        $modelToolAllocation = new QcToolAllocation();
        $modelToolAllocation->confirmStatus = $confirmStatus;
        $modelToolAllocation->allocationDate = $allocationDate;
        $modelToolAllocation->allocationStaff_id = $allocationStaffId;
        $modelToolAllocation->receiveStaff_id = $receiveStaffId;
        $modelToolAllocation->created_at = $hFunction->createdAt();
        if ($modelToolAllocation->save()) {
            $this->lastId = $modelToolAllocation->allocation_id;
            return true;
        } else {
            return false;
        }
    }

    public function insertGetId()
    {
        return $this->lastId;
    }

    public function checkIdNull($allocationId)
    {
        return (empty($allocationId)) ? $this->allocationId() : $allocationId;
    }

    public function receiveConfirm($allocationId = null)
    {
        $hFunction = new \Hfunction();
        return QcToolAllocation::where('allocation_id', $this->checkIdNull($allocationId))->update(['confirmStatus' => 1, 'confirmDate' => $hFunction->createdAt()]);
    }

    public function deleteAllocation($allocationId = null)
    {
        return QcToolAllocation::where('allocation_id', $this->checkIdNull($allocationId))->delete();
    }
    //========== ========= ========= RELATION ========== ========= ==========
    //---------- nhân viên bàn giao -----------
    public function allocationStaff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'allocationStaff_id', 'staff_id');
    }

    //---------- nhân viên nhận -----------
    public function receiveStaff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'receiveStaff_id', 'staff_id');
    }

    public function infoOfReceiveStaff($receiveStaffId)
    {
        return QcToolAllocation::where('receiveStaff_id', $receiveStaffId)->orderBy('allocationDate', 'DESC')->get();
    }

    public function listIdOfReceiveStaff($receiveStaffId)
    {
        return QcToolAllocation::where('receiveStaff_id', $receiveStaffId)->orderBy('allocationDate', 'DESC')->pluck('allocation_id');
    }

    //---------- Chi tiết cấp -----------
    public function toolAllocationDetail()
    {
        return $this->belongsTo('App\Models\Ad3d\ToolAllocationDetail\QcStaff', 'allocation_id', 'allocation_id');
    }

    public function totalAmountToolOfAllocation($allocationId = null)
    {
        $modelToolAllocationDetail = new QcToolAllocationDetail();
        return $modelToolAllocationDetail->totalAmountOfAllocation($this->checkIdNull($allocationId));
    }

    public function toolAllocationDetailOfAllocation($allocationId = null)
    {
        $modelToolAllocationDetail = new QcToolAllocationDetail();
        return $modelToolAllocationDetail->infoOfToolAllocation($this->checkIdNull($allocationId));
    }

    public function toolAllocationDetailOfListAllocation($listAllocationId)
    {
        $modelToolAllocationDetail = new QcToolAllocationDetail();
        return $modelToolAllocationDetail->infoOfListToolAllocation($listAllocationId);
    }

    //========= ========== ========== lấy thông tin ========== ========== ==========
    public function selectInfoOfListReceiveStaffAndDate($listStaffId, $dateFilter = null)
    {
        if (empty($dateFilter)) {
            return QcToolAllocation::whereIn('receiveStaff_id', $listStaffId)->orderBy('allocationDate', 'DESC')->select('*');
        } else {
            return QcToolAllocation::whereIn('receiveStaff_id', $listStaffId)->where('allocationDate', 'like', "%$dateFilter%")->orderBy('allocationDate', 'DESC')->select('*');
        }
    }

    public function getInfo($allocationId = '', $field = '')
    {
        if (empty($allocationId)) {
            return QcToolAllocation::get();
        } else {
            $result = QcToolAllocation::where('allocation_id', $allocationId)->first();
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
            return QcToolAllocation::where('allocation_id', $objectId)->pluck($column);
        }
    }

    public function allocationId()
    {
        return $this->allocation_id;
    }

    public function allocationDate($allocationId = null)
    {

        return $this->pluck('allocationDate', $allocationId);
    }

    public function confirmStatus($allocationId = null)
    {

        return $this->pluck('confirmStatus', $allocationId);
    }

    public function createdAt($allocationId = null)
    {
        return $this->pluck('created_at', $allocationId);
    }

    public function allocationStaffId($allocationId = null)
    {
        return $this->pluck('allocationStaff_id', $allocationId);
    }

    public function receiveStaffId($allocationId = null)
    {
        return $this->pluck('receiveStaff_id', $allocationId);
    }

    // last id
    public function lastId()
    {
        $result = QcToolAllocation::orderBy('allocation_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->allocation_id;
    }

    public function checkConfirm($allocationId = null)
    {
        return ($this->confirmStatus($allocationId) == 0) ? false : true;
    }
}
