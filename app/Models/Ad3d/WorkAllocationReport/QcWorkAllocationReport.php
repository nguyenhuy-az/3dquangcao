<?php

namespace App\Models\Ad3d\WorkAllocationReport;

use App\Models\Ad3d\TimekeepingProvisionalImage\QcTimekeepingProvisionalImage;
use App\Models\Ad3d\WorkAllocation\QcWorkAllocation;
use App\Models\Ad3d\WorkAllocationReportImage\QcWorkAllocationReportImage;
use Illuminate\Database\Eloquent\Model;

class QcWorkAllocationReport extends Model
{
    protected $table = 'qc_work_allocation_report';
    protected $fillable = ['report_id', 'reportDate', 'content', 'created_at', 'allocation_id'];
    protected $primaryKey = 'report_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= THEM  && CAP NHAT ========== ========= =========
    //---------- them ----------
    public function insert($reportDate, $content, $allocationId)
    {
        $hFunction = new \Hfunction();
        $modelWorkAllocationReport = new QcWorkAllocationReport();
        $modelWorkAllocationReport->reportDate = $reportDate;
        $modelWorkAllocationReport->content = $content;
        $modelWorkAllocationReport->allocation_id = $allocationId;
        $modelWorkAllocationReport->created_at = $hFunction->createdAt();
        if ($modelWorkAllocationReport->save()) {
            $this->lastId = $modelWorkAllocationReport->report_id;
            return true;
        } else {
            return false;
        }
    }

    public function insertGetId()
    {
        return $this->lastId;
    }

    public function checkIdNull($reportId = null)
    {
        return (empty($reportId)) ? $this->reportId() : $reportId;
    }

    public function deleteReport($reportId = null)
    {
        return QcWorkAllocationReport::where('report_id', $this->checkIdNull($reportId))->delete();
    }
    //========== ========= ========= RELATION ========== ========= ==========
    //---------- phan viec -----------
    public function workAllocation()
    {
        return $this->belongsTo('App\Models\Ad3d\WorkAllocation\QcWorkAllocation', 'allocation_id', 'allocation_id');
    }

    public function infoOfWorkAllocation($allocationId, $take = null)
    {
        if (empty($take)) {
            return QcWorkAllocationReport::where('allocation_id', $allocationId)->orderBy('reportDate', 'DESC')->get();
        } else {
            return QcWorkAllocationReport::where('allocation_id', $allocationId)->orderBy('reportDate', 'DESC')->skip(0)->take($take)->get();
        }
    }

    //---------- hinh bao cao truc tiep -----------
    public function workAllocationReportImage()
    {
        return $this->hasMany('App\Models\Ad3d\WorkAllocationReportImage\QcWorkAllocationReportImage', 'report_id', 'report_id');
    }

    public function workAllocationReportImageInfo($reportId = null)
    {
        $modelReportImage = new QcWorkAllocationReportImage();
        return $modelReportImage->infoOfReport((empty($reportId)) ? $this->reportId() : $reportId);
    }

    //---------- hinh bao cao khi bao gio ra -----------
    public function timekeepingProvisionalImage()
    {
        return $this->hasMany('App\Models\Ad3d\TimekeepingProvisionalImage\QcTimekeepingProvisionalImage', 'report_id', 'report_id');
    }

    # anh bao cao thong qua bao gio ra trong ngay
    public function timekeepingProvisionalImageInfo($reportId = null)
    {
        $modelTimekeepingProvisionalImage = new QcTimekeepingProvisionalImage();
        return $modelTimekeepingProvisionalImage->infoOfWorkAllocationReport((empty($reportId)) ? $this->reportId() : $reportId);
    }

    //---------- bao cao thi con của don hang -----------
    public function infoAllOfOrder($orderId)
    {
        $modelWorkAllocation = new QcWorkAllocation();
        $listWorkAllocationId = $modelWorkAllocation->listIdOfOrder($orderId);
        return QcWorkAllocationReport::whereIn('allocation_id', $listWorkAllocationId)->orderBy('reportDate', 'DESC')->get();
    }

    public function listIdOfOrder($orderId)
    {  // thong tin phan cong tren san pham của dơn hang
        $modelWorkAllocation = new QcWorkAllocation();
        $listWorkAllocationId = $modelWorkAllocation->listIdOfOrder($orderId);
        return QcWorkAllocationReport::whereIn('allocation_id', $listWorkAllocationId)->orderBy('reportDate', 'DESC')->pluck('report_id');
    }

    //========= ========== ========== lay thong tin ========== ========== ==========
    public function selectInfoOfListWorkAllocation($listAllocationId, $dateFilter = null) # tat ca
    {
        if (empty($dateFilter)) {
            return QcWorkAllocationReport::whereIn('allocation_id', $listAllocationId)->orderBy('reportDate', 'DESC')->select('*');
        } else {
            return QcWorkAllocationReport::whereIn('allocation_id', $listAllocationId)->where('reportDate', 'like', "%$dateFilter%")->orderBy('reportDate', 'DESC')->select('*');
        }
    }

    public function getInfo($reportId = '', $field = '')
    {
        if (empty($reportId)) {
            return QcWorkAllocationReport::get();
        } else {
            $result = QcWorkAllocationReport::where('report_id', $reportId)->first();
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
            return QcWorkAllocationReport::where('report_id', $objectId)->pluck($column);
        }
    }

    public function reportId()
    {
        return $this->report_id;
    }

    public function reportDate($reportId = null)
    {
        return $this->pluck('reportDate', $reportId);
    }

    public function content($reportId = null)
    {
        return $this->pluck('content', $reportId);
    }


    public function createdAt($reportId = null)
    {
        return $this->pluck('created_at', $reportId);
    }

    public function allocationId($reportId = null)
    {
        return $this->pluck('allocation_id', $reportId);
    }

    // last id
    public function lastId()
    {
        $result = QcWorkAllocationReport::orderBy('report_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->report_id;
    }
}
