<?php

namespace App\Models\Ad3d\ToolPackageAllocationDetail;

use App\Models\Ad3d\CompanyStore\QcCompanyStore;
use App\Models\Ad3d\ToolPackageAllocation\QcToolPackageAllocation;
use App\Models\Ad3d\ToolPackageAllocationReturn\QcToolPackageAllocationReturn;
use Illuminate\Database\Eloquent\Model;

class QcToolPackageAllocationDetail extends Model
{
    protected $table = 'qc_tool_package_allocation_detail';
    protected $fillable = ['detail_id', 'image', 'newStatus', 'created_at', 'action', 'store_id', 'allocation_id'];
    protected $primaryKey = 'detail_id';
    public $timestamps = false;

    private $lastId;

    # mac dinh hinh anh
    public function getDefaultImage()
    {
        return null;
    }

    # mac dinh trang thai moi
    public function getDefaultHasNew()
    {
        return 1;
    }

    # mac dinh trang thai khong moi
    public function getDefaultNotNew()
    {
        return 0;
    }

    # mac dinh trang thai dang hoat dong
    public function getDefaultHasAction()
    {
        return 1;
    }

    # mac dinh trang thai khong hoat dong
    public function getDefaultNotAction()
    {
        return 0;
    }

    # mac dinh ma ban giao
    public function getDefaultAllocationId()
    {
        return null;
    }
    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- thêm ----------
    public function insert($allocationId, $storeId)
    {
        $hFunction = new \Hfunction();
        $modelCompanyStore = new QcCompanyStore();
        $modelToolPackageAllocationDetail = new QcToolPackageAllocationDetail();
        $dataCompanyStore = $modelCompanyStore->getInfo($storeId);
        # lay thong tin giao sau cung cua do nghe
        $dataLastToolAllocationDetail = $dataCompanyStore->toolAllocationDetailLastInfo();
        $allocationImage = null;
        # de nghe da tung duoc giao
        if ($hFunction->checkCount($dataLastToolAllocationDetail)) {
            $newStatus = $this->getDefaultNotNew();
            # lay thong tin tra sau cung cua lan giao
            $dataToolReturn = $dataLastToolAllocationDetail->lastInfoOfToolReturn();
            $returnImage = $dataToolReturn->image();
            # copy anh tra cua do nghe
            if (copy($dataToolReturn->pathSmallImage($returnImage), $this->rootPathSmallImage() . '/' . $returnImage)) {
                if (copy($dataToolReturn->pathFullImage($returnImage), $this->rootPathFullImage() . '/' . $returnImage)) {
                    $allocationImage = $returnImage;
                }
            }
        } else {
            $newStatus = $this->getDefaultHasNew();
            $dataImport = $dataCompanyStore->import;
            $dataImportImage = $dataImport->getOneImportImage();
            $importImageName = $dataImportImage->name();
            # copy anh nhap kho cua do nghe
            if (copy($dataImportImage->pathSmallImage($importImageName), $this->rootPathSmallImage() . '/' . $importImageName)) {
                if (copy($dataImportImage->pathFullImage($importImageName), $this->rootPathFullImage() . '/' . $importImageName)) {
                    $allocationImage = $importImageName;
                }
            }
        }

        $modelToolPackageAllocationDetail->image = $allocationImage;
        $modelToolPackageAllocationDetail->newStatus = $newStatus;
        $modelToolPackageAllocationDetail->store_id = $storeId;
        $modelToolPackageAllocationDetail->allocation_id = $allocationId;
        $modelToolPackageAllocationDetail->created_at = $hFunction->createdAt();
        if ($modelToolPackageAllocationDetail->save()) {
            $this->lastId = $modelToolPackageAllocationDetail->detail_id;
            return true;
        } else {
            return false;
        }
    }

    public function insertGetId()
    {
        return $this->lastId;
    }

    public function checkNullId($id = null)
    {
        $hFunction = new \Hfunction();
        return ($hFunction->checkEmpty($id)) ? $this->detailId() : $id;
    }

    public function deleteDetail($detailId = null)
    {
        return QcToolPackageAllocationDetail::where('detail_id', $this->checkNullId($detailId))->delete();
    }

    public function disableDetail($detailId = null)
    {
        return QcToolPackageAllocationDetail::where('detail_id', $this->checkNullId($detailId))->update(['action' => $this->getDefaultNotAction()]);
    }

    # hinh anh
    public function rootPathFullImage()
    {
        return 'public/images/tool-package-allocation-detail/full';
    }

    public function rootPathSmallImage()
    {
        return 'public/images/tool-package-allocation-detail/small';
    }

    // get path image
    public function pathSmallImage($image)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($image)) {
            return $hFunction->getDefaultNull();
        } else {
            return asset($this->rootPathSmallImage() . '/' . $image);
        }
    }

    public function pathFullImage($image)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($image)) {
            return $hFunction->getDefaultNull();
        } else {
            return asset($this->rootPathFullImage() . '/' . $image);
        }
    }

    //========== ========= =========  CAC MOI QUAN HE ========== ========= ==========
    //Kho
    public function companyStore()
    {
        return $this->belongsTo('App\Models\Ad3d\CompanyStore\QcCompanyStore', 'store_id', 'store_id');
    }

    public function totalAmountOfStore($storeId)
    {
        return QcToolPackageAllocationDetail::where('store_id', $storeId)->count();
    }

    # danh sach ma kho cua cac lan ban giao
    public function listStoreIdOfListAllocation($listAllocationId)
    {
        return QcToolPackageAllocationDetail::whereIn('allocation_id', $listAllocationId)->groupBy('store_id')->pluck('store_id');
    }

    # lay thong tin giao sau cung cua dung cu trong kho
    public function lastInfoOfCompanyStore($storeId)
    {
        return QcToolPackageAllocationDetail::where('store_id', $storeId)->orderBy('detail_id', 'DESC')->first();
    }

    # lay ma ban giao sau cung cua dung cu trong kho
    public function lastIdOfCompanyStore($storeId)
    {
        $hFunction = new \Hfunction();
        $lastInfo = $this->lastInfoOfCompanyStore($storeId);
        return (!$hFunction->checkEmpty($lastInfo)) ? $lastInfo->detailId() : $hFunction->getDefaultNull();
    }

    # thong tin do nghe dang ban giao chua dươc tra cua 1 loai do nghe
    public function infoActivityOfToolAndCompany($toolId, $companyId)
    {
        $modelCompanyStore = new QcCompanyStore();
        $listStoreId = $modelCompanyStore->listIdOfToolAndCompany($toolId, $companyId);
        return QcToolPackageAllocationDetail::whereIn('store_id', $listStoreId)->where('action', $this->getDefaultHasAction())->get();
    }

    # danh sach ma do nghe trong kho dang duoc cap phat
    public function listStoreIdIsActivity()
    {
        return QcToolPackageAllocationDetail::where('action', $this->getDefaultHasAction())->pluck('store_id');
    }

    # thong tin dang phat cua do nghe
    public function infoActivityOfStore($storeId)
    {
        return QcToolPackageAllocationDetail::where('store_id', $storeId)->where('action', $this->getDefaultHasAction())->first();
    }

    //---------- tra do nghe -----------
    public function toolPackageAllocationReturn()
    {
        return $this->hasMany('App\Models\Ad3d\ToolPackageAllocationReturn\QcToolPackageAllocationReturn', 'detail_id', 'detail_id');
    }

    # ly thong tin bao tra cuoi sau cung
    public function lastInfoOfToolReturn($detailId = null)
    {
        $modelToolPackageAllocationReturn = new QcToolPackageAllocationReturn();
        return $modelToolPackageAllocationReturn->lastInfoOfToolAllocationDetail($this->checkNullId($detailId));
    }

    //---------- phiếu bàn giao -----------
    public function toolPackageAllocation()
    {
        return $this->belongsTo('App\Models\Ad3d\ToolPackageAllocation\QcToolPackageAllocation', 'allocation_id', 'allocation_id');
    }

    # thong tin chua tra cua 1 tui do nghe da ban giao
    public function getInfoNotReturnOfAllocation($allocationId)
    {
        $modelToolPackageAllocationReturn = new QcToolPackageAllocationReturn();
        return QcToolPackageAllocationDetail::where('allocation_id', $allocationId)->whereNotIn('detail_id', $modelToolPackageAllocationReturn->getAllocationDetailListId())->get();
    }

    # tong so luong tat ca do nghe cua 1 lan giao
    public function totalAmountOfAllocation($allocationId)
    {
        return QcToolPackageAllocationDetail::where('allocation_id', $allocationId)->count();
    }

    # tong so luong tat ca do nghe cua 1 lan hoac nhieu lan giao
    public function totalAmountOfListAllocationId($listAllocationId)
    {
        return QcToolPackageAllocationDetail::whereIn('allocation_id', $listAllocationId)->count();
    }


    public function toolAllocationDetailOfAllocation($allocationId)
    {
        return QcToolPackageAllocationDetail::where('allocation_id', $allocationId)->get();
    }

    # thong tin do nghe cua 1 lan giao
    public function infoOfToolAllocation($allocationId)
    {
        return QcToolPackageAllocationDetail::where('allocation_id', $allocationId)->orderBy('detail_id', 'DESC')->get();
    }

    #thong tin cua tat ca cty
    public function infoOfListToolAllocation($listAllocationId)
    {
        return QcToolPackageAllocationDetail::whereIn('allocation_id', $listAllocationId)->orderBy('created_at', 'DESC')->get();
    }

    #thong tin cua 1 cty
    public function infoOfListToolAllocationAndCompany($listAllocationId, $companyId)
    {
        return QcToolPackageAllocationDetail::whereIn('allocation_id', $listAllocationId)->where('company_id', $companyId)->orderBy('created_at', 'DESC')->get();
    }

    # tong so luong 1 cong cu cua 1 lan hoac nhieu lan giao tai cac cty
    public function totalToolOfListAllocationId($listAllocationId, $toolId)
    {
        $modelCompanyStore = new QcCompanyStore();
        $listStoreId = $modelCompanyStore->listIdOfTool($toolId);
        return QcToolPackageAllocationDetail::whereIn('allocation_id', $listAllocationId)->whereIn('store_id', $listStoreId)->count();
    }

    # danh sanh ma kho da ban giao cho nhan vien
    public function listStoreIdOfWork($workId)
    {
        $modelToolPackageAllocation = new QcToolPackageAllocation();
        $listAllocationId = $modelToolPackageAllocation->listIdOfWork($workId);
        return QcToolPackageAllocationDetail::whereIn('allocation_id', $listAllocationId)->groupBy('store_id')->pluck('store_id');
    }

    # danh sanh ma chi tiet ban giao cua cac do nghe
    public function listIdOfListAllocationId($listAllocationId)
    {
        return QcToolPackageAllocationDetail::whereIn('allocation_id', $listAllocationId)->groupBy('detail_id')->pluck('detail_id');
    }

    # danh sach ma dung cu trong kho cua 1 bo do nghe duoc cap phat
    public function storeIdOfToolAllocation($allocationId)
    {
        return QcToolPackageAllocationDetail::where('allocation_id', $allocationId)->groupBy('store_id')->pluck('store_id');
    }

    # thong tin chi tiet dang hoat dong cua 1 lan ban giao
    public function infoActivityOfToolAllocation($allocationId)
    {
        return QcToolPackageAllocationDetail::where('allocation_id', $allocationId)->where('action', $this->getDefaultHasAction())->get();
    }

    # thong tin ban giao cua loai do nghe  trong bo do nghe duoc giao
    public function infoOfToolAllocationAndTool($allocationId, $toolId)
    {
        $modelCompanyStore = new QcCompanyStore();
        $listStoreId = $modelCompanyStore->listIdOfTool($toolId);
        return QcToolPackageAllocationDetail::where('allocation_id', $allocationId)->whereIn('store_id', $listStoreId)->get();
    }

    # thong tin ban giao cua loai do nghe  trong bo do nghe duoc giao, dang hoat hoat dong
    public function infoActivityOfToolAllocationAndTool($allocationId, $toolId)
    {
        $modelCompanyStore = new QcCompanyStore();
        $listStoreId = $modelCompanyStore->listIdOfTool($toolId);
        return QcToolPackageAllocationDetail::where('allocation_id', $allocationId)->whereIn('store_id', $listStoreId)->where('action', $this->getDefaultHasAction())->get();
    }

    # tong so luong 1 cong cu cua 1 lan hoac nhieu lan giao tai 1 cty
    /* public function totalToolOfListAllocationAndCompany($listAllocationId, $companyId, $toolId)
     {
         return 3000;//QcToolPackageAllocationDetail::whereIn('allocation_id', $listAllocationId)->where('company_id',$companyId)->where('tool_id', $toolId)->sum('amount');
     }*/


    # danh sanh ma dung cu da ban giao cho nhan vien
    /*public function listToolIdOfWork($workId)
    {
        $modelToolPackageAllocation = new QcToolPackageAllocation();
        $listAllocationId = $modelToolPackageAllocation->listIdOfWork($workId);
        return QcToolPackageAllocationDetail::whereIn('allocation_id', $listAllocationId)->groupBy('tool_id')->pluck('tool_id');
    }*/
    # tong so luong 1 cong cu cua 1 lan giao
    /*public function totalToolOfAllocation($allocationId, $toolId)
    {
        return 1000;// QcToolPackageAllocationDetail::where('allocation_id', $allocationId)->where('tool_id', $toolId)->sum('amount');
    }*/


    //========= ========== ========== lay thong tin cua nhan vien ========== ========== ==========
    # tong dung cu cua 1 NV khi lam viec o 1 cty
    public function totalToolOfWork($toolId, $workId)
    {
        $modelToolPackageAllocation = new QcToolPackageAllocation();
        return $this->totalToolOfListAllocationId($modelToolPackageAllocation->listIdOfWork($workId), $toolId);
    }

    # cua 1 cty
    /*public function totalToolOfStaffAndCompany($staffId, $companyId, $toolId)
    {
        $modelToolPackageAllocation = new QcToolPackageAllocation();
        $listAllocationId = $modelToolPackageAllocation->listIdOfReceiveStaff($staffId);
        return $this->totalToolOfListAllocationAndCompany($listAllocationId, $companyId, $toolId);
    }*/

    //========= ========== ========== lấy thông tin ========== ========== ==========
    public function getInfo($detailId = '', $field = '')
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($detailId)) {
            return QcToolPackageAllocationDetail::get();
        } else {
            $result = QcToolPackageAllocationDetail::where('detail_id', $detailId)->first();
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
            return QcToolPackageAllocationDetail::where('detail_id', $objectId)->pluck($column);
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

    public function action($detailId = null)
    {
        return $this->pluck('action', $detailId);
    }


    public function createdAt($detailId = null)
    {
        return $this->pluck('created_at', $detailId);
    }

    public function toolId($detailId = null)
    {
        return $this->pluck('tool_id', $detailId);
    }

    public function storeId($detailId = null)
    {
        return $this->pluck('store_id', $detailId);
    }

    public function allocationId($detailId = null)
    {
        return $this->pluck('allocation_id', $detailId);
    }

    // last id
    public function lastId()
    {
        $hFunction = new \Hfunction();
        $result = QcToolPackageAllocationDetail::orderBy('detail_id', 'DESC')->first();
        return ($hFunction->checkEmpty($result)) ? 0 : $result->detail_id;
    }

    # trang thai con hoat dong
    public function checkActivity($detailId = null)
    {
        return ($this->action($detailId) == $this->getDefaultHasAction()) ? true : false;
    }

    //kiểm tra thông tin
    public function checkNewStatus($detailId = null)
    {
        return ($this->newStatus($detailId) == $this->getDefaultHasNew()) ? true : false;
    }
}
