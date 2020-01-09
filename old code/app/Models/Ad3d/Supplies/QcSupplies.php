<?php

namespace App\Models\Ad3d\Supplies;

use Illuminate\Database\Eloquent\Model;

class QcSupplies extends Model
{
    protected $table = 'qc_supplies';
    protected $fillable = ['supplies_id', 'name', 'unit','confirmStatus','applyStatus', 'action', 'created_at'];
    protected $primaryKey = 'supplies_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== Thêm && cập nhật ========== ========== ==========
    #---------- Thêm ----------
    public function insert($name, $unit,$confirmStatus = 1, $applyStatus = 1)
    {
        $hFunction = new \Hfunction();
        $modelSupplies = new QcSupplies();
        $modelSupplies->name = $name;
        $modelSupplies->unit = $unit;
        $modelSupplies->confirmStatus = $confirmStatus;
        $modelSupplies->applyStatus = $applyStatus;
        $modelSupplies->created_at = $hFunction->createdAt();
        if ($modelSupplies->save()) {
            $this->lastId = $modelSupplies->supplies_id;
            return true;
        } else {
            return false;
        }
    }

    # lấy Id mới thêm
    public function insertGetId()
    {
        return $this->lastId;
    }

    #----------- cập nhật thông tin ----------
    public function updateInfo($suppliesId, $name, $unit)
    {
        return QcSupplies::where('supplies_id', $suppliesId)->update([
            'unit' => $unit,
            'name' => $name
        ]);
    }

    # xóa
    public function deleteSupplies($suppliesId = null)
    {
        if (empty($suppliesId)) $suppliesId = $this->suppliesId();
        return QcSupplies::where('supplies_id', $suppliesId)->update(['action' => 0]);
    }

    #========== ========== ========== Mối quan hệ ========== ========== ==========
    #----------- chi tiết nhập ------------
    public function importDetail()
    {
        return $this->hasMany('App\Models\Ad3d\ImportDetail\QcImportDetail', 'supplies_id', 'supplies_id');
    }


    #============ =========== ============ Lấy thông tin ============= =========== ==========
    public function selectAllInfo()
    {
        return QcSupplies::orderBy('name', 'ASC')->select('*');
    }

    public function getInfoByName($name)
    {
        return QcSupplies::where('name', $name)->first();
    }

    public function getInfo($suppliesId = '', $field = '')
    {
        if (empty($suppliesId)) {
            return QcSupplies::get();
        } else {
            $result = QcSupplies::where('supplies_id', $suppliesId)->first();
            if (empty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }

    public function getInfoActivity($oderBy = 'ASC')
    {
        return QcSupplies::where('action', 1)->orderBy('name', $oderBy)->get();
    }
    public function getInfoOrderByName($suppliesId = null, $field = null, $oderBy = 'ASC')
    {
        if (empty($suppliesId)) {
            return QcSupplies::orderBy('name', $oderBy)->get();
        } else {
            $result = QcSupplies::where('supplies_id', $suppliesId)->orderBy('name', $oderBy)->first();
            if (empty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }

    # tạo select box
    public function getOption($selected = '')
    {
        $hFunction = new \Hfunction();
        $result = QcSupplies::select('supplies_id as optionKey', 'name as optionValue')->get()->toArray();
        return $hFunction->option($result, $selected);
    }

    public function pluck($column, $objectId = null)
    {
        if (empty($objectId)) {
            return $this->$column;
        } else {
            return QcSupplies::where('supplies_id', $objectId)->pluck($column);
        }
    }

    #----------- thông tin -------------
    public function suppliesId()
    {
        return $this->supplies_id;
    }

    public function unit($suppliesId = null)
    {
        return $this->pluck('unit', $suppliesId);
    }

    public function name($suppliesId = null)
    {
        return $this->pluck('name', $suppliesId);
    }

    public function confirmStatus($suppliesId = null)
    {
        return $this->pluck('confirmStatus', $suppliesId);
    }
    public function applyStatus($suppliesId = null)
    {
        return $this->pluck('applyStatus', $suppliesId);
    }
    public function action($suppliesId = null)
    {
        return $this->pluck('action', $suppliesId);
    }

    public function createdAt($suppliesId = null)
    {
        return $this->pluck('created_at', $suppliesId);
    }

    # tổng mẫu tin
    public function totalRecords()
    {
        return QcSupplies::count();
    }

    #============ =========== ============ kiểm tra thông tin ============= =========== ==========
    # tồn tại tên khi thêm mới
    public function existName($name)
    {
        $result = QcSupplies::where('name', $name)->count();
        return ($result > 0) ? true : false;
    }

    public function existEditName($suppliesId, $name)
    {
        $result = QcSupplies::where('name', $name)->where('supplies_id', '<>', $suppliesId)->count();
        return ($result > 0) ? true : false;
    }

    public function checkAction($suppliesId = null)
    {
        return ($this->action($suppliesId) == 0) ? false : true;
    }
}
