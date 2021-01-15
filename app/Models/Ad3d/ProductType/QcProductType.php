<?php

namespace App\Models\Ad3d\ProductType;

use App\Models\Ad3d\ProductTypeConstruction\QcProductTypeConstruction;
use App\Models\Ad3d\ProductTypeImage\QcProductTypeImage;
use App\Models\Ad3d\ProductTypePrice\QcProductTypePrice;
use Illuminate\Database\Eloquent\Model;

class QcProductType extends Model
{
    protected $table = 'qc_product_type';
    protected $fillable = ['type_id', 'typeCode', 'name', 'unit', 'description', 'warrantyTime', 'confirmStatus', 'applyStatus', 'action', 'created_at'];
    protected $primaryKey = 'type_id';
    public $timestamps = false;

    private $lastId;

    # mac dinh mo static
    public function getDefaultDescription()
    {
        return null;
    }

    #mac dinh thoi gian bao hanh
    public function getDefaultWarrantyTime()
    {
        return 0;
    }

    #mac dinh da xac nhan
    public function getDefaultHasConfirm()
    {
        return 1;
    }

    #mac dinh chua xac nhan
    public function getDefaultNotConfirm()
    {
        return 0;
    }

    # mac dinh co ap dung
    public function getDefaultHasApply()
    {
        return 1;
    }

    # mac dinh khong ap dung
    public function getDefaultNotApply()
    {
        return 0;
    }

    #mac dinh dang hoat dong
    public function getDefaultHasAction()
    {
        return 1;
    }

    # mac dinh khong hoat dong
    public function getDefaultNotAction()
    {
        return 0;
    }

    # mac dinh don vi
    public function getDefaultUnit()
    {
        return null;
    }
    #========== ========== ========== Thêm && cập nhật ========== ========== ==========
    #---------- Thêm ----------
    public function insert($name, $description = null, $unit = null, $confirmStatus = 1, $applyStatus = 1, $warrantyTime = 0)
    {
        $hFunction = new \Hfunction();
        $modelProductType = new QcProductType();
        $modelProductType->typeCode = $hFunction->strtoupper($hFunction->getAcronymOfString($name));
        $modelProductType->name = $hFunction->convertValidHTML($name);
        $modelProductType->unit = $unit;
        $modelProductType->description = (empty($description)) ? $description : $hFunction->convertValidHTML($description);
        $modelProductType->warrantyTime = $warrantyTime;
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
    public function updateInfo($typeId, $name, $description, $unit, $warrantyTime)
    {
        $hFunction = new \Hfunction();
        return QcProductType::where('type_id', $typeId)->update([
            'typeCode' => $hFunction->strtoupper($hFunction->getAcronymOfString($name)),
            'name' => $name,
            'unit' => $unit,
            'warrantyTime' => $warrantyTime,
            'description' => $description
        ]);
    }

    public function checkIdNull($typeId)
    {
        return (empty($typeId)) ? $this->typeId() : $typeId;
    }

    public function confirmApplyStatus($typeId, $applyStatusId)
    {
        return QcProductType::where('type_id', $typeId)->update([
            'applyStatus' => $applyStatusId,
            'confirmStatus' => $this->getDefaultHasConfirm()
        ]);
    }

    # xóa
    public function actionDelete($typeId = null)
    {
        return QcProductType::where('type_id', $this->checkIdNull($typeId))->update(['action' => $this->getDefaultNotAction()]);
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

    #----------- hang muc thi cong ------------
    public function productTypeConstruction()
    {
        return $this->hasMany('App\Models\Ad3d\ProductTypeConstruction\QcProductTypeConstruction', 'type_id', 'type_id');
    }

    public function constructionWorkListId($typeId = null)
    {
        $modelProductTypeConstruction = new QcProductTypeConstruction();
        return $modelProductTypeConstruction->listDepartmentWorkIdOfProductType($this->checkIdNull($typeId));
    }

    public function constructionWorkInfo($typeId = null)
    {
        $modelProductTypeConstruction = new QcProductTypeConstruction();
        return $modelProductTypeConstruction->infoDepartmentWorkOfProductType($this->checkIdNull($typeId));
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
        return QcProductType::where('action', $this->getDefaultHasAction())->orderBy('name', 'ASC')->get();
    }

    public function listIdActivity($orderBy = 'ASC')
    {
        return QcProductType::where('action', $this->getDefaultHasAction())->orderBy('name', $orderBy)->pluck('type_id');
    }

    public function listIdActivityByName($name, $orderBy = 'ASC')
    {
        return QcProductType::where('name', 'like', "%$name%")->where('action', $this->getDefaultHasAction())->orderBy('name', $orderBy)->pluck('type_id');
    }

    public function listIdByName($name, $orderBy = 'ASC')
    {
        return QcProductType::where('name', 'like', "%$name%")->orderBy('name', $orderBy)->pluck('type_id');
    }

    public function getInfo($typeId = '', $field = '')
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($typeId)) {
            return QcProductType::get();
        } else {
            $result = QcProductType::where('type_id', $typeId)->first();
            if ($hFunction->checkEmpty($field)) {
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
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($objectId)) {
            return $this->$column;
        } else {
            return QcProductType::where('type_id', $objectId)->pluck($column)[0];
        }
    }

    #----------- thông tin -------------
    public function infoFromExactlyName($name)
    {
        return QcProductType::where('name', '=', $name)->first();
    }

    public function infoFromSuggestionName($name)
    {
        return QcProductType::where('name', 'like', "%$name%")->where('applyStatus', $this->getDefaultHasApply())->get();
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

    public function warrantyTime($typeId = null)
    {
        return $this->pluck('warrantyTime', $typeId);
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
        return ($this->applyStatus($typeId) == $this->getDefaultHasApply()) ? true : false;
    }

    public function checkConfirmStatus($typeId = null)
    {
        return ($this->confirmStatus($typeId) == $this->getDefaultHasConfirm()) ? true : false;
    }

    # tồn tại tên khi thêm mới
    public function existName($name)
    {
        return QcProductType::where('name', $name)->exists();
    }

    public function existEditName($typeId, $name)
    {
        return QcProductType::where('name', $name)->where('type_id', '<>', $typeId)->exists();
    }

    public function existTypeCode($typeCode)
    {
        return QcProductType::where('typeCode', $typeCode)->exists();
    }

    public function existEditTypeCode($typeId, $typeCode)
    {
        return QcProductType::where('typeCode', $typeCode)->where('type_id', '<>', $typeId)->exists();
    }
}
