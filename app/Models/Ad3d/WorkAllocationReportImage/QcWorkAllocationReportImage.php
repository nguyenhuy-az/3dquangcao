<?php

namespace App\Models\Ad3d\WorkAllocationReportImage;

use App\Models\Ad3d\Product\QcProduct;
use App\Models\Ad3d\WorkAllocation\QcWorkAllocation;
use App\Models\Ad3d\WorkAllocationReport\QcWorkAllocationReport;
use Illuminate\Database\Eloquent\Model;
use League\Flysystem\File;

class QcWorkAllocationReportImage extends Model
{
    protected $table = 'qc_work_allocation_report_image';
    protected $fillable = ['image_id', 'name', 'created_at', 'report_id'];
    protected $primaryKey = 'image_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- them ----------
    public function insert($name, $reportId)
    {
        $hFunction = new \Hfunction();
        $modelWorkAllocationReportImage = new QcWorkAllocationReportImage();
        $modelWorkAllocationReportImage->name = $name;
        $modelWorkAllocationReportImage->report_id = $reportId;
        $modelWorkAllocationReportImage->created_at = $hFunction->createdAt();
        if ($modelWorkAllocationReportImage->save()) {
            $this->lastId = $modelWorkAllocationReportImage->image_id;
            return true;
        } else {
            return false;
        }
    }

    public function insertGetId()
    {
        return $this->lastId;
    }

    public function rootPathFullImage()
    {
        return 'public/images/work-allocation-report-image/full';
    }

    public function rootPathSmallImage()
    {
        return 'public/images/work-allocation-report-image/small';
    }

    public function uploadImage($source_img, $imageName, $resize = 500)
    {
        $hFunction = new \Hfunction();
        $pathSmallImage = $this->rootPathSmallImage();
        $pathFullImage = $this->rootPathFullImage();
        if (!is_dir($pathFullImage)) mkdir($pathFullImage);
        if (!is_dir($pathSmallImage)) mkdir($pathSmallImage);
        return $hFunction->uploadSaveByFileName($source_img, $imageName, $pathSmallImage . '/', $pathFullImage . '/', $resize);
    }

    public function deleteImage($imageId = null)
    {
        if (empty($imageId)) $imageId = $this->imageId();
        $imageName = $this->name($imageId)[0];
        if (QcWorkAllocationReportImage::where('image_id', $imageId)->delete()) {
            $this->dropImage($imageName);
        }
    }

    //drop image
    public function dropImage($imageName)
    {
        unlink($this->rootPathSmallImage() . '/' . $imageName);
        unlink($this->rootPathFullImage() . '/' . $imageName);
    }
    //========== ========= ========= RELATION ========== ========= ==========
    //---------- phan viec -----------
    public function workAllocationReport()
    {
        return $this->belongsTo('App\Models\Ad3d\WorkAllocationReport\QcWorkAllocationReport', 'report_id', 'report_id');
    }

    public function infoOfReport($reportId)
    {
        return QcWorkAllocationReportImage::where('report_id', $reportId)->get();
    }

    //========= ========== ==========  lay thong tin ========== ========== ==========
    //---------- hinh anh bao cao thi cong của don hang -----------
    public function infoAllOfOrder($orderId,$take, $orderByCreated = 'DESC')
    {
        $modelWorkAllocationReport = new QcWorkAllocationReport();
        $listWorkAllocationReportId = $modelWorkAllocationReport->listIdOfOrder($orderId);
        return QcWorkAllocationReportImage::whereIn('report_id', $listWorkAllocationReportId)->skip(0)->take($take)->orderBy('created_at', $orderByCreated)->get();
    }
    public function listIdOfOrder($orderId)
    {
        $modelWorkAllocationReport = new QcWorkAllocationReport();
        $listWorkAllocationReportId = $modelWorkAllocationReport->listIdOfOrder($orderId);
        return QcWorkAllocationReportImage::whereIn('allocation_id', $listWorkAllocationReportId)->pluck('image_id');
    }


    public function getInfo($imageId = '', $field = '')
    {
        if (empty($imageId)) {
            return QcWorkAllocationReportImage::get();
        } else {
            $result = QcWorkAllocationReportImage::where('image_id', $imageId)->first();
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
            return QcWorkAllocationReportImage::where('image_id', $objectId)->pluck($column);
        }
    }

    public function imageId()
    {
        return $this->image_id;
    }

    public function name($imageId = null)
    {
        return $this->pluck('name', $imageId);
    }

    public function createdAt($imageId = null)
    {
        return $this->pluck('created_at', $imageId);
    }

    public function reportId($imageId = null)
    {
        return $this->pluck('report_id', $imageId);
    }

    // last id
    public function lastId()
    {
        $result = QcWorkAllocationReportImage::orderBy('image_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->image_id;
    }

    // get path image
    public function pathSmallImage($image)
    {
        if (empty($image)) {
            return null;
        } else {
            return asset($this->rootPathSmallImage() . '/' . $image);
        }
    }

    public function pathFullImage($image)
    {
        if (empty($image)) {
            return null;
        } else {
            return asset($this->rootPathFullImage() . '/' . $image);
        }
    }
}
