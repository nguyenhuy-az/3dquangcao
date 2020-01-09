<?php

namespace App\Models\Ad3d\ProductDesign;

use Illuminate\Database\Eloquent\Model;

class QcProductDesign extends Model
{
    protected $table = 'qc_product_design';
    protected $fillable = ['design_id', 'image', 'description', 'applyStatus', 'confirmStatus', 'confirmDate', 'action', 'created_at', 'product_id', 'staff_id', 'confirmStaff_id'];
    protected $primaryKey = 'design_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== THEM  - SỬA ========== ========== ==========
    #---------- Them ----------
    public function insert($image, $description, $productId, $staffId)
    {
        $hFunction = new \Hfunction();
        $modelProductDesign = new QcProductDesign();
        $modelProductDesign->image = $image;
        $modelProductDesign->description = $description;
        $modelProductDesign->product_id = $productId;
        $modelProductDesign->staff_id = $staffId;
        $modelProductDesign->created_at = $hFunction->createdAt();
        if ($modelProductDesign->save()) {
            $this->lastId = $modelProductDesign->design_id;
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
        return (empty($id)) ? $this->designId() : $id;
    }

    #----------- update ----------
    public function rootPathFullImage()
    {
        return 'public/images/product-design/full';
    }

    public function rootPathSmallImage()
    {
        return 'public/images/product-design/small';
    }

    # xoa
    public function actionDelete($designId = null)
    {
        $designId = (empty($designId)) ? $this->imageId() : $designId;
        $imageName = $this->image($designId)[0];
        if (QcProductDesign::where('design_id', $designId)->delete()) {
            $this->dropImage($imageName); # xoa anh
        }
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

    // xac nhan su dung
    public function confirmApplyStatus($designId, $applyStatus, $confirmStatus, $confirmStaffId)
    {
        $hFunction = new \Hfunction();
        # thiet ke dang ap dung
        $dataApplyStatusActivity = $this->infoApplyStatusActivityOfProduct($this->productId($designId));
        if (QcProductDesign::where('design_id', $designId)->update(
            [
                'applyStatus' => $applyStatus,
                'confirmStatus' => $confirmStatus,
                'confirmDate' => $hFunction->carbonNow(),
                'confirmStaff_id' => $confirmStaffId
            ])
        ) {
            if ($applyStatus == 1) { # su dung thiet ke
                if (count($dataApplyStatusActivity) > 0) { # ton tai thiet ke dang app dung
                    $this->disableApplyStatus($dataApplyStatusActivity->designId()); # vo hieu design cu
                }
            }
        }
    }

    public function disableApplyStatus($designId = null)
    {
        return QcProductDesign::where('design_id', $this->checkIdNull($designId))->update(['applyStatus' => 0]);
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
    #----------- product  ------------
    public function product()
    {
        return $this->belongsTo('App\Models\Ad3d\Product\QcProduct', 'product_id', 'product_id');
    }

    public function infoAllOfProduct($productId)
    {
        return QcProductDesign::where('product_id', $productId)->get();
    }

    public function infoLastOfProduct($productId)
    {
        return QcProductDesign::where('product_id', $productId)->orderBy('product_id', 'DESC')->first();
    }

    public function infoApplyStatusActivityOfProduct($productId)
    {
        return QcProductDesign::where('product_id', $productId)->where('applyStatus', 1)->where('action', 1)->first();
    }

    public function totalDesignOfProduct($productId)
    {
        return QcProductDesign::where('product_id', $productId)->count();
    }

    #============ =========== ============ LAY THONG TIN ============= =========== ==========

    public function getInfo($designId = '', $field = '')
    {
        if (empty($designId)) {
            return QcProductDesign::get();
        } else {
            $result = QcProductDesign::where('design_id', $designId)->first();
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
            return QcProductDesign::where('design_id', $objectId)->pluck($column);
        }
    }

    #----------- DEPARTMENT INFO -------------
    public function checkApplyStatus($designId=null)
    {
        return ($this->applyStatus($designId) == 1) ? true : false;
    }

    public function designId()
    {
        return $this->design_id;
    }

    public function image($designId = null)
    {
        return $this->pluck('image', $designId);
    }

    public function description($designId = null)
    {
        return $this->pluck('description', $designId);
    }

    public function applyStatus($designId = null)
    {
        return $this->pluck('applyStatus', $designId);
    }

    public function confirmStatus($designId = null)
    {
        return $this->pluck('confirmStatus', $designId);
    }

    public function confirmDate($designId = null)
    {
        return $this->pluck('confirmDate', $designId);
    }

    public function productId($designId = null)
    {
        return $this->pluck('product_id', $designId);
    }

    public function staffId($designId = null)
    {
        return $this->pluck('staff_id', $designId);
    }

    public function confirmStaffId($designId = null)
    {
        return $this->pluck('confirmStaff_id', $designId);
    }

    public function createdAt($designId = null)
    {
        return $this->pluck('created_at', $designId);
    }

    public function action($designId = null)
    {
        return $this->pluck('action', $designId);
    }
}
