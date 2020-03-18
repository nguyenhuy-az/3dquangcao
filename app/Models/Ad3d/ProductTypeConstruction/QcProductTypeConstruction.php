<?php

namespace App\Models\Ad3d\ProductTypeConstruction;

use Illuminate\Database\Eloquent\Model;

class QcProductTypeConstruction extends Model
{
    protected $table = 'qc_product_type_construction';
    protected $fillable = ['detail_id','created_at', 'type_id', 'construction_id'];
    protected $primaryKey = 'detail_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== THEM - SUA ========== ========== ==========
    #---------- Them ----------
    public function insert($typeId, $constructionId)
    {
        $hFunction = new \Hfunction();
        $modelProductTypeConstruction = new QcProductTypeConstruction();
        $modelProductTypeConstruction->type_id = $typeId;
        $modelProductTypeConstruction->construction_id = $constructionId;
        $modelProductTypeConstruction->created_at = $hFunction->createdAt();
        if ($modelProductTypeConstruction->save()) {
            $this->lastId = $modelProductTypeConstruction->detail_id;
            return true;
        } else {
            return false;
        }
    }

    public function checkIdNull($detailId=null)
    {
        return (empty($detailId)) ? $this->cancalId() : $detailId;
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

    //---------- danh muc thi cong  -----------
    public function constructionWork()
    {
        return $this->belongsTo('App\Models\Ad3d\ConstructionWork\QcConstructionWork', 'construction_id', 'construction_id');
    }

    public function infoOfConstructionWork($constructionId, $orderBy = 'DESC')
    {
        return QcProductTypeConstruction::where('construction_id', $constructionId)->orderBy('created_at', $orderBy)->get();
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

    public function constructionId($detailId = null)
    {
        return $this->pluck('construction_id', $detailId);
    }

    public function createdAt($detailId = null)
    {
        return $this->pluck('created_at', $detailId);
    }
}
