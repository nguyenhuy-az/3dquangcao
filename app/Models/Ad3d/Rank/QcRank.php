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

    # get new id
    public function insertGetId()
    {
        return $this->lastId;
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
        if (empty($rankId)) $rankId = $this->departmentId();
        return QcRank::where('rank_id', $rankId)->delete();
    }

    #----------- department-staff ------------
    public function departmentStaff()
    {
        return $this->hasMany('App\Models\Ad3d\DepartmentStaff\QcDepartmentStaff', 'rank_id', 'rank_id');
    }

    #============ =========== ============ GET INFO ============= =========== ==========
    public function getInfo($rankId = '', $field = '')
    {
        if (empty($rankId)) {
            return QcRank::get();
        } else {
            $result = QcRank::where('rank_id', $rankId)->first();
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
        $result = QcRank::select('rank_id as optionKey', 'name as optionValue')->get()->toArray();
        return $hFunction->option($result, $selected);
    }

    public function pluck($column, $objectId = null)
    {
        if (empty($objectId)) {
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

    // ID cấp quản lý
    public function manageRankId()
    {
        return 1;
    }

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
        $result = QcRank::where('name', $name)->count();
        return ($result > 0) ? true : false;
    }

    public function existEditName($rankId, $name)
    {
        $result = QcRank::where('name', $name)->where('rank_id', '<>', $rankId)->count();
        return ($result > 0) ? true : false;
    }

    // cấp quản lý
    public function checkManageRank($rankId)
    {
        return ((empty($rankId) ? $this->rankId() : $rankId) == 1) ? true : false;
    }

    // cấp thông thường
    public function checkNormalRank($rankId)
    {
        return ((empty($rankId) ? $this->rankId() : $rankId) == 2) ? true : false;
    }
}
