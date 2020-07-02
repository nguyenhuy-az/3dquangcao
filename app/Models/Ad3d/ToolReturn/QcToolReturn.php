<?php

namespace App\Models\Ad3d\ToolReturn;

use Illuminate\Database\Eloquent\Model;

class QcToolReturn extends Model
{
    protected $table = 'qc_tool_return';
    protected $fillable = ['return_id','returnDate','confirmStatus','confirmDate', 'created_at', 'work_id', 'confirmStaff_id'];
    protected $primaryKey = 'return_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- thêm ----------
    public function insert($workId)
    {
        $hFunction = new \Hfunction();
        $modelToolReturn = new QcToolReturn();
        $modelToolReturn->returnDate = $hFunction->carbonNow();
        $modelToolReturn->work_id = $workId;
        $modelToolReturn->created_at = $hFunction->createdAt();
        if ($modelToolReturn->save()) {
            $this->lastId = $modelToolReturn->return_id;
            return true;
        } else {
            return false;
        }
    }

    public function insertGetId()
    {
        return $this->lastId;
    }

    public function deleteReturn($returnId = null)
    {
        $returnId = (empty($returnId)) ? $this->returnId() : $returnId;
        return QcToolReturn::where('return_id', $returnId)->delete();
    }
    //========== ========= ========= RELATION ========== ========= ==========
    //---------- nhân viên tra -----------
    public function companyStaffWork()
    {
        return $this->belongsTo('App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork', 'work_id', 'work_id');
    }

    # lay thong tin tra
    public function infoOfWork($workId)
    {

        return QcToolReturn::where('work_id', $workId)->orderBy('returnDate', 'DESC')->get();
    }

    # danh sach ma ban giao
    public function listIdOfWork($workId)
    {
        return QcToolReturn::where('work_id', $workId)->orderBy('returnDate', 'DESC')->pluck('return_id');
    }

    //---------- thong tin giao -----------
    public function work()
    {
        return $this->belongsTo('App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork', 'work_id', 'work_id');
    }

    //---------- nhân viên nhận -----------
    public function confirmStaff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'confirmStaff_id', 'staff_id');
    }

    //========= ========== ========== lấy thông tin ========== ========== ==========
    public function getInfo($returnId = '', $field = '')
    {
        if (empty($returnId)) {
            return QcToolReturn::get();
        } else {
            $result = QcToolReturn::where('return_id', $returnId)->first();
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
            return QcToolReturn::where('return_id', $objectId)->pluck($column);
        }
    }

    public function returnId()
    {
        return $this->return_id;
    }

    public function returnDate($returnId = null)
    {

        return $this->pluck('returnDate', $returnId);
    }

    public function confirmStatus($returnId = null)
    {

        return $this->pluck('confirmStatus', $returnId);
    }

    public function confirmDate($returnId = null)
    {

        return $this->pluck('confirmDate', $returnId);
    }

    public function workId($returnId = null)
    {
        return $this->pluck('work_id', $returnId);
    }

    public function confirmStaffId($returnId = null)
    {
        return $this->pluck('confirmStaff_id', $returnId);
    }
    public function createdAt($returnId = null)
    {
        return $this->pluck('created_at', $returnId);
    }

    // last id
    public function lastId()
    {
        $result = QcToolReturn::orderBy('return_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->return_id;
    }
}
