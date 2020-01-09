<?php

namespace App\Models\Ad3d\StaffKpi;

use Illuminate\Database\Eloquent\Model;

class QcStaffKpi extends Model
{
    protected $table = 'qc_staff_kpi';
    protected $fillable = ['staffKpi_id', 'registerDate', 'applyDate', 'confirmDate', 'confirmStatus', 'agreeStatus', 'action', 'created_at', 'kpi_id', 'staff_id', 'confirmStaff_id'];
    protected $primaryKey = 'staffKpi_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- thêm ----------
    public function insert($registerDate, $kpiId, $staffId)
    {
        $hFunction = new \Hfunction();
        $modelStaffKpi = new QcStaffKpi();
        $modelStaffKpi->registerDate = $registerDate;
        $modelStaffKpi->kpi_id = $kpiId;
        $modelStaffKpi->staff_id = $staffId;
        $modelStaffKpi->created_at = $hFunction->createdAt();
        if ($modelStaffKpi->save()) {
            $this->lastId = $modelStaffKpi->staffKpi_id;
            return true;
        } else {
            return false;
        }
    }

    public function insertGetId()
    {
        return $this->lastId;
    }

    public function checkIdNull($staffKpiId)
    {
        return (empty($staffKpiId)) ? $this->staffKpiId() : $staffKpiId;
    }

    // xac nhan chay kpi
    public function confirmKpi($staffKpiId, $applyDate, $agreeStatus, $confirmStaffId)
    {
        $hFunction = new \Hfunction();
        return QcStaffKpi::where('staffKpi_id', $staffKpiId)->update([
            'applyDate' => $applyDate,
            'agreeStatus' => $agreeStatus,
            'confirmStaff_id' => $confirmStaffId,
            'confirmDate' => $hFunction->carbonNow(),
        ]);
    }

    public function deleteStaffKpi($staffKpiId = null)
    {
        $staffKpiId = (empty($staffKpiId)) ? $this->staffKpiId() : $staffKpiId;
        return QcStaffKpi::where('staffKpi_id', $staffKpiId)->delete();
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

    public function infoActivityOfStaff($staffId)
    {
        return QcStaffKpi::where('staff_id', $staffId)->where('action', 1)->first();
    }

    //---------- NHAN VIEN XAC NHAN -----------
    # nha vien xac nhan
    public function confirmStaff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'confirmStaff_id', 'staff_id');
    }

    //---------- DON HANG -----------
    public function order()
    {
        return $this->hasMany('App\Models\Ad3d\Order\QcOrder', 'staffKpi_id', 'staffKpi_id');
    }

    //========= ========== ========== LAY THONG TIN ========== ========== ==========
    public function getInfo($staffKpiId = '', $field = '')
    {
        if (empty($staffKpiId)) {
            return QcStaffKpi::get();
        } else {
            $result = QcStaffKpi::where('staffKpi_id', $staffKpiId)->first();
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
            return QcStaffKpi::where('staffKpi_id', $objectId)->pluck($column);
        }
    }

    public function staffKpiId()
    {
        return $this->staffKpi_id;
    }

    public function registerDate($staffKpiId = null)
    {
        return $this->pluck('registerDate', $staffKpiId);
    }


    public function confirmDate($staffKpiId = null)
    {

        return $this->pluck('confirmDate', $staffKpiId);
    }

    public function confirmStatus($staffKpiId = null)
    {

        return $this->pluck('confirmStatus', $staffKpiId);
    }

    public function agreeStatus($staffKpiId = null)
    {

        return $this->pluck('agreeStatus', $staffKpiId);
    }

    public function action($staffKpiId = null)
    {

        return $this->pluck('action', $staffKpiId);
    }

    public function createdAt($staffKpiId = null)
    {
        return $this->pluck('created_at', $staffKpiId);
    }

    public function kpiId($staffKpiId = null)
    {
        return $this->pluck('kpi_id', $staffKpiId);
    }

    public function staffId($staffKpiId = null)
    {
        return $this->pluck('staff_id', $staffKpiId);
    }

    public function confirmStaffId($staffKpiId = null)
    {
        return $this->pluck('confirmStaff_id', $staffKpiId);
    }

    // last id
    public function lastId()
    {
        $result = QcStaffKpi::orderBy('staffKpi_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->staffKpi_id;
    }

}
