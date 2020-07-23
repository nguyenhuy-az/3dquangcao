<?php

namespace App\Models\Ad3d\Tool;

use App\Models\Ad3d\CompanyStore\QcCompanyStore;
use App\Models\Ad3d\ToolAllocationDetail\QcToolAllocationDetail;
use App\Models\Ad3d\ToolReturnDetail\QcToolReturnDetail;
use Illuminate\Database\Eloquent\Model;

class QcTool extends Model
{
    protected $table = 'qc_tools';
    protected $fillable = ['tool_id', 'name', 'unit', 'description', 'type', 'created_at'];
    protected $primaryKey = 'tool_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== Thêm && cập nhật ========== ========== ==========
    #---------- Thêm ----------
    public function insert($name, $unit, $description = null, $type = 2)
    {
        $hFunction = new \Hfunction();
        $modelTool = new QcTool();
        $modelTool->name = $name;
        $modelTool->unit = $unit;
        $modelTool->description = $description;
        $modelTool->type = $type;
        $modelTool->created_at = $hFunction->createdAt();
        if ($modelTool->save()) {
            $this->lastId = $modelTool->tool_id;
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

    public function checkNullId($id = null)
    {
        return (empty($id)) ? $this->toolId() : $id;
    }

    #----------- cập nhật thông tin ----------
    public function updateInfo($toolId, $name, $unit, $description,$type)
    {
        return QcTool::where('tool_id', $toolId)->update([
            'unit' => $unit,
            'name' => $name,
            'description' => $description,
            'type' => $type
        ]);
    }

    # xóa
    public function deleteTool($toolId = null)
    {
        if (empty($toolId)) $toolId = $this->toolId();
        return QcTool::where('tool_id', $toolId)->delete();
    }

    #========== ========== ========== Mối quan hệ ========== ========== ==========
    #----------- chi tiết nhập ------------
    public function importDetail()
    {
        return $this->hasMany('App\Models\Ad3d\ImportDetail\QcImportDetail', 'detail_id', 'detail_id');
    }

    #----------- kho ------------
    public function companyStore()
    {
        return $this->hasMany('App\Models\Ad3d\CompanyStore\QcCompanyStore', 'tool_id', 'tool_id');
    }

    public function existInStore($toolId = null)
    {
        $modelCompanyStore = new QcCompanyStore();
        return $modelCompanyStore->existOfTool((empty($toolId)) ? $this->toolId() : $toolId);
    }

    # toan he thong
    public function amountInStore($toolId = null)
    {
        $modelCompanyStore = new QcCompanyStore();
        return $modelCompanyStore->amountOfTool((empty($toolId)) ? $this->toolId() : $toolId);
    }

    # cua 1 cty
    public function amountOfCompany($toolId, $companyId)
    {
        $modelCompanyStore = new QcCompanyStore();
        return $modelCompanyStore->totalToolOfCompany($companyId,$toolId);
    }


    #============ =========== ============ Lấy thông tin ============= =========== ==========
    # lay danh sach id theo type
    public function listIdByType($type = 0)
    {
        if ($type > 0) {
            return QcTool::where('type', $type)->pluck('tool_id');
        } else { # mac dinh lay tat ca
            return QcTool::select('*')->pluck('tool_id');
        }
    }

    # lay danh sach ma dong cu dung chung
    public function publicListId()
    {
        return QcTool::where('type', $this->publicType())->pluck('tool_id');
    }

    # lay danh sach ma dong cu dung rieng
    public function privateListId()
    {
        return QcTool::where('type', $this->privateType())->pluck('tool_id');
    }

    # dung chung
    public function publicType()
    {
        return 1;
    }

    # phat cho ca nhan
    public function privateType()
    {
        return 2;
    }

    public function infoFromSuggestionName($name)
    {
        return QcTool::where('name', 'like', "%$name%")->orderBy('name', 'ASC')->get();
    }

    # lay thong tin theo loai cong cu
    public function selectAllInfo($type = null)
    {
        if (empty($type)) {
            return QcTool::orderBy('name', 'ASC')->select('*');
        } else {
            return QcTool::where('type', $type)->orderBy('name', 'ASC')->select('*');
        }
    }

    # lay thong tin theo danh sach ma cong cu co san
    public function getInfoByListId($listId)
    {
        return QcTool::whereIn('tool_id', $listId)->get();
    }

    public function getInfoByName($name)
    {
        return QcTool::where('name', $name)->first();
    }

    public function getInfo($toolId = '', $field = '')
    {
        if (empty($toolId)) {
            return QcTool::get();
        } else {
            $result = QcTool::where('tool_id', $toolId)->first();
            if (empty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }

    public function getInfoOrderByName($toolId = null, $field = null, $oderBy = 'ASC')
    {
        if (empty($toolId)) {
            return QcTool::orderBy('name', $oderBy)->get();
        } else {
            $result = QcTool::where('tool_id', $toolId)->orderBy('name', $oderBy)->first();
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
        $result = QcTool::select('tool_id as optionKey', 'name as optionValue')->get()->toArray();
        return $hFunction->option($result, $selected);
    }

    public function pluck($column, $objectId = null)
    {
        if (empty($objectId)) {
            return $this->$column;
        } else {
            return QcTool::where('tool_id', $objectId)->pluck($column);
        }
    }

    #----------- thông tin -------------
    public function toolId()
    {
        return $this->tool_id;
    }

    public function unit($toolId = null)
    {
        return $this->pluck('unit', $toolId);
    }

    public function name($toolId = null)
    {
        return $this->pluck('name', $toolId);
    }

    public function description($toolId = null)
    {
        return $this->pluck('description', $toolId);
    }

    public function type($toolId = null)
    {
        return $this->pluck('type', $toolId);
    }

    public function createdAt($toolId = null)
    {
        return $this->pluck('created_at', $toolId);
    }

    # tổng mẫu tin
    public function totalRecords()
    {
        return QcTool::count();
    }

    # kiem tra loại dung chung
    public function checkPublicType($toolId = null)
    {
        return ($this->type($this->checkNullId($toolId))[0] == 1) ? true : false;
    }

    # kieu dung de phat cho ca nhan
    public function checkPrivateType($toolId = null)
    {
        return ($this->type($this->checkNullId($toolId))[0] == 2) ? true : false;
    }

    public function getLabelType($toolId = null)
    {
        if ($this->checkPublicType($toolId)) {
            return 'Dùng chung';
        } elseif ($this->checkPrivateType($toolId)) {
            return 'Dùng cấp phát';
        } else {
            return 'Null';
        }
    }
    #============ =========== ============ kiểm tra thông tin ============= =========== ==========
    //số lượng tồn kho
    public function amountInventoryOfTool($toolId = null)
    {
        $modelCompanyStore = new QcCompanyStore();
        $modelToolAllocationDetail = new QcToolAllocationDetail();
        $modelToolReturnDetail = new QcToolReturnDetail();
        $toolId = (empty($toolId)) ? $this->toolId() : $toolId;
        return $modelCompanyStore->amountOfTool($toolId) - $modelToolAllocationDetail->amountAllocatedOfTool($toolId) + $modelToolReturnDetail->amountReturnOfTool($toolId);
    }

    # tồn tại tên khi thêm mới
    public function existName($name)
    {
        $result = QcTool::where('name', $name)->count();
        return ($result > 0) ? true : false;
    }

    public function existEditName($toolId, $name)
    {
        $result = QcTool::where('name', $name)->where('tool_id', '<>', $toolId)->count();
        return ($result > 0) ? true : false;
    }
}
