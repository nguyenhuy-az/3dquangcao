<?php

namespace App\Models\Ad3d\CompanyStore;

use App\Models\Ad3d\CompanyStoreCheckReport\QcCompanyStoreCheckReport;
use App\Models\Ad3d\Tool\QcTool;
use App\Models\Ad3d\ToolPackageAllocationDetail\QcToolPackageAllocationDetail;
use Illuminate\Database\Eloquent\Model;

class QcCompanyStore extends Model
{
    protected $table = 'qc_company_store';
    protected $fillable = ['store_id', 'name', 'importPrice', 'useStatus', 'created_at', 'company_id', 'tool_id', 'supplies_id', 'import_id', 'package_id'];
    protected $primaryKey = 'store_id';
    public $timestamps = false;

    private $lastId;

    #mac dinh dung duoc
    public function getDefaultHasUse()
    {
        return 1;
    }

    # mac dinh khong dung duoc
    public function getDefaultNotUse()
    {
        return 2;
    }

    # mac dinh da mat
    public function getDefaultLostUse()
    {
        return 3;
    }

    # mac dinh ma dung cu
    public function getDefaultToolId()
    {
        return null;
    }

    # mac dinh ma vat tu
    public function getDefaultSuppliesId()
    {
        return null;
    }

    # mac dinh ma nhap vat tu
    public function getDefaultImportId()
    {
        return null;
    }

    # mac dinh tui o nghe
    public function getDefaultPackageId()
    {
        return null;
    }
    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- thêm ----------
    public function insert($name, $companyId, $toolId = null, $suppliesId = null, $importId = null, $importPrice = 1000, $packageId = null)
    {
        $hFunction = new \Hfunction();
        $modelCompanyStore = new QcCompanyStore();
        $modelCompanyStore->name = $name;
        $modelCompanyStore->importPrice = $importPrice;
        $modelCompanyStore->company_id = $companyId;
        $modelCompanyStore->package_id = $packageId;
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

    public function checkIdNull($id)
    {
        $hFunction = new \Hfunction();
        return ($hFunction->checkEmpty($id)) ? $this->storeId() : $id;
    }

    public function deleteStore($storeId = null)
    {
        return QcCompanyStore::where('store_id', $this->checkIdNull($storeId))->delete();
    }

    # cap nhat trang thai su dung
    public function updateUseStatus($storeId, $useStatus)
    {
        return QcCompanyStore::where('store_id', $storeId)->update([
            'useStatus' => $useStatus
        ]);
    }

    # cap nhat trang thai su dung binh thuong cho dung cu
    public function updateNormalUseStatus($storeId)
    {
        return QcCompanyStore::where('store_id', $storeId)->update([
            'useStatus' => $this->getDefaultHasUse()
        ]);
    }
    //========== ========= ========= CAC MON QUAN HE ========== ========= ==========
    //---------- thong tin bao cao kiem tra do nghe dung chung -----------
    public function companyStoreCheckReport()
    {
        return $this->hasMany('App\Models\Ad3d\CompanyStoreCheckReport\QcCompanyStoreCheckReport', 'store_id', 'store_id');
    }

    # lay thong tin lan phat sau cung co hinh anh
    public function companyStoreCheckReportLastInfoHasImage($storeId = null)
    {
        $modelReport = new QcCompanyStoreCheckReport();
        return $modelReport->lastInfoOfCompanyStoreHasImage($this->checkIdNull($storeId));
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
        return QcCompanyStore::where('company_id', $companyId)->where('useStatus', $this->getDefaultHasUse())->whereIn('tool_id', $modelTool->publicListId())->get();
    }

    public function selectInfoToolOfListCompanyAndListToolAnd($listCompanyId, $listToolId, $orderBy = 'DESC')
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($listToolId)) {
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

    //---------- bo bo nghe -----------
    public function toolPackage()
    {
        return $this->belongsTo('App\Models\Ad3d\ToolPackage\QcToolPackage', 'package_id', 'package_id');
    }

    # thong tin tat ca dung cu cua 1 tui do nghe
    public function infoIsActiveOfToolPackage($packageId)
    {
        return QcCompanyStore::where('package_id', $packageId)->where('useStatus', 1)->get();
    }

    # thong tin dung cu cua 1 tui do nghe theo 1 loai do nghe dang hoat dong
    public function infoIsActiveOfPackageAndTool($packageId, $toolId)
    {
        return QcCompanyStore::where('package_id', $packageId)->where('tool_id', $toolId)->where('useStatus', 1)->get();
    }


    //---------- phat dung cu -----------
    public function toolPackageAllocationDetail()
    {
        return $this->hasMany('App\Models\Ad3d\ToolPackageAllocationDetail\QcToolPackageAllocationDetail', 'store_id', 'store_id');
    }

    # tong so luong da phat
    public function totalAmountAllocation($storeId = null)
    {
        $modelToolAllocationDetail = new QcToolPackageAllocationDetail();
        return $modelToolAllocationDetail->totalAmountOfStore($this->checkIdNull($storeId));
    }

    # lay thong tin lan phat sau cung
    public function toolAllocationDetailLastInfo($storeId = null)
    {
        $modelToolAllocationDetail = new QcToolPackageAllocationDetail();
        return $modelToolAllocationDetail->lastInfoOfCompanyStore($this->checkIdNull($storeId));
    }

    # lay thong tin chi tiet dang phat cua do nghe
    public function toolAllocationDetailInfoActivity($storeId = null)
    {
        $modelToolAllocationDetail = new QcToolPackageAllocationDetail();
        return $modelToolAllocationDetail->infoActivityOfStore($this->checkIdNull($storeId));
    }

    //---------- công cụ -----------
    public function tool()
    {
        return $this->belongsTo('App\Models\Ad3d\Tool\QcTool', 'tool_id', 'tool_id');
    }

    public function existOfTool($toolId)
    {
        return QcCompanyStore::where('tool_id', $toolId)->exists();
    }

    public function existOfToolAndCompany($toolId, $companyId)
    {
        return QcCompanyStore::where('tool_id', $toolId)->where('company_id', $companyId)->exists();
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

    # lay 1 loai do nghe trong kho cua 1 cong ty de phat cho nhan vien
    public function getOneInfoToAllocationOfTool($toolId, $companyId)
    {
        $modelToolAllocationDetail = new QcToolPackageAllocationDetail();
        # lay ma do nghe dang cap phat
        $listStoreId = $modelToolAllocationDetail->listStoreIdIsActivity();
        return QcCompanyStore::whereNotIn('store_id', $listStoreId)->where('useStatus', $this->getDefaultHasUse())->where('tool_id', $toolId)->where('company_id', $companyId)->first();
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
        return QcCompanyStore::where('supplies_id', $suppliesId)->exists();
    }

    public function existOfSuppliesAndCompany($suppliesId, $companyId)
    {
        $result = QcCompanyStore::where('supplies_id', $suppliesId)->where('company_id', $companyId)->exists();
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
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($storeId)) {
            return QcCompanyStore::get();
        } else {
            $result = QcCompanyStore::where('store_id', $storeId)->first();
            if ($hFunction->checkEmpty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }

    # lay 1 gia tri
    public function pluck($column, $objectId = null)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($objectId)) {
            return $this->$column;
        } else {
            return QcCompanyStore::where('store_id', $objectId)->pluck($column)[0];
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

    public function importPrice($storeId = null)
    {
        return $this->pluck('importPrice', $storeId);
    }

    public function useStatus($storeId = null)
    {
        return $this->pluck('useStatus', $storeId);
    }

    public function labelUseStatus($storeId = null)
    {
        $useStatus = $this->useStatus($storeId);
        if ($useStatus == $this->getDefaultHasUse()) {
            return 'Bình thường';
        } elseif ($useStatus == $this->getDefaultNotUse()) {
            return 'Hư';
        } elseif ($useStatus == $this->getDefaultLostUse()) {
            return 'Mất';
        } else {
            return null;
        }
    }

    # kiem tra trang thai do nghe binh thương
    public function checkNormalUseByStatus($useStatus)
    {
        return ($useStatus == $this->getDefaultHasUse()) ? true : false;
    }

    # kiem tra trang thai do nghe bị hu
    public function checkBrokenUseByStatus($useStatus)
    {
        return ($useStatus == $this->getDefaultNotUse()) ? true : false;
    }

    # kiem tra trang thai do nghe binh thương
    public function checkLostUseByStatus($useStatus)
    {
        return ($useStatus == $this->getDefaultLostUse()) ? true : false;
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
        $hFunction = new \Hfunction();
        $result = QcCompanyStore::orderBy('store_id', 'DESC')->first();
        return ($hFunction->checkEmpty($result)) ? 0 : $result->store_id;
    }

    # lay src hinh anh cuoi cung cua do nghe
    public function lastPathSmallImage($storeId)
    {
        //$companyStore = $this->getInfo($storeId);
        //$dataToolAllocationDetail = $companyStore->toolAllocationDetailLastInfo();
    }
}
