<?php

namespace App\Models\Ad3d\TransfersDetail;

use App\Models\Ad3d\OrderPay\QcOrderPay;
use App\Models\Ad3d\Transfers\QcTransfers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class QcTransfersDetail extends Model
{
    protected $table = 'qc_transfers_detail';
    protected $fillable = ['detail_id', 'created_at', 'transfers_id', 'pay_id'];
    protected $primaryKey = 'detail_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== INSERT && UPDATE ========== ========== ==========
    #---------- Insert ----------
    public function insert($transfersId, $payId)
    {
        $hFunction = new \Hfunction();
        $modelTransfersDetail = new QcTransfersDetail();
        $modelTransfersDetail->transfers_id = $transfersId;
        $modelTransfersDetail->pay_id = $payId;
        $modelTransfersDetail->created_at = $hFunction->createdAt();
        if ($modelTransfersDetail->save()) {
            $this->lastId = $modelTransfersDetail->detail_id;
            return true;
        } else {
            return false;
        }
    }

    public function deleteDetail($detailId)
    {
        $modelTransfer = new QcTransfers();
        $modelOrderPay = new QcOrderPay();
        $transferId = $this->transfersId($detailId);
        $moneyPay = $modelOrderPay->money($this->payId($detailId));
        if (QcTransfersDetail::where('detail_id', $detailId)->delete()) {
            # cap nhat so tien chuyen
            $moneyTransfer = $modelTransfer->money($transferId);
            $moneyTransfer = (is_int($moneyTransfer)) ? $moneyTransfer : $moneyTransfer[0];
            $moneyPay = (is_int($moneyPay)) ? $moneyPay : $moneyPay[0];
            $modelTransfer->updateMoney($transferId, $moneyTransfer - $moneyPay);
        }
    }

    //---------- thanh toan  don hang -----------
    public function orderPay()
    {
        return $this->belongsTo('App\Models\Ad3d\OrderPay\QcOrderPay', 'pay_id', 'pay_id');
    }

    public function listPayId()
    {
        return QcTransfersDetail::select()->pluck('pay_id');
    }

    public function getInfoOfPay($payId)
    {
        return QcTransfersDetail::where('pay_id', $payId)->get();
    }

    //----------- chuyen tien ------------
    public function transfers()
    {
        return $this->belongsTo('App\Models\Ad3d\Transfers/QcTransfers', 'transfers_id', 'transfers_id');
    }

    public function infoOfTransfers($transfersId)
    {
        return QcTransfersDetail::where('transfers_id', $transfersId)->get();
    }

    #============ =========== ============ GET INFO ============= =========== ==========
    public function getInfo($detailId = '', $field = '')
    {
        if (empty($detailId)) {
            return QcTransfersDetail::get();
        } else {
            $result = QcTransfersDetail::where('detail_id', $detailId)->first();
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
            return QcTransfersDetail::where('detail_id', $objectId)->pluck($column);
        }
    }

    public function detailId()
    {
        return $this->detail_id;
    }

    public function transfersId($detailId = null)
    {
        return $this->pluck('transfers_id', $detailId);
    }

    public function payId($detailId = null)
    {
        return $this->pluck('pay_id', $detailId);
    }

    public function createdAt($detailId = null)
    {
        return $this->pluck('created_at', $detailId);
    }

}
