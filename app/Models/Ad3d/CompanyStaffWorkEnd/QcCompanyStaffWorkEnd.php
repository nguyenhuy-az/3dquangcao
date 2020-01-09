<?php

namespace App\Models\Ad3d\CompanyStaffWorkEnd;

use Illuminate\Database\Eloquent\Model;

class QcCompanyStaffWorkEnd extends Model
{
    protected $table = 'qc_company_staff_work_end';
    protected $fillable = ['end_id', 'endDate', 'endReason', 'action', 'created_at', 'work_id', 'staff_id'];
    protected $primaryKey = 'end_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- thêm ----------
    public function insert($endDate, $endReason, $workId, $staffId)
    {
        $hFunction = new \Hfunction();
        $modelCompantStaffWorkEnd = new QcCompanyStaffWorkEnd();
        $modelCompantStaffWorkEnd->endDate = $endDate;
        $modelCompantStaffWorkEnd->endReason = $endReason;
        $modelCompantStaffWorkEnd->work_id = $workId;
        $modelCompantStaffWorkEnd->staff_id = $staffId;
        $modelCompantStaffWorkEnd->created_at = $hFunction->createdAt();
        if ($modelCompantStaffWorkEnd->save()) {
            $this->lastId = $modelCompantStaffWorkEnd->end_id;
            return true;
        } else {
            return false;
        }
    }

    public function insertGetId()
    {
        return $this->lastId;
    }

    //========== ========= ========= quan he cac bang ========== ========= ==========
    //----------  làm vi?c -----------
    public function companyStaffWork()
    {
        return $this->belongsTo('App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork', 'work_id', 'work_id');
    }

    public function infoOfWork($workId)
    {
        return QcCompanyStaffWorkEnd::where('work_id', $workId)->get();
    }

    //========= ========== ========== l?y thông tin ========== ========== ==========
    public function getInfo($endId = '', $field = '')
    {
        if (empty($endId)) {
            return QcCompanyStaffWorkEnd::get();
        } else {
            $result = QcCompanyStaffWorkEnd::where('end_id', $endId)->first();
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
            return QcCompanyStaffWorkEnd::where('end_id', $objectId)->pluck($column);
        }
    }

    public function endId()
    {
        return $this->end_id;
    }

    public function endDate($endId = null)
    {
        return $this->pluck('endDate', $endId);
    }

    public function endReason($endId = null)
    {
        return $this->pluck('endReason', $endId);
    }

    public function createdAt($endId = null)
    {
        return $this->pluck('created_at', $endId);
    }

    public function workId($endId = null)
    {
        return $this->pluck('work_id', $endId);
    }

    public function staffId($endId = null)
    {
        return $this->pluck('staff_id', $endId);
    }

    // last id
    public function lastId()
    {
        $result = QcCompanyStaffWorkEnd::orderBy('end_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->end_id;
    }
}
