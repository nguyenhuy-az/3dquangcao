<?php

namespace App\Models\Ad3d\ImportPay;

use App\Models\Ad3d\Import\QcImport;
use Illuminate\Database\Eloquent\Model;

class QcImportPay extends Model
{
    protected $table = 'qc_import_pay';
    protected $fillable = ['pay_id', 'money', 'confirmStatus', 'confirmDate', 'created_at', 'import_id', 'staff_id'];
    protected $primaryKey = 'pay_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== INSERT && UPDATE ========== ========== ==========
    #---------- Insert ----------
    public function insert($money, $importId, $payStaffId)
    {
        $hFunction = new \Hfunction();
        $modelImportPay = new QcImportPay();
        $modelImportPay->money = $money;
        $modelImportPay->import_id = $importId;
        $modelImportPay->staff_id = $payStaffId;
        $modelImportPay->created_at = $hFunction->createdAt();
        if ($modelImportPay->save()) {
            $this->lastId = $modelImportPay->pay_id;
            return true;
        } else {
            return false;
        }
    }

    public function insertGetId()
    {
        return $this->lastId;
    }


    # xóa
    public function actionDelete($payId = null)
    {
        if (empty($payId)) $payId = $this->payId();
        return QcImportPay::where('pay_id', $payId)->delete();
    }

    #========== ========== ========== RELATION ========== ========== ==========
    #----------- thông tin nhập ------------
    public function import()
    {
        return $this->belongsTo('App\Models\Ad3d\Import\QcImport', 'import_id', 'import_id');
    }

    public function checkPayOfImport($importId)
    {
        $result = QcImportPay::where('import_id', $importId)->where('confirmStatus', 1)->get();
        return (count($result) > 0) ? true : false;
    }

    public function updateConfirmPayOfImport($importId)
    {
        $hFunction = new \Hfunction();
        return QcImportPay::where('import_id', $importId)->update(['confirmStatus' => 1, 'confirmDate' => $hFunction->createdAt()]);
    }

    public function totalMoneyOfImportStaffAndDate($staffId, $date)
    {
        $modelImport = new QcImport();
        $listImportId = $modelImport->listIdOfStaff($staffId, null, 3);
        if (!empty($date)) {
            return QcImportPay::whereIn('import_id', $listImportId)->where('created_at', 'like', "%$date%")->sum('money');
        } else {
            return QcImportPay::whereIn('import_id', $listImportId)->sum('money');
        }

    }

    public function totalMoneyOfPayStaffAndDate($staffId, $date)
    {
        if (!empty($date)) {
            return QcImportPay::where('staff_id', $staffId)->where('created_at', 'like', "%$date%")->sum('money');
        } else {
            return QcImportPay::where('staff_id', $staffId)->sum('money');
        }

    }

    public function totalMoneyOfPayListStaffAndDate($listStaffId, $date)
    {
        if (!empty($date)) {
            return QcImportPay::whereIn('staff_id', $listStaffId)->where('created_at', 'like', "%$date%")->sum('money');
        } else {
            return QcImportPay::whereIn('staff_id', $listStaffId)->sum('money');
        }

    }
    /*public function totalMoneyOfListImport($listImport, $date)
    {
        $modelImport = new QcImport();
        if (!empty($date)) {
            return QcOrderPay::whereIn('import_id', $listImport)->where('created_at', 'like', "%$date%")->sum('money');
        } else {
            return QcOrderPay::whereIn('import_id', $listImport)->sum('money');
        }

    }*/
    #============ =========== ============ GET INFO ============= =========== ==========
    public function getInfo($payId = '', $field = '')
    {
        if (empty($payId)) {
            return QcImportPay::get();
        } else {
            $result = QcImportPay::where('pay_id', $payId)->first();
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
            return QcImportPay::where('pay_id', $objectId)->pluck($column);
        }
    }

    #----------- thông tin thanh toán-------------
    public function payId()
    {
        return $this->pay_id;
    }

    public function money($payId = null)
    {
        return $this->pluck('money', $payId);
    }

    public function confirmStatus($payId = null)
    {
        return $this->pluck('confirmStatus', $payId);
    }

    public function importId($payId = null)
    {
        return $this->pluck('import_id', $payId);
    }

    public function staffId($payId = null)
    {
        return $this->pluck('staff_id', $payId);
    }

    public function createdAt($payId = null)
    {
        return $this->pluck('created_at', $payId);
    }

    # lay import id da thanh toan
    public function listImportId()
    {
        return QcImportPay::select()->pluck('import_id');
    }
}
