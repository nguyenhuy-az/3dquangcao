<?php

namespace App\Models\Ad3d\OrderBonusBudget;

use App\Models\Ad3d\BonusDepartment\QcBonusDepartment;
use App\Models\Ad3d\Department\QcDepartment;
use App\Models\Ad3d\Order\QcOrder;
use App\Models\Ad3d\Rank\QcRank;
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
}
