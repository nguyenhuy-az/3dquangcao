<?php

namespace App\Models\Ad3d\CompanyStoreCheck;

use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\CompanyStore\QcCompanyStore;
use App\Models\Ad3d\CompanyStoreCheckReport\QcCompanyStoreCheckReport;
use Illuminate\Database\Eloquent\Model;

class QcCompanyStoreCheck extends Model
{
    protected $table = 'qc_company_store_check';
    protected $fillable = ['check_id', 'confirmStatus', 'confirmDate', 'confirmAutoStatus', 'receiveStatus', 'receiveDate', 'created_at', 'work_id'];
    protected $primaryKey = 'check_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- thêm ----------
    public function insert($workId)
    {
        $hFunction = new \Hfunction();
        $modelCompanyStoreCheck = new QcCompanyStoreCheck();
        $modelCompanyStoreCheck->receiveDate = $hFunction->carbonNow();
        $modelCompanyStoreCheck->work_id = $workId;
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
        return (empty($checkId)) ? $this->checkId() : $checkId;
    }

    /*public function deleteAllocation($checkId = null)
    {
        return QcCompanyStoreCheck::where('check_id', $this->checkIdNull($checkId))->delete();
    }*/
    public function confirmCheck($checkId, $confirmAutoStatus = 0)
    {
        $hFunction = new \Hfunction();
        return QcCompanyStoreCheck::where('check_id', $checkId)->update(
            [
                'confirmStatus' => 1,
                'confirmDate' => $hFunction->carbonNow(),
                'confirmAutoStatus' => $confirmAutoStatus
            ]);
    }

    # he thong xac nhan tu dong khi NV khong xac nhan
    public function autoConfirm($checkId)
    {
        $hFunction = new \Hfunction();
        $modelCompanyStore = new QcCompanyStore();
        $modelCompanyStoreCheckReport = new QcCompanyStoreCheckReport();
        $dataCompanyStoreCheck = $this->getInfo($checkId);
        $confirmAutoStatus = 1; // he thong tu xac nhan
        if ($this->confirmCheck($checkId, $confirmAutoStatus)) {
            $dataCompanyStaffWork = $dataCompanyStoreCheck->companyStaffWork;
            #do nghe dung chung cua he thong can kiem tra
            $dataCompanyStore = $modelCompanyStore->getPublicToolToCheckOfCompany($dataCompanyStaffWork->companyId());
            if ($hFunction->checkCount($dataCompanyStore)) {
                foreach ($dataCompanyStore as $companyStore) {
                    $storeId = $companyStore->storeId();
                    $useStatus = $companyStore->useStatus();
                    # neu chua co bao cao
                    if (!$modelCompanyStoreCheckReport->checkExistReportOfCompanyCheck($storeId, $checkId)) {
                        $confirmStatus = 1;
                        $confirmNote = 'Xác nhận tự động';
                        $confirmDate = $hFunction->carbonNow();
                        # them bao cao
                        $modelCompanyStoreCheckReport->insert($useStatus, $storeId, $checkId, $confirmStatus, $confirmNote, $confirmDate);
                    }
                }
            }
        }

    }

    #  lam moi vong kiem tra
    public function refreshCheckAround()
    {
        return QcCompanyStoreCheck::where('receiveStatus', 1)->update(['receiveStatus' => 0]);
    }
    //========== ========= ========= RELATION ========== ========= ==========
    //---------- thong tin bao cao kiem tra do nghe dung chung -----------
    public function companyStoreCheckReport()
    {
        return $this->hasMany('App\Models\Ad3d\CompanyStoreCheckReport\QcCompanyStoreCheckReport', 'check_id', 'check_id');
    }

    # lay thong tin bao cao
    public function infoCompanyStoreCheckReport($checkId = null)
    {
        $modelCompanyStoreCheckReport = new QcCompanyStoreCheckReport();
        return $modelCompanyStoreCheckReport->infoOfCompanyStoreCheck($this->checkIdNull($checkId));
    }

    //---------- nhan vien tra -----------
    public function companyStaffWork()
    {
        return $this->belongsTo('App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork', 'work_id', 'work_id');
    }

    # thong tin kiem tra trong vong dang nhan
    public function infoReceiveStatusOfWork($workId)
    {
        return QcCompanyStoreCheck::where('work_id', $workId)->where('receiveStatus', 1)->first();
    }

    # thong tin kiem tra trong vong dang nhan
    public function lastInfoOfWork($workId)
    {
        return QcCompanyStoreCheck::where('work_id', $workId)->orderBy('check_id', 'DESC')->first();
    }

    # lay thong tin kiem tra chu co xac nhan kiem tra sau cung cua 1 cong ty
    public function lastInfoUnConfirmOfWork($companyId)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        return QcCompanyStoreCheck::where('confirmStatus', 0)->whereIn('work_id', $modelCompanyStaffWork->listStaffIdOfListCompanyId([$companyId]))->orderBy('check_id', 'DESC')->first();
    }


    # kiem tra da phan cong kiem tra trong ngay hay chưa
    public function checkExistDateOfCompany($companyId, $checkDate)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        return QcCompanyStoreCheck::whereIn('work_id', $modelCompanyStaffWork->listStaffIdOfListCompanyId([$companyId]))->where('receiveDate', 'like', "%$checkDate%")->exists();
    }

    # kiem tra thong tin 1 nv co da duoc phan cong trong vong kiem tra chưa
    public function checkExistWorkReceived($workId)
    {
        return QcCompanyStoreCheck::where('work_id', $workId)->where('receiveStatus', 1)->exists();
    }

    # kiem tra ton tai den lich ma chua xac nhan cua 1 NV - trong vong chon
    public function checkExistUnConfirmInRoundOfWork($workId)
    {
        return QcCompanyStoreCheck::where('work_id', $workId)->where('confirmStatus', 0)->where('receiveStatus', 1)->exists();
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

    public function confirmAutoStatus($checkId)
    {
        return $this->pluck('confirmAutoStatus', $checkId);
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


    public function workId($checkId = null)
    {
        return $this->pluck('work_id', $checkId);
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
