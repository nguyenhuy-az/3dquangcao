<?php

namespace App\Models\Ad3d\ToolAllocationDetail;

use App\Models\Ad3d\CompanyStore\QcCompanyStore;
use App\Models\Ad3d\ToolAllocation\QcToolAllocation;
use Illuminate\Database\Eloquent\Model;

class QcToolAllocationDetail extends Model
{
    protected $table = 'qc_tool_allocation_detail';
    protected $fillable = ['detail_id', 'image', 'newStatus', 'created_at', 'store_id', 'allocation_id'];
    protected $primaryKey = 'detail_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- thêm ----------
    public function insert($image, $newStatus, $allocationId, $storeId)
    {
        $hFunction = new \Hfunction();
        $modelToolAllocationDetail = new QcToolAllocationDetail();
        $modelToolAllocationDetail->image = $image;
        $modelToolAllocationDetail->newStatus = $newStatus;
        $modelToolAllocationDetail->store_id = $storeId;
        $modelToolAllocationDetail->allocation_id = $allocationId;
        $modelToolAllocationDetail->created_at = $hFunction->createdAt();
        if ($modelToolAllocationDetail->save()) {
            $this->lastId = $modelToolAllocationDetail->detail_id;
            return true;
        } else {
            return false;
        }
    }

    public function insertGetId()
    {
        return $this->lastId;
    }

    public function deleteDetail($detailId = null)
    {
        $detailId = (empty($detailId)) ? $this->detailId() : $detailId;
        return QcToolAllocationDetail::where('detail_id', $detailId)->delete();
    }
    //========== ========= =========  CAC MOI QUAN HE ========== ========= ==========
    //Kho
    public function companyStory()
    {
        return $this->belongsTo('App\Models\Ad3d\CompanyStore\QcCompanyStore', 'store_id', 'store_id');
    }

    public function totalAmountOfStore($storeId)
    {
        return QcToolAllocationDetail::where('store_id', $storeId)->count();
    }

    # danh sach ma kho cua cac lan ban giao
    public function listStoreIdOfListAllocation($listAllocationId)
    {
        return QcToolAllocationDetail::whereIn('allocation_id', $listAllocationId)->groupBy('store_id')->pluck('store_id');
    }

    //---------- phiếu bàn giao -----------
    public function toolAllocation()
    {
        return $this->belongsTo('App\Models\Ad3d\ToolAllocation\QcToolAllocation', 'allocation_id', 'allocation_id');
    }

    # tong so luong tat ca cong cu cua 1 lan giao
    public function totalAmountOfAllocation($allocationId)
    {
        return QcToolAllocationDetail::where('allocation_id', $allocationId)->count();
    }

    # tong so luong tat ca cong cu cua 1 lan hoac nhieu lan giao
    public function totalAmountOfListAllocationId($listAllocationId)
    {
        return QcToolAllocationDetail::whereIn('allocation_id', $listAllocationId)->count();
    }


    public function toolAllocationDetailOfAllocation($allocationId)
    {
        return QcToolAllocationDetail::where('allocation_id', $allocationId)->get();
    }

    public function infoOfToolAllocation($allocationId)
    {
        return QcToolAllocationDetail::where('allocation_id', $allocationId)->get();
    }

    #thong tin cua tat ca cty
    public function infoOfListToolAllocation($listAllocationId)
    {
        return QcToolAllocationDetail::whereIn('allocation_id', $listAllocationId)->orderBy('created_at', 'DESC')->get();
    }

    #thong tin cua 1 cty
    public function infoOfListToolAllocationAndCompany($listAllocationId, $companyId)
    {
        return QcToolAllocationDetail::whereIn('allocation_id', $listAllocationId)->where('company_id', $companyId)->orderBy('created_at', 'DESC')->get();
    }


    # tong so luong 1 cong cu cua 1 lan giao
    public function totalToolOfAllocation($allocationId, $toolId)
    {
        return 1000;// QcToolAllocationDetail::where('allocation_id', $allocationId)->where('tool_id', $toolId)->sum('amount');
    }

    # tong so luong 1 cong cu cua 1 lan hoac nhieu lan giao tai cac cty
    public function totalToolOfListAllocationId($listAllocationId, $toolId)
    {
        $modelCompanyStore = new QcCompanyStore();
        $listStoreId = $modelCompanyStore->listIdOfTool($toolId);
        return QcToolAllocationDetail::whereIn('allocation_id', $listAllocationId)->whereIn('store_id', $listStoreId)->count();
    }

    # tong so luong 1 cong cu cua 1 lan hoac nhieu lan giao tai 1 cty
    public function totalToolOfListAllocationAndCompany($listAllocationId, $companyId, $toolId)
    {
        return 3000;//QcToolAllocationDetail::whereIn('allocation_id', $listAllocationId)->where('company_id',$companyId)->where('tool_id', $toolId)->sum('amount');
    }


    # danh sanh ma dung cu da ban giao cho nhan vien
    public function listToolIdOfWork($workId)
    {
        $modelToolAllocation = new QcToolAllocation();
        $listAllocationId = $modelToolAllocation->listIdOfWork($workId);
        return QcToolAllocationDetail::whereIn('allocation_id', $listAllocationId)->groupBy('tool_id')->pluck('tool_id');
    }

    # danh sanh ma kho da ban giao cho nhan vien
    public function listStoreIdOfWork($workId)
    {
        $modelToolAllocation = new QcToolAllocation();
        $listAllocationId = $modelToolAllocation->listIdOfWork($workId);
        return QcToolAllocationDetail::whereIn('allocation_id', $listAllocationId)->groupBy('store_id')->pluck('store_id');
    }

    //========= ========== ========== lay thong tin cua nhan vien ========== ========== ==========
    # tong dung cu cua 1 NV khi lam viec o 1 cty
    public function totalToolOfWork($toolId, $workId)
    {
        $modelToolAllocation = new QcToolAllocation();
        return $this->totalToolOfListAllocationId($modelToolAllocation->listIdOfWork($workId), $toolId);
    }

    # cua 1 cty
    /*public function totalToolOfStaffAndCompany($staffId,$companyId, $toolId)
    {
        $modelToolAllocation = new QcToolAllocation();
        $listAllocationId = $modelToolAllocation->listIdOfReceiveStaff($staffId);
        return $this->totalToolOfListAllocationAndCompany($listAllocationId, $companyId, $toolId);
    }*/

    //========= ========== ========== lấy thông tin ========== ========== ==========
    public function getInfo($detailId = '', $field = '')
    {
        if (empty($detailId)) {
            return QcToolAllocationDetail::get();
        } else {
            $result = QcToolAllocationDetail::where('detail_id', $detailId)->first();
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
            return QcToolAllocationDetail::where('detail_id', $objectId)->pluck($column);
        }
    }

    public function detailId()
    {
        return $this->detail_id;
    }

    public function image($detailId = null)
    {
        return $this->pluck('image', $detailId);
    }

    public function newStatus($detailId = null)
    {
        return $this->pluck('newStatus', $detailId);
    }

    public function createdAt($detailId = null)
    {
        return $this->pluck('created_at', $detailId);
    }

    public function toolId($detailId = null)
    {
        return $this->pluck('tool_id', $detailId);
    }

    public function allocationId($detailId = null)
    {
        return $this->pluck('allocation_id', $detailId);
    }

    // last id
    public function lastId()
    {
        $result = QcToolAllocationDetail::orderBy('detail_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->detail_id;
    }

    //kiểm tra thông tin
    public function checkNewStatus($detailId = null)
    {
        return ($this->newStatus($detailId) == 1) ? true : false;
    }
}
