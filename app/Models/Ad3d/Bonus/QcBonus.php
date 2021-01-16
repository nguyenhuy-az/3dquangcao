<?php

namespace App\Models\Ad3d\Bonus;

use App\Models\Ad3d\StaffNotify\QcStaffNotify;
use Illuminate\Database\Eloquent\Model;

class QcBonus extends Model
{
    protected $table = 'qc_bonus';
    protected $fillable = ['bonus_id', 'money', 'bonusDate', 'note', 'applyStatus', 'cancelStatus', 'cancelImage', 'cancelNote', 'action', 'created_at', 'work_id', 'orderAllocation_id', 'orderConstruction_id', 'orderPay_id', 'workAllocation_id'];
    protected $primaryKey = 'bonus_id';
    public $timestamps = false;
    private $lastId;

    #========== ========== ========== INSERT && UPDATE ========== ========== ==========
    # trang thai mac dinh ap dung thuong
    public function getHasApplyStatus() # cu
    {
        return 1;
    }

    # trang thai mac dinh ap dung thuong
    public function getNotApplyStatus() # cu
    {
        return 0;
    }

    # mac dinh co ap dung
    public function getDefaultHasApply()
    {
        return 1;
    }

    # trang thai mac dinh ap dung thuong
    public function getDefaultNotApply()
    {
        return 0;
    }

    # mac dinh da huy
    public function getDefaultHasCancel()
    {
        return 1;
    }

    # trang thai mac dinh ap dung thuong
    public function getDefaultNotCancel()
    {
        return 0;
    }

    # mac dinh anh huy
    public function getDefaultCancelImage()
    {
        return null;
    }

    # mac dinh ghi chu huy
    public function getDefaultCancelNot()
    {
        return null;
    }

    # mac dinh dang hoat dong
    public function getDefaultHasAction()
    {
        return 1;
    }

    # mac dinh khong con hoat dong
    public function getDefaultNotAction()
    {
        return 0;
    }

    # mac dinh ma don hang - thuong quan ly don hang
    public function getDefaultOrderAllocationId()
    {
        return null;
    }

    # mac dinh ma don hang - thuong quan ly thi cong don hang
    public function getDefaultOrderConstructionId()
    {
        return null;
    }

    # mac dinh ma thu tien don hang
    public function getDefaultOrderPayId()
    {
        return null;
    }

    # mac dinh ma phan viec - thi cong san pham
    public function getDefaultWorkAllocationId()
    {
        return null;
    }

    #---------- Insert ----------
    /*
     * them khi thanh toan don hang - QcOrderPay
     * them khi xet thương don hang - QcOrderBonusBudget
     * */
    public function insert($money, $bonusDate, $note, $applyStatus, $workId, $orderAllocationId = null, $orderConstructionId = null, $orderPayId = null, $workAllocationId = null)
    {
        $hFunction = new \Hfunction();
        $modelBonus = new QcBonus();
        $modelBonus->money = $money;
        $modelBonus->bonusDate = $bonusDate;
        $modelBonus->note = $note;
        $modelBonus->applyStatus = $applyStatus;
        $modelBonus->work_id = $workId;
        $modelBonus->orderAllocation_id = $orderAllocationId;
        $modelBonus->orderConstruction_id = $orderConstructionId;
        $modelBonus->orderPay_id = $orderPayId;
        $modelBonus->workAllocation_id = $workAllocationId;
        $modelBonus->created_at = $hFunction->createdAt();
        if ($modelBonus->save()) {
            $this->lastId = $modelBonus->bonus_id;
            return true;
        } else {
            return false;
        }
    }

    public function insertGetId()
    {
        return $this->lastId;
    }

    public function checkNullId($bonusId)
    {
        $hFunction = new \Hfunction();
        return ($hFunction->checkEmpty($bonusId)) ? $this->payId() : $bonusId;
    }

    public function rootPathFullImage()
    {
        return 'public/images/bonus/cancel/full';
    }

    public function rootPathSmallImage()
    {
        return 'public/images/bonus/cancel/small';
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
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($image)) {
            return null;
        } else {
            return asset($this->rootPathSmallImage() . '/' . $image);
        }
    }

    public function pathFullImage($image)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($image)) {
            return null;
        } else {
            return asset($this->rootPathFullImage() . '/' . $image);
        }
    }

    # huy thuong
    public function cancelBonus($bonusId, $cancelNote, $image)
    {
        return QcBonus::where('bonus_id', $bonusId)->update([
            'cancelStatus' => $this->getDefaultHasCancel(),
            'cancelNote' => $cancelNote,
            'cancelImage' => $image,
            'action' => $this->getDefaultNotAction()
        ]);
    }

    # ---------- Thanh toan dơn hNG -----------------
    public function orderPay()
    {
        return $this->belongsTo('App\Models\Ad3d\OrderPay\QcOrderPay', 'orderPay_id', 'pay_id');
    }

    # kiem nv da duoc thuong khi thanh toan don hang hay chua
    public function checkOrderPayBonus($workId, $orderPayId)
    {
        return QcBonus::where('work_id', $workId)->where('orderPay_id', $orderPayId)->exists();
    }


    # ---------- trien khai don hang thi cong -----------------
    public function orderConstruction()
    {
        return $this->belongsTo('App\Models\Ad3d\Order\QcOrder', 'orderConstruction_id', 'order_id');
    }

    public function checkExistBonusWorkOfOrderConstruction($workId, $orderConstructionId)
    {
        return QcBonus::where('work_id', $workId)->where('orderConstruction_id', $orderConstructionId)->exists();
    }

    //----------- làm việc ------------
    public function work()
    {
        return $this->belongsTo('App\Models\Ad3d\Work\QcWork', 'work_id', 'work_id');
    }

    public function infoOfWork($workId)
    {
        return QcBonus::where('work_id', $workId)->orderBy('bonusDate', 'DESC')->get();
    }

    # tong tin phat cua 1 bang uong
    public function totalMoneyOfWork($workId)
    {
        return QcBonus::where('work_id', $workId)->where('cancelStatus', $this->getDefaultNotCancel())->sum('money');
    }

    public function totalMoneyApplyOfWork($workId)
    {
        return QcBonus::where('work_id', $workId)->where('applyStatus', $this->getDefaultHasApply())->sum('money');
    }

    public function totalMoneyNotApplyOfWork($workId)
    {
        return QcBonus::where('work_id', $workId)->where('applyStatus', $this->getDefaultNotApply())->sum('money');
    }

    # tong tien da ap dung phat
    public function totalMoneyAppliedOfWork($workId)
    {
        return QcBonus::where('work_id', $workId)->where('applyStatus', $this->getDefaultHasApply())->where('cancelStatus', $this->getDefaultNotCancel())->where('action', $this->getDefaultNotAction())->sum('money');
    }

    # neu khong huy thi se mac dinh la dong y
    public function autoCheckApplyBonusEndWork($workId)
    {
        return QcBonus::where('work_id', $workId)->where('cancelStatus', $this->getDefaultNotCancel())->where('action', $this->getDefaultHasAction())->update(
            [
                'applyStatus' => $this->getDefaultHasApply(),
                'action' => $this->getDefaultNotAction()
            ]);
    }

    //---------- thong bao ban giao don hang moi ----------- -------
    public function orderAllocation()
    {
        return $this->belongsTo('App\Models\Ad3d\OrderAllocation\QcOrderAllocation', 'orderAllocation_id', 'allocation_id');
    }

    public function checkExistBonusWorkOfOrderAllocation($workId, $orderAllocationId)
    {
        return QcBonus::where('work_id', $workId)->where('orderAllocation_id', $orderAllocationId)->exists();
    }

    //---------- thong bao ban giao don hang moi -----------
    public function workAllocation()
    {
        return $this->belongsTo('App\Models\Ad3d\WorkAllocation\QcWorkAllocation', 'workAllocation_id', 'allocation_id');
    }

    public function checkExistBonusWorkOfWorkAllocation($workId, $workAllocationId)
    {
        return QcBonus::where('work_id', $workId)->where('workAllocation_id', $workAllocationId)->exists();
    }

    //---------- thong bao ban giao don hang moi -----------
    public function staffNotify()
    {
        return $this->hasMany('App\Models\Ad3d\StaffNotify\QcStaffNotify', 'bonus_id', 'bonus_id');
    }

    public function checkViewedNewBonus($bonusId, $staffId)
    {
        $modelStaffAllocation = new QcStaffNotify();
        return $modelStaffAllocation->checkViewedBonusOfStaff($staffId, $bonusId);
    }

    #============ =========== ============ GET INFO ============= =========== ==========
    public function selectInfoHasFilter($listWorkId, $dateFilter = null)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($dateFilter)) {
            return QcBonus::whereIn('work_id', $listWorkId)->orderBy('bonusDate', 'DESC')->select('*');
        } else {
            return QcBonus::whereIn('work_id', $listWorkId)->where('bonusDate', 'like', "%$dateFilter%")->orderBy('bonusDate', 'DESC')->select('*');
        }
    }

    # lay thong tin thuong duoc ap dung theo danh sach ma cham cong
    public function getInfoHasApplyFromListWorkId($listWorkId, $dateFilter = null)
    {
        $hFunction = new \Hfunction();
        # co ap dung
        $hasApply = $this->getDefaultHasApply();
        # khong huy
        $notCancel = $this->getDefaultNotCancel();
        if ($hFunction->checkEmpty($dateFilter)) {
            return QcBonus::whereIn('work_id', $listWorkId)->where('applyStatus', $hasApply)->where('cancelStatus', $notCancel)->orderBy('bonusDate', 'DESC')->get();
        } else {
            return QcBonus::whereIn('work_id', $listWorkId)->where('bonusDate', 'like', "%$dateFilter%")->where('applyStatus', $hasApply)->where('cancelStatus', $notCancel)->orderBy('bonusDate', 'DESC')->get();
        }
    }

    # tong tien phat theo danh sach ma bang cham cong - khong huy
    public function totalMoneyHasFilter($listWorkId, $dateFilter)
    {
        $hFunction = new \Hfunction();
        # khong huy
        $notCancel = $this->getDefaultNotCancel();
        if ($hFunction->checkEmpty($dateFilter)) {
            return QcBonus::whereIn('work_id', $listWorkId)->where('cancelStatus', $notCancel)->sum('money');
        } else {
            return QcBonus::whereIn('work_id', $listWorkId)->where('cancelStatus', $notCancel)->where('bonusDate', 'like', "%$dateFilter%")->sum('money');
        }
    }

    public function getInfo($bonusId = '', $field = '')
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($bonusId)) {
            return QcBonus::get();
        } else {
            $result = QcBonus::where('bonus_id', $bonusId)->first();
            if ($hFunction->checkEmpty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }

    public function pluck($column, $objectId = null)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($objectId)) {
            return $this->$column;
        } else {
            return QcBonus::where('bonus_id', $objectId)->pluck($column)[0];
        }
    }

    public function bonusId()
    {
        return $this->bonus_id;
    }

    public function money($bonusId = null)
    {
        return $this->pluck('money', $bonusId);
    }

    public function bonusDate($bonusId = null)
    {
        return $this->pluck('bonusDate', $bonusId);
    }

    public function note($bonusId = null)
    {
        return $this->pluck('note', $bonusId);
    }

    public function applyStatus($bonusId = null)
    {
        return $this->pluck('applyStatus', $bonusId);
    }

    public function cancelStatus($bonusId = null)
    {
        return $this->pluck('cancelStatus', $bonusId);
    }

    public function cancelNote($bonusId = null)
    {
        return $this->pluck('cancelNote', $bonusId);
    }

    public function cancelImage($bonusId = null)
    {
        return $this->pluck('cancelImage', $bonusId);
    }

    public function action($bonusId = null)
    {
        return $this->pluck('action', $bonusId);
    }


    public function createdAt($bonusId = null)
    {
        return $this->pluck('created_at', $bonusId);
    }

    public function workId($bonusId = null)
    {
        return $this->pluck('work_id', $bonusId);
    }

    public function orderAllocationId($bonusId = null)
    {
        return $this->pluck('orderAllocation_id', $bonusId);
    }

    public function orderConstructionId($bonusId = null)
    {
        return $this->pluck('orderConstruction_id', $bonusId);
    }

    public function orderPayId($bonusId = null)
    {
        return $this->pluck('orderPay_id', $bonusId);
    }

    public function workAllocationId($bonusId = null)
    {
        return $this->pluck('workAllocation_id', $bonusId);
    }

    #========= ============= KIEM TRA THONG TIN ============= =============
    public function checkCancelStatus($bonusId = null)
    {
        return ($this->cancelStatus($bonusId) == $this->getDefaultHasCancel()) ? true : false;
    }

    # kiem tra co ap dung phat
    public function checkEnableApply($bonusId = null)
    {
        if ($this->applyStatus($bonusId) == $this->getDefaultHasApply() && $this->cancelStatus() == $this->getDefaultNotCancel()) {
            return true; # ap dung phat
        } else {
            return false; # khong ap dung phat
        }
    }

}
