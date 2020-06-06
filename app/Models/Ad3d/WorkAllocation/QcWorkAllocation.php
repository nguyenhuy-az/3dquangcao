<?php

namespace App\Models\Ad3d\WorkAllocation;

use App\Models\Ad3d\Product\QcProduct;
use App\Models\Ad3d\StaffNotify\QcStaffNotify;
use App\Models\Ad3d\WorkAllocationFinish\QcWorkAllocationFinish;
use App\Models\Ad3d\WorkAllocationReport\QcWorkAllocationReport;
use Illuminate\Database\Eloquent\Model;

class QcWorkAllocation extends Model
{
    protected $table = 'qc_work_allocation';
    protected $fillable = ['allocation_id', 'allocationDate', 'receiveStatus', 'receiveDeadline', 'confirmStatus', 'confirmDate', 'noted', 'role', 'action', 'product_id', 'allocationStaff_id', 'receiveStaff_id', 'created_at'];
    protected $primaryKey = 'allocation_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- them moi ----------
    public function insert($allocationDate, $receiveStatus, $receiveDeadline, $confirmStatus, $confirmDate, $noted, $productId, $allocationStaffId, $receiveStaffId, $role = 0)
    {
        $hFunction = new \Hfunction();
        $modelWorkAllocation = new QcWorkAllocation();
        $modelWorkAllocation->allocationDate = $allocationDate;
        $modelWorkAllocation->receiveStatus = $receiveStatus;
        $modelWorkAllocation->receiveDeadline = $receiveDeadline;
        $modelWorkAllocation->confirmStatus = $confirmStatus;
        $modelWorkAllocation->confirmDate = $confirmDate;
        $modelWorkAllocation->noted = $noted;
        $modelWorkAllocation->role = $role;
        $modelWorkAllocation->product_id = $productId;
        $modelWorkAllocation->allocationStaff_id = $allocationStaffId;
        $modelWorkAllocation->receiveStaff_id = $receiveStaffId;
        $modelWorkAllocation->created_at = $hFunction->createdAt();
        if ($modelWorkAllocation->save()) {
            $this->lastId = $modelWorkAllocation->allocation_id;
            return true;
        } else {
            return false;
        }
    }

    // lay id moi them
    public function insertGetId()
    {
        return $this->lastId;
    }

    public function checkIdNull($allocationId = null)
    {
        return (empty($allocationId)) ? $this->allocationId() : $allocationId;
    }

    // ket thuc cong viec
    public function confirmFinish($allocationId, $reportDate, $finishStatus, $finishReason = 0, $finishNoted = null)
    {
        $hFunction = new \Hfunction();
        $modelWorkAllocationFinish = new QcWorkAllocationFinish();
        $allocationId = (empty($allocationId)) ? $this->allocationId() : $allocationId;
        if (QcWorkAllocation::where('allocation_id', $allocationId)->update(['action' => 0])) {
            $receiveDate = $this->receiveDeadline($allocationId)[0];
            if (date('Y-m-d H:i', strtotime($reportDate)) > date('Y-m-d H:i', strtotime($receiveDate))) {
                # hoan thanh tre
                $finishLevel = 2;
            } elseif (date('Y-m-d H:i', strtotime($reportDate)) == date('Y-m-d H:i', strtotime($receiveDate))) {
                $finishLevel = 0;
            } else {
                $finishLevel = 1;
            }
            $modelWorkAllocationFinish->insert($reportDate, $finishStatus, $finishLevel, $finishReason, $finishNoted, $allocationId);
        }
    }

    # xac nhan hoan thanh khi bao hoan thanh san pham
    public function confirmFinishFromFinishProduct($productId)
    {
        $hFunction = new \Hfunction();
        $dataWorkAllocation = $this->infoActivityOfProduct($productId);
        if ($hFunction->checkCount($dataWorkAllocation)) {
            foreach ($dataWorkAllocation as $workAllocation) {
                $this->confirmFinish($workAllocation->allocationId(), $hFunction->carbonNow(), 1, 0, 'Kết thúc từ báo hoàn thành sản phẩm');
            }
        }
    }

    # xac nhan hoan thanh khi huy san pham
    public function confirmFinishFromCancelProduct($productId)
    {
        $hFunction = new \Hfunction();
        $dataWorkAllocation = $this->infoActivityOfProduct($productId);
        if ($hFunction->checkCount($dataWorkAllocation)) {
            foreach ($dataWorkAllocation as $workAllocation) {
                $this->confirmFinish($workAllocation->allocationId(), $hFunction->carbonNow(), 1, 0, 'Kết thúc từ báo hủy sản phẩm');
            }
        }
    }

    # huy ban giao
    public function cancelAllocation($allocationId)
    {
        $hFunction = new \Hfunction();
        return QcWorkAllocation::where('allocation_id', $allocationId)->update(['action' => 0, 'cancelStatus' => 1, 'cancelDate' => $hFunction->carbonNow()]);
    }
    //========== ========= ========= RELATION ========== ========= ==========
    //---------- thong bao ban giao san pham moi -----------
    public function staffNotify()
    {
        return $this->hasMany('App\Models\Ad3d\StaffNotify\QcStaffNotify', 'workAllocation_id', 'allocation_id');
    }

    public function checkViewedNewWorkAllocation($workAllocationId, $staffId)
    {
        $modelStaffNotify = new QcStaffNotify();
        return $modelStaffNotify->checkViewedWorkAllocationOfStaff($staffId, $workAllocationId);
    }

    //---------- nhan vien ban giao -----------
    public function allocationStaff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'allocationStaff_id', 'staff_id');
    }

    //---------- nhan vien nhan -----------
    public function receiveStaff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'receiveStaff_id', 'staff_id');
    }

    # lay tat ca thong tin cua nguoi nhan
    public function infoOfStaffReceive($staffId, $dateFilter = null)
    {
        if (empty($dateFilter)) {
            return QcWorkAllocation::where('receiveStaff_id', $staffId)->orderBy('allocationDate', 'DESC')->get();
        } else {
            return QcWorkAllocation::where('receiveStaff_id', $staffId)->where('allocationDate', 'like', "%$dateFilter%")->orderBy('allocationDate', 'DESC')->get();
        }
    }

    public function selectInfoOfStaffReceive($staffId, $finishStatus = 100, $dateFilter = null)
    {
        $modelWorkAllocationFinish = new QcWorkAllocationFinish();

        if($finishStatus < 100){
            $allocationFinishId = $modelWorkAllocationFinish->listAllocationId();
            if($finishStatus == 0){ #chua hoan thanh
                if (empty($dateFilter)) {
                    return QcWorkAllocation::whereNotIn('allocation_id', $allocationFinishId)->where('receiveStaff_id', $staffId)->orderBy('allocationDate', 'DESC')->select('*');
                } else {
                    return QcWorkAllocation::whereNotIn('allocation_id', $allocationFinishId)->where('receiveStaff_id', $staffId)->where('allocationDate', 'like', "%$dateFilter%")->orderBy('allocationDate', 'DESC')->select('*');
                }
            }elseif($finishStatus == 1){ # da hoan thanh
                if (empty($dateFilter)) {
                    return QcWorkAllocation::whereIn('allocation_id', $allocationFinishId)->where('receiveStaff_id', $staffId)->orderBy('allocationDate', 'DESC')->select('*');
                } else {
                    return QcWorkAllocation::whereIn('allocation_id', $allocationFinishId)->where('receiveStaff_id', $staffId)->where('allocationDate', 'like', "%$dateFilter%")->orderBy('allocationDate', 'DESC')->select('*');
                }
            }
        }else{
            if (empty($dateFilter)) {
                return QcWorkAllocation::where('receiveStaff_id', $staffId)->orderBy('allocationDate', 'DESC')->select('*');
            } else {
                return QcWorkAllocation::where('receiveStaff_id', $staffId)->where('allocationDate', 'like', "%$dateFilter%")->orderBy('allocationDate', 'DESC')->select('*');
            }
        }

    }

    # kiem tra nhan vien da duoc phan cong san pham
    public function checkStaffReceiveProduct($staffId, $productId)
    {
        return QcWorkAllocation::where('receiveStaff_id', $staffId)->where('product_id', $productId)->exists();
    }

    # lay thong tin dang thi cong cua nhan vien
    public function infoActivityOfStaffReceive($staffId)
    {
        return QcWorkAllocation::where('receiveStaff_id', $staffId)->where('action', 1)->orderBy('allocationDate', 'DESC')->get();
    }

    # lay thong tin viec da ket thuc cua nhan vien cua nhan vien
    public function infoFinishOfStaffReceive($staffId)
    {
        return QcWorkAllocation::where('receiveStaff_id', $staffId)->where('action', 0)->orderBy('receiveDeadline', 'ASC')->get();
    }

    public function listProductIdActivityOfReceiveStaff($receiveStaffId)
    {
        return QcWorkAllocation::where('receiveStaff_id', $receiveStaffId)->where('action', 1)->orderBy('receiveDeadline', 'ASC')->pluck('product_id');
    }

    public function listIdOfReceiveStaff($receiveStaffId)
    {
        return QcWorkAllocation::whereIn('receiveStaff_id', $receiveStaffId)->orderBy('receiveDeadline', 'ASC')->pluck('allocation_id');
    }

    //---------- don hang -----------
    public function infoAllOfOrder($orderId)
    {  // thong tin phan cong tren san pham của dơn hang
        $modelProduct = new QcProduct();
        $listProductId = $modelProduct->listIdOfOrder($orderId);
        return QcWorkAllocation::whereIn('product_id', $listProductId)->orderBy('allocationDate', 'DESC')->get();
    }

    public function listIdOfOrder($orderId)
    {  // thong tin phan cong tren san pham của dơn hang
        $modelProduct = new QcProduct();
        $listProductId = $modelProduct->listIdOfOrder($orderId);
        return QcWorkAllocation::whereIn('product_id', $listProductId)->orderBy('allocationDate', 'DESC')->pluck('allocation_id');
    }

    //---------- san pham -----------
    public function product()
    {
        return $this->belongsTo('App\Models\Ad3d\Product\QcProduct', 'product_id', 'product_id');
    }

    # kiem tra sam pham co nguoi nhan chin
    public function existMaimRoleActivityOfProduct($productId)
    {
        return QcWorkAllocation::where('product_id', $productId)->where('role', 1)->exists();
    }

    # kiem tra sam pham dang duoc phan viec
    public function existInfoActivityOfProduct($productId)
    {
        return QcWorkAllocation::where('product_id', $productId)->exists();
    }

    public function infoActivityOfProduct($productId)
    {
        return QcWorkAllocation::where('product_id', $productId)->where('action', 1)->get();
    }

    public function infoOfProduct($productId, $orderBy = 'DESC')
    {
        return QcWorkAllocation::where('product_id', $productId)->orderBy('allocationDate', $orderBy)->get();
    }

    public function listReceiveStaffIdOfListProduct($listProductId)
    {
        return QcWorkAllocation::whereIn('product_id', $listProductId)->pluck('receiveStaff_id');
    }

    //---------- ket thuc cong viec -----------
    public function workAllocationFinish()
    {
        return $this->hasMany('App\Models\Ad3d\WorkAllocationFinish\QcWorkAllocationFinish', 'allocation_id', 'allocation_id');
    }

    public function workAllocationFinishInfo($allocationId = null)
    {
        $modelWorkAllocationFinish = new QcWorkAllocationFinish();
        return $modelWorkAllocationFinish->infoOfAllocation($this->checkIdNull($allocationId));
    }

    public function checkFinishAllocation($allocationId = null)
    {
        $modelWorkAllocationFinish = new QcWorkAllocationFinish();
        return $modelWorkAllocationFinish->checkFinishOfAllocation($this->checkIdNull($allocationId));
    }

    //---------- bao cao -----------
    public function workAllocationReport()
    {
        return $this->hasMany('App\Models\Ad3d\WorkAllocationReport\QcWorkAllocationReport', 'allocation_id', 'allocation_id');
    }

    public function workAllocationReportInfo($allocationId = null, $take = null)
    {
        $modelWorkAllocationReport = New QcWorkAllocationReport();
        return $modelWorkAllocationReport->infoOfWorkAllocation((empty($allocationId)) ? $this->allocationId() : $allocationId, $take);
    }

    //========= ========== ========== lay thong tin ========== ========== ==========
    public function selectInfoOfReceiveListStaff($listStaffId, $finishStatus = 2, $dateFilter = null) # tat ca
    {
        if ($finishStatus == 2) {
            if (empty($dateFilter)) {
                return QcWorkAllocation::whereIn('receiveStaff_id', $listStaffId)->orderBy('receiveDeadline', 'DESC')->select('*');
            } else {
                return QcWorkAllocation::whereIn('receiveStaff_id', $listStaffId)->where('receiveDeadline', 'like', "%$dateFilter%")->orderBy('receiveDeadline', 'DESC')->select('*');
            }
        } else {
            if (empty($dateFilter)) {
                return QcWorkAllocation::whereIn('receiveStaff_id', $listStaffId)->where('action', $finishStatus)->orderBy('receiveDeadline', 'DESC')->select('*');
            } else {
                return QcWorkAllocation::whereIn('receiveStaff_id', $listStaffId)->where('action', $finishStatus)->where('receiveDeadline', 'like', "%$dateFilter%")->orderBy('receiveDeadline', 'DESC')->select('*');
            }
        }
    }

    public function getInfo($allocationId = '', $field = '')
    {
        if (empty($allocationId)) {
            return QcWorkAllocation::get();
        } else {
            $result = QcWorkAllocation::where('allocation_id', $allocationId)->first();
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
            return QcWorkAllocation::where('allocation_id', $objectId)->pluck($column);
        }
    }

    public function allocationId()
    {
        return $this->allocation_id;
    }

    public function allocationDate($allocationId = null)
    {
        return $this->pluck('allocationDate', $allocationId);
    }

    public function receiveStatus($allocationId = null)
    {
        return $this->pluck('receiveStatus', $allocationId);
    }


    public function receiveDeadline($allocationId = null)
    {

        return $this->pluck('receiveDeadline', $allocationId);
    }

    public function confirmStatus($allocationId = null)
    {

        return $this->pluck('confirmStatus', $allocationId);
    }


    public function confirmDate($allocationId = null)
    {

        return $this->pluck('confirmDate', $allocationId);
    }


    public function noted($allocationId = null)
    {

        return $this->pluck('noted', $allocationId);
    }

    public function cancelStatus($allocationId = null)
    {
        return $this->pluck('cancelStatus', $allocationId);
    }

    public function cancelDate($allocationId = null)
    {

        return $this->pluck('cancelDate', $allocationId);
    }


    public function role($allocationId = null)
    {

        return $this->pluck('role', $allocationId);
    }

    public function productId($allocationId = null)
    {
        return $this->pluck('product_id', $allocationId);
    }

    public function allocationStaffId($allocationId = null)
    {
        return $this->pluck('allocationStaff_id', $allocationId);
    }

    public function receiveStaffId($allocationId = null)
    {
        return $this->pluck('receiveStaff_id', $allocationId);
    }

    public function action($allocationId = null)
    {
        return $this->pluck('action', $allocationId);
    }

    public function createdAt($allocationId = null)
    {
        return $this->pluck('created_at', $allocationId);
    }

// tong mau tin
    public function totalRecords()
    {
        return QcWorkAllocation::count();
    }

// id cuoi
    public function lastId()
    {
        $result = QcWorkAllocation::orderBy('allocation_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->allocation_id;
    }

    // kiem tra thong tin
    #con hoat dong
    public function checkActivity($allocationId = null)
    {
        return ($this->action($allocationId) == 1) ? true : false;
    }

    #kiem tra huy phan viec is_int
    public function checkCancel($allocationId = null)
    {
        $result = $this->cancelStatus($allocationId);
        $result = is_int($result)?$result:$result[0];
        return ($result == 1) ? true : false;
    }

    # xac nhan phan cong
    public function checkConfirm($allocationId = null)
    {
        $result = $this->confirmStatus($allocationId);
        $result = is_int($result)?$result:$result[0];
        return ($result == 1) ? true : false;
    }

    # kiem tra tho lam chinh
    public function checkRoleMain($allocationId = null)
    {
        $result = $this->role($allocationId);
        $result = is_int($result)?$result:$result[0];
        return ($result == 1) ? true : false;
    }

    #kiem tra phan viec bi tre
    public function checkLate($allocationId)
    {
        $hFunction = new \Hfunction();
        $checkDate = $hFunction->carbonNow();
        $lateStatus = false;
        $dataWorkAllocationFinish = $this->workAllocationFinishInfo($allocationId);
        if ($hFunction->checkCount($dataWorkAllocationFinish)) { # dơn hang da ke thuc
            $lateStatus = $dataWorkAllocationFinish->checkFinishLate();
        } else { # don hang chưa ket thuc
            if (!$this->checkCancel($allocationId)) { # chua huy
                $receiveDeadline = $this->receiveDeadline($allocationId); // lay ngay den han
                if ($checkDate > $receiveDeadline[0])$lateStatus = true;
            }
        }
        return $lateStatus;
    }
}
