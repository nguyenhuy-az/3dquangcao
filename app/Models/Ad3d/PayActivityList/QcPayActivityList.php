<?php

namespace App\Models\Ad3d\PayActivityList;

use Illuminate\Database\Eloquent\Model;

class QcPayActivityList extends Model
{
    protected $table = 'qc_pay_activity_list';
    protected $fillable = ['payList_id', 'name', 'description', 'type', 'created_at'];
    protected $primaryKey = 'payList_id';
    public $timestamps = false;

    private $lastId;

    #mac dinh mo ta
    public function getDefaultDescription()
    {
        return null;
    }

    # mac dinh phi co dinh
    public function getDefaultTypeHasPermanent()
    {
        return 1;
    }

    # mac dinh bien phi
    public function getDefaultTypeNotPermanent()
    {
        return 2;
    }

    ##---------- them moi ----------
    # kiem tra id
    public function checkIdNull($payListId)
    {
        $hFunction = new \Hfunction();
        return ($hFunction->checkEmpty($payListId)) ? $this->payListId() : $payListId;
    }

    public function insert($name, $description, $type)
    {
        $hFunction = new \Hfunction();
        $modelPayActivityList = new QcPayActivityList();
        $modelPayActivityList->name = $name;
        $modelPayActivityList->description = $description;
        $modelPayActivityList->type = $type;
        $modelPayActivityList->created_at = $hFunction->createdAt();
        if ($modelPayActivityList->save()) {
            $this->lastId = $modelPayActivityList->payList_id;
            return true;
        } else {
            return false;
        }
    }

    # lay id moi them
    public function insertGetId()
    {
        return $this->lastId;
    }

    #----------- cap nhat thong tin ----------
    public function updateInfo($payListId, $name, $description, $type)
    {
        return QcPayActivityList::where('payList_id', $payListId)->update([
            'name' => $name,
            'description' => $description,
            'type' => $type,
        ]);
    }

    # xoa
    public function actionDelete($payListId = null)
    {
        return QcPayActivityList::where('payList_id', $this->checkIdNull($payListId))->delete();
    }
    #============ =========== ============ CAC MOI QUAN HE DU LIEU ============= =========== ==========
    #----------- Chi hoat dong ------------
    public function payActivityDetail()
    {
        return $this->hasMany('App\Models\Ad3d\PayActivityDetail\QcPayActivityDetail', 'staff_id', 'staff_id');
    }

    #============ =========== ============ LAY THONG TIN CO BAN ============= =========== ==========
    public function selectInfo($orderBy = 'ASC')
    {
        return QcPayActivityList::orderBy('name', $orderBy)->select('*');
    }

    public function getInfo($payListId = null, $field = null)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($payListId)) {
            return QcPayActivityList::get();
        } else {
            $result = QcPayActivityList::where('payList_id', $payListId)->first();
            if ($hFunction->checkEmpty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }

    # create option
    public function getOption($selected = null)
    {
        $hFunction = new \Hfunction();
        $result = QcPayActivityList::select('payList_id as optionKey', 'name as optionValue')->get()->toArray();
        return $hFunction->option($result, $selected);
    }

    public function pluck($column, $objectId = null)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($objectId)) {
            return $this->$column;
        } else {
            return QcPayActivityList::where('payList_id', $objectId)->pluck($column)[0];
        }
    }

    #----------- thong tin co ban -------------
    public function payListId()
    {
        return $this->payList_id;
    }

    public function name($payListId = null)
    {
        return $this->pluck('name', $payListId);
    }

    public function description($payListId = null)
    {
        return $this->pluck('description', $payListId);
    }

    public function type($payListId = null)
    {
        return $this->pluck('type', $payListId);
    }


    public function createdAt($payListId = null)
    {
        return $this->pluck('created_at', $payListId);
    }

    # tong mau tin
    public function totalRecords()
    {
        return QcPayActivityList::count();
    }

    public function typeLabel($payListId = null)
    {
        if ($this->checkTypePermanent($payListId)) {
            return 'Cố định';
        } elseif ($this->checkTypeVariable($payListId)) {
            return 'Không cố định';
        } else {
            return 'Chưa xác định';
        }
    }

    # kiem tra lai loai phi khong co đinh
    public function checkTypeVariable($payListId = null)
    {
        return ($this->type($payListId) == $this->getDefaultTypeNotPermanent()) ? true : false;
    }

    # kiem tra lai loai phi la co đinh
    public function checkTypePermanent($payListId = null)
    {
        return ($this->type($payListId) == $this->getDefaultTypeNotPermanent()) ? true : false;
    }
    #============ =========== ============ KIEM TRA THONG TIN ============= =========== ==========
    #TON TAI TEN
    public function existName($name)
    {
        return QcPayActivityList::where('name', $name)->exists();
    }

    public function existEditName($payListId, $name)
    {
        return QcPayActivityList::where('name', $name)->where('payList_id', '<>', $payListId)->exists();
    }
}
