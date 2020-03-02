<?php

namespace App\Models\Ad3d\OrderImage;

use Illuminate\Database\Eloquent\Model;

class QcOrderImage extends Model
{
    protected $table = 'qc_order_image';
    protected $fillable = ['image_id', 'image', 'action', 'created_at', 'order_id', 'designStaff_id'];
    protected $primaryKey = 'image_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== THEM  - SỬA ========== ========== ==========
    #---------- Them ----------
    public function insert($image, $orderId, $staffId)
    {
        $hFunction = new \Hfunction();
        $modelOrderImage = new QcOrderImage();
        $modelOrderImage->image = $image;
        $modelOrderImage->order_id = $orderId;
        $modelOrderImage->designStaff_id = $staffId;
        $modelOrderImage->created_at = $hFunction->createdAt();
        if ($modelOrderImage->save()) {
            $this->lastId = $modelOrderImage->image_id;
            return true;
        } else {
            return false;
        }
    }

    # lay Id mơi them
    public function insertGetId()
    {
        return $this->lastId;
    }

//kiem tra ID
    public function checkIdNull($id = null)
    {
        return (empty($id)) ? $this->imageId() : $id;
    }

    #----------- update ----------
    public function rootPathFullImage()
    {
        return 'public/images/order-image/full';
    }

    public function rootPathSmallImage()
    {
        return 'public/images/order-image/small';
    }

    # xoa
    public function actionDelete($imageId = null)
    {
        return QcOrderImage::where('image_id', $this->checkIdNull($imageId))->update(['action'=> 0]);
    }

    //them anh thie ke
    public function uploadImage($file, $imageName, $size = 500)
    {
        $hFunction = new \Hfunction();
        $pathSmallImage = $this->rootPathSmallImage();
        $pathFullImage = $this->rootPathFullImage();
        if (!is_dir($pathFullImage)) mkdir($pathFullImage);
        if (!is_dir($pathSmallImage)) mkdir($pathSmallImage);
        return $hFunction->uploadSaveByFileName($file, $imageName, $pathSmallImage . '/', $pathFullImage . '/', $size);
    }

    //xoa anh thiet ke
    public function dropImage($imageName)
    {
        unlink($this->rootPathSmallImage() . '/' . $imageName);
        unlink($this->rootPathFullImage() . '/' . $imageName);
    }

    // duong dan hinh anh
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
    #----------- don hang  ------------
    public function order()
    {
        return $this->belongsTo('App\Models\Ad3d\Order\QcOrder', 'order_id', 'order_id');
    }

    # tat ca thiet ke
    public function infoAllOfOrder($orderId)
    {
        return QcOrderImage::where('order_id', $orderId)->orderBy('image_id', 'DESC')->get();
    }

    # thiet ke dang hoat dong
    public function infoActivityOfOrder($orderId)
    {
        return QcOrderImage::where('order_id', $orderId)->where('action', 1)->orderBy('image_id', 'DESC')->get();
    }


    public function infoLastOfOrder($orderId)
    {
        return QcOrderImage::where('order_id', $orderId)->orderBy('product_id', 'DESC')->first();
    }

    public function totalDesignOfOrder($orderId)
    {
        return QcOrderImage::where('order_id', $orderId)->count();
    }

    //---------- Nhan vien thiet ke -----------
    public function designStaff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staff_id', 'staff_id');
    }

    #============ =========== ============ LAY THONG TIN ============= =========== ==========

    public function getInfo($imageId = '', $field = '')
    {
        if (empty($imageId)) {
            return QcOrderImage::get();
        } else {
            $result = QcOrderImage::where('image_id', $imageId)->first();
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
            return QcOrderImage::where('image_id', $objectId)->pluck($column);
        }
    }

    #----------- DEPARTMENT INFO -------------
    public function imageId()
    {
        return $this->image_id;
    }

    public function image($imageId = null)
    {
        return $this->pluck('image', $imageId);
    }

    public function orderId($imageId = null)
    {
        return $this->pluck('order_id', $imageId);
    }

    public function designStaffId($imageId = null)
    {
        return $this->pluck('designStaff_id', $imageId);
    }

    public function createdAt($imageId = null)
    {
        return $this->pluck('created_at', $imageId);
    }

    public function action($imageId = null)
    {
        return $this->pluck('action', $imageId);
    }
}
