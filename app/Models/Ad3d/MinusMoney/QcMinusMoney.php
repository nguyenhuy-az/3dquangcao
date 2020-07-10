<?php

namespace App\Models\Ad3d\MinusMoney;

use App\Models\Ad3d\Order\QcOrder;
use App\Models\Ad3d\OrderAllocation\QcOrderAllocation;
use App\Models\Ad3d\PunishContent\QcPunishContent;
use App\Models\Ad3d\StaffNotify\QcStaffNotify;
use Illuminate\Database\Eloquent\Model;

class QcMinusMoney extends Model
{
    protected $table = 'qc_minus_money';
    protected $fillable = ['minus_id', 'money', 'dateMinus', 'reason', 'feedbackContent', 'feedbackImage', 'applyStatus', 'cancelStatus', 'action', 'created_at', 'work_id', 'staff_id', 'punish_id', 'orderAllocation_id', 'orderConstruction_id'];
    protected $primaryKey = 'minus_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== INSERT && UPDATE ========== ========== ==========
    #---------- Insert ----------
    public function insert($dateMinus, $reason, $workId, $staffId = null, $punishId, $applyStatus = 1, $orderAllocationId = null, $orderConstructionId = null)
    {
        $hFunction = new \Hfunction();
        $modelOrder = new QcOrder();
        $modelOrderAllocation = new QcOrderAllocation();
        $modelPunishContent = new QcPunishContent();
        $modelMinusMoney = new QcMinusMoney();
        $money = 0;
        if (empty($orderAllocationId) && empty($orderConstructionId)) {  # phat theo noi quy
            # tien phat
            $money = $modelPunishContent->money($punishId)[0];
        } else {
            # phat theo gia tri don han
            if (!empty($orderAllocationId)) {# phat truong thi cong
                $dataOrderAllocation = $modelOrderAllocation->getInfo($orderAllocationId);
                $dataOrder = $dataOrderAllocation->orders;
                $money = (int)$dataOrder->getMinusMoneyOrderAllocationLate();
            } elseif (!empty($orderConstructionId)) { # phat quan ly thi cong
                $money = (int)$modelOrder->getBonusAndMinusMoneyOfManageRank($orderConstructionId);
            }

        }
        $modelMinusMoney->money = $money;
        $modelMinusMoney->dateMinus = $dateMinus;
        $modelMinusMoney->reason = $reason;
        $modelMinusMoney->applyStatus = $applyStatus;
        $modelMinusMoney->work_id = $workId;
        $modelMinusMoney->staff_id = $staffId;
        $modelMinusMoney->punish_id = $punishId;
        $modelMinusMoney->orderAllocation_id = $orderAllocationId;
        $modelMinusMoney->orderConstruction_id = $orderConstructionId;
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

    # cap nhat phan hoi
    public function updateFeedback($minusId, $feedbackContent, $feedbackImage)
    {
        return QcMinusMoney::where('minus_id', $minusId)->update(['feedbackContent' => $feedbackContent, 'feedbackImage' => $feedbackImage]);
    }

    public function cancelFeedback($minusId = null)
    {
        $feedbackImage = $this->feedbackImage($this->checkNullId($minusId))[0];
        QcMinusMoney::where('minus_id', $minusId)->update(['feedbackContent' => null, 'feedbackImage' => null]);
        if (!empty($feedbackImage)) {
            $this->dropImage($feedbackImage);
        }
    }

    public function updateInfo($minusId, $money, $datePay, $reason, $punishId)
    {
        return QcMinusMoney::where('minus_id', $minusId)->update(['money' => $money, 'dateMinus' => $datePay, 'reason' => $reason, 'punish_id' => $punishId]);
    }

    public function cancelMinus($minusId = null)
    {
        return QcMinusMoney::where('minus_id', $this->checkNullId($minusId))->update(['cancelStatus' => 1, 'action' => 0]);
    }


    public function deleteInfo($minusId = null)
    {
        return QcMinusMoney::where('minus_id', $this->checkNullId($minusId))->delete();
    }

    # hinh anh
    public function rootPathFullImage()
    {
        return 'public/images/minus-money/full';
    }

    public function rootPathSmallImage()
    {
        return 'public/images/minus-money/small';
    }

    # xóa 1 hình ảnh
    public function deleteImage($minusId = null)
    {
        $imageName = $this->feedbackImage($minusId)[0];
        if (QcMinusMoney::where('minus_id', $minusId)->update(['image' => null])) {
            $this->dropImage($imageName);
        }
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
        if (file_exists($this->rootPathSmallImage() . '/' . $imageName)) unlink($this->rootPathSmallImage() . '/' . $imageName);
        if (file_exists($this->rootPathFullImage() . '/' . $imageName)) unlink($this->rootPathFullImage() . '/' . $imageName);
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

    //---------- nhân viên -----------
    public function staff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staff_id', 'staff_id');
    }

    //kiển tra người nhập
    public function checkStaffInput($staffId, $minusId = null)
    {
        return (QcMinusMoney::where('staff_id', $staffId)->where('minus_id', $this->checkNullId($minusId))->count() > 0) ? true : false;
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
        return QcMinusMoney::where('work_id', $workId)->where('cancelStatus', 0)->sum('money');
    }

    public function totalMoneyAppliedOfWork($workId)
    {
        return QcMinusMoney::where('work_id', $workId)->where('applyStatus', 1)->where('cancelStatus', 0)->where('action', 0)->sum('money');
    }

    public function autoCheckApplyMinusMoneyEndWork($workId)
    {
        return QcMinusMoney::where('work_id', $workId)->where('cancelStatus', 0)->where('action', 1)->update(['applyStatus' => 1, 'action' => 0]);
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
    public function orderAllocation()
    {
        return $this->belongsTo('App\Models\Ad3d\OrderAllocation\QcOrderAllocation', 'orderAllocation_id', 'allocation_id');
    }

    # kiem tra da phat thi cong tre
    public function checkExistMinusMoneyAllocationLate($orderAllocationId)
    {
        $modelPunishContent = new QcPunishContent();
        $punishId = $modelPunishContent->getPunishIdOfOrderAllocationLate();
        return QcMinusMoney::where('orderAllocation_id', $orderAllocationId)->where('punish_id', $punishId)->exists();
    }

    //----------- lý do phạt ------------
    public function punishContent()
    {
        return $this->belongsTo('App\Models\Ad3d\PunishContent\QcPunishContent', 'punish_id', 'punish_id');
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
            return QcMinusMoney::where('cancelStatus', 0)->where('dateMinus', 'like', "%$dateFilter%")->whereIn('work_id', $listWorkId)->sum('money');
        } else {
            return QcMinusMoney::where('cancelStatus', 0)->where('punish_id', $punishId)->where('dateMinus', 'like', "%$dateFilter%")->whereIn('work_id', $listWorkId)->sum('money');
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

    public function feedbackContent($minusId = null)
    {
        return $this->pluck('feedbackContent', $minusId);
    }

    public function feedbackImage($minusId = null)
    {
        return $this->pluck('feedbackImage', $minusId);
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


    #========= ======
    public function checkCancelStatus($minusId = null)
    {
        return ($this->cancelStatus($minusId) == 1) ? true : false;
    }

    public function checkEnableApply($minusId = null)
    {
        if ($this->applyStatus($minusId) == 1 && $this->cancelStatus() == 0) {
            return true; # ap dung phat
        } else {
            return false; # khong ap dung phat
        }
    }
}
