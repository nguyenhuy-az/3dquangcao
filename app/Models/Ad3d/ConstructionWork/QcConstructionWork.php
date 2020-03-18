<?php

namespace App\Models\Ad3d\ConstructionWork;

use Illuminate\Database\Eloquent\Model;

class QcConstructionWork extends Model
{
    protected $table = 'qc_construction_work';
    protected $fillable = ['construction_id', 'name', 'description', 'created_at'];
    protected $primaryKey = 'construction_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== Thêm && cập nhật ========== ========== ==========
    #---------- Thêm ----------
    public function insert($name, $description)
    {
        $hFunction = new \Hfunction();
        $modelConstructionWork = new QcConstructionWork();
        $modelConstructionWork->name = $name;
        $modelConstructionWork->description = $hFunction->convertValidHTML($description);
        $modelConstructionWork->created_at = $hFunction->createdAt();
        if ($modelConstructionWork->save()) {
            $this->lastId = $modelConstructionWork->construction_id;
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
    public function updateInfo($constructionId, $name, $description)
    {
        return QcConstructionWork::where('construction_id', $constructionId)->update([
            'name' => $name,
            'description' => $description
        ]);
    }

    public function checkIdNull($constructionId)
    {
        return (empty($constructionId)) ? $this->typeId() : $constructionId;
    }

    # xóa
    public function delete($constructionId = null)
    {
        return QcConstructionWork::where('construction_id', $this->checkIdNull($constructionId))->delete();
    }

    #========== ========== ========== Mối quan hệ ========== ========== ==========
    public function selectActivityInfo()
    {
        return QcConstructionWork::orderBy('name', 'ASC')->select('*');
    }

    #----------- loai san pham ------------
    public function productTypeConstruction()
    {
        return $this->hasMany('App\Models\Ad3d\ProductTypeConstruction\QcProductTypeConstruction', 'construction_id', 'construction_id');
    }

    #========== ========== ========== thong tin ========== ========== ==========
    public function getInfoByListId($listConstructionId)
    {
        return  QcConstructionWork::whereIn('construction_id', $listConstructionId)->get();
    }

    public function getInfo($constructionId = '', $field = '')
    {
        if (empty($constructionId)) {
            return QcConstructionWork::get();
        } else {
            $result = QcConstructionWork::where('construction_id', $constructionId)->first();
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
        $result = QcConstructionWork::select('construction_id as optionKey', 'name as optionValue')->get()->toArray();
        return $hFunction->option($result, $selected);
    }

    public function pluck($column, $objectId = null)
    {
        if (empty($objectId)) {
            return $this->$column;
        } else {
            return QcConstructionWork::where('construction_id', $objectId)->pluck($column);
        }
    }

    public function constructionId()
    {
        return $this->construction_id;
    }

    public function name($constructionId = null)
    {
        $hFunction = new \Hfunction();
        return $hFunction->htmlEntities($this->pluck('name', $constructionId));
    }

    public function description($constructionId = null)
    {
        $hFunction = new \Hfunction();
        return $hFunction->htmlEntities($this->pluck('description', $constructionId));
    }

    public function createdAt($constructionId = null)
    {
        return $this->pluck('created_at', $constructionId);
    }

    # tổng mẫu tin
    public function totalRecords()
    {
        return QcConstructionWork::count();
    }

    #============ =========== ============ kiểm tra thông tin ============= =========== ==========
    # tồn tại tên khi thêm mới
    public function existName($name)
    {
        $result = QcConstructionWork::where('name', $name)->count();
        return ($result > 0) ? true : false;
    }

    public function existEditName($constructionId, $name)
    {
        $result = QcConstructionWork::where('name', $name)->where('construction_id', '<>', $constructionId)->count();
        return ($result > 0) ? true : false;
    }
}
