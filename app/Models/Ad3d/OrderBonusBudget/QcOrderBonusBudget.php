<?php

namespace App\Models\Ad3d\OrderBonusBudget;

use App\Models\Ad3d\Bonus\QcBonus;
use App\Models\Ad3d\BonusDepartment\QcBonusDepartment;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\Department\QcDepartment;
use App\Models\Ad3d\Order\QcOrder;
use App\Models\Ad3d\OrderAllocation\QcOrderAllocation;
use App\Models\Ad3d\OrderPay\QcOrderPay;
use App\Models\Ad3d\Product\QcProduct;
use App\Models\Ad3d\Rank\QcRank;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\StaffNotify\QcStaffNotify;
use App\Models\Ad3d\WorkAllocation\QcWorkAllocation;
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
    public function applyBonusDepartmentConstruction($orderId)
    {
        # Thuong cap quan ly
        $this->applyBonusDepartmentConstructionManage($orderId);
        # Thuong cap nhan vien
        $this->applyBonusDepartmentConstructionStaff($orderId);
    }

    # ap dung thuong cho thi cong cap quan ly
    public function applyBonusDepartmentConstructionManage($orderId)
    {
        $hFunction = new \Hfunction();
        $modelBonus = new QcBonus();
        $modelStaffNotify = new QcStaffNotify();
        $modelOrderAllocation = new QcOrderAllocation();
        #tien thuong hoan thanh thi cong cap quan ly
        $orderBonusPrice = $this->totalBudgetMoneyOfConstructionManage($orderId);
        if ($orderBonusPrice > 0) { # CO AP DUNG THUONG
            # lay thong tin ban giao thi cong sau cung
            $dataOrderAllocation = $modelOrderAllocation->lastInfoOfOrder($orderId);
            # co ban giao quan ly thi cong
            if ($hFunction->checkCount($dataOrderAllocation)) {
                # KHONG BI HUY VA BI TRE => XET THUONG
                $allocationId = $dataOrderAllocation->allocationId();
                if (!$dataOrderAllocation->checkCancelAllocation() && !$dataOrderAllocation->checkLate($allocationId)) {
                    # lay thong tin cua nguoi nhan thuong (duoc ban giao)
                    $dataReceiveStaff = $dataOrderAllocation->receiveStaff;
                    # thong tin lam viec dang hoat dong
                    $dataWork = $dataReceiveStaff->workInfoActivityOfStaff();
                    if ($hFunction->checkCount($dataWork)) {
                        $workId = $dataWork->workId();
                        if (!$modelBonus->checkExistBonusWorkOfOrderAllocation($workId, $allocationId)) { # chua ap dung thuong
                            if ($modelBonus->insert($orderBonusPrice, $hFunction->carbonNow(), 'Quản lý thi công đơn hàng', 1, $workId, $allocationId, null, null, null)) {
                                $bonusId = $modelBonus->insertGetId();
                                $allocationStaffId = $dataReceiveStaff->staffId();
                                $allocationStaffId = (is_int($allocationStaffId)) ? $allocationStaffId : $allocationStaffId[0];
                                # thong bao cho nguoi nhan thuong
                                $modelStaffNotify->insert(null, $allocationStaffId, 'Quản lý thi công đơn hàng', null, null, $bonusId, null, null);
                            }
                        }
                    }
                }
            }
        }
    }

    # ap dung thuong  cho thi cong cap nhan vien
    public function applyBonusDepartmentConstructionStaff($orderId)
    {
        $hFunction = new \Hfunction();
        $modelBonus = new QcBonus();
        $modelStaffNotify = new QcStaffNotify();
        $modelProduct = new QcProduct();
        $modelWorkAllocation = new QcWorkAllocation();
        # lay phan tram thuong thi cong tren 1 don hang - cap nhan vien
        $orderBonusPercent = $this->getPercentOfConstructionStaff($orderId);
        if ($orderBonusPercent > 0) { # co ap dung thuong
            # lay danh sach san pham cua don hang khong bi huy
            $dataProduct = $modelProduct->infoActivityOfOrder($orderId);
            if ($hFunction->checkCount($dataProduct)) {
                foreach ($dataProduct as $product) {
                    $productId = $product->productId();
                    # lay danh sach trien khai thi cong cua san pham - KHONG BI HUY
                    $dataWorkAllocation = $modelWorkAllocation->infoNotCancelOfProduct($productId);
                    if ($hFunction->checkCount($dataWorkAllocation)) { # co trien khai thi cong
                        # so tien moi nguoi nhan
                        $bonusMoneyOfEachPerson = $this->totalBudgetMoneyEachPersonOfConstructionStaffOfProduct($productId);
                        # lay so tien chia cho moi nguoi thi cong
                        foreach ($dataWorkAllocation as $workAllocation) {
                            $allocationId = $workAllocation->allocationId();
                            # kiem tra co bi tre khong
                            if (!$workAllocation->checkLate($allocationId)) {
                                # lay thong tin cua nguoi nhan
                                $dataReceiveStaff = $workAllocation->receiveStaff;
                                # thong tin lam viec dang hoat dong
                                $dataWork = $dataReceiveStaff->workInfoActivityOfStaff();
                                if ($hFunction->checkCount($dataWork)) {
                                    $workId = $dataWork->workId();
                                    if (!$modelBonus->checkExistBonusWorkOfWorkAllocation($workId, $allocationId)) { # chua ap dung thuong
                                        if ($modelBonus->insert($bonusMoneyOfEachPerson, $hFunction->carbonNow(), 'Thi công sản phẩm', 1, $workId, null, null, null, $allocationId)) {
                                            $bonusId = $modelBonus->insertGetId();
                                            $allocationStaffId = $dataReceiveStaff->staffId();
                                            $allocationStaffId = (is_int($allocationStaffId)) ? $allocationStaffId : $allocationStaffId[0];
                                            # thong bao cho nguoi nhan thuong
                                            $modelStaffNotify->insert(null, $allocationStaffId, 'Thi công sản phẩm', null, null, $bonusId, null, null);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

            }
        }
    }

    # lay phan tram thuong - phat tren don hang cua bo phan thi cong cap quan ly
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
        return (int)$orderTotalPrice * ($this->getPercentOfConstructionManage($orderId) / 100);
    }

    # lay phan tram thuong - phat tren don hang cua bo phan thi cong cap nhan vien
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
    public function totalBudgetMoneyOfConstructionStaff($orderId)
    {
        $modelOrder = new QcOrder();
        # tong tien hoa don
        $orderTotalPrice = $modelOrder->totalPrice($orderId);
        return (int)$orderTotalPrice * ($this->getPercentOfConstructionStaff($orderId) / 100);
    }

    # Tong tien thuong - phat tren 1 san pham cua bo phan thi cong cap nhan vien
    public function totalBudgetMoneyOfConstructionStaffOfProduct($productId)
    {
        $modelProduct = new QcProduct();
        $dataProduct = $modelProduct->getInfo($productId);
        $productPrice = $dataProduct->price();
        $orderId = $dataProduct->orderId();
        $bonusPercent = $this->getPercentOfConstructionStaff($orderId);
        return (int)$productPrice * ($bonusPercent / 100);
    }

    # Tong tien thuong - phat tren 1 san pham cua moi nguoi thi cong cap nhan vien
    public function totalBudgetMoneyEachPersonOfConstructionStaffOfProduct($productId)
    {
        $hFunction = new \Hfunction();
        $modelWorkAllocation = new QcWorkAllocation();
        # lay danh sach trien khai thi cong cua san pham - KHONG BI HUY
        $dataWorkAllocation = $modelWorkAllocation->infoNotCancelOfProduct($productId);
        # so luong nguoi thi cong
        $amountAllocation = $hFunction->getCount($dataWorkAllocation);
        if ($amountAllocation > 0) {
            $totalBonusMoney = $this->totalBudgetMoneyOfConstructionStaffOfProduct($productId);
            return (int)($totalBonusMoney / $amountAllocation);
        } else {
            return 0;
        }
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
