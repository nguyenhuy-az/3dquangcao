<?php

namespace App\Models\Ad3d\ProductTypeImage;

use Illuminate\Database\Eloquent\Model;

class QcProductTypeImage extends Model
{
    protected $table = 'qc_product_type_image';
    protected $fillable = ['image_id', 'name', 'created_at', 'type_id'];
    protected $primaryKey = 'image_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== INSERT && UPDATE ========== ========== ==========
    #---------- Insert ----------
    public function insert($name, $typeId)
    {
        $hFunction = new \Hfunction();
        $modelProductTypeImage = new QcProductTypeImage();
        $modelProductTypeImage->name = $name;
        $modelProductTypeImage->type_id = $typeId;
        $modelProductTypeImage->created_at = $hFunction->createdAt();
        if ($modelProductTypeImage->save()) {
            $this->lastId = $modelProductTypeImage->image_id;
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
        return 'public/images/product-type-image/full';
    }

    public function rootPathSmallImage()
    {
        return 'public/images/product-type-image/small';
    }

    # xoa
    public function actionDelete($imageId = null)
    {
        $imageId = (empty($imageId)) ? $this->imageId() : $imageId;
            $imageName = $this->name($imageId)[0];
        if (QcProductTypeImage::where('image_id', $imageId)->delete()) {
            $this->dropImage($imageName); # xoa anh
        }
    }

    //thêm hình ?nh
    public function uploadImage($file, $imageName, $size = 500)
    {
        $hFunction = new \Hfunction();
        $pathSmallImage = $this->rootPathSmallImage();
        $pathFullImage = $this->rootPathFullImage();
        if (!is_dir($pathFullImage)) mkdir($pathFullImage);
        if (!is_dir($pathSmallImage)) mkdir($pathSmallImage);
        return $hFunction->uploadSaveByFileName($file, $imageName, $pathSmallImage . '/', $pathFullImage . '/', $size);
    }

    //Xóa ?nh
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
    #========== ========== ========== CAC MOI QUAN HE DU LIEU ========== ========== ==========
    #----------- product - type ------------
    public function productType()
    {
        return $this->belongsTo('App\Models\Ad3d\ProductType\QcProductType', 'type_id', 'type_id');
    }

    public function infoOfProductType($typeIdd)
    {
        return QcProductTypeImage::where('type_id', $typeIdd)->get();
    }

    #============ =========== ============ LAY THONG TIN ============= =========== ==========
    public function getInfo($imageId = '', $field = '')
    {
        if (empty($imageId)) {
            return QcProductTypeImage::get();
        } else {
            $result = QcProductTypeImage::where('image_id', $imageId)->first();
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
            return QcProductTypeImage::where('image_id', $objectId)->pluck($column);
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

    public function typeId($imageId = null)
    {
        return $this->pluck('type_id', $imageId);
    }

    public function createdAt($imageId = null)
    {
        return $this->pluck('created_at', $imageId);
    }
}
