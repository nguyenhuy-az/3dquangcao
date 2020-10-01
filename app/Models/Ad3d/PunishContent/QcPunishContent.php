<?php

namespace App\Models\Ad3d\PunishContent;

use Illuminate\Database\Eloquent\Model;

class QcPunishContent extends Model
{
    protected $table = 'qc_punish_content';
    protected $fillable = ['punish_id', 'punishCode', 'name', 'money', 'note', 'created_at', 'type_id'];
    protected $primaryKey = 'punish_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- thêm ----------

    // insert
    public function insert($punishCode, $name, $money, $note, $typeId)
    {
        $hFunction = new \Hfunction();
        $modelPunishContent = new QcPunishContent();
        // insert
        $modelPunishContent->name = $name;
        $modelPunishContent->punishCode = $punishCode;
        $modelPunishContent->money = $money;
        $modelPunishContent->note = $note;
        $modelPunishContent->type_id = $typeId;
        $modelPunishContent->created_at = $hFunction->createdAt();
        if ($modelPunishContent->save()) {
            $this->lastId = $modelPunishContent->punish_id;
            return true;
        } else {
            return false;
        }
    }

    // lấy id mới thêm
    public function insertGetId()
    {
        return $this->lastId;
    }

    // cập nhật thông tin
    public function updateInfo($punishId, $name, $money, $note, $typeId)
    {
        return QcPunishContent::where('punish_id', $punishId)->update([
            'name' => $name,
            'money' => $money,
            'note' => $note,
            'type_id' => $typeId
        ]);
    }

    public function deleteInfo($punishId = null)
    {
        $punishId = (empty($punishId)) ? $this->punishId() : $punishId;
        return QcPunishContent::where('punish_id', $punishId)->delete();
    }
    //========== ========= ========= RELATION ========== ========= ==========
    //---------- lĩnh vực phạt -----------
    public function punishType()
    {
        return $this->belongsTo('App\Models\Ad3d\PunishType\QcPunishType', 'type_id', 'type_id');
    }

    //---------- phạt -----------
    public function minusMoney()
    {
        return $this->hasMany('App\Models\Ad3d\MinusMoney\QcMinusMoney', 'punish_id', 'punish_id');
    }

    //========= ========== ========== GET INFO ========== ========== ==========
    # lay danh muc phat truc tiep - co qui dinh muc phat truc tiep
    public function getInfoForDirectMinusMoney()
    {
        return QcPunishContent::where('money', '>', 0)->orderBy('name', 'ASC')->get();
    }
    # lay tat ca thong tin
    public function selectInfoAll()
    {
        return QcPunishContent::orderBy('name', 'ASC')->select('*');
    }

    public function selectInfoByPunishType($punishTypeId)
    {
        return QcPunishContent::where('type_id', $punishTypeId)->orderBy('name', 'ASC')->select('*');
    }

    public function getInfoById($punishId)
    {
        return QcPunishContent::where('punish_id', $punishId)->first();
    }

    public function getInfo($punishId = '', $field = '')
    {
        if (empty($punishId)) {
            return QcPunishContent::get();
        } else {
            $result = QcPunishContent::where('punish_id', $punishId)->first();
            if (empty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }

    public function getInfoOrderName($punishId = '', $field = '')
    {
        if (empty($punishId)) {
            return QcPunishContent::orderBy('name', 'ASC')->get();
        } else {
            $result = QcPunishContent::where('punish_id', $punishId)->orderBy('name', 'ASC')->first();
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
            return QcPunishContent::where('punish_id', $objectId)->pluck($column);
        }
    }

    public function punishId()
    {
        return $this->punish_id;
    }

    public function punishCode($punishId = null)
    {
        return $this->pluck('punishCode', $punishId);
    }

    public function name($punishId = null)
    {
        return $this->pluck('name', $punishId);
    }


    public function money($punishId = null)
    {

        return $this->pluck('money', $punishId);
    }

    public function note($punishId = null)
    {

        return $this->pluck('note', $punishId);
    }

    public function createdAt($punishId = null)
    {
        return $this->pluck('created_at', $punishId);
    }

    public function typeId($punishId = null)
    {
        return $this->pluck('type_id', $punishId);
    }

    // total records
    public function totalRecords()
    {
        return QcPunishContent::count();
    }

    // last id
    public function lastId()
    {
        $result = QcPunishContent::orderBy('punish_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->punish_id;
    }

    public function punishIdOfLateWork()
    {
        return QcPunishContent::where('punishCode', 'ĐLTKP')->pluck('punish_id');
    }

    public function punishIdOfOffWork()
    {
        return QcPunishContent::where('punishCode', 'NLKP')->pluck('punish_id');
    }

    public function punishIdOfTimekeepingAccuracy()
    {
        return QcPunishContent::where('punishCode', 'BGKĐ')->pluck('punish_id');
    }

    // ---------- ---------- CHECK INFO --------- -------
    public function existName($name)
    {
        $result = QcPunishContent::where('name', $name)->count();
        return ($result > 0) ? true : false;
    }

    public function existEditName($punishId, $name)
    {
        $result = QcPunishContent::where('name', $name)->where('punish_id', '<>', $punishId)->count();
        return ($result > 0) ? true : false;
    }

    public function existPunishCode($punishCode)
    {
        $result = QcPunishContent::where('punishCode', $punishCode)->count();
        return ($result > 0) ? true : false;
    }

    public function existEditPunishCode($punishId, $punishCode)
    {
        $result = QcPunishContent::where('punishCode', $punishCode)->where('punish_id', '<>', $punishId)->count();
        return ($result > 0) ? true : false;
    }

    #============= ======== lay id cua danh muc phat =========== ==========
    # lay Id theo  ma phat
    public function getPunishIdByCode($punishCode)
    {
        $result = QcPunishContent::where('punishCode', $punishCode)->pluck('punish_id');
        return (count($result) > 0) ? $result : null;
    }

    # ma phat boi thuong vat tu thi cong
    public function getPunishIdForMinusMoneySupplies()
    {
        return $this->getPunishIdByCode('BTVTTC');
    }

    # ban giao don hang tre
    public function getPunishIdOfOrderAllocationLate()
    {
        return $this->getPunishIdByCode('BGĐHT');
    }

    # quan ly đơn hàng thi cong tre
    public function getPunishIdOfOrderConstructionLate()
    {
        return $this->getPunishIdByCode('QLĐHTCT');
    }

    # thi cong khong dem do nghe
    public function getPunishIdNotBringTool()
    {
        return $this->getPunishIdByCode('TCKMĐN');
    }

    # lam mat đo nghe
    public function getPunishIdLostPublicTool()
    {
        return $this->getPunishIdByCode('LMĐNDC');
    }

    # bao cao lma mat do nghe khong dung
    public function getPunishIdWrongReportLostTool()
    {
        return $this->getPunishIdByCode('BCLMĐNKĐ');
    }
}
