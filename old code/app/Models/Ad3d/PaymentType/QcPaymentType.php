<?php

namespace App\Models\Ad3d\PaymentType;

use Illuminate\Database\Eloquent\Model;

class QcPaymentType extends Model
{
    protected $table = 'qc_payment_type';
    protected $fillable = ['type_id', 'name','created_at'];
    protected $primaryKey = 'type_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== INSERT && UPDATE ========== ========== ==========
    #---------- Insert ----------
    public function insert($name)
    {
        $hFunction = new \Hfunction();
        $modelPaymentType = new QcPaymentType();
        $modelPaymentType->name = $name;
        $modelPaymentType->created_at = $hFunction->createdAt();
        if ($modelPaymentType->save()) {
            $this->lastId = $modelPaymentType->type_id;
            return true;
        } else {
            return false;
        }
    }

    # get new id
    public function insertGetId()
    {
        return $this->lastId;
    }

    #----------- update ----------
    public function updateInfo($typeId, $name)
    {
        return QcPaymentType::where('type_id', $typeId)->update([
            'name' => $name
        ]);
    }

    # delete
    public function actionDelete($typeId = null)
    {
        if (empty($typeId)) $typeId = $this->typeId();
        return QcPaymentType::where('type_id', $typeId)->update(['action' => 0]);
    }

    #========== ========== ========== RELATION ========== ========== ==========
    #----------- TF-PRODUCT ------------
    public function product()
    {
        return $this->hasMany('App\Models\Ad3d\Payment\QcPayment', 'type_id', 'type_id');
    }

    #============ =========== ============ GET INFO ============= =========== ==========
    public function infoActivity()
    {
        return QcPaymentType::where('action', 1)->orderBy('name', 'ASC')->get();
    }

    public function getInfo($typeId = '', $field = '')
    {
        if (empty($typeId)) {
            return QcPaymentType::get();
        } else {
            $result = QcPaymentType::where('type_id', $typeId)->first();
            if (empty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }

    # create option
    public function getOption($selected = '')
    {
        $hFunction = new \Hfunction();
        $result = QcPaymentType::select('type_id as optionKey', 'name as optionValue')->get()->toArray();
        return $hFunction->option($result, $selected);
    }

    public function pluck($column, $objectId = null)
    {
        if (empty($objectId)) {
            return $this->$column;
        } else {
            return QcPaymentType::where('type_id', $objectId)->pluck($column);
        }
    }

    #----------- DEPARTMENT INFO -------------
    public function typeId()
    {
        return $this->type_id;
    }

    public function name($typeId = null)
    {
        return $this->pluck('name', $typeId);
    }

    public function createdAt($typeId = null)
    {
        return $this->pluck('created_at', $typeId);
    }

    # total record
    public function totalRecords()
    {
        return QcPaymentType::count();
    }

    #============ =========== ============ CHECK INFO ============= =========== ==========
    # exist name (add new)
    public function existName($name)
    {
        $result = QcPaymentType::where('name', $name)->count();
        return ($result > 0) ? true : false;
    }

    public function existEditName($typeId, $name)
    {
        $result = QcPaymentType::where('name', $name)->where('type_id', '<>', $typeId)->count();
        return ($result > 0) ? true : false;
    }
}
