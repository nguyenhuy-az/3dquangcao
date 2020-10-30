<?php

namespace App\Models\Ad3d\ProductRepair;

use App\Models\Ad3d\ProductRepairFinish\QcProductRepairFinish;
use Illuminate\Database\Eloquent\Model;

class QcProductRepair extends Model
{
    protected $table = 'qc_product_repair';
    protected $fillable = ['repair_id', 'image', 'note', 'finishStatus', 'finishDate', 'confirmStatus', 'confirmDate', 'action', 'created_at', 'product_id', 'notifyStaff_id', 'confirmStaff_id'];
    protected $primaryKey = 'repair_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- them moi ----------
    public function insert($image, $note, $productId, $notifyStaffId)
    {
        $hFunction = new \Hfunction();
        $modelProductRepair = new QcProductRepair();
        $modelProductRepair->image = $image;
        $modelProductRepair->note = $note;
        $modelProductRepair->product_id = $productId;
        $modelProductRepair->notifyStaff_id = $notifyStaffId;
        $modelProductRepair->created_at = $hFunction->createdAt();
        if ($modelProductRepair->save()) {
            $this->lastId = $modelProductRepair->repair_id;
            return true;
        } else {
            return false;
        }
    }

    // lay id moi them
    public function insertGetId()
    {
        return $this->lastId;
    }

    #----------- update ----------
    public function rootPathFullImage()
    {
        return 'public/images/product-repair/full';
    }

    public function rootPathSmallImage()
    {
        return 'public/images/product-repair/small';
    }

    //thêm hình ảnh
    public function uploadImage($file, $imageName, $size = 500)
    {
        $hFunction = new \Hfunction();
        $pathSmallImage = $this->rootPathSmallImage();
        $pathFullImage = $this->rootPathFullImage();
        if (!is_dir($pathFullImage)) mkdir($pathFullImage);
        if (!is_dir($pathSmallImage)) mkdir($pathSmallImage);
        return $hFunction->uploadSaveByFileName($file, $imageName, $pathSmallImage . '/', $pathFullImage . '/', $size);
    }

    //Xóa ảnh
    public function dropImage($imageName)
    {
        unlink($this->rootPathSmallImage() . '/' . $imageName);
        unlink($this->rootPathFullImage() . '/' . $imageName);
    }

    public function pathSmallImage($image = null)
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
    // ket thuc cong viec
    //========== ========= ========= RELATION ========== ========= ==========
    //---------- nhan vien bao sua -----------
    public function notifyStaff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'notifyStaff_id', 'staff_id');
    }

    //---------- nhan vien xac nhan hoan thanh -----------
    public function confirmStaff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'confirmStaff_id', 'staff_id');
    }

    //---------- san pham -----------
    public function product()
    {
        return $this->belongsTo('App\Models\Ad3d\Product\QcProduct', 'product_id', 'product_id');
    }

    # kiem tra ton tai san pham dang bao sua
    public function existInfoActivityOfProduct($productId)
    {
        return QcProductRepair::where('product_id', $productId)->where('action', 1)->exists();
    }

    # thong tin san pham dang bao sua
    public function infoActivityOfProduct($productId)
    {
        return QcProductRepair::where('product_id', $productId)->where('action', 1)->get();
    }

    # tat ca thong tin bao sua cua 1 san pham
    public function infoOfProduct($productId)
    {
        return QcProductRepair::where('product_id', $productId)->get();
    }

    # chon tat ca thong tin tu 1 danh sach ma san pham theo ngay thang
    public function selectInfoOfListProductIdAndDate($listProductId, $dateFilter = null, $finishStatus = 100) # mac dinh 100 la chon tat ca
    {
        if (empty($dateFilter)) {
            if ($finishStatus == 100) {
                return QcProductRepair::whereIn('product_id', $listProductId)->select('*');
            } else {
                return QcProductRepair::whereIn('product_id', $listProductId)->where('finishStatus', $finishStatus)->select('*');
            }
        } else {
            if ($finishStatus == 100) {
                return QcProductRepair::whereIn('product_id', $listProductId)->where('created_at', 'like', "%$dateFilter%")->select('*');
            } else {
                return QcProductRepair::whereIn('product_id', $listProductId)->where('created_at', 'like', "'%$dateFilter%'")->where('finishStatus', $finishStatus)->select('*');
            }
        }
    }

    //========= ========== ========== lay thong tin ========== ========== ==========
    public function getInfo($repairId = '', $field = '')
    {
        if (empty($repairId)) {
            return QcProductRepair::get();
        } else {
            $result = QcProductRepair::where('repair_id', $repairId)->first();
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
            return QcProductRepair::where('repair_id', $objectId)->pluck($column);
        }
    }

    public function repairId()
    {
        return $this->repair_id;
    }

    public function finishStatus($repairId = null)
    {
        return $this->pluck('finishStatus', $repairId);
    }

    public function finishDate($repairId = null)
    {
        return $this->pluck('finishDate', $repairId);
    }

    public function confirmStatus($repairId = null)
    {

        return $this->pluck('confirmStatus', $repairId);
    }

    public function confirmDate($repairId = null)
    {
        return $this->pluck('confirmDate', $repairId);
    }

    public function image($repairId = null)
    {
        return $this->pluck('image', $repairId);
    }

    public function note($repairId = null)
    {

        return $this->pluck('note', $repairId);
    }

    public function productId($repairId = null)
    {
        return $this->pluck('product_id', $repairId);
    }

    public function confirmStaffId($repairId = null)
    {
        return $this->pluck('confirmStaff_id', $repairId);
    }

    public function notifyStaffId($repairId = null)
    {
        return $this->pluck('notifyStaff_id', $repairId);
    }

    public function action($repairId = null)
    {
        return $this->pluck('action', $repairId);
    }

    public function createdAt($repairId = null)
    {
        return $this->pluck('created_at', $repairId);
    }

// tong mau tin
    public function totalRecords()
    {
        return QcProductRepair::count();
    }

    // id cuoi
    public function lastId()
    {
        $result = QcProductRepair::orderBy('repair_id', 'DESC')->first();
        return (empty($result)) ? null : $result->repair_id;
    }

    // kiem tra thong tin
    #con hoat dong
    public function checkActivity($repairId = null)
    {
        return ($this->action($repairId) == 1) ? true : false;
    }
}
