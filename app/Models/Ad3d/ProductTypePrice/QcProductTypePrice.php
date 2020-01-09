<?php

namespace App\Models\Ad3d\ProductTypePrice;

use Illuminate\Database\Eloquent\Model;

class QcProductTypePrice extends Model
{
    protected $table = 'qc_product_type_price';
    protected $fillable = ['price_id', 'price', 'note', 'applyDate', 'action', 'created_at', 'type_id', 'company_id', 'staff_id'];
    protected $primaryKey = 'price_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= THEM && CAP NHAT ========== ========= =========
    // insert
    public function insert($price, $note, $typeId, $companyId, $staffId)
    {
        $hFunction = new \Hfunction();
        $modelProduct = new QcProductTypePrice();
        $modelProduct->price = $price;
        $modelProduct->note = $hFunction->convertValidHTML($note);
        $modelProduct->applyDate = $hFunction->carbonNow();
        $modelProduct->type_id = $typeId;
        $modelProduct->company_id = $companyId;
        $modelProduct->staff_id = $staffId;
        $modelProduct->created_at = $hFunction->createdAt();
        if ($modelProduct->save()) {
            $this->lastId = $modelProduct->price_id;
            return true;
        } else {
            return false;
        }
    }

    // get new id after insert
    public function insertGetId()
    {
        return $this->lastId;
    }

    // vo hieu hoa
    public function updateNote($priceId, $note)
    {
        return QcProductTypePrice::where('price_id', $priceId)->update(['note' => $note]);
    }

    // vo hieu hoa
    public function disablePrice($priceId)
    {
        return QcProductTypePrice::where('price_id', $priceId)->update(['action' => 0]);
    }

    //========== ========= ========= CAC MOI QUAN HE DU LIEU ========== ========= ==========
    //---------- cong ty -----------
    public function company()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'confirmStaff_id', 'staff_id');
    }

    public function selectInfoActivityOfCompany($companyId)
    {
        return QcProductTypePrice::where('company_id', $companyId)->where('action', 1)->select('*');
    }

    public function listTypeIdActivityOfCompany($companyId)
    {
        return QcProductTypePrice::where('company_id', $companyId)->where('action', 1)->pluck('type_id');
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

    public function selectInfoActivityOfListProductTypeAndCompany($listProductTypeId, $companyId)
    {
        return QcProductTypePrice::whereIn('type_id', $listProductTypeId)->where('company_id', $companyId)->where('action', 1)->select('*');
    }


    //========= ========== ========== LAY THONG TIN CO BAN ========== ========== ==========
    public function getInfo($priceId = '', $field = '')
    {
        if (empty($priceId)) {
            return QcProductTypePrice::get();
        } else {
            $result = QcProductTypePrice::where('price_id', $priceId)->first();
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
            return QcProductTypePrice::where('price_id', $objectId)->pluck($column);
        }
    }

    public function priceId()
    {
        return $this->price_id;
    }

    public function price($priceId = null)
    {

        return $this->pluck('price', $priceId);
    }

    public function note($priceId = null)
    {
        $hFunction = new \Hfunction();
        return $hFunction->htmlEntities($this->pluck('note', $priceId));
    }

    public function applyDate($priceId = null)
    {
        return $this->pluck('applyDate', $priceId);
    }

    public function action($orderId = null)
    {
        return $this->pluck('action', $orderId);
    }

    public function cancelStatus($priceId = null)
    {
        return $this->pluck('cancelStatus', $priceId);
    }

    public function createdAt($priceId = null)
    {
        return $this->pluck('created_at', $priceId);
    }

    public function typeId($priceId = null)
    {
        return $this->pluck('type_id', $priceId);
    }

    public function companyId($priceId = null)
    {
        return $this->pluck('company_id', $priceId);
    }


    public function staffId($priceId = null)
    {
        return $this->pluck('staff_id', $priceId);
    }

    // total records
    public function totalRecords()
    {
        return QcProductTypePrice::count();
    }

    // last id
    public function lastId()
    {
        $result = QcProductTypePrice::orderBy('price_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->price_id;
    }
}
