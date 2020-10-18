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

    # tien thuong - phạt tren don hang cua bo phan kinh doanh cap quan ly
    public function getBudgetMoneyOfBusinessManage($orderId)
    {
        $hFunction = new \Hfunction();
        $modelOrder = new QcOrder();
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $budgetMoney = 0;
        $dataInfo = $this->getInfoOfOrderAndDepartmentRank($orderId, $modelDepartment->businessDepartmentId(), $modelRank->manageRankId());
        if ($hFunction->checkCount($dataInfo)) {
            # tong tien hoa don
            $orderTotalPrice = $modelOrder->totalPrice($orderId);
            # phan tran thuong
            $percent = $dataInfo->bonusDepartment->percent();
            $budgetMoney = (int)$orderTotalPrice * ($percent / 100);
        }
        return $budgetMoney;
    }
    # tien thuong - phạt tren don hang cua bo phan kinh doanh cap nhan vien
    public function getBudgetMoneyOfBusinessRank($orderId)
    {
        $hFunction = new \Hfunction();
        $modelOrder = new QcOrder();
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $budgetMoney = 0;
        $dataInfo = $this->getInfoOfOrderAndDepartmentRank($orderId, $modelDepartment->businessDepartmentId(), $modelRank->staffRankId());
        if ($hFunction->checkCount($dataInfo)) {
            # tong tien hoa don
            $orderTotalPrice = $modelOrder->totalPrice($orderId);
            # phan tran thuong
            $percent = $dataInfo->bonusDepartment->percent();
            $budgetMoney = (int)$orderTotalPrice * ($percent / 100);
        }
        return $budgetMoney;
    }

    # tien thuong - phạt tren don hang cua bo phan thi cong cap quan ly
    public function getBudgetMoneyOfConstructionManage($orderId)
    {
        $hFunction = new \Hfunction();
        $modelOrder = new QcOrder();
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $budgetMoney = 0;
        $dataInfo = $this->getInfoOfOrderAndDepartmentRank($orderId, $modelDepartment->constructionDepartmentId(), $modelRank->manageRankId());
        if ($hFunction->checkCount($dataInfo)) {
            # tong tien hoa don
            $orderTotalPrice = $modelOrder->totalPrice($orderId);
            # phan tran thuong
            $percent = $dataInfo->bonusDepartment->percent();
            $budgetMoney = (int)$orderTotalPrice * ($percent / 100);
        }
        return $budgetMoney;
    }
    # tien thuong - phạt tren don hang cua bo phan thi cong cap nhan vien
    public function getBudgetMoneyOfConstructionRank($orderId)
    {
        $hFunction = new \Hfunction();
        $modelOrder = new QcOrder();
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $budgetMoney = 0;
        $dataInfo = $this->getInfoOfOrderAndDepartmentRank($orderId, $modelDepartment->constructionDepartmentId(), $modelRank->staffRankId());
        if ($hFunction->checkCount($dataInfo)) {
            # tong tien hoa don
            $orderTotalPrice = $modelOrder->totalPrice($orderId);
            # phan tran thuong
            $percent = $dataInfo->bonusDepartment->percent();
            $budgetMoney = (int)$orderTotalPrice * ($percent / 100);
        }
        return $budgetMoney;
    }

    # lay thong tin ngan sach cua don hang theo bo phan va cap back
    public function getInfoOfOrderAndDepartmentRank($orderId, $departmentId, $rankId)
    {
        return QcOrderBonusBudget::where('order_id', $orderId)->where('department_id', $departmentId)->where('rank_id', $rankId)->first();
    }

    #============ =========== ============ LAY THONG TIN ============= =========== ==========
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

    #======= ======== ======== XET THUONG ========= ========= =========
    # lay gia tri tien thuong  1 lan thanh toan cua bo phan kinh doanh cap quan ly
    public function getBonusMoneyOfBusinessManageRank($payId)
    {
        $hFunction = new \Hfunction();
        $modelOrderPay = new QcOrderPay();
        $modelDepartment = new QcDepartment();
        $modelBonusDepartment = new QcBonusDepartment();
        $dataBonusDepartment = $modelBonusDepartment->infoActivityOfManageRank($modelDepartment->businessDepartmentId());
        # co ap dung thuong
        if ($hFunction->checkCount($dataBonusDepartment)) {
            $dataOrderPay = $modelOrderPay->getInfo($payId);
            $moneyPay = $dataOrderPay->money($payId);
            $moneyPay = (is_int($moneyPay)) ? $moneyPay : $moneyPay[0];
            $percent = $dataBonusDepartment->percent();
            return (int)$moneyPay * ($percent / 100);
        } else {
            return 0;
        }

    }

    # lay gia tri tien thuong  1 lan thanh toan cua bo phan kinh doanh cap nhan vien
    public function getBonusMoneyOfBusinessStaffRank($payId = null)
    {
        $hFunction = new \Hfunction();
        $modelDepartment = new QcDepartment();
        $modelBonusDepartment = new QcBonusDepartment();
        $dataBonusDepartment = $modelBonusDepartment->infoActivityOfStaffRank($modelDepartment->businessDepartmentId());
        # co ap dung thuong
        if ($hFunction->checkCount($dataBonusDepartment)) {
            $moneyPay = $this->money($payId);
            $moneyPay = (is_int($moneyPay)) ? $moneyPay : $moneyPay[0];
            $percent = $dataBonusDepartment->percent();
            return (int)$moneyPay * ($percent / 100);
        } else {
            return 0;
        }

    }

    # xet thuong cho bo phan kinh doanh
    public function applyBonusDepartmentBusiness($payId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelStaffNotify = new QcStaffNotify();
        $modelBonus = new QcBonus();
        $dataOrderPay = $this->getInfo($payId);
        if ($hFunction->checkCount($dataOrderPay)) {
            $dataOrder = $dataOrderPay->order;
            # thong tin nhan vien tao don hang
            $dataStaffCreated = $dataOrder->staff;
            # CAP QUAN LY - lay danh sach NV kinh doanh cap quan ly
            $dataStaffBusiness = $modelStaff->infoActivityStaffBusinessRankManage($dataOrder->companyId());
            if ($hFunction->checkCount($dataStaffBusiness)) {
                $bonusMoney = $this->getBonusMoneyOfBusinessManageRank($payId);
                if ($bonusMoney > 0) {
                    foreach ($dataStaffBusiness as $staffBusiness) {
                        $dataWork = $staffBusiness->workInfoActivityOfStaff();
                        if ($hFunction->checkCount($dataWork)) {
                            $workId = $dataWork->workId();
                            # kiem tra da duoc thuong chua - neu chua thi thuong
                            if (!$modelBonus->checkOrderPayBonus($workId, $payId)) {
                                if ($modelBonus->insert($bonusMoney, $hFunction->carbonNow(), 'Thưởng Quản lý kinh doanh nhận tiền từ đơn hàng', 1, $workId, null, null, $payId)) {
                                    $bonusId = $modelBonus->insertGetId();
                                    $notifyStaffId = $staffBusiness->staffId();
                                    $notifyStaffId = (is_int($notifyStaffId)) ? $notifyStaffId : $notifyStaffId[0];
                                    # thong bao cho nguoi nhan thuong
                                    $modelStaffNotify->insert(null, $notifyStaffId, 'Thưởng Quản lý kinh doanh nhận tiền từ đơn hàng', null, null, $bonusId, null, null);
                                }
                            }
                        }
                    }
                }

            }
            #NGUOI NHAN DON HANG - thuong cho nguoi nhan don hang
            if ($hFunction->checkCount($dataStaffCreated)) {
                $bonusMoney_created = $this->getBonusMoneyOfBusinessStaffRank($payId);
                if ($bonusMoney_created > 0) {
                    $dataWork = $dataStaffCreated->workInfoActivityOfStaff();
                    if ($hFunction->checkCount($dataWork)) {
                        $workId = $dataWork->workId();
                        # kiem tra da duoc thuong chua - neu chua thi thuong
                        if (!$modelBonus->checkOrderPayBonus($workId, $payId)) {
                            if ($modelBonus->insert($bonusMoney_created, $hFunction->carbonNow(), 'Thưởng Nhân viên kinh doanh nhận tiền đơn hàng', 1, $workId, null, null, $payId)) {
                                $bonusId = $modelBonus->insertGetId();
                                $notifyStaffId = $dataStaffCreated->staffId();
                                $notifyStaffId = (is_int($notifyStaffId)) ? $notifyStaffId : $notifyStaffId[0];
                                # thong bao cho nguoi nhan thuong
                                $modelStaffNotify->insert(null, $notifyStaffId, 'Thưởng Nhân viên kinh doanh nhận tiền đơn hàng', null, null, $bonusId, null, null);
                            }
                        }
                    }
                }
            }
        }
    }
    # kiem tra thuong nguoi quan ly thi cong
    public function checkBonusConstruction($orderId)
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

}
