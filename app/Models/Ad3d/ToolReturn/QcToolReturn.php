<?php

namespace App\Models\Ad3d\ToolReturn;

use App\Models\Ad3d\ToolReturnDetail\QcToolReturnDetail;
use Illuminate\Database\Eloquent\Model;

class QcToolReturn extends Model
{
    protected $table = 'qc_tool_return';
    protected $fillable = ['return_id', 'returnDate', 'confirmStatus', 'confirmDate', 'created_at', 'work_id', 'confirmStaff_id'];
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

    public function checkNullId($id = null)
    {
        return (empty($id)) ? $this->returnId() : $id;
    }

    public function deleteReturn($returnId = null)
    {
        $returnId = (empty($returnId)) ? $this->returnId() : $returnId;
        return QcToolReturn::where('return_id', $returnId)->delete();
    }

    # xac nhan
    public function updateConfirm($returnId)
    {
        $hFunction = new \Hfunction();
        return QcToolReturn::where('return_id', $returnId)->update(['confirmStatus' => 1, 'confirmDate' => $hFunction->carbonNow()]);
    }
    //========== ========= ========= RELATION ========== ========= ==========
    //---------- nhân viên tra -----------
    public function companyStaffWork()
    {
        return $this->belongsTo('App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork', 'work_id', 'work_id');
    }

    # lay thong tin tra cua 1 NV
    public function infoOfWork($workId)
    {
        return QcToolReturn::where('work_id', $workId)->orderBy('returnDate', 'DESC')->get();
    }

    # lay thong tin tra cua nhieu NV
    public function infoOfListWork($listWorkId, $confirmStatus = 100)
    {
        # 100 - lay tat ca thong
        if ($confirmStatus == 100) {
            return QcToolReturn::whereIn('work_id', $listWorkId)->orderBy('returnDate', 'DESC')->get();
        } else {
            return QcToolReturn::whereIn('work_id', $listWorkId)->where('confirmStatus', $confirmStatus)->orderBy('returnDate', 'DESC')->get();
        }
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

    //---------- nhan vien xac nhan -----------
    public function confirmStaff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'confirmStaff_id', 'staff_id');
    }

    //---------- chi tiet ban giao -----------
    public function toolReturnDetail()
    {
        return $this->hasMany('App\Models\Ad3d\ToolReturnDetail\QcToolReturnDetail', 'return_id', 'return_id');
    }

    public function toolReturnDetailInfo($returnId = null)
    {
        $modelToolReturnDetail = new QcToolReturnDetail();
        return $modelToolReturnDetail->getInfoOfReturn($this->checkNullId($returnId));
    }

    # tong so dung cu bao abstract
    public function totalAmountStoreReturn($returnId = null)
    {
        $modelToolReturnDetail = new QcToolReturnDetail();
        return $modelToolReturnDetail->totalAmountOfReturn($this->checkNullId($returnId));
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

    # ========= ============ Kiem tra thong tin ============= ==============
    public function checkConfirm($returnId = null)
    {
        return ($this->confirmStatus($returnId) == 0) ? false : true;
    }
}
