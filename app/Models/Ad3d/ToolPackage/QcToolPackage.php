<?php

namespace App\Models\Ad3d\ToolPackage;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\CompanyStore\QcCompanyStore;
use App\Models\Ad3d\ToolPackageAllocation\QcToolPackageAllocation;
use App\Models\Ad3d\ToolPackageAllocationDetail\QcToolPackageAllocationDetail;
use Illuminate\Database\Eloquent\Model;

class QcToolPackage extends Model
{
    protected $table = 'qc_tool_package';
    protected $fillable = ['package_id', 'name', 'action', 'company_id', 'created_at'];
    protected $primaryKey = 'package_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== INSERT && UPDATE ========== ========== ==========

    #---------- Insert ----------
    public function insert($companyId)
    {
        $hFunction = new \Hfunction();
        $modelCompany = new QcCompany();
        $modelToolPackage = new QcToolPackage();
        $companyId = (is_int($companyId)) ? $companyId : $companyId[0];
        $companyNameCode = $modelCompany->nameCode($companyId)[0];
        $amountInfo = $this->amountOfCompany($companyId) + 1;
        $modelToolPackage->name = $companyNameCode . "_" . $amountInfo;
        $modelToolPackage->company_id = $companyId;
        $modelToolPackage->created_at = $hFunction->createdAt();
        if ($modelToolPackage->save()) {
            $this->lastId = $modelToolPackage->package_id;
            return true;
        } else {
            return false;
        }
    }

    # get new id
    public function insertGetId()
    {
        return $this->lastId;
    }

    public function checkIdNull($id)
    {
        return (empty($id)) ? $this->packageId() : $id;
    }

    #----------- update ----------
    public function updateInfo($packageId, $name)
    {
        return QcToolPackage::where('package_id', $packageId)->update([
            'name' => $name
        ]);
    }

    # delete
    public function deleteInfo($packageId = null)
    {
        return QcToolPackage::where('package_id', $packageId)->delete();
    }

    #========== ========== ========== RELATION ========== ========== ==========
    #----------- do nghe trong cong ty ------------
    public function companyStore()
    {
        return $this->hasMany('App\Models\Ad3d\CompanyStore\QcCompanyStore', 'store_id', 'store_id');
    }

    # lay danh sach do nghe trong tui dang dung binh thuong
    public function companyStoreGetInfoIsActive($packageId = null)
    {
        $modelCompanyStore = new QcCompanyStore();
        return $modelCompanyStore->infoIsActiveOfToolPackage($this->checkIdNull($packageId));
    }

    # lay danh sach do nghe trong tui dang dung binh thuong theo 1 loai cong cu
    public function companyStoreGetInfoIsActiveOfTool($packageId, $toolId)
    {
        $modelCompanyStore = new QcCompanyStore();
        return $modelCompanyStore->infoIsActiveOfPackageAndTool($packageId, $toolId);
    }

    #----------- cong ty ------------
    public function company()
    {
        return $this->belongsTo('App\Models\Ad3d\Company\QcCompany', 'company_id', 'company_id');
    }

    // tong bo do nghe cua 1 cty
    public function amountOfCompany($companyId)
    {
        return QcToolPackage::where('company_id', $companyId)->count();
    }

    // danh sach tui do nghe cua 1 cty
    public function getInfoOfCompany($companyId)
    {
        return QcToolPackage::where('company_id', $companyId)->get();
    }

    #----------- ban giao cho nhan vien ------------
    public function toolPackageAllocation()
    {
        return $this->hasMany('App\Models\Ad3d\ToolPackageAllocation\QcToolPackageAllocation', 'package_id', 'package_id');
    }

    # giao 1 bo do nghe cho 1 nhan vien - chon 1 bo do nghe ngau nhien chua duoc phat
    public function allocationForCompanyStaffWork($workId)
    {
        $hFunction = new \Hfunction();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelToolPackageAllocation = new QcToolPackageAllocation();
        $modelToolPackageAllocationDetail = new QcToolPackageAllocationDetail();
        # lay 1 tui do nghe dang hoat dong ma chua duoc cap phat
        $dataToolPackage = $this->oneInfoNotAllocatedOfCompany($modelCompanyStaffWork->companyId($workId));
        # co tui do nghe
        if ($hFunction->checkCount($dataToolPackage)) {
            # lay danh do nghe trong tui - dang hoat dong
            $dataCompanyStore = $dataToolPackage->companyStoreGetInfoIsActive();
            # phat cho nhan vien dang lam viec
            if ($modelToolPackageAllocation->insert(null, $workId, $dataToolPackage->packageId())) {
                $newAllocationId = $modelToolPackageAllocation->insertGetId();
                foreach ($dataCompanyStore as $companyStore) {
                    $storeId = $companyStore->storeId();
                    # chi tiet giao do nghe trong tui
                    $modelToolPackageAllocationDetail->insert($newAllocationId, $storeId);
                }
            }
        } else {
            # tao bo do nghe moi
            $companyId = $modelCompanyStaffWork->companyId($workId);
            if ($this->insert($companyId)) {
                $newPackageId = $this->insertGetId();
                # phat tui do nghe cho NV
                $modelToolPackageAllocation->insert(null, $workId, $newPackageId);
            }

        }

    }

    # lay ngau nhien 1 bo do nghe chua dc cap phat cua cong ty
    public function oneInfoNotAllocatedOfCompany($companyId)
    {
        $modelToolPackageAllocation = new QcToolPackageAllocation();
        $listPackageId = $modelToolPackageAllocation->listPackageIdIsActive();
        return QcToolPackage::where('company_id', $companyId)->whereNotIn('package_id', $listPackageId)->first();
    }

    # thong tin dang ban giao cua tui do nghe
    public function toolPackageAllocationIsActive($packageId = null)
    {
        $modelToolPackageAllocation = new QcToolPackageAllocation();
        return $modelToolPackageAllocation->infoIsActiveOfPackage($this->checkIdNull($packageId));
    }

    #============ =========== ============ GET INFO ============= =========== ==========
    public function getInfo($packageId = '', $field = '')
    {
        if (empty($packageId)) {
            return QcToolPackage::get();
        } else {
            $result = QcToolPackage::where('package_id', $packageId)->first();
            if (empty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }

    # create option
    public function getOption($selected = '')
    {
        $hFunction = new \Hfunction();
        $result = QcToolPackage::select('package_id as optionKey', 'name as optionValue')->get()->toArray();
        return $hFunction->option($result, $selected);
    }

    public function pluck($column, $objectId = null)
    {
        if (empty($objectId)) {
            return $this->$column;
        } else {
            return QcToolPackage::where('package_id', $objectId)->pluck($column);
        }
    }

    #----------- lay thong tin -------------
    public function packageId()
    {
        return $this->package_id;
    }

    public function name($packageId = null)
    {
        return $this->pluck('name', $packageId);
    }

    public function action($packageId = null)
    {
        return $this->pluck('action', $packageId);
    }

    public function companyId($packageId = null)
    {
        return $this->pluck('company_id', $packageId);
    }


    public function createdAt($packageId = null)
    {
        return $this->pluck('created_at', $packageId);
    }

    # total record
    public function totalRecords()
    {
        return QcToolPackage::count();
    }

    #============ =========== ============ CHECK INFO ============= =========== ==========
    # exist name (add new)
    public function existName($name)
    {
        $result = QcToolPackage::where('name', $name)->count();
        return ($result > 0) ? true : false;
    }

    public function existEditName($packageId, $name)
    {
        $result = QcToolPackage::where('name', $name)->where('package_id', '<>', $packageId)->count();
        return ($result > 0) ? true : false;
    }
}
