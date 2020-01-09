<?php

namespace App\Models\Ad3d\ProductType;

use App\Models\Ad3d\ProductTypeImage\QcProductTypeImage;
use App\Models\Ad3d\ProductTypePrice\QcProductTypePrice;
use Illuminate\Database\Eloquent\Model;

class QcProductType extends Model
{
    protected $table = 'qc_product_type';
    protected $fillable = ['type_id', 'typeCode', 'name', 'unit', 'description', 'confirmStatus', 'applyStatus', 'action', 'created_at'];
    protected $primaryKey = 'type_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== Thêm && cập nhật ========== ========== ==========
    #---------- Thêm ----------
    public function insert($typeCode, $name, $description = null, $unit = null, $confirmStatus = 1, $applyStatus = 1)
    {
        $hFunction = new \Hfunction();
        $modelProductType = new QcProductType();
        $modelProductType->typeCode = $typeCode;
        $modelProductType->name = $hFunction->convertValidHTML($name);
        $modelProductType->unit = $unit;
        $modelProductType->description = $hFunction->convertValidHTML($description);
        $modelProductType->confirmStatus = $confirmStatus;
        $modelProductType->applyStatus = $applyStatus;
        $modelProductType->created_at = $hFunction->createdAt();
        if ($modelProductType->save()) {
            $this->lastId = $modelProductType->type_id;
            return true;
        } else {
            return false;
        }
    }

    # lấy Id mới thêm
    public function insertGetId()
    {
        return $this->lastId;
    }

    #----------- cập nhật thông tin ----------
    public function updateInfo($typeId, $typeCode, $name, $description, $unit)
    {
        return QcProductType::where('type_id', $typeId)->update([
            'typeCode' => $typeCode,
            'name' => $name,
            'unit' => $unit,
            'description' => $description
        ]);
    }

    public function checkIdNull($typeId)
    {
        return (empty($typeId)) ? $this->typeId() : $typeId;
    }

    # xóa
    public function actionDelete($typeId = null)
    {
        return QcProductType::where('type_id', $this->checkIdNull($typeId))->update(['action' => 0]);
    }

    #========== ========== ========== Mối quan hệ ========== ========== ==========
    #----------- sản phẩm ------------
    public function product()
    {
        return $this->hasMany('App\Models\Ad3d\Product\QcProduct', 'type_id', 'type_id');
    }

    #----------- bang gia ------------
    public function productTypePrice()
    {
        return $this->hasMany('App\Models\Ad3d\ProductTypePrice\QcProductTypePrice', 'type_id', 'type_id');
    }

    #----------- anh mau ------------
    public function productTypeImage()
    {
        return $this->hasMany('App\Models\Ad3d\ProductTypeImage\QcProductTypeImage', 'type_id', 'type_id');
    }

    public function infoProductTypeImage($typeId = null)
    {
        $modelProductTypeImage = new QcProductTypeImage();
        return $modelProductTypeImage->infoOfProductType($this->checkIdNull($typeId));
    }

    #============ =========== ============ Lấy thông tin ============= =========== ==========
    public function getInfoActivityToCreatedPriceList($companyId)
    {
        $modelProductTypePrice = new QcProductTypePrice();
        $listTypeIdActivityOfCompany = $modelProductTypePrice->listTypeIdActivityOfCompany($companyId);
        return QcProductType::whereNotIn('type_id', $listTypeIdActivityOfCompany)->where('action', 1)->orderBy('name', 'ASC')->get();
    }

    public function infoActivity()
    {
        return QcProductType::where('action', 1)->orderBy('name', 'ASC')->get();
    }

    public function listIdActivity($orderBy = 'ASC')
    {
        return QcProductType::where('action', 1)->orderBy('name', $orderBy)->pluck('type_id');
    }

    public function listIdActivityByName($name, $orderBy = 'ASC')
    {
        return QcProductType::where('name', 'like', "%$name%")->where('action', 1)->orderBy('name', $orderBy)->pluck('type_id');
    }

    public function listIdByName($name, $orderBy = 'ASC')
    {
        return QcProductType::where('name', 'like', "%$name%")->orderBy('name', $orderBy)->pluck('type_id');
    }

    public function getInfo($typeId = '', $field = '')
    {
        if (empty($typeId)) {
            return QcProductType::get();
        } else {
            $result = QcProductType::where('type_id', $typeId)->first();
            if (empty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }

    # tạo select box
    public function getOption($selected = '')
    {
        $hFunction = new \Hfunction();
        $result = QcProductType::select('type_id as optionKey', 'name as optionValue')->get()->toArray();
        return $hFunction->option($result, $selected);
    }

    public function pluck($column, $objectId = null)
    {
        if (empty($objectId)) {
            return $this->$column;
        } else {
            return QcProductType::where('type_id', $objectId)->pluck($column);
        }
    }

    #----------- thông tin -------------
    public function infoFromExactlyName($name)
    {
        return QcProductType::where('name', 'like', "$name")->where('applyStatus', 1)->first();
    }

    public function infoFromSuggestionName($name)
    {
        return QcProductType::where('name', 'like', "%$name%")->where('applyStatus', 1)->get();
    }

    public function typeId()
    {
        return $this->type_id;
    }

    public function typeCode($typeId = null)
    {
        return $this->pluck('typeCode', $typeId);
    }

    public function name($typeId = null)
    {
        $hFunction = new \Hfunction();
        return $hFunction->htmlEntities($this->pluck('name', $typeId));
    }

    public function unit($typeId = null)
    {
        return $this->pluck('unit', $typeId);
    }

    public function description($typeId = null)
    {
        $hFunction = new \Hfunction();
        return $hFunction->htmlEntities($this->pluck('description', $typeId));
    }

    public function confirmStatus($typeId = null)
    {
        return $this->pluck('confirmStatus', $typeId);
    }

    public function applyStatus($typeId = null)
    {
        return $this->pluck('applyStatus', $typeId);
    }

    public function createdAt($typeId = null)
    {
        return $this->pluck('created_at', $typeId);
    }

    # tổng mẫu tin
    public function totalRecords()
    {
        return QcProductType::count();
    }

    #============ =========== ============ kiểm tra thông tin ============= =========== ==========
    public function checkApplyStatus($typeId = null)
    {
        return ($this->applyStatus($typeId) == 1) ? true : false;
    }

    public function checkConfirmStatus($typeId = null)
    {
        return ($this->confirmStatus($typeId) == 1) ? true : false;
    }

    # tồn tại tên khi thêm mới
    public function existName($name)
    {
        $result = QcProductType::where('name', $name)->count();
        return ($result > 0) ? true : false;
    }

    public function existEditName($typeId, $name)
    {
        $result = QcProductType::where('name', $name)->where('type_id', '<>', $typeId)->count();
        return ($result > 0) ? true : false;
    }

    public function existTypeCode($typeCode)
    {
        $result = QcProductType::where('typeCode', $typeCode)->count();
        return ($result > 0) ? true : false;
    }

    public function existEditTypeCode($typeId, $typeCode)
    {
        $result = QcProductType::where('typeCode', $typeCode)->where('type_id', '<>', $typeId)->count();
        return ($result > 0) ? true : false;
    }
}
