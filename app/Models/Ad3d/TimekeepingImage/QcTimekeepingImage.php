<?php

namespace App\Models\Ad3d\TimekeepingImage;

use Illuminate\Database\Eloquent\Model;

class QcTimekeepingImage extends Model
{
    protected $table = 'qc_timekeeping_image';
    protected $fillable = ['image_id', 'name', 'created_at', 'timekeeping_id'];
    protected $primaryKey = 'image_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== INSERT && UPDATE ========== ========== ==========
    #---------- Insert ----------
    public function insert($name, $timekeepingId)
    {
        $hFunction = new \Hfunction();
        $modelTimekeepingImage = new QcTimekeepingImage();
        $modelTimekeepingImage->name = $name;
        $modelTimekeepingImage->timekeeping_id = $timekeepingId;
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

    #----------- update ----------
    public function rootPathFullImage()
    {
        return 'public/images/timekeeping-image/full';
    }

    public function rootPathSmallImage()
    {
        return 'public/images/timekeeping-image/small';
    }

    public function updateInfo($imageId, $name)
    {
        return QcTimekeepingImage::where('image_id', $imageId)->update([
            'name' => $name
        ]);
    }

    # delete
    public function actionDelete($imageId = null)
    {
        if (empty($imageId)) $imageId = $this->typeId();
        return QcTimekeepingImage::where('image_id', $imageId)->update(['action' => 0]);
    }

    //thêm hình ảnh
    public function uploadImage($file, $imageName, $size = 500)
    {
        $hFunction = new \Hfunction();
        $pathSmallImage = $this->rootPathSmallImage();
        $pathFullImage = $this->rootPathFullImage();
        if (!is_dir($pathFullImage)) mkdir($pathFullImage);
        if (!is_dir($pathSmallImage)) mkdir($pathSmallImage);
        return $hFunction->uploadSaveByFileName($file,$imageName, $pathSmallImage . '/', $pathFullImage . '/', $size);
    }

    //Xóa ảnh
    public function dropImage($imageName)
    {
        unlink($this->rootPathSmallImage() . '/' . $imageName);
        unlink($this->rootPathFullImage() . '/' . $imageName);
    }

    // get path image
    public function pathSmallImage($image)
    {
        if (empty($image)) {
            return null;
        } else {
            return asset($this->rootPathSmallImage().'/' . $image);
        }
    }

    public function pathFullImage($image)
    {
        if (empty($image)) {
            return null;
        } else {
            return asset($this->rootPathFullImage().'/' . $image);
        }
    }
    #========== ========== ========== RELATION ========== ========== ==========
    #----------- timekeeping ------------
    public function timekeeping()
    {
        return $this->belongsTo('App\Models\Ad3d\Timekeeping\QcTimekeeping', 'timekeeping_id', 'timekeeping_id');
    }

    public function infoOfTimekeeping($timekeepingId)
    {
        return QcTimekeepingImage::where('timekeeping_id',$timekeepingId)->get();
    }

    #============ =========== ============ GET INFO ============= =========== ==========
    public function getInfo($imageId = '', $field = '')
    {
        if (empty($imageId)) {
            return QcTimekeepingImage::get();
        } else {
            $result = QcTimekeepingImage::where('image_id', $imageId)->first();
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
            return QcTimekeepingImage::where('image_id', $objectId)->pluck($column);
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

    public function timekeepingId($imageId = null)
    {
        return $this->pluck('timekeeping_id', $imageId);
    }

    public function createdAt($imageId = null)
    {
        return $this->pluck('created_at', $imageId);
    }


}
