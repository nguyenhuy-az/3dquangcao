<?php

namespace App\Models\Ad3d\PunishType;

use Illuminate\Database\Eloquent\Model;

class QcPunishType extends Model
{
    protected $table = 'qc_punish_type';
    protected $fillable = ['type_id', 'name','created_at'];
    protected $primaryKey = 'type_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== INSERT && UPDATE ========== ========== ==========
    #---------- Insert ----------
    public function insert($name)
    {
        $hFunction = new \Hfunction();
        $modelPunishType = new QcPunishType();
        $modelPunishType->name = $name;
        $modelPunishType->created_at = $hFunction->createdAt();
        if ($modelPunishType->save()) {
            $this->lastId = $modelPunishType->type_id;
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
        return QcPunishType::where('type_id', $typeId)->update([
            'name' => $name
        ]);
    }

    # delete
    public function deleteInfo($typeId = null)
    {
        return QcPunishType::where('type_id', $typeId)->delete();
    }

    #========== ========== ========== RELATION ========== ========== ==========
    #----------- qc-punish-content ------------
    public function punishContent()
    {
        return $this->hasMany('App\Models\Ad3d\PunishContent\QcPunishContent', 'type_id', 'type_id');
    }

    #============ =========== ============ GET INFO ============= =========== ==========
    public function getInfo($typeId = '', $field = '')
    {
        if (empty($typeId)) {
            return QcPunishType::get();
        } else {
            $result = QcPunishType::where('type_id', $typeId)->first();
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
        $result = QcPunishType::select('type_id as optionKey', 'name as optionValue')->get()->toArray();
        return $hFunction->option($result, $selected);
    }

    public function pluck($column, $objectId = null)
    {
        if (empty($objectId)) {
            return $this->$column;
        } else {
            return QcPunishType::where('type_id', $objectId)->pluck($column);
        }
    }

    #----------- punish type info -------------
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
        return QcPunishType::count();
    }

    #============ =========== ============ CHECK INFO ============= =========== ==========
    # exist name (add new)
    public function existName($name)
    {
        $result = QcPunishType::where('name', $name)->count();
        return ($result > 0) ? true : false;
    }

    public function existEditName($typeId, $name)
    {
        $result = QcPunishType::where('name', $name)->where('type_id', '<>', $typeId)->count();
        return ($result > 0) ? true : false;
    }
}
