<?php

namespace App\Models\Ad3d\StaffKpiRegister;

use Illuminate\Database\Eloquent\Model;

class QcStaffKpiRegister extends Model
{
    protected $table = 'qc_staff_kpi_register';
    protected $fillable = ['register_id', 'registerDate', 'applyDate', 'confirmDate', 'confirmStatus', 'agreeStatus', 'action', 'created_at', 'kpi_id', 'staff_id', 'confirmStaff_id'];
    protected $primaryKey = 'register_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- thêm ----------
    public function insert($registerDate, $kpiId, $staffId)
    {
        $hFunction = new \Hfunction();
        $modelStaffKpiRegister = new QcStaffKpiRegister();
        $modelStaffKpiRegister->registerDate = $registerDate;
        $modelStaffKpiRegister->kpi_id = $kpiId;
        $modelStaffKpiRegister->staff_id = $staffId;
        $modelStaffKpiRegister->created_at = $hFunction->createdAt();
        if ($modelStaffKpiRegister->save()) {
            $this->lastId = $modelStaffKpiRegister->register_id;
            return true;
        } else {
            return false;
        }
    }

    public function insertGetId()
    {
        return $this->lastId;
    }

    public function checkIdNull($registerId)
    {
        return (empty($registerId)) ? $this->staffKpiId() : $registerId;
    }

    // xac nhan chay kpi
    public function confirmKpi($registerId, $applyDate, $agreeStatus, $confirmStaffId)
    {
        $hFunction = new \Hfunction();
        return QcStaffKpiRegister::where('register_id', $registerId)->update([
            'applyDate' => $applyDate,
            'agreeStatus' => $agreeStatus,
            'confirmStaff_id' => $confirmStaffId,
            'confirmDate' => $hFunction->carbonNow(),
        ]);
    }

    public function deleteRegister($registerId = null)
    {
        $registerId = (empty($registerId)) ? $this->staffKpiId() : $registerId;
        return QcStaffKpiRegister::where('register_id', $registerId)->delete();
    }
    //========== ========= ========= CAC MOI QUAN HE DU LIEU ========== ========= ==========
    //---------- KPI-----------
    public function kpi()
    {
        return $this->belongsTo('App\Models\Ad3d\Kpi\QcKpi', 'kpi_id', 'kpi_id');
    }

    //---------- NHAN VIEN DANG KY -----------
    public function staff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staff_id', 'staff_id');
    }

    //---------- NHAN VIEN XAC NHAN -----------
    # nha vien xac nhan
    public function confirmStaff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'confirmStaff_id', 'staff_id');
    }

    //========= ========== ========== LAY THONG TIN ========== ========== ==========
    public function getInfo($registerId = '', $field = '')
    {
        if (empty($registerId)) {
            return QcStaffKpiRegister::get();
        } else {
            $result = QcStaffKpiRegister::where('register_id', $registerId)->first();
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
            return QcStaffKpiRegister::where('register_id', $objectId)->pluck($column);
        }
    }

    public function registerId()
    {
        return $this->register_id;
    }

    public function registerDate($registerId = null)
    {
        return $this->pluck('registerDate', $registerId);
    }


    public function confirmDate($registerId = null)
    {

        return $this->pluck('confirmDate', $registerId);
    }

    public function confirmStatus($registerId = null)
    {

        return $this->pluck('confirmStatus', $registerId);
    }

    public function agreeStatus($registerId = null)
    {

        return $this->pluck('agreeStatus', $registerId);
    }

    public function action($registerId = null)
    {

        return $this->pluck('action', $registerId);
    }

    public function createdAt($registerId = null)
    {
        return $this->pluck('created_at', $registerId);
    }

    public function kpiId($registerId = null)
    {
        return $this->pluck('kpi_id', $registerId);
    }

    public function staffId($registerId = null)
    {
        return $this->pluck('staff_id', $registerId);
    }

    public function confirmStaffId($registerId = null)
    {
        return $this->pluck('confirmStaff_id', $registerId);
    }

    // last id
    public function lastId()
    {
        $result = QcStaffKpiRegister::orderBy('register_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->register_id;
    }
}
