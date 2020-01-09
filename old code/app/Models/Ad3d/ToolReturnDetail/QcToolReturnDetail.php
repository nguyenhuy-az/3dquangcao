<?php

namespace App\Models\Ad3d\ToolReturnDetail;

use Illuminate\Database\Eloquent\Model;

class QcToolReturnDetail extends Model
{
    protected $table = 'qc_tool_return_detail';
    protected $fillable = ['detail_id', 'amount', 'useStatus', 'created_at', 'tool_id', 'return_id'];
    protected $primaryKey = 'detail_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- thêm ----------
    public function insert($amount, $useStatus, $toolId, $returnId)
    {
        $hFunction = new \Hfunction();
        $modelToolAllocationDetail = new QcToolReturnDetail();
        $modelToolAllocationDetail->useStatus = $useStatus;
        $modelToolAllocationDetail->tool_id = $toolId;
        $modelToolAllocationDetail->return_id = $returnId;
        $modelToolAllocationDetail->created_at = $hFunction->createdAt();
        if ($modelToolAllocationDetail->save()) {
            $this->lastId = $modelToolAllocationDetail->detail_id;
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
        return QcToolReturnDetail::where('detail_id', $detailId)->delete();
    }
    //========== ========= ========= RELATION ========== ========= ==========
    //---------- công c? -----------
    public function tool()
    {
        return $this->belongsTo('App\Models\Ad3d\Tool\QcTools', 'tool_id', 'tool_id');
    }

    // s? l??ng ?ã bàn giao c?a d?ng c?
    public function amountReturnOfTool($toolId)
    {
        return QcToolReturnDetail::where('tool_id',$toolId)->sum('amount');
    }

    //---------- phieu ban giao -----------
    public function toolReturn()
    {
        return $this->belongsTo('App\Models\Ad3d\ToolReturn\QcToolReturn', 'return_id', 'return_id');
    }

    //========= ========== ========== l?y thông tin ========== ========== ==========
    public function getInfo($detailId = '', $field = '')
    {
        if (empty($detailId)) {
            return QcToolReturnDetail::get();
        } else {
            $result = QcToolReturnDetail::where('detail_id', $detailId)->first();
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
            return QcToolReturnDetail::where('detail_id', $objectId)->pluck($column);
        }
    }

    public function detailId()
    {
        return $this->detail_id;
    }

    public function amount($detailId = null)
    {
        return $this->pluck('amount', $detailId);
    }

    public function createdAt($detailId = null)
    {
        return $this->pluck('created_at', $detailId);
    }

    public function toolId($detailId = null)
    {
        return $this->pluck('tool_id', $detailId);
    }

    public function returnId($detailId = null)
    {
        return $this->pluck('return_id', $detailId);
    }

    // last id
    public function lastId()
    {
        $result = QcToolReturnDetail::orderBy('detail_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->detail_id;
    }
}
