<?php

namespace App\Models\Ad3d\Kpi;

use Illuminate\Database\Eloquent\Model;

class QcKpi extends Model
{
    protected $table = 'qc_kpi';
    protected $fillable = ['kpi_id', 'kpiLimit', 'plusPercent', 'minusPercent', 'description', 'action', 'created_at', 'department_id'];
    protected $primaryKey = 'kpi_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- thêm mới ----------

    // insert
    public function insert($kpiLimit, $plusPercent, $minusPercent, $description, $departmentId)
    {
        $hFunction = new \Hfunction();
        $modelKpi = new QcKpi();
        $modelKpi->kpiLimit = $kpiLimit;
        $modelKpi->plusPercent = $plusPercent;
        $modelKpi->minusPercent = $minusPercent;
        $modelKpi->description = $description;
        $modelKpi->department_id = $departmentId;
        $modelKpi->created_at = $hFunction->createdAt();
        if ($modelKpi->save()) {
            $this->lastId = $modelKpi->kpi_id;
            return true;
        } else {
            return false;
        }
    }

    // get new id after insert
    public function insertGetId()
    {
        return $this->lastId;
    }

    public function checkIdNull($kpiId)
    {
        return (empty($kpiId)) ? $this->kpiId() : $kpiId;
    }

    //xóa
    public function deleteKpi($kpiId = null)
    {
        return QcKpi::where('kpi_id', $this->checkIdNull($kpiId))->delete();
    }
    //========== ========= ========= CAC MOI QUAN HE ========== ========= ==========
    //---------- BO PHAN -----------
    public function department()
    {
        return $this->belongsTo('App\Models\Ad3d\Department\QcDepartment', 'department_id', 'department_id');
    }

    public function getInfoOfDepartment($departmentId)
    {
        return QcKpi::where('department_id', $departmentId)->where('action', 1)->orderBy('kpiLimit', 'ASC')->get();
    }

    //---------- CHI TIET KPI -----------
    public function staffKpi()
    {
        return $this->hasMany('App\Models\Ad3d\StaffKpi\QcStaffKpi', 'kpi_id', 'kpi_id');
    }


    //========= ========== ========== LAY THONG TIN ========== ========== ==========
    public function selectActivityInfo()
    {
        return QcKpi::where('action', 1)->orderBy('kpiLimit', 'ASC')->select('*');
    }

    public function getInfo($kpiId = '', $field = '')
    {
        if (empty($kpiId)) {
            return QcKpi::get();
        } else {
            $result = QcKpi::where('kpi_id', $kpiId)->first();
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
            return QcKpi::where('kpi_id', $objectId)->pluck($column);
        }
    }

    public function kpiId()
    {
        return $this->kpi_id;
    }

    public function kpiLimit($kpiId = null)
    {
        return $this->pluck('kpiLimit', $kpiId);
    }


    public function plusPercent($kpiId = null)
    {

        return $this->pluck('plusPercent', $kpiId);
    }

    public function minusPercent($kpiId = null)
    {

        return $this->pluck('minusPercent', $kpiId);
    }

    public function description($kpiId = null)
    {

        return $this->pluck('description', $kpiId);
    }

    public function action($kpiId = null)
    {
        return $this->pluck('action', $kpiId);
    }

    public function departmentId($kpiId = null)
    {
        return $this->pluck('department_id', $kpiId);
    }

    public function createdAt($kpiId = null)
    {
        return $this->pluck('created_at', $kpiId);
    }

// tổng mẫu tin
    public function totalRecords()
    {
        return QcKpi::count();
    }

    public function lastId()
    {
        $result = QcKpi::orderBy('kpi_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->kpi_id;
    }

    // ----------------- kiem tra thong tin -----------------
    public function existActivityKpiLimit($kpiLimit)
    {
        return QcKpi::where('action', 1)->where('kpiLimit', $kpiLimit)->exists();
    }
    public function existActivityOfDepartment($departmentId, $limit, $plusPercent, $minusPercent)
    {
        return QcKpi::where('action', 1)->where('department_id', $departmentId)->where('kpiLimit', $limit)->where('plusPercent', $plusPercent)->where('minusPercent', $minusPercent)->exists();
    }
}
