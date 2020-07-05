<?php

namespace App\Models\Ad3d\ToolReturnConfirm;

use App\Models\Ad3d\CompanyStore\QcCompanyStore;
use App\Models\Ad3d\ToolReturn\QcToolReturn;
use Illuminate\Database\Eloquent\Model;

class QcToolReturnConfirm extends Model
{
    protected $table = 'qc_tool_return_confirm';
    protected $fillable = ['confirm_id', 'amount', 'created_at', 'store_id', 'return_id'];
    protected $primaryKey = 'confirm_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- them ----------
    public function insert($amount, $storeId, $returnId)
    {
        $hFunction = new \Hfunction();
        $modelToolReturnConfirm = new QcToolReturnConfirm();
        $modelToolReturnConfirm->amount = $amount;
        $modelToolReturnConfirm->store_id = $storeId;
        $modelToolReturnConfirm->return_id = $returnId;
        $modelToolReturnConfirm->created_at = $hFunction->createdAt();
        if ($modelToolReturnConfirm->save()) {
            $this->lastId = $modelToolReturnConfirm->confirm_id;
            return true;
        } else {
            return false;
        }
    }

    public function insertGetId()
    {
        return $this->lastId;
    }
    //========== ========= ========= RELATION ========== ========= ==========
    //---------- kho dung cu -----------
    public function companyStore()
    {
        return $this->belongsTo('App\Models\Ad3d\CompanyStore\QcCompanyStore', 'store_id', 'store_id');
    }

    # tong so duoc xac nhan tra kho
    public function amountOfReturnAndStore($returnId, $storeId)
    {
        return QcToolReturnConfirm::where('return_id', $returnId)->where('store_id', $storeId)->pluck('amount');
    }

    //---------- phieu ban giao -----------
    public function toolReturn()
    {
        return $this->belongsTo('App\Models\Ad3d\ToolReturn\QcToolReturn', 'return_id', 'return_id');
    }

    public function getInfoOfReturn($returnId)
    {
        return QcToolReturnConfirm::where('return_id', $returnId)->get();
    }

    //========= ========== ========== LAY THONG TIN ========== ========== ==========
    # tong so luong 1 cong cu cua 1 lan hoac nhieu lan giao tai cac cty
    public function totalToolOfListReturnId($listReturnId, $toolId)
    {
        $modelCompanyStore = new QcCompanyStore();
        $listStoreId = $modelCompanyStore->listIdOfTool($toolId);
        return QcToolReturnConfirm::whereIn('return_id', $listReturnId)->whereIn('store_id', $listStoreId)->sum('amount');
    }

    # tong dung giao đc xac nhan cua 1 NV khi lam viec o 1 cty
    public function totalToolOfWork($toolId, $workId)
    {
        $modelToolReturn = new QcToolReturn();
        return $this->totalToolOfListReturnId($modelToolReturn->listIdOfWork($workId), $toolId);
    }

    public function getInfo($confirmId = '', $field = '')
    {
        if (empty($confirmId)) {
            return QcToolReturnConfirm::get();
        } else {
            $result = QcToolReturnConfirm::where('confirm_id', $confirmId)->first();
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
            return QcToolReturnConfirm::where('confirm_id', $objectId)->pluck($column);
        }
    }

    public function confirmId()
    {
        return $this->confirm_id;
    }

    public function amount($confirmId = null)
    {
        return $this->pluck('amount', $confirmId);
    }

    public function createdAt($confirmId = null)
    {
        return $this->pluck('created_at', $confirmId);
    }

    public function storeId($confirmId = null)
    {
        return $this->pluck('store_id', $confirmId);
    }

    public function returnId($confirmId = null)
    {
        return $this->pluck('return_id', $confirmId);
    }

    // last id
    public function lastId()
    {
        $result = QcToolReturnConfirm::orderBy('confirm_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->confirm_id;
    }
}
