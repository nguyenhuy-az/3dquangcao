<?php

namespace App\Models\Ad3d\Rank;

use Illuminate\Database\Eloquent\Model;

class QcRank extends Model
{
    protected $table = 'qc_ranks';
    protected $fillable = ['rank_id', 'name', 'description', 'created_at'];
    protected $primaryKey = 'rank_id';
    public $timestamps = false;

    private $lastId;

    #mac dinh mo ta
    public function getDefaultDescription()
    {
        return null;
    }
    #========== ========== ========== INSERT && UPDATE ========== ========== ==========
    #---------- Insert ----------
    public function insert($name, $description)
    {
        $hFunction = new \Hfunction();
        $modelDepartment = new QcRank();
        $modelDepartment->name = $name;
        $modelDepartment->description = $description;
        $modelDepartment->created_at = $hFunction->createdAt();
        if ($modelDepartment->save()) {
            $this->lastId = $modelDepartment->rank_id;
            return true;
        } else {
            return false;
        }
    }

    # ma cap bac moi them
    public function insertGetId()
    {
        return $this->lastId;
    }

    # kiem tra Id
    public function checkNullId($id = null)
    {
        $hFunction = new \Hfunction();
        return ($hFunction->checkEmpty($id)) ? $id : $this->rankId();
    }

    #----------- update ----------
    public function updateInfo($rankId, $name, $description)
    {
        return QcRank::where('rank_id', $rankId)->update([
            'description' => $description,
            'name' => $name,
        ]);
    }

    # delete
    public function actionDelete($rankId = null)
    {
        return QcRank::where('rank_id', $this->checkNullId($rankId))->delete();
    }

    #----------- department-staff ------------
    public function departmentStaff()
    {
        return $this->hasMany('App\Models\Ad3d\DepartmentStaff\QcDepartmentStaff', 'rank_id', 'rank_id');
    }

    #----------- thuong theo cap bac-----------
    public function bonusDepartment()
    {
        return $this->hasMany('App\Models\Ad3d\BonusDepartment\QcBonusDepartment', 'rank_id', 'rank_id');
    }

    #============ =========== ============ GET INFO ============= =========== ==========
    public function getInfo($rankId = '', $field = '')
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($rankId)) {
            return QcRank::get();
        } else {
            $result = QcRank::where('rank_id', $rankId)->first();
            if ($hFunction->checkEmpty($field)) {
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
        $result = QcRank::select('rank_id as optionKey', 'name as optionValue')->get()->toArray();
        return $hFunction->option($result, $selected);
    }

    public function pluck($column, $objectId = null)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($objectId)) {
            return $this->$column;
        } else {
            return QcRank::where('rank_id', $objectId)->pluck($column);
        }
    }


    #----------- RANK INFO -------------
    public function rankId()
    {
        return $this->rank_id;
    }

    public function name($rankId = null)
    {
        return $this->pluck('name', $rankId);
    }

    public function description($rankId = null)
    {
        return $this->pluck('description', $rankId);
    }

    public function createdAt($rankId = null)
    {
        return $this->pluck('created_at', $rankId);
    }

    // mac dinh cap quan ly
    public function manageRankId()
    {
        return 1;
    }

    #mac din cap nhan vie
    public function staffRankId()
    {
        return 2;
    }

    public function rankIdReceiveNotifyNewOrder()
    {
        return $this->manageRankId();
    }

    #----------- CHECK INFO -------------
    public function existName($name)
    {
        return QcRank::where('name', $name)->exists();
    }

    public function existEditName($rankId, $name)
    {
        return QcRank::where('name', $name)->where('rank_id', '<>', $rankId)->exists();
    }

    // cấp quản lý
    public function checkManageRank($rankId=null)
    {
        return ($this->checkNullId($rankId) == $this->manageRankId()) ? true : false;
    }

    // cấp thông thường
    public function checkNormalRank($rankId=null)
    {
        return ($this->checkNullId($rankId) == $this->staffRankId()) ? true : false;
    }
}
