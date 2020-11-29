<?php

namespace App\Models\Ad3d\MinusMoney;

use App\Models\Ad3d\CompanyStore\QcCompanyStore;
use App\Models\Ad3d\CompanyStoreCheckReport\QcCompanyStoreCheckReport;
use App\Models\Ad3d\MinusMoneyFeedback\QcMinusMoneyFeedback;
use App\Models\Ad3d\Order\QcOrder;
use App\Models\Ad3d\OrderAllocation\QcOrderAllocation;
use App\Models\Ad3d\OrderBonusBudget\QcOrderBonusBudget;
use App\Models\Ad3d\PunishContent\QcPunishContent;
use App\Models\Ad3d\StaffNotify\QcStaffNotify;
use App\Models\Ad3d\WorkAllocation\QcWorkAllocation;
use Illuminate\Database\Eloquent\Model;

class QcMinusMoney extends Model
{
    protected $table = 'qc_minus_money';
    protected $fillable = ['minus_id', 'money', 'dateMinus', 'reason', 'reasonImage', 'applyStatus', 'cancelStatus', 'action', 'created_at', 'work_id', 'staff_id', 'punish_id', 'orderAllocation_id', 'orderConstruction_id', 'companyStoreCheckReport_id', 'workAllocation_id'];
    protected $primaryKey = 'minus_id';
    public $timestamps = false;
    private $lastId;

    #========== ========== ========== INSERT && UPDATE ========== ========== ==========
    # lay gia tri mac dinh cua ly do phat
    public function getDefaultReason()
    {
        return null;
    }

    # mac dinh anh phat
    public function getDefaultReasonImage()
    {
        return null;
    }

    #  mac dinh co ap dung phat
    public function getDefaultHasApplyStatus()
    {
        return 1;
    }

    #  mac dinh co ap dung
    public function getDefaultNotApplyStatus()
    {
        return 0;
    }

    # mac dinh da huy
    public function getDefaultHasCancelStatus()
    {
        return 1;
    }

    # mac dinh chua huy
    public function getDefaultNotCancelStatus()
    {
        return 0;
    }
    # mac dinh don hang duoc phan cong cong
    public function getDefaultOrderAllocation()
    {
        return null;
    }
    # mac dinh don hang thi cong
    public function getDefaultOrderConstruction()
    {
        return null;
    }
    # mac dinh tien phat
    public function getDefaultMoney()
    {
        return 0;# $money == 0 - phat theo so tien quy dinh san - $money > 0 - nhap truc tiep
    }
    #---------- Insert ----------
    /*
`   $orderAllocationId : quan ly thi cong don hang
    $orderConstructionId
    $companyStoreCheckReportId: mat do nghe
    $workAllocationId => phan viec thi cong san pham
    */
    public function insert($dateMinus, $reason, $workId, $staffId = null, $punishId, $applyStatus = 1, $orderAllocationId = null,
                           $orderConstructionId = null, $companyStoreCheckReportId = null, $workAllocationId = null, $money = 0, $reasonImage = null)
    {
        $hFunction = new \Hfunction();
        $modelOrder = new QcOrder();
        $modelOrderBonusBudget = new QcOrderBonusBudget();
        $modelOrderAllocation = new QcOrderAllocation();
        $modelWorkAllocation = new QcWorkAllocation();
        $modelPunishContent = new QcPunishContent();
        $modelMinusMoney = new QcMinusMoney();
        $modelCompanyStore = new QcCompanyStore();
        $modelCompanyStoreCheckReport = new QcCompanyStoreCheckReport();
        # $money == 0 - phat theo so tien quy dinh san - $money > 0 - nhap truc tiep
        if ($money == $this->getDefaultMoney()) {
            # phat theo noi quy
            if (empty($orderAllocationId) && empty($orderConstructionId) && empty($companyStoreCheckReportId) && empty($workAllocationId)) {
                # tien phat
                $money = $modelPunishContent->money($punishId);
            } else {
                # phat theo gia tri don hang
                if (!empty($orderAllocationId)) {
                    # quan ly thi cong don hang
                    $dataOrderAllocation = $modelOrderAllocation->getInfo($orderAllocationId);
                    $dataOrder = $dataOrderAllocation->orders;
                    $money = (int)$dataOrder->getBonusAndMinusMoneyOfConstructionManage();
                } elseif (!empty($workAllocationId)) {
                    # phat thi cong san pham
                    $productId = $modelWorkAllocation->productId($workAllocationId);
                    $money = (int)$modelOrderBonusBudget->totalBudgetMoneyEachPersonOfConstructionStaffOfProduct($productId);
                } elseif (!empty($orderConstructionId)) {
                    # kinh doanh tre don hang giao khach
                    $money = (int)$modelOrderBonusBudget->totalBudgetMoneyOfConstructionStaff($orderConstructionId);//  - chua phat kinh doanh
                } elseif (!empty($companyStoreCheckReportId)) {
                    $punishIdLostTool = $modelPunishContent->getPunishIdLostPublicTool();
                    $punishIdLostTool = (is_int($punishIdLostTool)) ? $punishIdLostTool : $punishIdLostTool[0];
                    # phat mat do nghe
                    if ($punishIdLostTool == $punishId) {
                        $money = $modelCompanyStore->importPrice($modelCompanyStoreCheckReport->storeId($companyStoreCheckReportId));
                    } else {
                        # tien phat
                        $money = $modelPunishContent->money($punishId);
                    }
                }

            }
        }
        $modelMinusMoney->money = (is_array($money)) ? $money[0] : $money;
        $modelMinusMoney->dateMinus = $dateMinus;
        $modelMinusMoney->reason = $reason;
        $modelMinusMoney->reasonImage = $reasonImage;
        $modelMinusMoney->applyStatus = $applyStatus;
        $modelMinusMoney->work_id = $workId;
        $modelMinusMoney->staff_id = $staffId;
        $modelMinusMoney->punish_id = $punishId;
        $modelMinusMoney->orderAllocation_id = $orderAllocationId;
        $modelMinusMoney->orderConstruction_id = $orderConstructionId;
        $modelMinusMoney->companyStoreCheckReport_id = $companyStoreCheckReportId;
        $modelMinusMoney->workAllocation_id = $workAllocationId;
        $modelMinusMoney->created_at = $hFunction->createdAt();
        if ($modelMinusMoney->save()) {
            $this->lastId = $modelMinusMoney->minus_id;
            return true;
        } else {
            return false;
        }
    }

    //lay id moi them
    public function insertGetId()
    {
        return $this->lastId;
    }

    public function checkNullId($minusId)
    {
        return (empty($minusId)) ? $this->minusId() : $minusId;
    }

    public function rootPathFullImage()
    {
        return 'public/images/minus-money/full';
    }

    public function rootPathSmallImage()
    {
        return 'public/images/minus-money/small';
    }

    //upload image
    public function uploadImage($source_img, $imageName, $resize = 500)
    {
        $hFunction = new \Hfunction();
        $pathSmallImage = $this->rootPathSmallImage();
        $pathFullImage = $this->rootPathFullImage();
        if (!is_dir($pathFullImage)) mkdir($pathFullImage);
        if (!is_dir($pathSmallImage)) mkdir($pathSmallImage);
        return $hFunction->uploadSaveByFileName($source_img, $imageName, $pathSmallImage . '/', $pathFullImage . '/', $resize);
    }

    //drop image
    public function dropImage($imageName)
    {
        $hFunction = new \Hfunction();
        if (!$hFunction->checkEmpty($imageName)) {
            unlink($this->rootPathSmallImage() . '/' . $imageName);
            unlink($this->rootPathFullImage() . '/' . $imageName);
        }
    }

    // get path image
    public function pathSmallImage($image)
    {
        if (empty($image)) {
            return null;
        } else {
            return asset($this->rootPathSmallImage() . '/' . $image);
        }
    }

    public function pathFullImage($image)
    {
        if (empty($image)) {
            return null;
        } else {
            return asset($this->rootPathFullImage() . '/' . $image);
        }
    }

    public function updateInfo($minusId, $money, $datePay, $reason, $punishId)
    {
        return QcMinusMoney::where('minus_id', $minusId)->update(['money' => $money, 'dateMinus' => $datePay, 'reason' => $reason, 'punish_id' => $punishId]);
    }

    public function cancelMinus($minusId = null)
    {
        return QcMinusMoney::where('minus_id', $this->checkNullId($minusId))->update(['cancelStatus' => $this->getDefaultHasCancelStatus(), 'action' => 0]);
    }


    public function deleteInfo($minusId = null)
    {
        return QcMinusMoney::where('minus_id', $this->checkNullId($minusId))->delete();
    }

//---------- phan hoi phat -----------
    public function minusMoneyFeedback()
    {
        return $this->hasOne('App\Models\Ad3d\MinusMoneyFeedback\QcMinusMoneyFeedback', 'minus_id', 'minus_id');
    }

    public function infoMinusMoneyFeedback($minusId = null)
    {
        $modelMinusMoneyFeedback = new QcMinusMoneyFeedback();
        return $modelMinusMoneyFeedback->infoOfMinusMoney($this->checkNullId($minusId));
    }

//---------- nhân viên -----------
    public function staff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staff_id', 'staff_id');
    }

//kiển tra người nhập
    public function checkStaffInput($staffId, $minusId = null)
    {
        return QcMinusMoney::where('staff_id', $staffId)->where('minus_id', $this->checkNullId($minusId))->exists();
    }

//---------- thong bao ban giao don hang moi -----------
    public function staffNotify()
    {
        return $this->hasMany('App\Models\Ad3d\StaffNotify\QcStaffNotify', 'minusMoney_id', 'minusMoney_id');
    }

    public function checkViewedNewMinusMoney($minusMoneyId, $staffId)
    {
        $modelStaffAllocation = new QcStaffNotify();
        return $modelStaffAllocation->checkViewedMinusMoneyOfStaff($staffId, $minusMoneyId);
    }

//----------- làm việc ------------
    public function work()
    {
        return $this->belongsTo('App\Models\Ad3d\Work\QcWork', 'work_id', 'work_id');
    }

    public function infoOfWork($workId)
    {
        return QcMinusMoney::where('work_id', $workId)->orderBy('dateMinus', 'DESC')->get();
    }

    /*public function infoOfWork($workId)
    {
        return QcMinusMoney::where('work_id', $workId)->orderBy('dateMinus', 'DESC')->get();
    }*/

# tong tien phat khong huy trong thang lam viec - tam
    public function totalMoneyOfWork($workId)
    {
        return QcMinusMoney::where('work_id', $workId)->where('cancelStatus', $this->getDefaultNotCancelStatus())->sum('money');
    }

    public function totalMoneyAppliedOfWork($workId)
    {
        return QcMinusMoney::where('work_id', $workId)->where('applyStatus', $this->getDefaultHasApplyStatus())->where('cancelStatus', $this->getDefaultNotCancelStatus())->where('action', 0)->sum('money');
    }

    # tu dong kiem tra tu xac nhan - tu dong ap dung
    public function autoCheckApplyMinusMoneyEndWork($workId)
    {
        return QcMinusMoney::where('work_id', $workId)->where('cancelStatus', $this->getDefaultNotCancelStatus())->where('action', 1)->update(['applyStatus' => $this->getDefaultHasApplyStatus(), 'action' => 0]);
    }

    //---------- quan ly don hang thi cong -----------
    public function orderConstruction()
    {
        return $this->belongsTo('App\Models\Ad3d\Order\QcOrder', 'orderConstruction_id', 'order_id');
    }

    # kiem tra da phat quan ly thi cong tre
    public function checkExistMinusMoneyOrderConstructionLate($orderConstructionId, $workId)
    {
        $modelPunishContent = new QcPunishContent();
        $punishId = $modelPunishContent->getPunishIdOfOrderConstructionLate();
        return QcMinusMoney::where('orderConstruction_id', $orderConstructionId)->where('work_id', $workId)->where('punish_id', $punishId)->exists();
    }

    //---------- ban giao don hang -----------
    public function workAllocation()
    {
        return $this->belongsTo('App\Models\Ad3d\WorkAllocation\QcWorkAllocation', 'workAllocation_id', 'allocation_id');
    }

    public function getInfoOfWorkAllocation($allocationId)
    {
        return QcMinusMoney::where('workAllocation_id', $allocationId)->get();
    }

    # kiem tra da phat thi cong san pham tre
    public function checkExistMinusMoneyWorkAllocationLate($workAllocationId)
    {
        $modelPunishContent = new QcPunishContent();
        $punishId = $modelPunishContent->getPunishIdForWorkAllocationLate();
        return QcMinusMoney::where('workAllocation_id', $workAllocationId)->where('punish_id', $punishId)->exists();
    }

    //---------- ban giao don hang -----------
    public function orderAllocation()
    {
        return $this->belongsTo('App\Models\Ad3d\OrderAllocation\QcOrderAllocation', 'orderAllocation_id', 'allocation_id');
    }

    # kiem tra da phat ban giao don hang thi cong tre
    public function checkExistMinusMoneyOrderAllocationLate($orderAllocationId)
    {
        $modelPunishContent = new QcPunishContent();
        $punishId = $modelPunishContent->getPunishIdForOrderAllocationLate();
        return QcMinusMoney::where('orderAllocation_id', $orderAllocationId)->where('punish_id', $punishId)->exists();
    }

    //---------- bao mat do nghe -----------
    public function companyStoreCheckReport()
    {
        return $this->belongsTo('App\Models\Ad3d\CompanyStoreCheckReport\QcCompanyStoreCheckReport', 'companyStoreCheckReport_id', 'report_id');
    }

    # kiem tra da phat mat do nghe dung chung
    public function checkExistMinusMoneyLostPublicTool($reportId, $workId)
    {
        $modelPunishContent = new QcPunishContent();
        $punishId = $modelPunishContent->getPunishIdLostPublicTool();
        return QcMinusMoney::where('companyStoreCheckReport_id', $reportId)->where('work_id', $workId)->where('punish_id', $punishId)->exists();
    }

    # kiem tra da phat bao cao lam mat do nghe dung chung
    public function checkExistMinusMoneyReportWrongLostTool($reportId, $workId)
    {
        $modelPunishContent = new QcPunishContent();
        $punishId = $modelPunishContent->getPunishIdWrongReportLostTool();
        return QcMinusMoney::where('companyStoreCheckReport_id', $reportId)->where('work_id', $workId)->where('punish_id', $punishId)->exists();
    }

//----------- lý do phạt ------------
    public function punishContent()
    {
        return $this->belongsTo('App\Models\Ad3d\PunishContent\QcPunishContent', 'punish_id', 'punish_id');
    }

# kiem tra phat mat do nghe
    public function checkMinusMoneyLostTool($minusId = null)
    {
        $modelPunishContent = new QcPunishContent();
        # ma ap dung phat trong he thong - phat mat do nghe
        $systemPunishId = $modelPunishContent->getPunishIdLostPublicTool();
        $systemPunishId = (is_int($systemPunishId)) ? $systemPunishId : $systemPunishId[0];
        $checkPunishId = $this->punishId($minusId);
        $checkPunishId = (is_int($checkPunishId)) ? $checkPunishId : $checkPunishId[0];
        return ($systemPunishId == $checkPunishId) ? true : false;
    }

#============ =========== ============ GET INFO ============= =========== ==========
    public function selectInfoHasFilter($listWorkId, $punishId, $dateFilter)
    {
        if (empty($punishId)) {
            return QcMinusMoney::where('dateMinus', 'like', "%$dateFilter%")->whereIn('work_id', $listWorkId)->orderBy('dateMinus', 'DESC')->select('*');
        } else {
            return QcMinusMoney::where('punish_id', $punishId)->where('dateMinus', 'like', "%$dateFilter%")->whereIn('work_id', $listWorkId)->orderBy('dateMinus', 'DESC')->select('*');
        }
    }

    public function totalMoneyHasFilter($listWorkId, $punishId, $dateFilter)
    {
        if (empty($punishId)) {
            return QcMinusMoney::where('cancelStatus', $this->getDefaultNotCancelStatus())->where('dateMinus', 'like', "%$dateFilter%")->whereIn('work_id', $listWorkId)->sum('money');
        } else {
            return QcMinusMoney::where('cancelStatus', $this->getDefaultNotCancelStatus())->where('punish_id', $punishId)->where('dateMinus', 'like', "%$dateFilter%")->whereIn('work_id', $listWorkId)->sum('money');
        }
    }

    public function getInfo($minusId = '', $field = '')
    {
        if (empty($minusId)) {
            return QcMinusMoney::get();
        } else {
            $result = QcMinusMoney::where('minus_id', $minusId)->first();
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
            return QcMinusMoney::where('minus_id', $objectId)->pluck($column);
        }
    }

    public function minusId()
    {
        return $this->minus_id;
    }

    public function money($minusId = null)
    {
        return $this->pluck('money', $minusId);
    }

    public function dateMinus($minusId = null)
    {
        return $this->pluck('dateMinus', $minusId);
    }

    public function reason($minusId = null)
    {
        return $this->pluck('reason', $minusId);
    }

    public function reasonImage($minusId = null)
    {
        return $this->pluck('reasonImage', $minusId);
    }

    public function applyStatus($minusId = null)
    {
        return $this->pluck('applyStatus', $minusId);
    }


    public function cancelStatus($minusId = null)
    {
        return $this->pluck('cancelStatus', $minusId);
    }

    public function action($minusId = null)
    {
        return $this->pluck('action', $minusId);
    }


    public function createdAt($minusId = null)
    {
        return $this->pluck('created_at', $minusId);
    }

    public function workId($minusId = null)
    {
        return $this->pluck('work_id', $minusId);
    }

    public function staffId($minusId = null)
    {
        return $this->pluck('staff_id', $minusId);
    }

    public function punishId($minusId = null)
    {
        return $this->pluck('punish_id', $minusId);
    }

    public function orderAllocationId($minusId = null)
    {
        return $this->pluck('orderAllocation_id', $minusId);
    }

    public function orderConstructionId($minusId = null)
    {
        return $this->pluck('orderConstruction_id', $minusId);
    }

    public function companyStoreCheckReportId($minusId = null)
    {
        return $this->pluck('companyStoreCheckReport_id', $minusId);
    }

    public function workAllocationId($minusId = null)
    {
        return $this->pluck('workAllocation_id', $minusId);
    }

    #========= ======
    # kiem tra co huy phat khong
    public function checkCancelStatus($minusId = null)
    {
        return ($this->cancelStatus($minusId) == $this->getDefaultHasCancelStatus()) ? true : false;
    }

    public function checkEnableApply($minusId = null)
    {
        if ($this->applyStatus($minusId) == $this->getDefaultHasApplyStatus() && $this->cancelStatus() == $this->getDefaultNotCancelStatus()) {
            return true; # ap dung phat
        } else {
            return false; # khong ap dung phat
        }
    }
}
