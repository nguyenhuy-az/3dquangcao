<?php

namespace App\Models\Ad3d\ProductTypeConstruction;

use App\Models\Ad3d\DepartmentWork\QcDepartmentWork;
use Illuminate\Database\Eloquent\Model;

class QcProductTypeConstruction extends Model
{
    protected $table = 'qc_product_type_construction';
    protected $fillable = ['detail_id', 'created_at', 'type_id', 'work_id'];
    protected $primaryKey = 'detail_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== THEM - SUA ========== ========== ==========
    #---------- Them ----------
    public function insert($typeId, $workId)
    {
        $hFunction = new \Hfunction();
        $modelProductTypeConstruction = new QcProductTypeConstruction();
        $modelProductTypeConstruction->type_id = $typeId;
        $modelProductTypeConstruction->work_id = $workId;
        $modelProductTypeConstruction->created_at = $hFunction->createdAt();
        if ($modelProductTypeConstruction->save()) {
            $this->lastId = $modelProductTypeConstruction->detail_id;
            return true;
        } else {
            return false;
        }
    }

    public function checkIdNull($detailId = null)
    {
        return (empty($detailId)) ? $this->cancalId() : $detailId;
    }

    public function deleteInfoOfType($typeId)
    {
        return QcProductTypeConstruction::where('type_id', $typeId)->delete();
    }
    #========== ========== ========== CAC MOI QUAN HE DU LIEU ========== ========== ==========
    //---------- loai san pham  -----------
    public function productType()
    {
        return $this->belongsTo('App\Models\Ad3d\ProductType\QcProductType', 'type_id', 'type_id');
    }

    public function infoOfProductType($typeId, $orderBy = 'DESC')
    {
        return QcProductTypeConstruction::where('type_id', $typeId)->orderBy('created_at', $orderBy)->get();
    }

    public function listDepartmentWorkIdOfProductType($typeId)
    {
        return QcProductTypeConstruction::where('type_id', $typeId)->pluck('work_id');
    }

    public function infoDepartmentWorkOfProductType($typeId)
    {
        $modelDepartmentWork = new QcDepartmentWork();
        return $modelDepartmentWork->getInfoByListId($this->listDepartmentWorkIdOfProductType($typeId));
    }

    //---------- danh muc thi cong  -----------
    public function departmentWork()
    {
        return $this->belongsTo('App\Models\Ad3d\DepartmentWork\QcDepartmentWork', 'work_id', 'work_id');
    }

    public function infoOfConstructionWork($workId, $orderBy = 'DESC')
    {
        return QcProductTypeConstruction::where('work_id', $workId)->orderBy('created_at', $orderBy)->get();
    }


    #============ =========== ============ LAY THONG TIN ============= =========== ==========
    public function getInfo($detailId = '', $field = '')
    {
        if (empty($detailId)) {
            return QcProductTypeConstruction::get();
        } else {
            $result = QcProductTypeConstruction::where('detail_id', $detailId)->first();
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
            return QcProductTypeConstruction::where('detail_id', $objectId)->pluck($column);
        }
    }

    public function detailId()
    {
        return $this->detail_id;
    }

    public function typeId($detailId = null)
    {
        return $this->pluck('type_id', $detailId);
    }

    public function workId($detailId = null)
    {
        return $this->pluck('work_id', $detailId);
    }

    public function createdAt($detailId = null)
    {
        return $this->pluck('created_at', $detailId);
    }
}
