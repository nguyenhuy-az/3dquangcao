<?php

namespace App\Models\Ad3d\WorkAllocationFinish;

use App\Models\Ad3d\WorkAllocation\QcWorkAllocation;
use Illuminate\Database\Eloquent\Model;

class QcWorkAllocationFinish extends Model
{
    protected $table = 'qc_work_allocation_finish';
    protected $fillable = ['finish_id', 'finishDate', 'finishStatus', 'finishLevel', 'finishReason', 'noted', 'created_at', 'allocation_id'];
    protected $primaryKey = 'finish_id';
    public $timestamps = false;

    private $lastId;

    # mac dinh da hoan thanh
    public function getDefaultHasFinish()
    {
        return 1;
    }

    # mac dinh khong hoan thanh
    public function getDefaultNotFinish()
    {
        return 0;
    }

    # mac dinh hoan thanh dung han
    public function getDefaultHasFinishLevel()
    {
        return 0;
    }

    # mac dinh hoan trưoc han han
    public function getDefaultBeforeFinishLevel()
    {
        return 1;
    }

    # mac dinh hoan thanh tre
    public function getDefaultLateFinishLevel()
    {
        return 2;
    }

    # mac dinh tu bao hoan thanh
    public function getDefaultStaffReportFinish()
    {
        return 0;
    }

    # mac dinh quan ly huy phan cong
    public function getDefaultCancelAllocationFinish()
    {
        return 2;
    }

    # mac dinh he thong huy
    public function getDefaultSystemFinish()
    {
        return 1;
    }

    #mac dinh chu thich
    public function getDefaultNote()
    {
        return null;
    }
    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- them ----------
    public function insert($finishDate, $finishStatus, $finishLevel, $finishReason, $noted, $allocationId)
    {
        $hFunction = new \Hfunction();
        $modelWorkAllocationFinish = new QcWorkAllocationFinish();
        $modelWorkAllocationFinish->finishDate = $finishDate;
        $modelWorkAllocationFinish->finishStatus = $finishStatus; # trang thai khi ket thuc 1 - hoan thanh / 0 - khong hoan thanh
        $modelWorkAllocationFinish->finishLevel = $finishLevel; # muc do hoan thanh # 0 - dung han / 1 - som / 2 tre
        $modelWorkAllocationFinish->finishReason = $finishReason; # lnguyen nhan ket thuc 0 - tu bao / 1- he thong huy / 2 - he thong phan sai
        $modelWorkAllocationFinish->noted = $noted;
        $modelWorkAllocationFinish->allocation_id = $allocationId;
        $modelWorkAllocationFinish->created_at = $hFunction->createdAt();
        if ($modelWorkAllocationFinish->save()) {
            $this->lastId = $modelWorkAllocationFinish->finish_id;
            return true;
        } else {
            return false;
        }
    }

    public function insertGetId()
    {
        return $this->lastId;
    }

    //========== ========= ========= RELATION ========== ========= ==========
    //---------- phan viec -----------
    public function workAllocation()
    {
        return $this->belongsTo('App\Models\Ad3d\WorkAllocation\QcWorkAllocation', 'allocation_id', 'allocation_id');
    }

    public function infoOfAllocation($allocationId)
    {
        return QcWorkAllocationFinish::where('allocation_id', $allocationId)->first();
    }

    # kiem tra thi cong co hoan thanh chua
    public function checkFinishOfAllocation($allocationId)
    {
        $dataWorkAllocationFinish = $this->infoOfAllocation($allocationId);
        if (count($dataWorkAllocationFinish) > 0) {
            return ($dataWorkAllocationFinish->finishStatus() == $this->getDefaultNotFinish()) ? false : true;
        } else {
            return false;
        }
    }

    # quan ly huy phan cong
    public function checkSystemCancelOfAllocation($allocationId)
    {
        $dataWorkAllocation = $this->infoOfAllocation($allocationId);
        if (count($dataWorkAllocation) > 0) {
            return ($dataWorkAllocation->finishReason() == $this->getDefaultSystemFinish()) ? true : false;
        } else {
            return false;
        }
    }

    public function listAllocationId()
    {
        return QcWorkAllocationFinish::select('*')->pluck('allocation_id');
    }

    //========= ========== ========== l?y th�ng tin ========== ========== ==========
    public function getInfo($finishId = '', $field = '')
    {
        if (empty($finishId)) {
            return QcWorkAllocationFinish::get();
        } else {
            $result = QcWorkAllocationFinish::where('finish_id', $finishId)->first();
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
            return QcWorkAllocationFinish::where('finish_id', $objectId)->pluck($column)[0];
        }
    }

    public function finishId()
    {
        return $this->finish_id;
    }

    public function finishDate($finishId = null)
    {
        return $this->pluck('finishDate', $finishId);
    }

    public function finishStatus($finishId = null)
    {
        return $this->pluck('finishStatus', $finishId);
    }

    public function createdAt($finishId = null)
    {
        return $this->pluck('created_at', $finishId);
    }

    public function finishLevel($finishId = null)
    {
        return $this->pluck('finishLevel', $finishId);
    }

    public function finishReason($finishId = null)
    {
        return $this->pluck('finishReason', $finishId);
    }

    public function noted($finishId = null)
    {
        return $this->pluck('noted', $finishId);
    }

    public function allocationId($finishId = null)
    {
        return $this->pluck('allocation_id', $finishId);
    }

    // last id
    public function lastId()
    {
        $result = QcWorkAllocationFinish::orderBy('finish_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->finish_id;
    }

    # kiem tra co hoan thanh khong
    public function checkFinishStatus($finishId = null)
    {
        return ($this->finishStatus($finishId) == $this->getDefaultHasFinish()) ? true : false;
    }
    # kiem tra hoan thanh truoc han
    public function checkFinishSoon($finishId = null)
    {
        return ($this->finishLevel($finishId) == $this->getDefaultBeforeFinishLevel()) ? true : false;
    }

    # kiem tra bi tre
    public function checkFinishLate($finishId = null)
    {
        $modelWorkAllocation = new QcWorkAllocation();
        $finishDate = $this->finishDate($finishId);
        $receiveDeadline = $modelWorkAllocation->receiveDeadline($this->allocationId($finishId));
        if ($finishDate > $receiveDeadline) {
            return true;
        } else {
            return false;
        }
    }

    // he thong huy
    public function checkSystemCancel($finishId = null)
    {
        return ($this->finishReason($finishId) == $this->getDefaultSystemFinish()) ? true : false;
    }
}
