<?php

namespace App\Models\Ad3d\OrderBonusBudget;

use App\Models\Ad3d\Bonus\QcBonus;
use App\Models\Ad3d\BonusDepartment\QcBonusDepartment;
use App\Models\Ad3d\Department\QcDepartment;
use App\Models\Ad3d\Order\QcOrder;
use App\Models\Ad3d\OrderAllocation\QcOrderAllocation;
use App\Models\Ad3d\OrderPay\QcOrderPay;
use App\Models\Ad3d\Rank\QcRank;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\StaffNotify\QcStaffNotify;
use Illuminate\Database\Eloquent\Model;

class QcOrderBonusBudget extends Model
{
    protected $table = 'qc_order_bonus_budget';
    protected $fillable = ['budget_id', 'created_at', 'order_id', 'bonus_id', 'department_id', 'rank_id'];
    protected $primaryKey = 'budget_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== THEM - SUA ========== ========== ==========
    #---------- Them ----------
    public function insert($orderId, $bonusId, $departmentId, $rankId)
    {
        $hFunction = new \Hfunction();
        $modelOrderBonusBudget = new QcOrderBonusBudget();
        $modelOrderBonusBudget->order_id = $orderId;
        $modelOrderBonusBudget->bonus_id = $bonusId;
        $modelOrderBonusBudget->department_id = $departmentId;
        $modelOrderBonusBudget->rank_id = $rankId;
        $modelOrderBonusBudget->created_at = $hFunction->createdAt();
        if ($modelOrderBonusBudget->save()) {
            $this->lastId = $modelOrderBonusBudget->budget_id;
            return true;
        } else {
            return false;
        }
    }

    public function checkIdNull($budgetId)
    {
        return (empty($budgetId)) ? $this->budgetId() : $budgetId;
    }

    #========== ========== ========== CAC MOI QUAN HE DU LIEU ========== ========== ==========
    //---------- THUONG THEO BO PHAN -----------
    public function bonusDepartment()
    {
        return $this->belongsTo('App\Models\Ad3d\BonusDepartment\QcBonusDepartment', 'bonus_id', 'bonus_id');
    }

    //---------- DON HANG -----------
    public function order()
    {
        return $this->belongsTo('App\Models\Ad3d\Order\QcOrder', 'order_id', 'order_id');
    }

    public function infoOfOrder($orderId)
    {
        return QcOrderBonusBudget::where('order_id', $orderId)->get();
    }

    # kiem tra da ton tai thuong theo bo phan va cap bac hay chua
    public function checkExistOrderAndBonusDepartment($orderId, $bonusId)
    {
        return QcOrderBonusBudget::where('order_id', $orderId)->where('bonus_id', $bonusId)->exists();
    }

    # lay danh sach ma thuong bo phan cua don hang
    public function listBonusIdOfOrder($orderId)
    {
        return QcOrderBonusBudget::where('order_id', $orderId)->pluck('bonus_id');
    }

    // ======== ======== THUONG BO PHAN KINH DOANH ========= ========
    # lay phan tram thuong tren don hang cua bo phan kinh doanh cap quan ly
    public function getPercentOfBusinessManage($orderId)
    {
        $hFunction = new \Hfunction();
        $modelRank = new QcRank();
        $modelDepartment = new QcDepartment();
        $dataInfo = $this->getInfoOfOrderAndDepartmentRank($orderId, $modelDepartment->businessDepartmentId(), $modelRank->manageRankId());
        if ($hFunction->checkCount($dataInfo)) {
            return $dataInfo->bonusDepartment->percent();
        } else {
            return 0;
        }
    }

    # Tong tien thuong - phạt tren don hang cua bo phan kinh doanh cap quan ly
    public function totalBudgetMoneyOfBusinessManage($orderId)
    {
        $modelOrder = new QcOrder();
        # tong tien hoa don
        $orderTotalPrice = $modelOrder->totalPrice($orderId);
        return (int)$orderTotalPrice * ($this->getPercentOfBusinessManage($orderId) / 100);
    }

    # lay phan tram thuong tren don hang cua bo phan kinh doanh cap nhan vien
    public function getPercentOfBusinessStaff($orderId)
    {
        $hFunction = new \Hfunction();
        $modelRank = new QcRank();
        $modelDepartment = new QcDepartment();
        $dataInfo = $this->getInfoOfOrderAndDepartmentRank($orderId, $modelDepartment->businessDepartmentId(), $modelRank->staffRankId());
        if ($hFunction->checkCount($dataInfo)) {
            return $dataInfo->bonusDepartment->percent();
        } else {
            return 0;
        }
    }

    # Tong tien thuong - phạt tren don hang cua bo phan kinh doanh cap nhan vien
    public function totalBudgetMoneyOfBusinessStaff($orderId)
    {
        $modelOrder = new QcOrder();
        # tong tien hoa don
        $orderTotalPrice = $modelOrder->totalPrice($orderId);
        return (int)$orderTotalPrice * ($this->getPercentOfBusinessStaff($orderId) / 100);
    }

    // ======== ======== THUONG BO PHAN THI CONG ========= ========
    # xet thuong bo phan thi cong tat ca cac cap bac
    public function applyBonusConstruction($orderId)
    {
        $hFunction = new \Hfunction();
        $modelOrder = new QcOrder();
        $modelBonus = new QcBonus();
        $modelStaffNotify = new QcStaffNotify();
        $modelOrderAllocation = new QcOrderAllocation();
        #QUAN LY THI CONG DON HANG
        $dataOrderAllocationFinish = $modelOrderAllocation->infoFinishOfOrder($orderId);
        # co thong tin xac nhan hoan thanh
        if ($hFunction->checkCount($dataOrderAllocationFinish)) {
            $dataOrder = $modelOrder->getInfo($orderId);
            # ngay xac nhan hoan thanh thi cong don hang
            $allocationConfirmDate = $dataOrderAllocationFinish->confirmDate();
            $allocationConfirmDate = date('Y-m-d', strtotime($allocationConfirmDate));
            # ngay hen giao don hang
            $orderDeliveryDate = $dataOrder->deliveryDate($orderId);
            $orderDeliveryDate = date('Y-m-d', strtotime($orderDeliveryDate[0]));
            # khong bi tre
            if ($allocationConfirmDate <= $orderDeliveryDate) {
                # lay thong tin cua quan ly ban giao
                $dataAllocationStaff = $dataOrderAllocationFinish->allocationStaff;
                # thong tin lam viec
                $dataWork = $dataAllocationStaff->workInfoActivityOfStaff();
                if ($hFunction->checkCount($dataWork)) {
                    $workId = $dataWork->workId();
                    if (!$modelBonus->checkExistBonusWorkOfOrderConstruction($workId, $orderId)) { # chua ap dung thuong
                        #tien thuong hoan thanh thi cong
                        /*$orderBonusPrice = $dataOrder->getBonusAndMinusMoneyOfManageRank($orderId);
                        if ($modelBonus->insert($orderBonusPrice, $hFunction->carbonNow(), 'Quản lý triển khai thi công', 0, $workId, null, $orderId, null)) {
                            $bonusId = $modelBonus->insertGetId();
                            $allocationStaffId = $dataAllocationStaff->staffId();
                            $allocationStaffId = (is_int($allocationStaffId)) ? $allocationStaffId : $allocationStaffId[0];
                            # thong bao cho nguoi nhan thuong
                            $modelStaffNotify->insert(null, $allocationStaffId, 'Quản lý triển khai thi công', null, null, $bonusId, null, null);
                        }*/
                    }
                }
            }
        }
        # THUONG NHAN VIEN THI CONG SAN PHAM
        # lay danh sach san pham cua don hang

        # xe thuong thi cong tre tung san pham
    }
    # lay phan tram thuong tren don hang cua bo phan thi cong cap quan ly
    public function getPercentOfConstructionManage($orderId)
    {
        $hFunction = new \Hfunction();
        $modelRank = new QcRank();
        $modelDepartment = new QcDepartment();
        $dataInfo = $this->getInfoOfOrderAndDepartmentRank($orderId, $modelDepartment->constructionDepartmentId(), $modelRank->manageRankId());
        if ($hFunction->checkCount($dataInfo)) {
            return $dataInfo->bonusDepartment->percent();
        } else {
            return 0;
        }
    }
    # Tong tien thuong - phạt tren don hang cua bo phan thi cong cap quan ly
    public function totalBudgetMoneyOfConstructionManage($orderId)
    {
        $modelOrder = new QcOrder();
        # tong tien hoa don
        $orderTotalPrice = $modelOrder->totalPrice($orderId);
        return  (int)$orderTotalPrice * ($this->getPercentOfConstructionManage($orderId) / 100);
    }

    # lay phan tram thuong tren don hang cua bo phan thi cong cap nhan vien
    public function getPercentOfConstructionStaff($orderId)
    {
        $hFunction = new \Hfunction();
        $modelRank = new QcRank();
        $modelDepartment = new QcDepartment();
        $dataInfo = $this->getInfoOfOrderAndDepartmentRank($orderId, $modelDepartment->constructionDepartmentId(), $modelRank->staffRankId());
        if ($hFunction->checkCount($dataInfo)) {
            return $dataInfo->bonusDepartment->percent();
        } else {
            return 0;
        }
    }
    # Tong tien thuong - phạt tren don hang cua bo phan thi cong cap nhan vien
    public function totalBudgetMoneyOfConstructionRank($orderId)
    {
        $modelOrder = new QcOrder();
        # tong tien hoa don
        $orderTotalPrice = $modelOrder->totalPrice($orderId);
        return  (int)$orderTotalPrice * ($this->getPercentOfConstructionStaff($orderId) / 100);
    }
    #============ =========== ============ LAY THONG TIN ============= =========== ==========
    # lay thong tin ngan sach cua don hang theo bo phan va cap bac
    public function getInfoOfOrderAndDepartmentRank($orderId, $departmentId, $rankId)
    {
        return QcOrderBonusBudget::where('order_id', $orderId)->where('department_id', $departmentId)->where('rank_id', $rankId)->first();
    }

    public function getInfo($budgetId = '', $field = '')
    {
        if (empty($budgetId)) {
            return QcOrderBonusBudget::get();
        } else {
            $result = QcOrderBonusBudget::where('budget_id', $budgetId)->first();
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
            return QcOrderBonusBudget::where('budget_id', $objectId)->pluck($column);
        }
    }

    public function budgetId()
    {
        return $this->budget_id;
    }

    public function orderId($budgetId = null)
    {
        return $this->pluck('order_id', $budgetId);
    }

    public function bonusId($budgetId = null)
    {
        return $this->pluck('bonus_id', $budgetId);
    }

    public function departmentId($budgetId = null)
    {
        return $this->pluck('department_id', $budgetId);
    }

    public function rankId($budgetId = null)
    {
        return $this->pluck('rank_id', $budgetId);
    }


    public function createdAt($budgetId = null)
    {
        return $this->pluck('created_at', $budgetId);
    }

}
