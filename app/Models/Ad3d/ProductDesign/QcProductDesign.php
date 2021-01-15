<?php

namespace App\Models\Ad3d\ProductDesign;

use Illuminate\Database\Eloquent\Model;

class QcProductDesign extends Model
{
    protected $table = 'qc_product_design';
    protected $fillable = ['design_id', 'image', 'description', 'designType', 'applyStatus', 'confirmStatus', 'confirmDate', 'action', 'created_at', 'product_id', 'staff_id', 'confirmStaff_id'];
    protected $primaryKey = 'design_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== THEM  - SỬA ========== ========== ==========
    # mac dinh thiet ke san pham
    public function getDefaultDesignTypeProduct()
    {
        return 1;
    }

    # mac dinh thiet ke san pham
    public function getDefaultDesignTypeConstruction()
    {
        return 2;
    }

    #mac dinh su dung
    public function getDefaultHasApply()
    {
        return 1;
    }

    #mac dinh khong su dung
    public function getDefaultNotApply()
    {
        return 0;
    }

    #mac dinh dang hoat dong
    public function getDefaultHasAction()
    {
        return 1;
    }

    #mac dinh khong hoat dong
    public function getDefaultNotAction()
    {
        return 0;
    }

    #mac dinh co xac nha
    public function getDefaultHasConfirm()
    {
        return 1;
    }

    #mac dinh khong xac nhan
    public function getDefaultNotConfirm()
    {
        return 0;
    }

    #mac dinh mo ta
    public function getDefaultDescription()
    {
        return null;
    }

    #---------- Them ----------
    public function insert($image, $description, $productId, $staffId, $designType = 1)
    {
        $hFunction = new \Hfunction();
        $modelProductDesign = new QcProductDesign();
        $modelProductDesign->image = $image;
        $modelProductDesign->description = $description;
        $modelProductDesign->designType = $designType;
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

    # xoa thiet ke
    public function actionDelete($designId = null)
    {
        $designId = $this->checkIdNull($designId);
        $imageName = $this->image($designId);
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

    // xac nhan su dung TK san pham
    public function confirmApplyStatus($designId, $applyStatus, $confirmStatus, $confirmStaffId)
    {
        $hFunction = new \Hfunction();
        # thiet ke sap dang ap dung
        $dataApplyStatusActivity = $this->infoApplyStatusActivityOfProduct($this->productId($designId));
        if (QcProductDesign::where('design_id', $designId)->update(
            [
                'applyStatus' => $applyStatus,
                'confirmStatus' => $confirmStatus,
                'confirmDate' => $hFunction->carbonNow(),
                'confirmStaff_id' => $confirmStaffId
            ])
        ) {
            # chi xet vo hieu khi up anh thiet ke sap pham - CHI DUOC TON 1 THIET KE SAN PHAM DANG HOẠT DONG
            if ($this->checkIsDesignProduct($this->designType($designId))) {
                if ($applyStatus == $this->getDefaultHasApply()) { # su dung thiet ke
                    if ($hFunction->checkCount($dataApplyStatusActivity)) { # ton tai thiet ke dang app dung
                        # vo hieu design cu
                        $this->disableApplyStatus($dataApplyStatusActivity->designId());
                    }
                }
            }
        }
    }

    # vo hieu a thiet ke
    public function disableApplyStatus($designId = null)
    {
        return QcProductDesign::where('design_id', $this->checkIdNull($designId))->update(['applyStatus' => $this->getDefaultNotApply()]);
    }

    # huy thiet ke
    public function cancelProductDesign($designId = null)
    {
        return QcProductDesign::where('design_id', $this->checkIdNull($designId))->update(
            [
                //'applyStatus' => $this->getDefaultNotApply(),
                'action' => $this->getDefaultNotAction()
            ]);
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
    #====> THIET KE SAN PHAM
    # tat cac thong tin thiet ke san pham - khong bao gom thiet ke thi cong
    public function infoAllOfProduct($productId)
    {
        $designType = $this->getDefaultDesignTypeProduct();
        return QcProductDesign::where('product_id', $productId)->where('designType', $designType)->orderBy('design_id', 'DESC')->get();
    }

    # thiet ke san pham sau cung cua 1 san pham
    public function infoLastOfProduct($productId)
    {
        $designType = $this->getDefaultDesignTypeProduct();
        return QcProductDesign::where('product_id', $productId)->where('designType', $designType)->orderBy('product_id', 'DESC')->first();
    }

    # thong tin thiet ke san pham dang ap dung
    public function infoApplyStatusActivityOfProduct($productId)
    {
        $designType = $this->getDefaultDesignTypeProduct();
        $applyStatus = $this->getDefaultHasApply();
        $action = $this->getDefaultHasAction();
        return QcProductDesign::where('product_id', $productId)->where('designType', $designType)->where('applyStatus', $applyStatus)->where('action', $action)->first();
    }

    # tong thiet ke san pham dang ap dung
    public function totalDesignOfProduct($productId)
    {
        $designType = $this->getDefaultDesignTypeProduct();
        return QcProductDesign::where('product_id', $productId)->where('designType', $designType)->count();
    }

    # THIET KE THI CONG
    # tat ca thiet ke thi cong - khong bao gom thiet ke san pham
    public function infoAllConstructionOfProduct($productId)
    {
        $designType = $this->getDefaultDesignTypeConstruction();
        return QcProductDesign::where('product_id', $productId)->where('designType', $designType)->orderBy('design_id', 'DESC')->get();
    }

    # tat ca thiet ke thi cong  - dang ap dung
    public function infoConstructionHasApplyOfProduct($productId)
    {
        $designType = $this->getDefaultDesignTypeConstruction();
        $applyStatus = $this->getDefaultHasApply();
        $action = $this->getDefaultHasAction();
        return QcProductDesign::where('product_id', $productId)->where('designType', $designType)->where('applyStatus', $applyStatus)->where('action', $action)->orderBy('design_id', 'DESC')->get();
    }

    //---------- Nhan vien thiet ke -----------
    public function staff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staff_id', 'staff_id');
    }

    public function confirmStaff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'confirmStaff_id', 'staff_id');
    }

    #============ =========== ============ LAY THONG TIN ============= =========== ==========

    public function getInfo($designId = '', $field = '')
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($designId)) {
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
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($objectId)) {
            return $this->$column;
        } else {
            return QcProductDesign::where('design_id', $objectId)->pluck($column)[0];
        }
    }

    #----------- DEPARTMENT INFO -------------
    # kiem tra co ap dung
    public function checkApplyStatus($designId = null)
    {
        return ($this->applyStatus($designId) == $this->getDefaultHasApply()) ? true : false;
    }

    # kiem tra con hoat dong
    public function checkHasAction($designId = null)
    {
        return ($this->action($designId) == $this->getDefaultHasAction()) ? true : false;
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

    public function designType($designId = null)
    {
        return $this->pluck('designType', $designId);
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
    # ======== kiem tra thong tin ============
    # kiem tra co phai thiet ke san pham khong
    public function checkIsDesignProduct($designType)
    {
        return ($designType == $this->getDefaultDesignTypeProduct()) ? true : false;
    }

    # kiem tra co phai thiet ke thi cong
    public function checkIsDesignConstruction($designType)
    {
        return ($designType == $this->getDefaultDesignTypeConstruction()) ? true : false;
    }
}
