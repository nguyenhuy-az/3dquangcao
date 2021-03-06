<?php

namespace App\Models\Ad3d\Product;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\ProductCancel\QcProductCancel;
use App\Models\Ad3d\ProductDesign\QcProductDesign;
use App\Models\Ad3d\ProductRepair\QcProductRepair;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\StaffNotify\QcStaffNotify;
use App\Models\Ad3d\WorkAllocation\QcWorkAllocation;
use App\Models\Ad3d\WorkAllocationReport\QcWorkAllocationReport;
use Illuminate\Database\Eloquent\Model;

class QcProduct extends Model
{
    protected $table = 'qc_products';
    protected $fillable = ['product_id', 'width', 'height', 'depth', 'warrantyTime', 'price', 'amount', 'description', 'designImage', 'productImage', 'finishStatus', 'finishDate', 'cancelStatus', 'created_at', 'type_id', 'order_id', 'confirmStaff_id'];
    protected $primaryKey = 'product_id';
    public $timestamps = false;

    private $lastId;

    #mac dinh chieu ngang
    public function getDefaultWidth()
    {
        return 0;
    }

    #mac dinh chieu cao
    public function getDefaultHeight()
    {
        return 0;
    }

    # mac dinh chieu sau
    public function getDefaultDepth()
    {
        return 0;
    }

    #mac dinh gia
    public function getDefaultPrice()
    {
        return 0;
    }

    # mac dinh so luong
    public function getDefaultAmount()
    {
        return 1;
    }

    # mac dinh da ket thuc
    public function getDefaultHasFinishStatus()
    {
        return 1;
    }

    # mac dinh da ket thuc
    public function getDefaultNotFinishStatus()
    {
        return 0;
    }

    # mac dinh da huy
    public function getDefaultHasCancel()
    {
        return 1;
    }

    # mac dinh khong huy
    public function getDefaultNotCancel()
    {
        return 0;
    }

    # mac dinh co thi cong
    public function getDefaultHasConstruction()
    {
        return 1;
    }

    # mac dinh khong thi cong
    public function getDefaultNotConstruction()
    {
        return 0;
    }

    # mac dinh anh thiet ke
    public function getDefaultDesignImage()
    {
        $hFunction = new \Hfunction();
        return $hFunction->getDefaultNull();
    }

    # mac dinh khong bao hanh
    public function getDefaultNotWarrantyTime()
    {
        return 0;
    }
    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- Insert ----------
    // insert
    public function insert($width, $height, $depth, $price, $amount, $description, $typeId, $orderId, $designImage = null, $warrantyTime = 0)
    {
        $hFunction = new \Hfunction();
        $modelProduct = new QcProduct();
        $modelProduct->width = $width;
        $modelProduct->height = $height;
        $modelProduct->depth = $depth;
        $modelProduct->price = $price;
        $modelProduct->amount = $amount;
        $modelProduct->description = $description;
        $modelProduct->warrantyTime = $warrantyTime;
        $modelProduct->designImage = $designImage;
        $modelProduct->productImage = null;
        $modelProduct->type_id = $typeId;
        $modelProduct->order_id = $orderId;
        $modelProduct->created_at = $hFunction->createdAt();
        if ($modelProduct->save()) {
            $this->lastId = $modelProduct->product_id;
            return true;
        } else {
            return false;
        }
    }

    // lay ID m?i them
    public function insertGetId()
    {
        return $this->lastId;
    }

    //kiem tra ID
    public function checkIdNull($productId = null)
    {
        $hFunction = new \Hfunction();
        return ($hFunction->checkEmpty($productId)) ? $this->productId() : $productId;
    }

    // cap nhat thong tin
    public function updateInfo($productId, $width, $height, $depth, $price, $amount, $description, $typeId)
    {
        return QcProduct::where('product_id', $productId)->update([
            'width' => $width,
            'height' => $height,
            'depth' => $depth,
            'price' => $price,
            'amount' => $amount,
            'description' => $description,
            'typeId' => $typeId
        ]);
    }

    public function updateInfoNotType($productId, $width, $height, $depth, $price, $amount, $description)
    {
        return QcProduct::where('product_id', $productId)->update([
            'width' => $width,
            'height' => $height,
            'depth' => $depth,
            'price' => $price,
            'amount' => $amount,
            'description' => $description
        ]);
    }

    // cap nhat thiet ke
    public function updateDesignImage($productId, $designImage)
    {
        return QcProduct::where('product_id', $productId)->update(['designImage' => $designImage]);
    }

    public function cancelProduct($productId, $reason, $staffCancelId)
    {
        $modelProductCancel = new QcProductCancel();
        $modelWorkAllocation = new QcWorkAllocation();
        if (QcProduct::where('product_id', $productId)->update(['finishStatus' => 1, 'cancelStatus' => 1, 'confirmStaff_id' => $staffCancelId])) {
            #them thong tin huy
            $modelProductCancel->insert($reason, $productId, $staffCancelId);

            # ket thuc phan viẹc
            $modelWorkAllocation->confirmFinishFromCancelProduct($productId);
        }
    }

    public function confirmFinish($confirmStaffId, $date, $productId = null)
    {
        return QcProduct::where('product_id', $this->checkIdNull($productId))->update(
            [
                'finishStatus' => $this->getDefaultHasFinishStatus(),
                'finishDate' => $date,
                'confirmStaff_id' => $confirmStaffId
            ]);
    }

    # xac nhan hoan thanh khi bao hoan thanh don hang
    public function confirmFinishFromFinishOrder($productId, $staffReportFinishId)
    {
        $hFunction = new \Hfunction();
        $modelWorkAllocation = new QcWorkAllocation();
        if (QcProduct::where('product_id', $productId)->update(
            [
                'finishStatus' => $this->getDefaultHasFinishStatus(),
                'finishDate' => $hFunction->carbonNow(),
                'finishReportStaff_id' => $staffReportFinishId,
                'confirmStaff_id' => $staffReportFinishId // tam thoi khong can duyen
            ])
        ) {
            # ket thuc phan viec tren san pham
            $modelWorkAllocation->confirmFinishFromFinishProduct($productId);
        }
    }

    public function rootPathDesignFullImage()
    {
        return 'public/images/product/design/full';
    }

    public function rootPathDesignSmallImage()
    {
        return 'public/images/product/design/small';
    }

    public function rootPathProductFullImage()
    {
        return 'public/images/product/design/full';
    }

    public function rootPathProductSmallImage()
    {
        return 'public/images/product/design/small';
    }

    //upload image
    public function uploadDesignImage($file, $imageName, $size = 500)
    {
        $hFunction = new \Hfunction();
        $pathSmallImage = $this->rootPathDesignSmallImage();
        $pathFullImage = $this->rootPathDesignFullImage();
        if (!is_dir($pathFullImage)) mkdir($pathFullImage);
        if (!is_dir($pathSmallImage)) mkdir($pathSmallImage);
        return $hFunction->uploadSaveByFileName($file, $imageName, $pathSmallImage . '/', $pathFullImage . '/', $size);
    }

    //drop image
    public function dropDesignImage($imageName)
    {
        unlink($this->rootPathDesignSmallImage() . '/' . $imageName);
        unlink($this->rootPathDesignFullImage() . '/' . $imageName);
    }

    //upload image
    public function uploadProductImage($file, $imageName, $size = 500)
    {
        $hFunction = new \Hfunction();
        $pathSmallImage = $this->rootPathProductSmallImage();
        $pathFullImage = $this->rootPathProductFullImage();
        if (!is_dir($pathFullImage)) mkdir($pathFullImage);
        if (!is_dir($pathSmallImage)) mkdir($pathSmallImage);
        return $hFunction->uploadSaveByFileName($file, $imageName, $pathSmallImage . '/', $pathFullImage . '/', $size);
    }

    //drop image
    public function dropProductImage($imageName)
    {
        unlink($this->rootPathProductSmallImage() . '/' . $imageName);
        unlink($this->rootPathProductFullImage() . '/' . $imageName);
    }

    //========== ========= ========= CAC MOI QUAN HE DU LIEU ========== ========= ==========
    #----------- ANH thiet ke  ------------
    public function productDesign()
    {
        return $this->hasMany('App\Models\Ad3d\ProductDesign\QcProductDesign', 'product_id', 'product_id');
    }

    # tat ca thiet ke san pham
    public function productDesignInfoAll($productId = null)
    {
        $modelProductDesign = new QcProductDesign();
        return $modelProductDesign->infoAllOfProduct($this->checkIdNull($productId));
    }

    #lay thiet ke dang ap dung
    public function productDesignInfoApplyActivity($productId = null)
    {
        $modelProductDesign = new QcProductDesign();
        return $modelProductDesign->infoApplyStatusActivityOfProduct($this->checkIdNull($productId));
    }

    #lay thiet ke sau cung
    public function productDesignInfoLast($productId = null)
    {
        $modelProductDesign = new QcProductDesign();
        return $modelProductDesign->infoLastOfProduct($this->checkIdNull($productId));
    }


    #tong so luong thiet ke cua san pham
    public function totalProductDesign($productId = null)
    {
        $modelProductDesign = new QcProductDesign();
        return $modelProductDesign->totalDesignOfProduct($this->checkIdNull($productId));
    }
    # ==> THIET KE THI CONG
    #tat ca thiet ke thi cong
    public function productDesignInfoConstruction($productId = null)
    {
        $modelProductDesign = new QcProductDesign();
        return $modelProductDesign->infoAllConstructionOfProduct($this->checkIdNull($productId));
    }

    #lay thiet ke thi cong dang ap dung
    public function productDesignInfoConstructionHasApply($productId = null)
    {
        $modelProductDesign = new QcProductDesign();
        return $modelProductDesign->infoConstructionHasApplyOfProduct($this->checkIdNull($productId));
    }

    //---------- mua vat tu -----------
    public function importDetail()
    {
        return $this->hasMany('App\Models\Ad3d\ImportDetail\QcImportDetail', 'product_id', 'product_id');
    }

    //---------- nhan vien -----------
    public function staff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'confirmStaff_id', 'staff_id');
    }

    //---------- loai san pham -----------
    public function productType()
    {
        return $this->belongsTo('App\Models\Ad3d\ProductType\QcProductType', 'type_id', 'type_id');
    }

    //---------- ---------- don hang ----------- ----------
    public function order()
    {
        return $this->belongsTo('App\Models\Ad3d\Order\QcOrder', 'order_id', 'order_id');
    }

    public function infoActivityOfOrder($orderId)
    {
        return QcProduct::where('order_id', $orderId)->where('cancelStatus', $this->getDefaultNotCancel())->get();
    }

    public function allInfoOfOrder($orderId)
    {
        return QcProduct::where('order_id', $orderId)->get();
    }

    public function infoNoCancelOfOrder($orderId)
    {
        //return QcProduct::where('order_id', $orderId)->where('cancelStatus', 0)->get();
    }

    public function cancelByOrder($orderId)
    {
        return QcProduct::where('order_id', $orderId)->update(['cancelStatus' => $this->getDefaultHasCancel()]);
    }

    # tong tien cua 1 don hang - khong tinh san pham da huy
    public function totalPriceOfOrder($orderId)
    {
        $hFunction = new \Hfunction();
        $totalPrice = 0;
        $dataProduct = QcProduct::where('order_id', $orderId)->where('cancelStatus', $this->getDefaultNotCancel())->get();
        if ($hFunction->checkCount($dataProduct)) {
            foreach ($dataProduct as $key => $value) {
                $totalPrice = $totalPrice + ($value['price'] * $value['amount']);
            }
        }
        return $totalPrice;
    }

    # tong tien cua danh sach don hang
    public function totalPriceOfListOrder($listOrderId)
    {
        $hFunction = new \Hfunction();
        $totalPrice = 0;
        $dataProduct = QcProduct::whereIn('order_id', $listOrderId)->where('cancelStatus', $this->getDefaultNotCancel())->get();
        if ($hFunction->checkCount($dataProduct)) {
            foreach ($dataProduct as $key => $value) {
                $totalPrice = $totalPrice + ($value['price'] * $value['amount']);
            }
        }
        return $totalPrice;
    }

    //kiem tra ton san pham khong hoan thanh cua don hang
    public function checkExistsProductNotFinishOfOrder($orderId)
    {
        return QcProduct::where('order_id', $orderId)->where('finishStatus', $this->getDefaultNotFinishStatus())->exists();
    }

    # danh sach ma san pham cua 1 don hang
    public function listIdOfOrder($orderId)
    {
        return QcProduct::where('order_id', $orderId)->pluck('product_id');
    }

    # danh sach ma san pham tu danh sach ma don hang
    public function listIdOfListOrderId($listOrderId)
    {
        return QcProduct::whereIn('order_id', $listOrderId)->pluck('product_id');
    }

    //---------- ---------- bao sua chua ---------- ----------
    public function productRepair()
    {
        return $this->hasMany('App\Models\Ad3d\ProductRepair\QcProductRepair', 'product_id', 'product_id');
    }

    #kiem ton tai san pham dang duoc bao sua chua
    public function productRepairActivityOfProduct($productId = null)
    {
        $modelProductRepair = new QcProductRepair();
        return $modelProductRepair->existInfoActivityOfProduct($this->checkIdNull($productId));
    }

    //---------- ---------- phan viec ---------- ----------
    public function workAllocation()
    {
        return $this->hasMany('App\Models\Ad3d\WorkAllocation\QcWorkAllocation', 'product_id', 'product_id');
    }

    public function listReceiveStaffIdWorkAllocationOfListProduct($listProductId)
    {
        $modelWorkAllocation = new QcWorkAllocation();
        return $modelWorkAllocation->listReceiveStaffIdOfListProduct($listProductId);
    }

    #kiem ton tai san pham dang dc phan viec
    public function existMaimRoleWorkAllocationActivity($productId = null)
    {
        $modelWorkAllocation = new QcWorkAllocation();
        return $modelWorkAllocation->existMaimRoleActivityOfProduct($this->checkIdNull($productId));
    }

    #kiem ton tai san pham dang dc phan viec
    public function workAllocationActivityOfProduct($productId = null)
    {
        $modelWorkAllocation = new QcWorkAllocation();
        return $modelWorkAllocation->existInfoActivityOfProduct($this->checkIdNull($productId));
    }

    # thong tin phan viec - DANG HOAT DONG
    public function workAllocationInfoActivityOfProduct($productId = null)
    {
        $modelWorkAllocation = new QcWorkAllocation();
        return $modelWorkAllocation->infoActivityOfProduct($this->checkIdNull($productId));
    }

    # thong tin phan viec - TAT CA
    public function workAllocationInfoOfProduct($productId = null)
    {
        $modelWorkAllocation = new QcWorkAllocation();
        return $modelWorkAllocation->infoOfProduct($this->checkIdNull($productId));
    }

    # thong tin phan viec - KHONG BI HUY
    public function workAllocationInfoNotCancelOfProduct($productId = null)
    {
        $modelWorkAllocation = new QcWorkAllocation();
        return $modelWorkAllocation->infoNotCancelOfProduct($this->checkIdNull($productId));
    }

    # kiem ton tai nhan vien da duoc phan cong
    public function checkStaffReceiveProduct($staffId, $productId)
    {
        $modelWorkAllocation = new QcWorkAllocation();
        return $modelWorkAllocation->checkStaffReceiveProduct($staffId, $productId);
    }

    //========= ========== ========== LAY THONG TIN CO BAN ========== ========== ==========
    # thong tin bao cao thi cong cua 1 san pham
    public function workAllocationReportInfo($productId = null)
    {
        $modelWorkAllocationReport = new QcWorkAllocationReport();
        return $modelWorkAllocationReport->infoOfProduct($this->checkIdNull($productId));
    }

    #thong tin bao cao phan viec - TRONG NGAY
    public function workAllocationReportInfoInDate($date, $productId = null)
    {
        $modelWorkAllocationReport = new QcWorkAllocationReport();
        return $modelWorkAllocationReport->infoOfProductInDate($date, $this->checkIdNull($productId));
    }

    # danh sach sp thu danh sach id
    public function infoFromListId($listProductId)
    {
        return QcProduct::whereIn('product_id', $listProductId)->get();
    }

    public function getInfo($productId = '', $field = '')
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($productId)) {
            return QcProduct::get();
        } else {
            $result = QcProduct::where('product_id', $productId)->first();
            if ($hFunction->checkEmpty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }

    # lay 1 gia tri
    public function pluck($column, $objectId = null)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($objectId)) {
            return $this->$column;
        } else {
            return QcProduct::where('product_id', $objectId)->pluck($column)[0];
        }
    }

    public function productId()
    {
        return $this->product_id;
    }

    public function width($productId = null)
    {

        return $this->pluck('width', $productId);
    }

    public function height($productId = null)
    {

        return $this->pluck('height', $productId);
    }

    public function depth($productId = null)
    {

        return $this->pluck('depth', $productId);
    }

    public function warrantyTime($productId = null)
    {
        return $this->pluck('warrantyTime', $productId);
    }

    public function price($productId = null)
    {
        return $this->pluck('price', $productId);
    }

    public function amount($productId = null)
    {
        return $this->pluck('amount', $productId);
    }

    public function description($productId = null)
    {
        return $this->pluck('description', $productId);
    }

    public function designImage($orderId = null)
    {
        return $this->pluck('designImage', $orderId);
    }

    // get path image
    public function pathSmallDesignImage($image)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($image)) {
            return $hFunction->getDefaultNull();
        } else {
            return asset($this->rootPathDesignSmallImage() . '/' . $image);
        }
    }

    public function pathFullDesignImage($image)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($image)) {
            return $hFunction->getDefaultNull();
        } else {
            return asset($this->rootPathDesignFullImage() . '/' . $image);
        }
    }

    public function productImage($orderId = null)
    {
        return $this->pluck('productImage', $orderId);
    }

    # get path image
    public function pathSmallProductImage($image)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($image)) {
            return $hFunction->getDefaultNull();
        } else {
            return asset($this->rootPathProductSmallImage() . '/' . $image);
        }
    }

    public function pathFullProductImage($image)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($image)) {
            return $hFunction->getDefaultNull();
        } else {
            return asset($this->rootPathProductFullImage() . '/' . $image);
        }
    }

    public function finishStatus($productId = null)
    {
        return $this->pluck('finishStatus', $productId);
    }

    public function finishDate($productId = null)
    {
        return $this->pluck('finishDate', $productId);
    }

    public function cancelStatus($productId = null)
    {
        return $this->pluck('cancelStatus', $productId);
    }

    public function createdAt($productId = null)
    {
        return $this->pluck('created_at', $productId);
    }

    public function typeId($productId = null)
    {
        return $this->pluck('type_id', $productId);
    }

    public function orderId($productId = null)
    {
        return $this->pluck('order_id', $productId);
    }

    public function finishReportStaffId($productId = null)
    {
        return $this->pluck('finishReportStaff_id', $productId);
    }

    public function confirmStaffId($productId = null)
    {
        return $this->pluck('confirmStaff_id', $productId);
    }

    // total records
    public function totalRecords()
    {
        return QcProduct::count();
    }

    // last id
    public function lastId()
    {
        $hFunction = new \Hfunction();
        $result = QcProduct::orderBy('product_id', 'DESC')->first();
        return ($hFunction->checkEmpty($result)) ? 0 : $result->product_id;
    }

    public function checkFinishStatus($productId = null)
    {
        return ($this->finishStatus($productId) == $this->getDefaultNotFinishStatus()) ? false : true;
    }

    public function checkCancelStatus($productId = null)
    {
        return ($this->cancelStatus($productId) == $this->getDefaultNotCancel()) ? false : true;
    }

    # kiem tra san pham co duoc bao hanh hay
    public function checkHasWarranty($productId = null)
    {
        return ($this->warrantyTime($productId) == $this->getDefaultNotWarrantyTime()) ? false : true;
    }

    # kiem tra con thoi gian bao hanh hay khong
    public function checkWarrantyExpires($productId = null)
    {
        $hFunction = new \Hfunction();
        $productId = $this->checkIdNull($productId);
        if (!$this->checkHasWarranty($productId)) {
            return false;
        } else {
            $createdAd = $this->createdAt($productId);
            $warrantyTime = $this->warrantyTime($productId);
            $currentDate = $hFunction->carbonNow();
            $checkTime = $hFunction->datetimePlusMonth($createdAd, $warrantyTime);
            if ($checkTime > $currentDate) {
                return true; // con bao hanh
            } else {
                return false; // het bao hanh
            }
        }
    }
    // ======== ======== TRIEN KHAI THI CONG ======== ========
    # phan cong tu dong
    /*
        goi sau khi them don hang -> them san pham
    */
    public function autoAllocation($productId)
    {
        $hFunction = new \Hfunction();
        $modelCompany = new QcCompany();
        $modelStaff = new QcStaff();
        $modelWorkAllocation = new QcWorkAllocation();
        # lay thong tin cong ty dang login
        $dataCompanyLogin = $modelStaff->companyLogin();
        # lay danh sach nhan vien cua bo phan thi cong
        # thong tin san pham
        $dataProduct = $this->getInfo($productId);
        if ($hFunction->checkCount($dataProduct)) {
            # lay danh sach nhan vien thi cong cap nhan vien
            $dataStaffReceive = $modelCompany->staffInfoActivityOfConstructionStaff($dataCompanyLogin->companyId());
            # mac dinh ngay ban giao la thoi gian hien tai
            $allocationDate = $hFunction->carbonNow();
            # lay gia tri mac dinh
            $note = $modelWorkAllocation->getDefaultNoted();
            $allocationStaffId = $modelWorkAllocation->getDefaultAllocationStaffId();
            $role = $modelWorkAllocation->getDefaultHasRole(); # mac dinh lam chinh
            $constructionNumber = $modelWorkAllocation->getDefaultFirstConstructionNumber();
            $productRepairId = $modelWorkAllocation->getDefaultProductRepairId();
            # thong tin don hang
            $dataOrders = $dataProduct->order;
            # ngay den hang cua don hang
            $receiveDeadline = $dataOrders->deliveryDate();
            if ($hFunction->checkCount($dataStaffReceive)) { # co nhan vien thi cong
                foreach ($dataStaffReceive as $staffReceive) {
                    $receiveStaffId = $staffReceive->staffId();
                    $this->allocationForStaff($productId, $receiveStaffId, $allocationDate, $receiveDeadline, $note, $allocationStaffId, $role, $constructionNumber, $productRepairId);
                }
            }
        }

    }

    # phan viec cho 1 NV
    public function allocationForStaff($productId, $receiveStaffId, $allocationDate, $receiveDeadline, $noted, $allocationStaffId, $role, $constructionNumber, $productRepairId)
    {
        $hFunction = new \Hfunction();
        $modelWorkAllocation = new QcWorkAllocation();
        $modelStaffNotify = new QcStaffNotify();
        # mac dinh co xac nha - phai nhan
        $confirmStatus = $modelWorkAllocation->getDefaultHasConfirmStatus();
        $confirmDate = $hFunction->carbonNow();
        # mac dinh phai nhan - bat buoc
        $receiveStatus = $modelWorkAllocation->getDefaultHasReceiveStatus();
        if ($modelWorkAllocation->insert($allocationDate, $receiveStatus, $receiveDeadline, $confirmStatus, $confirmDate, $noted, $productId, $allocationStaffId, $receiveStaffId, $role, $constructionNumber, $productRepairId)) {
            $newWorkAllocationId = $modelWorkAllocation->insertGetId();
            # lay gia tri mac dinh
            $notifyOrderId = $modelStaffNotify->getDefaultOrderId();
            $orderAllocationId = $modelStaffNotify->getDefaultOrderAllocationId();
            $bonusMoneyId = $modelStaffNotify->getDefaultBonusId();
            $minusId = $modelStaffNotify->getDefaultMinusId();
            $orderAllocationFinishId = $modelStaffNotify->getDefaultOrderAllocationFinishId();
            $notifyNote = 'Giao thi công sản phẩm';
            $modelStaffNotify->insert($notifyOrderId, $receiveStaffId, $notifyNote, $orderAllocationId, $newWorkAllocationId, $bonusMoneyId, $minusId, $orderAllocationFinishId);
        }

    }
}
