<?php

namespace App\Models\Ad3d\ImportDetail;

use Illuminate\Database\Eloquent\Model;

class QcImportDetail extends Model
{
    protected $table = 'qc_import_detail';
    protected $fillable = ['detail_id', 'price', 'amount', 'totalMoney', 'created_at', 'import_id', 'tool_id', 'supplies_id', 'newName', 'product_id'];
    protected $primaryKey = 'detail_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- thêm ----------
    public function insert($price, $amount, $totalMoney, $importId, $toolId = null, $suppliesId = null, $newName = null, $productId = null)
    {
        $hFunction = new \Hfunction();
        $modelImportDetail = new QcImportDetail();
        $modelImportDetail->price = $price;
        $modelImportDetail->amount = $amount;
        $modelImportDetail->totalMoney = $totalMoney;
        $modelImportDetail->import_id = $importId;
        $modelImportDetail->tool_id = $toolId;
        $modelImportDetail->supplies_id = $suppliesId;
        $modelImportDetail->newName = $newName;
        $modelImportDetail->product_id = $productId;
        $modelImportDetail->created_at = $hFunction->createdAt();
        if ($modelImportDetail->save()) {
            $this->lastId = $modelImportDetail->detail_id;
            return true;
        } else {
            return false;
        }
    }

    public function insertGetId()
    {
        return $this->lastId;
    }

    // cập nhật thông tin
    public function updateInfo($detailId, $price, $amount, $totalMoney, $toolId, $suppliesId, $newName)
    {
        return QcImportDetail::where('detail_id', $detailId)->update([
            'price' => $price,
            'amount' => $amount,
            'totalMoney' => $totalMoney,
            'tool_id' => $toolId,
            'supplies_id' => $suppliesId,
            'newName' => $newName
        ]);
    }

    public function deleteDetail($detailId = null)
    {
        $detailId = (empty($detailId)) ? $this->paymentId() : $detailId;
        return QcImportDetail::where('detail_id', $detailId)->delete();
    }
    //========== ========= ========= CAC MOI QUAN HE DU LIEU ========== ========= ==========
    //---------- san pham-----------
    public function product()
    {
        return $this->belongsTo('App\Models\Ad3d\Product\QcProduct', 'product_id', 'product_id');
    }

    //---------- thong tin nhap -----------
    public function import()
    {
        return $this->belongsTo('App\Models\Ad3d\Import\QcImport', 'import_id', 'import_id');
    }

    public function infoOfImport($importId)
    {
        return QcImportDetail::where('import_id', $importId)->get();
    }

    public function totalMoneyOfImport($importId)
    {
        return QcImportDetail::where('import_id', $importId)->sum('totalMoney');
    }

    public function totalMoneyOfListImport($listImportId)
    {
        return QcImportDetail::whereIn('import_id', $listImportId)->sum('totalMoney');
    }
    //---------- công cụ -----------
    public function tool()
    {
        return $this->belongsTo('App\Models\Ad3d\Tool\QcTool', 'tool_id', 'tool_id');
    }


    //---------- vật tư -----------
    public function supplies()
    {
        return $this->belongsTo('App\Models\Ad3d\Supplies\QcSupplies', 'supplies_id', 'supplies_id');
    }

    //========= ========== ========== GET INFO ========== ========== ==========
    public function getInfo($detailId = '', $field = '')
    {
        if (empty($detailId)) {
            return QcImportDetail::get();
        } else {
            $result = QcImportDetail::where('detail_id', $detailId)->first();
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
            return QcImportDetail::where('detail_id', $objectId)->pluck($column);
        }
    }

    public function detailId()
    {
        return $this->detail_id;
    }

    public function price($detailId = null)
    {
        return $this->pluck('price', $detailId);
    }


    public function amount($detailId = null)
    {

        return $this->pluck('amount', $detailId);
    }

    public function totalMoney($detailId = null)
    {

        return $this->pluck('totalMoney', $detailId);
    }

    public function createdAt($detailId = null)
    {
        return $this->pluck('created_at', $detailId);
    }

    public function importId($detailId = null)
    {
        return $this->pluck('import_id', $detailId);
    }

    public function toolId($detailId = null)
    {
        return $this->pluck('tool_id', $detailId);
    }

    public function suppliesId($detailId = null)
    {
        return $this->pluck('supplies_id', $detailId);
    }

    public function newName($detailId = null)
    {
        return $this->pluck('newName', $detailId);
    }

    public function productId($detailId = null)
    {
        return $this->pluck('product_id', $detailId);
    }

    // last id
    public function lastId()
    {
        $result = QcImportDetail::orderBy('detail_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->detail_id;
    }

    #============ =========== ============ STATISTICAL ============= =========== ==========
    public function totalAmountOfImport($importId)
    {
        return QcImportDetail::whereIn('detail_id', $importId)->sum('money');
    }
}
