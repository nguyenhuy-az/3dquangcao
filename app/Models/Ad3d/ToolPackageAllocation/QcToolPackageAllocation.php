<?php

namespace App\Models\Ad3d\ToolPackageAllocation;

use App\Models\Ad3d\ToolPackageAllocationDetail\QcToolPackageAllocationDetail;
use App\Models\Ad3d\ToolPackageAllocationReturn\QcToolPackageAllocationReturn;
use Illuminate\Database\Eloquent\Model;

class QcToolPackageAllocation extends Model
{
    protected $table = 'qc_tool_package_allocation';
    protected $fillable = ['allocation_id', 'action', 'allocationDate', 'created_at', 'allocationStaff_id', 'work_id', 'package_id'];
    protected $primaryKey = 'allocation_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- thêm ----------
    public function insert($allocationDate, $allocationStaffId, $workId, $packageId)
    {
        $hFunction = new \Hfunction();
        $modelToolPackageAllocation = new QcToolPackageAllocation();
        $modelToolPackageAllocation->allocationDate = $allocationDate;
        $modelToolPackageAllocation->allocationStaff_id = $allocationStaffId;
        $modelToolPackageAllocation->work_id = $workId;
        $modelToolPackageAllocation->package_id = $packageId;
        $modelToolPackageAllocation->created_at = $hFunction->createdAt();
        if ($modelToolPackageAllocation->save()) {
            $this->lastId = $modelToolPackageAllocation->allocation_id;
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

    public function disableAllocation($allocationId = null)
    {
        return QcToolPackageAllocation::where('allocation_id', $this->checkIdNull($allocationId))->update(['action' => 0]);
    }

    public function deleteAllocation($allocationId = null)
    {
        return QcToolPackageAllocation::where('allocation_id', $this->checkIdNull($allocationId))->delete();
    }
    //========== ========= ========= RELATION ========== ========= ==========
    //---------- nhân viên bàn giao -----------
    public function allocationStaff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'allocationStaff_id', 'staff_id');
    }

    //---------- nhân viên nhận -----------
    public function companyStaffWork()
    {
        return $this->belongsTo('App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork', 'work_id', 'work_id');
    }

    # lay thong tin ban giao dang hoat dong
    public function infoActivityOfWork($workId)
    {

        return QcToolPackageAllocation::where('work_id', $workId)->where('action', 1)->first();
    }

    # lay thong tin ban giao cua 1 nv
    public function infoOfWork($workId)
    {

        return QcToolPackageAllocation::where('work_id', $workId)->orderBy('allocationDate', 'DESC')->get();
    }

    # lay thong tin ban giao cua 1/nhieu nv
    public function infoActivityOfListWork($listWorkId)
    {

        return QcToolPackageAllocation::whereIn('work_id', $listWorkId)->where('action', 1)->orderBy('allocationDate', 'DESC')->get();
    }

    # danh sach ma ban giao
    public function listIdOfWork($workId)
    {
        return QcToolPackageAllocation::where('work_id', $workId)->orderBy('allocationDate', 'DESC')->pluck('allocation_id');
    }

    # danh sach ma ban giao cua nhieu NC
    public function listIdOfListWork($listWorkId)
    {
        return QcToolPackageAllocation::wherein('work_id', $listWorkId)->orderBy('allocationDate', 'DESC')->pluck('allocation_id');
    }

    //---------- bo do nghe -----------
    public function toolPackage()
    {
        return $this->belongsTo('App\Models\Ad3d\ToolPackage\QcToolPackage', 'package_id', 'package_id');
    }

    # lat danh sach ma bo do nghe dang duoc cap phat
    public function listPackageIdIsActive()
    {
        return QcToolPackageAllocation::wherein('action', 1)->pluck('package_id');
    }

    //---------- Chi tiết cấp -----------
    public function toolPackageAllocationDetail()
    {
        return $this->belongsTo('App\Models\Ad3d\ToolPackageAllocationDetail\QcToolPackageAllocationDetail', 'allocation_id', 'allocation_id');
    }

    # lay thong tin giao chua tra cua 1 bo do nghe
    public function toolAllocationDetailInfoNotReturn($allocationId)
    {
        $modelToolPackageAllocationDetail = new QcToolPackageAllocationDetail();
        return $modelToolPackageAllocationDetail->getInfoNotReturnOfAllocation($allocationId);
    }

    public function totalAmountToolOfAllocation($allocationId = null)
    {
        $modelToolPackageAllocationDetail = new QcToolPackageAllocationDetail();
        return $modelToolPackageAllocationDetail->totalAmountOfAllocation($this->checkIdNull($allocationId));
    }

    public function toolAllocationDetailOfAllocation($allocationId = null)
    {
        $modelToolPackageAllocationDetail = new QcToolPackageAllocationDetail();
        return $modelToolPackageAllocationDetail->infoOfToolAllocation($this->checkIdNull($allocationId));
    }

    public function toolAllocationDetailOfListAllocation($listAllocationId)
    {
        $modelToolPackageAllocationDetail = new QcToolPackageAllocationDetail();
        return $modelToolPackageAllocationDetail->infoOfListToolAllocation($listAllocationId);
    }

    # thong tin ban giao cua loai do nghe  trong bo do nghe duoc giao, dang hoat hoat dong
    public function infoActivityOfToolAllocationAndTool($allocationId, $toolId)
    {
        $modelToolPackageAllocationDetail = new QcToolPackageAllocationDetail();
        return $modelToolPackageAllocationDetail->infoActivityOfToolAllocationAndTool($allocationId, $toolId);
    }

    #--------- -------------- tra do nghe ------------- -----------
    # lay thong tin tra cua 1 bo do nghe
    public function toolReturnUnConfirmInfo($allocationId)
    {
        $modelToolPackageAllocationDetail = new QcToolPackageAllocationDetail();
        $modelToolReturn = new QcToolPackageAllocationReturn();
        $listDetailId = $modelToolPackageAllocationDetail->listIdOfListAllocationId([$this->checkIdNull($allocationId)]);
        return $modelToolReturn->infoUnConfirmOfListDetail($listDetailId);
    }

    //========= ========== ========== lấy thông tin ========== ========== ==========
    public function selectInfoOfListWorkAndDate($listStaffId, $dateFilter = null)
    {
        if (empty($dateFilter)) {
            return QcToolPackageAllocation::whereIn('work_id', $listStaffId)->orderBy('allocationDate', 'DESC')->select('*');
        } else {
            return QcToolPackageAllocation::whereIn('work_id', $listStaffId)->where('allocationDate', 'like', "%$dateFilter%")->orderBy('allocationDate', 'DESC')->select('*');
        }
    }

    public function getInfo($allocationId = '', $field = '')
    {
        if (empty($allocationId)) {
            return QcToolPackageAllocation::get();
        } else {
            $result = QcToolPackageAllocation::where('allocation_id', $allocationId)->first();
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
            return QcToolPackageAllocation::where('allocation_id', $objectId)->pluck($column);
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

    public function createdAt($allocationId = null)
    {
        return $this->pluck('created_at', $allocationId);
    }

    public function allocationStaffId($allocationId = null)
    {
        return $this->pluck('allocationStaff_id', $allocationId);
    }

    public function WorkId($allocationId = null)
    {
        return $this->pluck('work_id', $allocationId);
    }

    // last id
    public function lastId()
    {
        $result = QcToolPackageAllocation::orderBy('allocation_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->allocation_id;
    }

}
