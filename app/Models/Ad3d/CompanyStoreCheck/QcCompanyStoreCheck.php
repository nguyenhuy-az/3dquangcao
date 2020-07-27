<?php

namespace App\Models\Ad3d\CompanyStoreCheck;

use App\Models\Ad3d\CompanyStoreCheckReport\QcCompanyStoreCheckReport;
use Illuminate\Database\Eloquent\Model;

class QcCompanyStoreCheck extends Model
{
    protected $table = 'qc_company_store_check';
    protected $fillable = ['check_id', 'confirmStatus', 'confirmDate', 'receiveStatus', 'receiveDate', 'created_at', 'staff_id'];
    protected $primaryKey = 'check_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- thêm ----------
    public function insert($staffId)
    {
        $hFunction = new \Hfunction();
        $modelCompanyStoreCheck = new QcCompanyStoreCheck();
        $modelCompanyStoreCheck->receiveDate = $hFunction->carbonNow();
        $modelCompanyStoreCheck->staff_id = $staffId;
        $modelCompanyStoreCheck->created_at = $hFunction->createdAt();
        if ($modelCompanyStoreCheck->save()) {
            $this->lastId = $modelCompanyStoreCheck->check_id;
            return true;
        } else {
            return false;
        }
    }

    public function insertGetId()
    {
        return $this->lastId;
    }

    public function checkIdNull($checkId)
    {
        return (empty($checkId)) ? $this->checkId(): $checkId;
    }

    /*public function deleteAllocation($checkId = null)
    {
        return QcCompanyStoreCheck::where('check_id', $this->checkIdNull($checkId))->delete();
    }*/
    public function confirmCheck($checkId)
    {
        $hFunction = new \Hfunction();
        return QcCompanyStoreCheck::where('check_id', $checkId)->update(
            [
                'confirmStatus' => 1,
                'confirmDate' => $hFunction->carbonNow()
            ]);
    }
    //========== ========= ========= RELATION ========== ========= ==========
    //---------- thong tin bao cao kiem tra do nghe dung chung -----------
    public function companyStoreCheckReport()
    {
        return $this->hasMany('App\Models\Ad3d\CompanyStoreCheckReport\QcCompanyStoreCheckReport', 'check_id', 'check_id');
    }

    # lay thong tin bao cao
    public function infoCompanyStoreCheckReport($checkId=null)
    {
        $modelCompanyStoreCheckReport = new QcCompanyStoreCheckReport();
        return $modelCompanyStoreCheckReport->infoOfCompanyStoreCheck($this->checkIdNull($checkId));
    }

    //---------- nhan vien tra -----------
    public function staff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staff_id', 'staff_id');
    }

    # thong tin kiem tra trong vong dang nhan
    public function infoReceiveStatusOfStaff($staffId)
    {
        return QcCompanyStoreCheck::where('staff_id', $staffId)->where('receiveStatus', 1)->first();
    }

    # kiem tra da phan cong kiem tra trong ngay hay chưa
    public function checkExistDate($checkDate)
    {
        return QcCompanyStoreCheck::where('receiveDate', 'like', "%$checkDate%")->exists();
    }

    # kiem tra thong tin 1 nv co da duoc phan cong trong vong kiem tra chưa
    public function checkExistStaffReceived($staffId)
    {
        return QcCompanyStoreCheck::where('staff_id', $staffId)->where('receiveStatus', 1)->exists();
    }
    //========= ========== ========== lấy thông tin ========== ========== ==========
    /* public function selectInfoOfListWorkAndDate($listStaffId, $dateFilter = null)
     {
         if (empty($dateFilter)) {
             return QcCompanyStoreCheck::whereIn('work_id', $listStaffId)->orderBy('allocationDate', 'DESC')->select('*');
         } else {
             return QcCompanyStoreCheck::whereIn('work_id', $listStaffId)->where('allocationDate', 'like', "%$dateFilter%")->orderBy('allocationDate', 'DESC')->select('*');
         }
     }*/

    public function getInfo($checkId = '', $field = '')
    {
        if (empty($checkId)) {
            return QcCompanyStoreCheck::get();
        } else {
            $result = QcCompanyStoreCheck::where('check_id', $checkId)->first();
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
            return QcCompanyStoreCheck::where('check_id', $objectId)->pluck($column);
        }
    }

    public function checkId()
    {
        return $this->check_id;
    }

    public function confirmStatus($checkId = null)
    {

        return $this->pluck('confirmStatus', $checkId);
    }

    public function confirmDate($checkId = null)
    {

        return $this->pluck('confirmDate', $checkId);
    }

    public function receiveStatus($checkId = null)
    {

        return $this->pluck('receiveStatus', $checkId);
    }

    public function receiveDate($checkId = null)
    {

        return $this->pluck('receiveDate', $checkId);
    }

    public function createdAt($checkId = null)
    {
        return $this->pluck('created_at', $checkId);
    }


    public function staffId($checkId = null)
    {
        return $this->pluck('staff_id', $checkId);
    }

    // last id
    public function lastId()
    {
        $result = QcCompanyStoreCheck::orderBy('check_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->check_id;
    }

    # kiem tra da xac nhan do nghe chua
    public function checkConfirmStatus($checkId = null)
    {
        return ($this->confirmStatus($checkId) == 1) ? true : false;
    }
}
