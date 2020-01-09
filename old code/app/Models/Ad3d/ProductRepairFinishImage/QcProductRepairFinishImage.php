<?php

namespace App\Models\Ad3d\ProductRepairFinishImage;

use Illuminate\Database\Eloquent\Model;
use League\Flysystem\File;

class QcProductRepairFinishImage extends Model
{
    protected $table = 'qc_product_repair_finish_image';
    protected $fillable = ['image_id', 'name', 'created_at', 'finish_id'];
    protected $primaryKey = 'image_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- thêm ----------
    public function insert($name, $finishId)
    {
        $hFunction = new \Hfunction();
        $modelProductRepairFinishImage = new QcProductRepairFinishImage();
        $modelProductRepairFinishImage->name = $name;
        $modelProductRepairFinishImage->finish_id = $finishId;
        $modelProductRepairFinishImage->created_at = $hFunction->createdAt();
        if ($modelProductRepairFinishImage->save()) {
            $this->lastId = $modelProductRepairFinishImage->image_id;
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
        return 'public/images/product-repair-finish-image/full';
    }

    public function rootPathSmallImage()
    {
        return 'public/images/product-repair-finish-image/small';
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

    public function deleteImage($imageId=null)
    {
        if (empty($imageId)) $imageId = $this->imageId();
        $imageName = $this->name($imageId)[0];
        if (QcProductRepairFinishImage::where('image_id', $imageId)->delete()) {
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
    public function productRepairFinish()
    {
        return $this->belongsTo('App\Models\Ad3d\ProductRepairFinish\QcProductRepairFinishImage', 'finish_id', 'finish_id');
    }

    public function infoOfFinish($finishId)
    {
        return QcProductRepairFinishImage::where('finish_id', $finishId)->get();
    }

    //========= ========== ========== l?y thông tin ========== ========== ==========
    public function getInfo($imageId = '', $field = '')
    {
        if (empty($imageId)) {
            return QcProductRepairFinishImage::get();
        } else {
            $result = QcProductRepairFinishImage::where('image_id', $imageId)->first();
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
            return QcProductRepairFinishImage::where('image_id', $objectId)->pluck($column);
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

    public function finishId($imageId = null)
    {
        return $this->pluck('finish_id', $imageId);
    }

    // last id
    public function lastId()
    {
        $result = QcProductRepairFinishImage::orderBy('image_id', 'DESC')->first();
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
