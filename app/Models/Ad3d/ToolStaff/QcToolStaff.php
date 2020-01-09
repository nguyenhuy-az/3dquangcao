<?php

namespace App\Models\Ad3d\ToolStaff;

use Illuminate\Database\Eloquent\Model;

class QcToolStaff extends Model
{
    protected $table = 'qc_tool_staff';
    protected $fillable = ['detail_id','amount', 'addDate', 'created_at', 'tool_id', 'staff_id'];
    protected $primaryKey = 'detail_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- thêm ----------
    public function insert($amount, $addDate, $toolId, $staffId)
    {
        $hFunction = new \Hfunction();
        $modelToolStaff = new QcToolStaff();
        $modelToolStaff->amount = $amount;
        $modelToolStaff->addDate = $addDate;
        $modelToolStaff->tool_id = $toolId;
        $modelToolStaff->staff_id = $staffId;
        $modelToolStaff->created_at = $hFunction->createdAt();
        if ($modelToolStaff->save()) {
            $this->lastId = $modelToolStaff->detail_id;
            return true;
        } else {
            return false;
        }
    }

    public function insertGetId()
    {
        return $this->lastId;
    }

    public function deleteDetail($detailId = null)
    {
        $detailId = (empty($detailId)) ? $this->detailId() : $detailId;
        return QcToolStaff::where('detail_id', $detailId)->delete();
    }
    //========== ========= ========= RELATION ========== ========= ==========
    //---------- công c? -----------
    public function tool()
    {
        return $this->belongsTo('App\Models\Ad3d\Tool\QcTools', 'tool_id', 'tool_id');
    }

    //---------- nhân viên -----------
    public function confirmStaff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staff_id', 'staff_id');
    }
    //========= ========== ========== l?y thông tin ========== ========== ==========
    public function getInfo($detailId = '', $field = '')
    {
        if (empty($detailId)) {
            return QcToolStaff::get();
        } else {
            $result = QcToolStaff::where('detail_id', $detailId)->first();
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
            return QcToolStaff::where('detail_id', $objectId)->pluck($column);
        }
    }

    public function detailId()
    {
        return $this->detail_id;
    }


    public function createdAt($detailId = null)
    {
        return $this->pluck('created_at', $detailId);
    }

    public function toolId($detailId = null)
    {
        return $this->pluck('tool_id', $detailId);
    }

    public function staffId($detailId = null)
    {
        return $this->pluck('staff_id', $detailId);
    }

    // last id
    public function lastId()
    {
        $result = QcToolStaff::orderBy('detail_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->detail_id;
    }
}
