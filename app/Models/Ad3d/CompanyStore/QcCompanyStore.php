<?php

namespace App\Models\Ad3d\CompanyStore;

use App\Models\Ad3d\ToolAllocationDetail\QcToolAllocationDetail;
use Illuminate\Database\Eloquent\Model;

class QcCompanyStore extends Model
{
    protected $table = 'qc_company_store';
    protected $fillable = ['store_id', 'amount', 'updateDate', 'created_at', 'company_id', 'tool_id', 'supplies_id'];
    protected $primaryKey = 'store_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- thêm ----------
    public function insert($amount, $companyId, $toolId = null, $suppliesId = null)
    {
        $hFunction = new \Hfunction();
        $modelCompanyStore = new QcCompanyStore();
        $modelCompanyStore->amount = $amount;
        $modelCompanyStore->updateDate = $hFunction->createdAt();
        $modelCompanyStore->company_id = $companyId;
        $modelCompanyStore->tool_id = $toolId;
        $modelCompanyStore->supplies_id = $suppliesId;
        $modelCompanyStore->created_at = $hFunction->createdAt();
        if ($modelCompanyStore->save()) {
            $this->lastId = $modelCompanyStore->store_id;
            return true;
        } else {
            return false;
        }
    }

    public function insertGetId()
    {
        return $this->lastId;
    }

    public function checkIdNull($storeId)
    {
        return (empty($storeId)) ? $this->storeId() : $storeId;
    }
    // cập nhật thông tin
    public function updateInfo($storeId, $amount)
    {
        $hFunction = new \Hfunction();
        $oldAmount = $this->amount($storeId);
        return QcCompanyStore::where('store_id', $storeId)->update([
            'amount' => (int)$oldAmount + (int)$amount,
            'updateDate' => $hFunction->createdAt(),
        ]);
    }

    public function updateInfoByToolOrSupplies($companyId, $amount, $toolId, $suppliesId)
    {
        $hFunction = new \Hfunction();
        $oldAmount = QcCompanyStore::where('company_id', $companyId)->where('tool_id', $toolId)->where('supplies_id', $suppliesId)->sum('amount');
        return QcCompanyStore::where('company_id', $companyId)->where('tool_id', $toolId)->where('supplies_id', $suppliesId)->update([
            'amount' => (int)$oldAmount + (int)$amount,
            'updateDate' => $hFunction->createdAt(),
        ]);
    }

    /*public function deleteDetail($storeId = null)
    {
        $storeId = (empty($storeId)) ? $this->paymentId() : $storeId;
        return QcCompanyStore::where('store_id', $storeId)->delete();
    }*/
    //========== ========= ========= CAC MON QUAN HE ========== ========= ==========
    //---------- công ty -----------
    public function company()
    {
        return $this->belongsTo('App\Models\Ad3d\Company\QcCompany', 'company_id', 'company_id');
    }

    public function selectInfoToolOfListCompanyAndListToolAnd($listCompanyId, $listToolId, $orderBy = 'DESC')
    {
        if (empty($listToolId)) {
            return QcCompanyStore::whereIn('company_id', $listCompanyId)->whereNotNull('tool_id')->orderBy('updateDate', $orderBy)->select();
        } else {
            return QcCompanyStore::whereIn('company_id', $listCompanyId)->whereIn('tool_id', $listToolId)->orderBy('updateDate', $orderBy)->select();
        }
    }

    //---------- phat dung cu -----------
    public function toolAllocationDetail()
    {
        return $this->belongsTo('App\Models\Ad3d\ToolAllocationDetail\QcToolAllocationDetail', 'store_id', 'store_id');
    }

    public function toolAllocationDetailTotalAmount($storeId = null)
    {
        $modelToolAllocationDetail = new QcToolAllocationDetail();
        return $modelToolAllocationDetail->totalAmountOfStore($this->checkIdNull($storeId));
    }

    //---------- công cụ -----------
    public function tool()
    {
        return $this->belongsTo('App\Models\Ad3d\Tool\QcTool', 'tool_id', 'tool_id');
    }

    public function existOfTool($toolId)
    {
        $result = QcCompanyStore::where('tool_id', $toolId)->count();
        return ($result > 0) ? true : false;
    }

    public function existOfToolAndCompany($toolId, $companyId)
    {
        $result = QcCompanyStore::where('tool_id', $toolId)->where('company_id', $companyId)->count();
        return ($result > 0) ? true : false;
    }

    public function infoOfToolAndCompany($toolId, $companyId)
    {
        return QcCompanyStore::where('tool_id', $toolId)->where('company_id', $companyId)->first();
    }

    public function amountOfTool($toolId)
    {
        return QcCompanyStore::where('tool_id', $toolId)->sum('amount');
    }

    //---------- vật tư -----------
    public function supplies()
    {
        return $this->belongsTo('App\Models\Ad3d\Supplies\QcSupplies', 'supplies_id', 'supplies_id');
    }

    public function existOfSupplies($suppliesId)
    {
        $result = QcCompanyStore::where('supplies_id', $suppliesId)->count();
        return ($result > 0) ? true : false;
    }

    public function existOfSuppliesAndCompany($suppliesId, $companyId)
    {
        $result = QcCompanyStore::where('supplies_id', $suppliesId)->where('company_id', $companyId)->count();
        return ($result > 0) ? true : false;
    }

    public function amountOfSupplies($suppliesId)
    {
        return QcCompanyStore::where('supplies_id', $suppliesId)->count();
    }

    //========= ========== ========== GET INFO ========== ========== ==========
    public function getInfo($storeId = '', $field = '')
    {
        if (empty($storeId)) {
            return QcCompanyStore::get();
        } else {
            $result = QcCompanyStore::where('store_id', $storeId)->first();
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
            return QcCompanyStore::where('store_id', $objectId)->pluck($column);
        }
    }

    public function storeId()
    {
        return $this->store_id;
    }

    public function amount($storeId = null)
    {

        return $this->pluck('amount', $storeId);
    }

    public function updateDate($storeId = null)
    {

        return $this->pluck('updateDate', $storeId);
    }

    public function createdAt($storeId = null)
    {
        return $this->pluck('created_at', $storeId);
    }

    public function companyId($storeId = null)
    {
        return $this->pluck('company_id', $storeId);
    }

    public function toolId($storeId = null)
    {
        return $this->pluck('tool_id', $storeId);
    }

    public function suppliesId($storeId = null)
    {
        return $this->pluck('supplies_id', $storeId);
    }

    // last id
    public function lastId()
    {
        $result = QcCompanyStore::orderBy('store_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->store_id;
    }

}
