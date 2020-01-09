<?php

namespace App\Models\Ad3d\ToolAllocationDetail;

use Illuminate\Database\Eloquent\Model;

class QcToolAllocationDetail extends Model
{
    protected $table = 'qc_tool_allocation_detail';
    protected $fillable = ['detail_id', 'amount', 'newStatus', 'created_at', 'tool_id', 'company_id', 'store_id', 'allocation_id'];
    protected $primaryKey = 'detail_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- thêm ----------
    public function insert($amount, $newStatus, $toolId, $allocationId, $companyId, $storeId)
    {
        $hFunction = new \Hfunction();
        $modelToolAllocationDetail = new QcToolAllocationDetail();
        $modelToolAllocationDetail->amount = $amount;
        $modelToolAllocationDetail->newStatus = $newStatus;
        $modelToolAllocationDetail->tool_id = $toolId;
        $modelToolAllocationDetail->store_id = $storeId;
        $modelToolAllocationDetail->company_id = $companyId;
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
        return QcToolAllocationDetail::where('store_id', $storeId)->sum('amount');
    }

    //---------- công cụ -----------
    public function tool()
    {
        return $this->belongsTo('App\Models\Ad3d\Tool\QcTool', 'tool_id', 'tool_id');
    }

    // số lượng đã bàn giao của dụng cụ
    public function amountAllocatedOfTool($toolId)
    {
        return QcToolAllocationDetail::where('tool_id', $toolId)->sum('amount');
    }

    //---------- phiếu bàn giao -----------
    public function toolAllocation()
    {
        return $this->belongsTo('App\Models\Ad3d\ToolAllocation\QcToolAllocation', 'allocation_id', 'allocation_id');
    }

    public function totalAmountOfAllocation($allocationId)
    {
        return QcToolAllocationDetail::where('allocation_id', $allocationId)->sum('amount');
    }

    public function toolAllocationDetailOfAllocation($allocationId)
    {
        return QcToolAllocationDetail::where('allocation_id', $allocationId)->get();
    }

    public function infoOfToolAllocation($allocationId)
    {
        return QcToolAllocationDetail::where('allocation_id', $allocationId)->get();
    }

    public function infoOfListToolAllocation($listAllocationId)
    {
        return QcToolAllocationDetail::whereIn('allocation_id', $listAllocationId)->orderBy('created_at', 'DESC')->get();
    }

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

    public function amount($detailId = null)
    {
        return $this->pluck('amount', $detailId);
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
