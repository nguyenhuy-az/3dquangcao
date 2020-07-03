<?php

namespace App\Models\Ad3d\ToolReturnDetail;

use App\Models\Ad3d\CompanyStore\QcCompanyStore;
use App\Models\Ad3d\ToolReturn\QcToolReturn;
use Illuminate\Database\Eloquent\Model;

class QcToolReturnDetail extends Model
{
    protected $table = 'qc_tool_return_detail';
    protected $fillable = ['detail_id', 'amount', 'created_at', 'store_id', 'return_id'];
    protected $primaryKey = 'detail_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- th�m ----------
    public function insert($amount, $storeId, $returnId)
    {
        $hFunction = new \Hfunction();
        $modelToolReturnDetail = new QcToolReturnDetail();
        $modelToolReturnDetail->amount = $amount;
        $modelToolReturnDetail->store_id = $storeId;
        $modelToolReturnDetail->return_id = $returnId;
        $modelToolReturnDetail->created_at = $hFunction->createdAt();
        if ($modelToolReturnDetail->save()) {
            $this->lastId = $modelToolReturnDetail->detail_id;
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
        return QcToolReturnDetail::where('detail_id', $detailId)->delete();
    }
    //========== ========= ========= RELATION ========== ========= ==========
    //---------- kho dung cu -----------
    public function companyStore()
    {
        return $this->belongsTo('App\Models\Ad3d\CompanyStore\QcCompanyStore', 'store_id', 'store_id');
    }

    /*public function amountReturnOfTool($toolId)
    {
        return QcToolReturnDetail::where('store_id',$toolId)->sum('amount');
    }*/

    //---------- phieu ban giao -----------
    public function toolReturn()
    {
        return $this->belongsTo('App\Models\Ad3d\ToolReturn\QcToolReturn', 'return_id', 'return_id');
    }

    public function getInfoOfReturn($returnId)
    {
        return QcToolReturnDetail::where('return_id', $returnId)->get();
    }

    # tong so luong cua 1 lan abstract
    public function totalAmountOfReturn($returnId)
    {
        return QcToolReturnDetail::where('return_id', $returnId)->sum('amount');
    }

    //========= ========== ========== LAY THONG TIN ========== ========== ==========
    # tong so luong 1 cong cu cua 1 lan hoac nhieu lan giao tai cac cty
    public function totalToolOfListReturnId($listReturnId, $toolId)
    {
        $modelCompanyStore = new QcCompanyStore();
        $listStoreId = $modelCompanyStore->listIdOfTool($toolId);
        return QcToolReturnDetail::whereIn('return_id', $listReturnId)->whereIn('store_id', $listStoreId)->sum('amount');
    }

    # tong dung cu cua 1 NV khi lam viec o 1 cty
    public function totalToolOfWork($toolId, $workId)
    {
        $modelToolReturn = new QcToolReturn();
        return $this->totalToolOfListReturnId($modelToolReturn->listIdOfWork($workId), $toolId);
    }

    public function getInfo($detailId = '', $field = '')
    {
        if (empty($detailId)) {
            return QcToolReturnDetail::get();
        } else {
            $result = QcToolReturnDetail::where('detail_id', $detailId)->first();
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
            return QcToolReturnDetail::where('detail_id', $objectId)->pluck($column);
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

    public function createdAt($detailId = null)
    {
        return $this->pluck('created_at', $detailId);
    }

    public function storeId($detailId = null)
    {
        return $this->pluck('store_id', $detailId);
    }

    public function returnId($detailId = null)
    {
        return $this->pluck('return_id', $detailId);
    }

    // last id
    public function lastId()
    {
        $result = QcToolReturnDetail::orderBy('detail_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->detail_id;
    }
}
