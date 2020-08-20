<?php

namespace App\Models\Ad3d\ToolPackage;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\ToolPackageAllocation\QcToolPackageAllocation;
use Illuminate\Database\Eloquent\Model;

class QcToolPackage extends Model
{
    protected $table = 'qc_tool_package';
    protected $fillable = ['package_id', 'name', 'action', 'created_at'];
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
        $companyNameCode = $modelCompany->nameCode($companyId);
        $amountInfo = $this->amountOfCompany($companyId) + 1;
        $modelToolPackage->name = $companyNameCode . "_" . $amountInfo;
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

    #----------- ban giao cho nhan vien ------------
    public function toolPackageAllocation()
    {
        return $this->hasMany('App\Models\Ad3d\ToolPackageAllocation\QcToolPackageAllocation', 'package_id', 'package_id');
    }

    # giao 1 bo do nghe cho 1 nhan vien
    public function allocationForCompanyStaffWork($workId)
    {
        $hFunction = new \Hfunction();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        # lay 1 danh sach do nghe dang hoat dong ma chu duoc cap phat
        $dataToolPackage = $this->oneInfoNotAllocated();
        if ($hFunction->checkCount($dataToolPackage)) { # co bo do nghe

        } else {
            # tao bo do nghe moi
            $companyId = $modelCompanyStaffWork->companyId($workId);
            if ($this->insert($companyId)) {
                $newPackageId = $this->insertGetId();
            }

        }

    }

    # lay ngau nhien 1 bo do nghe chua dc cap phat
    public function oneInfoNotAllocated()
    {
        $modelToolPackageAllocation = new QcToolPackageAllocation();
        $listPackageId = $modelToolPackageAllocation->listPackageIdIsActive();
        return QcToolPackage::whereNotIn('package_id', $listPackageId)->first();
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
