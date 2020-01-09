<?php

namespace App\Models\Ad3d\TimekeepingProvisionalImage;

use App\Models\Ad3d\WorkAllocationReport\QcWorkAllocationReport;
use Illuminate\Database\Eloquent\Model;
use League\Flysystem\File;

class QcTimekeepingProvisionalImage extends Model
{
    protected $table = 'qc_timekeeping_provisional_image';
    protected $fillable = ['image_id', 'name', 'created_at', 'timekeeping_provisional_id', 'report_id'];
    protected $primaryKey = 'image_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== THEM  && CAP NHAT ========== ========== ==========
    #---------- them moi ----------
    public function insert($name, $timekeepingProvisionalId, $reportId = null)
    {
        $hFunction = new \Hfunction();
        $modelTimekeepingImage = new QcTimekeepingProvisionalImage();
        $modelTimekeepingImage->name = $name;
        $modelTimekeepingImage->timekeeping_provisional_id = $timekeepingProvisionalId;
        $modelTimekeepingImage->report_id = $reportId;
        $modelTimekeepingImage->created_at = $hFunction->createdAt();
        if ($modelTimekeepingImage->save()) {
            $this->lastId = $modelTimekeepingImage->image_id;
            return true;
        } else {
            return false;
        }
    }

    # get new id
    public function insertGetId()
    {
        return $this->lastId;
    }

    #----------- cap nhat thong tin ----------
    public function rootPathFullImage()
    {
        return 'public/images/timekeeping-provisional-image/full';
    }

    public function rootPathSmallImage()
    {
        return 'public/images/timekeeping-provisional-image/small';
    }

    public function updateInfo($imageId, $name)
    {
        return QcTimekeepingProvisionalImage::where('image_id', $imageId)->update([
            'name' => $name
        ]);
    }

    # xóa 1 hình ảnh
    public function actionDelete($imageId = null)
    {
        $modelWorkAllocationReport = new QcWorkAllocationReport();
        if (empty($imageId)) $imageId = $this->imageId();
        $imageName = $this->name($imageId)[0];
        $reportId = $this->reportId();
        if (QcTimekeepingProvisionalImage::where('image_id', $imageId)->delete()) {
            $this->dropImage($imageName);
            if (!empty($reportId)) $modelWorkAllocationReport->deleteReport($reportId); # xoa bao cao
        }
    }

    //upload image
    public function uploadImage($source_img, $imageName, $resize = 500)
    {
        $hFunction = new \Hfunction();
        $pathSmallImage = $this->rootPathSmallImage();
        $pathFullImage = $this->rootPathFullImage();
        if (!is_dir($pathFullImage)) mkdir($pathFullImage);
        if (!is_dir($pathSmallImage)) mkdir($pathSmallImage);
        return $hFunction->uploadSaveByFileName($source_img, $imageName, $pathSmallImage . '/', $pathFullImage . '/', $resize);
    }

    //drop image
    public function dropImage($imageName)
    {
        unlink($this->rootPathSmallImage() . '/' . $imageName);
        unlink($this->rootPathFullImage() . '/' . $imageName);
    }
    #========== ========== ========== CAC MOI QUAN HE DU LIEU ========== ========== ==========
    #----------- timekeeping-provisional ------------
    public function timekeepingProvisional()
    {
        return $this->belongsTo('App\Models\Ad3d\TimekeepingProvisional\QcTimekeepingProvisional', 'timekeeping_provisional_id', 'timekeeping_provisional_id');
    }

    public function infoOfTimekeepingProvisional($timekeepingProvisionalId)
    {
        return QcTimekeepingProvisionalImage::where('timekeeping_provisional_id', $timekeepingProvisionalId)->get();
    }

    #----------- work-allocation - report ------------
    public function workAllocationReport()
    {
        return $this->belongsTo('App\Models\Ad3d\WorkAllocationReport\QcWorkAllocationReport', 'report_id', 'report_id');
    }

    public function infoOfWorkAllocationReport($reportId)
    {
        return QcTimekeepingProvisionalImage::where('report_id', $reportId)->get();
    }

    #============ =========== ============ GET INFO ============= =========== ==========
    public function getInfo($imageId = '', $field = '')
    {
        if (empty($imageId)) {
            return QcTimekeepingProvisionalImage::get();
        } else {
            $result = QcTimekeepingProvisionalImage::where('image_id', $imageId)->first();
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
            return QcTimekeepingProvisionalImage::where('image_id', $objectId)->pluck($column);
        }
    }

    #----------- DEPARTMENT INFO -------------
    public function imageId()
    {
        return $this->image_id;
    }

    public function name($imageId = null)
    {
        return $this->pluck('name', $imageId);
    }

    public function reportId($imageId = null)
    {
        return $this->pluck('report_id', $imageId);
    }

    public function createdAt($imageId = null)
    {
        return $this->pluck('created_at', $imageId);
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
