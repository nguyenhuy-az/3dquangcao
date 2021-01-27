<?php

namespace App\Models\Ad3d\WorkAllocation;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\MinusMoney\QcMinusMoney;
use App\Models\Ad3d\Product\QcProduct;
use App\Models\Ad3d\PunishContent\QcPunishContent;
use App\Models\Ad3d\StaffNotify\QcStaffNotify;
use App\Models\Ad3d\WorkAllocationFinish\QcWorkAllocationFinish;
use App\Models\Ad3d\WorkAllocationReport\QcWorkAllocationReport;
use Illuminate\Database\Eloquent\Model;

class QcWorkAllocation extends Model
{
    protected $table = 'qc_work_allocation';
    protected $fillable = ['allocation_id', 'allocationDate', 'constructionNumber', 'receiveStatus', 'receiveDeadline', 'confirmStatus', 'confirmDate', 'noted', 'role', 'lateStatus', 'cancelStatus', 'cancelDate', 'action', 'product_id', 'allocationStaff_id', 'receiveStaff_id', 'created_at', 'productRepair_id'];
    protected $primaryKey = 'allocation_id';
    public $timestamps = false;

    private $lastId;

    # mac dinh co nhan
    public function getDefaultHasReceiveStatus()
    {
        return 1;
    }

    # mac dinh khong nhan
    public function getDefaultNotReceiveStatus()
    {
        return 0;
    }

    # mac dinh co xac nhan
    public function getDefaultHasConfirmStatus()
    {
        return 1;
    }

    # mac dinh khong xac nhan
    public function getDefaultNotConfirmStatus()
    {
        return 0;
    }

    # mac dinh lam chinh
    public function getDefaultHasRole()
    {
        return 1;
    }

    # mac dinh lam phu
    public function getDefaultNotRole()
    {
        return 0;
    }

    # mac dinh co tre
    public function getDefaultHasLate()
    {
        return 1;
    }

    # mac dinh khong tre
    public function getDefaultNotLate()
    {
        return 0;
    }

    # mac dinh co huy
    public function getDefaultHasCancelStatus()
    {
        return 1;
    }

    # mac dinh khong huy
    public function getDefaultNotCancelStatus()
    {
        return 0;
    }

    # mac dinh co dang hoat dong
    public function getDefaultHasAction()
    {
        return 1;
    }

    # mac dinh khong hoat dong
    public function getDefaultNotAction()
    {
        return 0;
    }

    # mac dinh tat ca hoat dong
    public function getDefaultAllAction()
    {
        return 100;
    }

    # mac dinh thi cong la lan 1
    public function getDefaultFirstConstructionNumber()
    {
        return 1;
    }

    # mac dinh sua san pham
    public function getDefaultProductRepairId()
    {
        return null;
    }

    # mac dinh ghi chu
    public function getDefaultNoted()
    {
        return null;
    }

    # mac dinh nguoi phan cong
    public function getDefaultAllocationStaffId()
    {
        return null; # null - phan cong tu dong
    }

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- them moi ----------
    public function insert($allocationDate, $receiveStatus, $receiveDeadline, $confirmStatus, $confirmDate, $noted, $productId, $allocationStaffId, $receiveStaffId, $role = 0, $constructionNumber = 1, $productRepairId = null)
    {
        $hFunction = new \Hfunction();
        $modelWorkAllocation = new QcWorkAllocation();
        $modelWorkAllocation->allocationDate = $allocationDate;
        $modelWorkAllocation->constructionNumber = $constructionNumber;
        $modelWorkAllocation->receiveStatus = $receiveStatus;
        $modelWorkAllocation->receiveDeadline = $receiveDeadline;
        $modelWorkAllocation->confirmStatus = $confirmStatus;
        $modelWorkAllocation->confirmDate = $confirmDate;
        $modelWorkAllocation->noted = $noted;
        $modelWorkAllocation->role = $role;
        $modelWorkAllocation->product_id = $productId;
        $modelWorkAllocation->allocationStaff_id = $allocationStaffId;
        $modelWorkAllocation->receiveStaff_id = $receiveStaffId;
        $modelWorkAllocation->productRepair_id = $productRepairId;
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
        $hFunction = new \Hfunction();
        return ($hFunction->checkEmpty($allocationId)) ? $this->allocationId() : $allocationId;
    }

    // ket thuc cong viec
    public function confirmFinish($allocationId, $reportDate, $finishStatus, $finishReason = 0, $finishNoted = null)
    {
        $hFunction = new \Hfunction();
        $modelWorkAllocationFinish = new QcWorkAllocationFinish();
        $allocationId = $this->checkIdNull($allocationId);
        if (QcWorkAllocation::where('allocation_id', $allocationId)->update(['action' => $this->getDefaultNotAction()])) {
            $receiveDate = $this->receiveDeadline($allocationId);
            $receiveDate = $hFunction->formatDateToYMDHI($receiveDate);
            $reportDate = $hFunction->formatDateToYMDHI($reportDate);
            if ($reportDate > $receiveDate) {
                # hoan thanh tre
                $finishLevel = $modelWorkAllocationFinish->getDefaultLateFinishLevel();
            } elseif ($reportDate == $receiveDate) {
                $finishLevel = $modelWorkAllocationFinish->getDefaultHasFinishLevel();
            } else {
                $finishLevel = $modelWorkAllocationFinish->getDefaultBeforeFinishLevel();
            }
            $modelWorkAllocationFinish->insert($reportDate, $finishStatus, $finishLevel, $finishReason, $finishNoted, $allocationId);
        }
    }

    # xac nhan hoan thanh khi bao hoan thanh san pham
    public function confirmFinishFromFinishProduct($productId)
    {
        $hFunction = new \Hfunction();
        $modelWorkAllocationFinish = new QcWorkAllocationFinish();
        $dataWorkAllocation = $this->infoActivityOfProduct($productId);
        if ($hFunction->checkCount($dataWorkAllocation)) {
            # lay gia tri mac dinh
            $hasFinish = $modelWorkAllocationFinish->getDefaultHasFinish();
            $reasonFinish = $modelWorkAllocationFinish->getDefaultStaffReportFinish();
            $note = 'Kết thúc từ báo hoàn thành sản phẩm';
            foreach ($dataWorkAllocation as $workAllocation) {
                $this->confirmFinish($workAllocation->allocationId(), $hFunction->carbonNow(), $hasFinish, $reasonFinish, $note);
            }
        }
    }

    # xac nhan hoan thanh khi huy san pham
    public function confirmFinishFromCancelProduct($productId)
    {
        $hFunction = new \Hfunction();
        $modelWorkAllocationFinish = new QcWorkAllocationFinish();
        $dataWorkAllocation = $this->infoActivityOfProduct($productId);
        if ($hFunction->checkCount($dataWorkAllocation)) {
            # lay gia tri mac dinh
            $hasFinish = $modelWorkAllocationFinish->getDefaultHasFinish();
            $reasonFinish = $modelWorkAllocationFinish->getDefaultStaffReportFinish();
            $note = 'Kết thúc từ báo hủy sản phẩm';
            foreach ($dataWorkAllocation as $workAllocation) {
                $this->confirmFinish($workAllocation->allocationId(), $hFunction->carbonNow(), $hasFinish, $reasonFinish, $note);
            }
        }
    }

    # cap nhat tre
    public function updateLateStatus($allocationId)
    {
        return QcWorkAllocation::where('allocation_id', $allocationId)->update(
            [
                'lateStatus' => $this->getDefaultHasLate()
            ]);
    }

    # huy ban giao
    public function cancelAllocation($allocationId)
    {
        $hFunction = new \Hfunction();
        return QcWorkAllocation::where('allocation_id', $allocationId)->update(
            [
                'action' => $this->getDefaultNotAction(),
                'cancelStatus' => $this->getDefaultHasCancelStatus(),
                'cancelDate' => $hFunction->carbonNow()
            ]);
    }
    //========== ========= ========= RELATION ========== ========= ==========
    //---------- thuong  -----------
    public function bonus()
    {
        return $this->hasMany('App\Models\Ad3d\Bonus\QcBonus', 'workAllocation_id', 'allocation_id');
    }

    //---------- phat  -----------
    public function minusMoney()
    {
        return $this->hasMany('App\Models\Ad3d\MinusMoney\QcMinusMoney', 'workAllocation_id', 'allocation_id');
    }

    public function minusMoneyGetInfo($allocationId = null)
    {
        $modelMinusMoney = new QcMinusMoney();
        return $modelMinusMoney->getInfoOfWorkAllocation($this->checkIdNull($allocationId));
    }

    public function applyMinusMoneyForSupplies($allocationId, $money, $note)
    {
        $hFunction = new \Hfunction();
        $modelPunishContent = new QcPunishContent();
        $modelStaffNotify = new QcStaffNotify();
        $modelMinusMoney = new QcMinusMoney();
        # thoi gian de kiem tra
        $checkDate = $hFunction->carbonNow();
        # danh muc phat boi thuong vat tu
        $punishId = $modelPunishContent->getPunishIdForMinusMoneySupplies();
        $punishId = (is_int($punishId)) ? $punishId : $punishId[0];
        # co ap dung phat
        if ($punishId) {
            $dataWorkAllocation = $this->getInfo($allocationId);
            $dataMinusMoneyStaff = $dataWorkAllocation->receiveStaff;
            #thong tin lam viec cua NV nhan thi cong
            $dataWork = $dataMinusMoneyStaff->workInfoActivityOfStaff();
            if ($hFunction->checkCount($dataWork)) {
                $workId = $dataWork->workId();
                if ($modelMinusMoney->insert($checkDate, $note, $workId, null, $punishId, 0, null, null, null, $allocationId, $money)) {
                    $modelStaffNotify->insert(null, $dataMinusMoneyStaff->staffId(), 'Bồi thường vật tư', null, null, null, $modelMinusMoney->insertGetId());
                }
            }
        }

    }

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

    # lay tat ca phan cong cua nguoi nhan
    public function infoOfStaffReceive($staffId, $dateFilter = null)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($dateFilter)) {
            return QcWorkAllocation::where('receiveStaff_id', $staffId)->orderBy('allocationDate', 'DESC')->get();
        } else {
            return QcWorkAllocation::where('receiveStaff_id', $staffId)->where('allocationDate', 'like', "%$dateFilter%")->orderBy('allocationDate', 'DESC')->get();
        }
    }

    # chon danh sach phan cong cua 1 nhan vien
    public function selectInfoOfStaffReceive($staffId, $finishStatus = 100, $dateFilter = null)
    {
        $hFunction = new \Hfunction();
        $modelCompany = new QcCompany();
        $modelWorkAllocationFinish = new QcWorkAllocationFinish();
        # da ket thuc
        $hasFinishStatus = $modelCompany->getDefaultValueHasFinish();
        # chua ket thuc
        $notFinishStatus = $modelCompany->getDefaultValueNotFinish();
        #tat ca trang thai ket thuc
        $defaultAllFinishStatus = $modelCompany->getDefaultValueAllFinish();
        if ($finishStatus != $defaultAllFinishStatus) { # khong lay tat ca
            $allocationFinishId = $modelWorkAllocationFinish->listAllocationId();
            if ($finishStatus == $notFinishStatus) { #chua hoan thanh
                if ($hFunction->checkEmpty($dateFilter)) {
                    return QcWorkAllocation::whereNotIn('allocation_id', $allocationFinishId)->where('receiveStaff_id', $staffId)->orderBy('receiveDeadline', 'DESC')->select('*');
                } else {
                    return QcWorkAllocation::whereNotIn('allocation_id', $allocationFinishId)->where('receiveStaff_id', $staffId)->where('allocationDate', 'like', "%$dateFilter%")->orderBy('receiveDeadline', 'DESC')->select('*');
                }
            } elseif ($finishStatus == $hasFinishStatus) { # da hoan thanh
                if ($hFunction->checkEmpty($dateFilter)) {
                    return QcWorkAllocation::whereIn('allocation_id', $allocationFinishId)->where('receiveStaff_id', $staffId)->orderBy('receiveDeadline', 'DESC')->select('*');
                } else {
                    return QcWorkAllocation::whereIn('allocation_id', $allocationFinishId)->where('receiveStaff_id', $staffId)->where('allocationDate', 'like', "%$dateFilter%")->orderBy('receiveDeadline', 'DESC')->select('*');
                }
            }
        } else {
            if ($hFunction->checkEmpty($dateFilter)) {
                return QcWorkAllocation::where('receiveStaff_id', $staffId)->orderBy('receiveDeadline', 'DESC')->select('*');
            } else {
                return QcWorkAllocation::where('receiveStaff_id', $staffId)->where('allocationDate', 'like', "%$dateFilter%")->orderBy('receiveDeadline', 'DESC')->select('*');
            }
        }

    }

    # chon danh sach phan cong bi tre cua 1 nhan vien
    public function selectInfoHasLateOfStaffReceive($staffId, $dateFilter = null)
    {
        $hFunction = new \Hfunction();
        $hasLateStatus = $this->getDefaultHasLate();
        if ($hFunction->checkEmpty($dateFilter)) {
            return QcWorkAllocation::where('receiveStaff_id', $staffId)->where('lateStatus', $hasLateStatus)->orderBy('receiveDeadline', 'DESC')->select('*');
        } else {
            return QcWorkAllocation::where('receiveStaff_id', $staffId)->where('lateStatus', $hasLateStatus)->where('allocationDate', 'like', "%$dateFilter%")->orderBy('receiveDeadline', 'DESC')->select('*');
        }
    }

    # chon danh sach phan cong da hoan thanh khong tre (dung hen)cua 1 nhan vien
    public function selectInfoFinishNotLateOfStaffReceive($staffId, $dateFilter = null)
    {
        $hFunction = new \Hfunction();
        $modelWorkAllocationFinish = new QcWorkAllocationFinish();
        $allocationFinishId = $modelWorkAllocationFinish->listAllocationId();
        $notLateStatus = $this->getDefaultNotLate();
        if ($hFunction->checkEmpty($dateFilter)) {
            return QcWorkAllocation::whereIn('allocation_id', $allocationFinishId)->where('lateStatus', $notLateStatus)->where('receiveStaff_id', $staffId)->orderBy('receiveDeadline', 'DESC')->select('*');
        } else {
            return QcWorkAllocation::whereIn('allocation_id', $allocationFinishId)->where('lateStatus', $notLateStatus)->where('receiveStaff_id', $staffId)->where('allocationDate', 'like', "%$dateFilter%")->orderBy('receiveDeadline', 'DESC')->select('*');
        }
    }

    # chon danh sach phan cong da hoan thanh bi tre cua 1 nhan vien
    public function selectInfoFinishHasLateOfStaffReceive($staffId, $dateFilter = null)
    {
        $hFunction = new \Hfunction();
        $modelWorkAllocationFinish = new QcWorkAllocationFinish();
        $allocationFinishId = $modelWorkAllocationFinish->listAllocationId();
        $hasLateStatus = $this->getDefaultHasLate();
        if ($hFunction->checkEmpty($dateFilter)) {
            return QcWorkAllocation::whereIn('allocation_id', $allocationFinishId)->where('lateStatus', $hasLateStatus)->where('receiveStaff_id', $staffId)->orderBy('receiveDeadline', 'DESC')->select('*');
        } else {
            return QcWorkAllocation::whereIn('allocation_id', $allocationFinishId)->where('lateStatus', $hasLateStatus)->where('receiveStaff_id', $staffId)->where('allocationDate', 'like', "%$dateFilter%")->orderBy('receiveDeadline', 'DESC')->select('*');
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
        return QcWorkAllocation::where('receiveStaff_id', $staffId)->where('action', $this->getDefaultHasAction())->orderBy('allocationDate', 'DESC')->get();
    }

    # lay thong tin viec da ket thuc cua 1 nhan vien
    public function infoFinishOfStaffReceive($staffId)
    {
        return QcWorkAllocation::where('receiveStaff_id', $staffId)->where('action', $this->getDefaultNotAction())->orderBy('receiveDeadline', 'ASC')->get();
    }

    # danh sach ma san pham dang thi cong cua 1 nhan vien
    public function listProductIdActivityOfReceiveStaff($receiveStaffId)
    {
        return QcWorkAllocation::where('receiveStaff_id', $receiveStaffId)->where('action', $this->getDefaultHasAction())->orderBy('receiveDeadline', 'ASC')->pluck('product_id');
    }

    # danh sach ma phan cong cua 1 nhan vien nhan
    public function listIdOfReceiveStaff($receiveStaffId)
    {
        return QcWorkAllocation::whereIn('receiveStaff_id', $receiveStaffId)->orderBy('receiveDeadline', 'ASC')->pluck('allocation_id');
    }

    # lay gia tri mang ve (tien) tu thi cong cua nhieu san pham
    public function valueMoneyFromListWorkAllocation($dataListWorkAllocation)
    {
        $hFunction = new \Hfunction();
        $result = 0;
        if ($hFunction->checkCount($dataListWorkAllocation)) {
            foreach ($dataListWorkAllocation as $value) {
                $result = $result + $this->valueMoneyFromAllocation($value->allocationId());
            }
        }
        return $result;
    }

    # lay gia tri mang ve (tien) tu thi cong cua 1 san pham
    public function valueMoneyFromAllocation($allocationId = null)
    {
        $hFunction = new \Hfunction();
        $dataWorkAllocation = $this->getInfo($this->checkIdNull($allocationId));
        # thong tin san pham
        $dataProduct = $dataWorkAllocation->product;
        $productId = $dataProduct->productId();
        $productPrice = $dataProduct->price();
        # lay danh sach trien khai thi cong cua san pham - KHONG BI HUY
        $dataListWorkAllocation = $this->infoNotCancelOfProduct($productId);
        # so luong nguoi thi cong
        $amountAllocation = $hFunction->getCount($dataListWorkAllocation);
        if ($amountAllocation == 0) {
            return 0;
        } else {
            return $productPrice / $amountAllocation;
        }

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
        return QcWorkAllocation::where('product_id', $productId)->where('role', $this->getDefaultHasRole())->exists();
    }

    # kiem tra sam pham dang duoc phan viec
    public function existInfoActivityOfProduct($productId)
    {
        return QcWorkAllocation::where('product_id', $productId)->exists();
    }

    # danh sach thi cong cua san pham  - DANG HOAT DONG
    public function infoActivityOfProduct($productId)
    {
        return QcWorkAllocation::where('product_id', $productId)->where('action', $this->getDefaultHasAction())->get();
    }

    # danh sach thi cong cua san pham - TAT CA
    public function infoOfProduct($productId, $orderBy = 'DESC')
    {
        return QcWorkAllocation::where('product_id', $productId)->orderBy('allocationDate', $orderBy)->get();
    }

    # danh sach thi cong cua san pham - KHONG BI HUY
    public function infoNotCancelOfProduct($productId, $orderBy = 'DESC')
    {
        return QcWorkAllocation::where('product_id', $productId)->where('cancelStatus', $this->getDefaultNotCancelStatus())->orderBy('allocationDate', $orderBy)->get();
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

    # kiem tra phan viec da ket thuc chua
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
        return $modelWorkAllocationReport->infoOfWorkAllocation($this->checkIdNull($allocationId), $take);
    }

    //========= ========== ========== lay thong tin ========== ========== ==========
    public function selectInfoOfReceiveListStaff($listStaffId, $actionStatus = 100, $dateFilter = null) # tat ca
    {
        $hFunction = new \Hfunction();
        if ($actionStatus == $this->getDefaultAllAction()) {
            if ($hFunction->checkEmpty($dateFilter)) {
                return QcWorkAllocation::whereIn('receiveStaff_id', $listStaffId)->orderBy('receiveDeadline', 'DESC')->select('*');
            } else {
                return QcWorkAllocation::whereIn('receiveStaff_id', $listStaffId)->where('receiveDeadline', 'like', "%$dateFilter%")->orderBy('receiveDeadline', 'DESC')->select('*');
            }
        } else {
            if ($hFunction->checkEmpty($dateFilter)) {
                return QcWorkAllocation::whereIn('receiveStaff_id', $listStaffId)->where('action', $actionStatus)->orderBy('receiveDeadline', 'DESC')->select('*');
            } else {
                return QcWorkAllocation::whereIn('receiveStaff_id', $listStaffId)->where('action', $actionStatus)->where('receiveDeadline', 'like', "%$dateFilter%")->orderBy('receiveDeadline', 'DESC')->select('*');
            }
        }
    }

    #lay thong tin con hoat dong
    public function getAllInfoHasAction()
    {
        return QcWorkAllocation::where('action', $this->getDefaultHasAction())->get();
    }

    public function getInfo($allocationId = '', $field = '')
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($allocationId)) {
            return QcWorkAllocation::get();
        } else {
            $result = QcWorkAllocation::where('allocation_id', $allocationId)->first();
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
            return QcWorkAllocation::where('allocation_id', $objectId)->pluck($column)[0];
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

    public function constructionNumber($allocationId = null)
    {
        return $this->pluck('constructionNumber', $allocationId);
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

    public function lateStatus($allocationId = null)
    {
        return $this->pluck('lateStatus', $allocationId);
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

    public function productRepairId($allocationId = null)
    {
        return $this->pluck('productRepair_id', $allocationId);
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
        $hFunction = new \Hfunction();
        $result = QcWorkAllocation::orderBy('allocation_id', 'DESC')->first();
        return ($hFunction->checkEmpty($result)) ? 0 : $result->allocation_id;
    }

    # chon tat cac cac thong tin dang hoat dong
    public function selectInfoActivity()
    {
        return QcWorkAllocation::where('action', $this->getDefaultHasAction())->select('*');
    }

    # chon tat ca thong tin dang hoat dong se het han trong thang hien tai
    public function selectInfoActivityDeadlineInCurrentMonth()
    {
        $currentMonth = date('Y-m');
        return QcWorkAllocation::where('action', $this->getDefaultHasAction())->where('receiveDeadline', 'like', "%$currentMonth%")->select('*');
    }

    //======= ======= KIEM TRA THONG TIN ======== =========
    #con hoat dong
    public function checkActivity($allocationId = null)
    {
        return ($this->action($allocationId) == $this->getDefaultHasAction()) ? true : false;
    }

    #kiem tra huy phan viec is_int
    public function checkCancel($allocationId = null)
    {
        return ($this->cancelStatus($allocationId) == $this->getDefaultHasCancelStatus()) ? true : false;
    }

    # xac nhan phan cong
    public function checkConfirm($allocationId = null)
    {
        return ($this->confirmStatus($allocationId) == $this->getDefaultHasConfirmStatus()) ? true : false;
    }

    # kiem tra tho lam chinh
    public function checkRoleMain($allocationId = null)
    {
        return ($this->role($allocationId) == $this->getDefaultHasRole()) ? true : false;
    }

    # kiem tra co bi tre khi sau check tu dong
    public function checkHasLate($allocationId = null)
    {
        return ($this->lateStatus($allocationId) == $this->getDefaultHasLate()) ? true : false;
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
                if ($checkDate > $receiveDeadline) $lateStatus = true;
            }
        }
        return $lateStatus;
    }

    # kiem tra cap nhat trang thai bi tre
    public function checkUpdateLateStatus()
    {
        $hFunction = new \Hfunction();
        # chi kiem tra thong tin con hoat dong
        $dataWorkAllocation = $this->getAllInfoHasAction();
        if ($hFunction->checkCount($dataWorkAllocation)) {
            foreach ($dataWorkAllocation as $workAllocation) {
                $allocationId = $workAllocation->allocationId();
                if ($this->checkLate($allocationId)) {
                    $this->updateLateStatus($allocationId);
                }
            }
        }
    }

    # ========= ======== KIEM TRA PHAT TREN PHAN VIEC ======== =======
    #kiem tra tu dong thi cong bi tre - tat ca thong tin ban giao
    public function autoCheckMinusMoneyLateWorkAllocation()
    {
        $hFunction = new \Hfunction();
        $modelPunishContent = new QcPunishContent();
        # chi xet khi co ap dung phat
        if ($modelPunishContent->checkApplyMinusMoneyWhenWorkAllocationLate()) {
            #lay thong tin con hoat dong se het han trong thang hien tai
            $dataWorkAllocation = $this->selectInfoActivityDeadlineInCurrentMonth()->get();
            if ($hFunction->checkCount($dataWorkAllocation)) {
                foreach ($dataWorkAllocation as $workAllocation) {
                    $this->checkMinusMoneyLate($workAllocation->allocationId());
                }
            }
        }
    }

    # kiem tra ap dung phat khi tre
    public function checkMinusMoneyLate($allocationId = null)
    {
        $hFunction = new \Hfunction();
        $modelPunishContent = new QcPunishContent();
        $modelStaffNotify = new QcStaffNotify();
        $modelMinusMoney = new QcMinusMoney();
        # thoi gian de kiem tra
        $checkDate = $hFunction->carbonNow();
        # danh muc tre don hang duoc ban giao thi cong
        $punishId = $modelPunishContent->getPunishIdForWorkAllocationLate();
        # thong tin ban giao
        $dataWorkAllocation = $this->getInfo($this->checkIdNull($allocationId));
        $allocationId = $dataWorkAllocation->allocationId();
        $receiveDeadline = $dataWorkAllocation->receiveDeadline();
        $dataReceiveStaff = $dataWorkAllocation->receiveStaff;
        #thong tin lam viec cua NV quan ly nhan don hang thi cong
        $dataWork = $dataReceiveStaff->workInfoActivityOfStaff();
        if ($hFunction->checkCount($dataWork)) {
            $workId = $dataWork->workId();
            if ($receiveDeadline < $checkDate) { # tre ngay
                if (!$modelMinusMoney->checkExistMinusMoneyWorkAllocationLate($allocationId)) { // Neu chua phat thi se phat
                    //$punishId = (is_int($punishId)) ? $punishId : $punishId[0];
                    $minusReason = $modelMinusMoney->getDefaultReason();
                    $minusStaffId = $modelMinusMoney->getDefaultStaffId();
                    $minusApply = $modelMinusMoney->getDefaultHasApplyStatus();
                    $minusOrderConstructionId = $modelMinusMoney->getDefaultOrderConstruction();
                    $minusCompanyStoreCheckReportId = $modelMinusMoney->getDefaultCompanyStoreCheckReport();
                    $minusWorkAllocationId = $modelMinusMoney->getDefaultWorkAllocation();
                    $minusMoney = $modelMinusMoney->getDefaultMoney();
                    $minusReasonImage = $modelMinusMoney->getDefaultReasonImage();
                    if ($modelMinusMoney->insert($checkDate, $minusReason, $workId, $minusStaffId, $punishId, $minusApply, $minusOrderConstructionId, $minusCompanyStoreCheckReportId, $minusWorkAllocationId, $allocationId, $minusMoney, $minusReasonImage)) {
                        # lay gia tri mac dinh
                        $notifyOrderId = $modelStaffNotify->getDefaultOrderId();
                        $orderAllocationId = $modelStaffNotify->getDefaultOrderAllocationId();
                        $workAllocationId = $modelStaffNotify->getDefaultWorkAllocationId();
                        $bonusId = $modelStaffNotify->getDefaultBonusId();
                        $orderAllocationFinishId = $modelStaffNotify->getDefaultOrderAllocationFinishId();
                        $notifyNote = 'Thi công sản phẩm';
                        $modelStaffNotify->insert($notifyOrderId, $dataReceiveStaff->staffId(), $notifyNote, $orderAllocationId, $workAllocationId, $bonusId, $modelMinusMoney->insertGetId(), $orderAllocationFinishId);
                    }
                }

            }
        }
    }

}
