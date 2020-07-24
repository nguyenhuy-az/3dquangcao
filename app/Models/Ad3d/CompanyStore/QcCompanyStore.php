<?php

namespace App\Models\Ad3d\CompanyStore;

use App\Models\Ad3d\Tool\QcTool;
use App\Models\Ad3d\ToolAllocationDetail\QcToolAllocationDetail;
use Illuminate\Database\Eloquent\Model;

class QcCompanyStore extends Model
{
    protected $table = 'qc_company_store';
    protected $fillable = ['store_id', 'name', 'created_at', 'company_id', 'tool_id', 'supplies_id', 'import_id'];
    protected $primaryKey = 'store_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- thêm ----------
    public function insert($name, $companyId, $toolId = null, $suppliesId = null, $importId = null)
    {
        $hFunction = new \Hfunction();
        $modelCompanyStore = new QcCompanyStore();
        $modelCompanyStore->name = $name;
        $modelCompanyStore->company_id = $companyId;
        $modelCompanyStore->tool_id = $toolId;
        $modelCompanyStore->supplies_id = $suppliesId;
        $modelCompanyStore->import_id = $importId;
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

    public function deleteStore($storeId = null)
    {
        return QcCompanyStore::where('store_id', $this->checkIdNull($storeId))->delete();
    }
    //========== ========= ========= CAC MON QUAN HE ========== ========= ==========
    //---------- thong tin bao cao kiem tra do nghe dung chung -----------
    public function companyStoreCheckReport()
    {
        return $this->hasMany('App\Models\Ad3d\CompanyStoreCheckReport\QcCompanyStoreCheckReport', 'store_id', 'store_id');
    }

    //---------- công ty -----------
    public function company()
    {
        return $this->belongsTo('App\Models\Ad3d\Company\QcCompany', 'company_id', 'company_id');
    }

    # cong cu dung chung de nv thi cong kiem tra trong ngay
    public function getPublicToolToCheckOfCompany($companyId)
    {
        $modelTool = new QcTool();
        return QcCompanyStore::where('company_id', $companyId)->whereIn('tool_id', $modelTool->publicListId())->get();
    }

    public function selectInfoToolOfListCompanyAndListToolAnd($listCompanyId, $listToolId, $orderBy = 'DESC')
    {
        if (empty($listToolId)) {
            return QcCompanyStore::whereIn('company_id', $listCompanyId)->whereNotNull('tool_id')->orderBy('store_id', $orderBy)->select();
        } else {
            return QcCompanyStore::whereIn('company_id', $listCompanyId)->whereIn('tool_id', $listToolId)->orderBy('store_id', $orderBy)->select();
        }
    }

    public function totalToolOfCompany($companyId, $toolId)
    {
        return QcCompanyStore::where('company_id', $companyId)->where('tool_id', $toolId)->count();
    }

    public function getInfoOfListToolAndCompany($companyId, $listToolId)
    {
        return QcCompanyStore::where('company_id', $companyId)->whereIn('tool_id', $listToolId)->get();
    }

    //---------- phat dung cu -----------
    public function toolAllocationDetail()
    {
        return $this->belongsTo('App\Models\Ad3d\ToolAllocationDetail\QcToolAllocationDetail', 'store_id', 'store_id');
    }

    # tong so luong da phat
    public function totalAmountAllocation($storeId = null)
    {
        $modelToolAllocationDetail = new QcToolAllocationDetail();
        return $modelToolAllocationDetail->totalAmountOfStore($this->checkIdNull($storeId));
    }

    # lay thong tin lan phat sau cung
    public function toolAllocationDetailLastInfo($storeId = null)
    {
        $modelToolAllocationDetail = new QcToolAllocationDetail();
        return $modelToolAllocationDetail->lastInfoOfCompanyStore($this->checkIdNull($storeId));
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

    # so lương dung cu trong kho cua tat ca cty
    public function amountOfTool($toolId)
    {
        return QcCompanyStore::where('tool_id', $toolId)->count();
    }

    # so lương dung cu trong kho cua 1 cty
    public function amountOfToolAndCompany($toolId, $companyId)
    {
        return QcCompanyStore::where('tool_id', $toolId)->where('company_id', $companyId)->count();
    }

    # lay danh sach ma kho cua dung cu cua tat ca cty
    public function listIdOfTool($toolId)
    {
        return QcCompanyStore::where('tool_id', $toolId)->pluck('store_id');
    }

    # lay danh sach ma kho cua dung cu tat 1 cty
    public function listIdOfToolAndCompany($toolId, $companyId)
    {
        return QcCompanyStore::where('tool_id', $toolId)->where('company_id', $companyId)->pluck('store_id');
    }

    #danh sach ma dung cu theo kho
    public function listToolIdOfListStore($listStoreId)
    {
        return QcCompanyStore::whereIn('store_id', $listStoreId)->pluck('tool_id');
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

    //---------- thong tin nhap -----------
    public function import()
    {
        return $this->belongsTo('App\Models\Ad3d\Import\QcImport', 'import_id', 'import_id');
    }
    //========= ========== ========== GET INFO ========== ========== ==========
    # lay thong tin theo dung cu da ban giao nhan vien theo cong ty
    public function getInfoByListId($listStoreId)
    {
        return QcCompanyStore::whereIn('store_id', $listStoreId)->get();
    }

    # lay thong tin theo dung cu va cong ty
    public function getInfoByListToolAndCompany($listToolId, $companyId)
    {
        return QcCompanyStore::whereIn('tool_id', $listToolId)->where('company_id', $companyId)->get();
    }

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

    public function name($storeId = null)
    {

        return $this->pluck('name', $storeId);
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

    public function importId($storeId = null)
    {
        return $this->pluck('import_id', $storeId);
    }

    // last id
    public function lastId()
    {
        $result = QcCompanyStore::orderBy('store_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->store_id;
    }

}
