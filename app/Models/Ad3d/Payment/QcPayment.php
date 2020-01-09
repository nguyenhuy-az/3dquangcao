<?php

namespace App\Models\Ad3d\Payment;

use App\Models\Ad3d\Staff\QcStaff;
use Illuminate\Database\Eloquent\Model;

class QcPayment extends Model
{
    protected $table = 'qc_payment';
    protected $fillable = ['payment_id', 'paymentCode', 'money', 'datePay', 'note', 'created_at', 'type_id', 'staff_id', 'company_id'];
    protected $primaryKey = 'payment_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- Insert ----------

    // insert
    public function insert($money, $datePay, $note, $typeId, $staffId, $companyId = null)
    {
        $hFunction = new \Hfunction();
        $modelPayment = new QcPayment();
        $modelStaff = new QcStaff();
        //create code
        $nameCode = $hFunction->getTimeCode();
        // insert
        $modelPayment->paymentCode = $nameCode;
        $modelPayment->money = $money;
        $modelPayment->datePay = $datePay;
        $modelPayment->note = $note;
        $modelPayment->type_id = $typeId;
        $modelPayment->company_id = (empty($companyId)) ? $modelStaff->companyId($staffId) : $companyId;
        $modelPayment->staff_id = $staffId;
        $modelPayment->created_at = $hFunction->createdAt();
        if ($modelPayment->save()) {
            $this->lastId = $modelPayment->payment_id;
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

    // insert
    public function updateInfo($paymentId, $money, $datePay, $note, $typeId, $companyId)
    {
        return QcPayment::where('payment_id', $paymentId)->update([
            'money' => $money,
            'datePay' => $datePay,
            'note' => $note,
            'company_id' => $companyId,
            'type_id' => $typeId
        ]);
    }

    public function deletePayment($paymentId = null)
    {
        $paymentId = (empty($paymentId)) ? $this->paymentId() : $paymentId;
        return QcPayment::where('payment_id', $paymentId)->delete();
    }
    //========== ========= ========= RELATION ========== ========= ==========
    //---------- payment TYPE -----------
    public function paymentType()
    {
        return $this->belongsTo('App\Models\Ad3d\PaymentType\QcPaymentType', 'type_id', 'type_id');
    }

    //---------- staff -----------
    public function staff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staff_id', 'staff_id');
    }

    //kiển tra người nhập
    public function checkStaffInput($staffId, $paymentId = null)
    {
        $paymentId = (empty($paymentId)) ? $this->paymentId() : $paymentId;
        return (QcPayment::where('staff_id', $staffId)->where('payment_id', $paymentId)->count() > 0) ? true : false;
    }

    //---------- company -----------
    public function company()
    {
        return $this->belongsTo('App\Models\Ad3d\Company\QcCompany', 'company_id', 'company_id');
    }

    //========= ========== ========== GET INFO ========== ========== ==========
    public function getInfo($paymentId = '', $field = '')
    {
        if (empty($paymentId)) {
            return qcPayment::get();
        } else {
            $result = QcPayment::where('payment_id', $paymentId)->first();
            if (empty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }

    // ---------- ---------- STAFF INFO --------- -------
    public function infoFromPaymentCode($paymentCode)
    {
        return QcPayment::where('paymentCode', $paymentCode)->first();
    }

    public function pluck($column, $objectId = null)
    {
        if (empty($objectId)) {
            return $this->$column;
        } else {
            return QcPayment::where('payment_id', $objectId)->pluck($column);
        }
    }

    public function paymentId()
    {
        return $this->payment_id;
    }

    public function paymentCode($paymentId = null)
    {
        return $this->pluck('paymentCode', $paymentId);
    }


    public function money($paymentId = null)
    {

        return $this->pluck('money', $paymentId);
    }

    public function datePay($paymentId = null)
    {

        return $this->pluck('datePay', $paymentId);
    }

    public function note($paymentId = null)
    {

        return $this->pluck('note', $paymentId);
    }

    public function createdAt($paymentId = null)
    {
        return $this->pluck('created_at', $paymentId);
    }

    public function typeId($paymentId = null)
    {
        return $this->pluck('type_id', $paymentId);
    }

    public function companyId($paymentId = null)
    {
        return $this->pluck('company_id', $paymentId);
    }

    public function staffId($paymentId = null)
    {
        return $this->pluck('staff_id', $paymentId);
    }

    // total records
    public function totalRecords()
    {
        return QcPayment::count();
    }

    // last id
    public function lastId()
    {
        $result = QcPayment::orderBy('payment_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->payment_id;
    }

    #============ =========== ============ STATISTICAL ============= =========== ==========
    public function totalPaidOfCompany($listCompanyId, $dateFilter = null)
    {
        if (empty($dateFilter)) {
            return QcPayment::whereIn('company_id', $listCompanyId)->sum('money');
        } else {
            return QcPayment::whereIn('company_id', $listCompanyId)->where('datePay', 'like', "%$dateFilter%")->sum('money');
        }
    }

    public function totalPaidOfCompanyStaffTypeDate($listCompanyId, $staffId, $paymentTypeId, $dateFilter = null)
    {
        if (empty($dateFilter)) {
            return QcPayment::whereIn('company_id', $listCompanyId)->where('staff_id',$staffId)->where('type_id', $paymentTypeId)->sum('money');
        } else {
            return QcPayment::whereIn('company_id', $listCompanyId)->where('staff_id',$staffId)->where('type_id', $paymentTypeId)->where('datePay', 'like', "%$dateFilter%")->sum('money');
        }
    }

    public function totalPaidOfStaffAndCompany($listCompanyId, $staffId, $dateFilter = null)
    {
        if($dateFilter == null){
            return QcPayment::whereIn('company_id', $listCompanyId)->where('staff_id', $staffId)->sum('money');
        }else{
            return QcPayment::whereIn('company_id', $listCompanyId)->where('staff_id', $staffId)->where('datePay', 'like', "%$dateFilter%")->sum('money');
        }

    }


    public function infoStaffPayment($listCompanyId, $dateFilter = null)
    {
        if (empty($dateFilter)) {
            return QcPayment::whereIn('company_id', $listCompanyId)->groupBy('staff_id')->pluck('staff_id');
        } else {
            return QcPayment::whereIn('company_id', $listCompanyId)->where('datePay', 'like', "%$dateFilter%")->groupBy('staff_id')->pluck('staff_id');
        }
    }
}
