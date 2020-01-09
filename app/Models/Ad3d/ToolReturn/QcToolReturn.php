<?php

namespace App\Models\Ad3d\ToolReturn;

use Illuminate\Database\Eloquent\Model;

class QcToolReturn extends Model
{
    protected $table = 'qc_tool_return';
    protected $fillable = ['return_id','returnDate', 'created_at', 'returnStaff_id', 'confirmStaff_id'];
    protected $primaryKey = 'return_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- thêm ----------
    public function insert($returnDate, $returnStaffId, $confirmStaffId)
    {
        $hFunction = new \Hfunction();
        $modelToolReturn = new QcToolReturn();
        $modelToolReturn->returnDate = $returnDate;
        $modelToolReturn->returnStaff_id = $returnStaffId;
        $modelToolReturn->confirmStaff_id = $confirmStaffId;
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
        $returnId = (empty($returnId)) ? $this->allocationId() : $returnId;
        return QcToolReturn::where('return_id', $returnId)->delete();
    }
    //========== ========= ========= RELATION ========== ========= ==========
    //---------- nhân viên bàn giao -----------
    public function returnStaff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'returnStaff_id', 'staff_id');
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

    public function createdAt($returnId = null)
    {
        return $this->pluck('created_at', $returnId);
    }

    public function returnStaffId($returnId = null)
    {
        return $this->pluck('returnStaff_id', $returnId);
    }

    public function confirmStaffId($returnId = null)
    {
        return $this->pluck('confirmStaff_id', $returnId);
    }

    // last id
    public function lastId()
    {
        $result = QcToolReturn::orderBy('return_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->return_id;
    }
}
