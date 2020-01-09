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

    #---------- them moi ----------
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

    # kiem tra id
    public function checkIdNull($payListId)
    {
        return (empty($payListId)) ? $this->payListId() : $payListId;
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

    public function getInfo($payListId = '', $field = '')
    {
        if (empty($payListId)) {
            return QcPayActivityList::get();
        } else {
            $result = QcPayActivityList::where('payList_id', $payListId)->first();
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
        $result = QcPayActivityList::select('payList_id as optionKey', 'name as optionValue')->get()->toArray();
        return $hFunction->option($result, $selected);
    }

    public function pluck($column, $objectId = null)
    {
        if (empty($objectId)) {
            return $this->$column;
        } else {
            return QcPayActivityList::where('payList_id', $objectId)->pluck($column);
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
        return ($this->type($payListId) == 1) ? 'Cố định' : 'Không cố định';
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
