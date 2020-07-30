<?php

namespace App\Models\Ad3d\ToolReturn;

use App\Models\Ad3d\ToolAllocationDetail\QcToolAllocationDetail;
use Illuminate\Database\Eloquent\Model;

class QcToolReturn extends Model
{
    protected $table = 'qc_tool_return';
    protected $fillable = ['return_id', 'returnDate', 'image', 'confirmStatus', 'confirmDate', 'acceptStatus', 'created_at', 'detail_id', 'confirmStaff_id'];
    protected $primaryKey = 'return_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- thêm ----------
    public function insert($detailId, $image)
    {
        $hFunction = new \Hfunction();
        $modelToolReturn = new QcToolReturn();
        $modelToolReturn->returnDate = $hFunction->carbonNow();
        $modelToolReturn->image = $image;
        $modelToolReturn->detail_id = $detailId;
        $modelToolReturn->created_at = $hFunction->createdAt();
        if ($modelToolReturn->save()) {
            $this->lastId = $modelToolReturn->return_id;
            return true;
        } else {
            return false;
        }
    }

    public function insertGetId()
    {
        return $this->lastId;
    }

    public function checkNullId($id = null)
    {
        return (empty($id)) ? $this->returnId() : $id;
    }

    public function deleteReturn($returnId = null)
    {
        return QcToolReturn::where('return_id', $this->checkNullId($returnId))->delete();
    }

    # xac nhan tra
    public function confirmReturn($returnId, $acceptStatus, $confirmStaffId)
    {
        $modelToolAllocationDetail = new QcToolAllocationDetail();
        if ($this->updateConfirm($returnId, $acceptStatus, $confirmStaffId)) {
            # chap nhan tra
            if ($this->checkAcceptStatus($returnId)) $modelToolAllocationDetail->disableDetail($this->detailId($returnId));
        }
    }

    public function updateConfirm($returnId, $acceptStatus, $confirmStaffId)
    {
        $hFunction = new \Hfunction();
        return QcToolReturn::where('return_id', $returnId)->update(['confirmStatus' => 1, 'confirmDate' => $hFunction->carbonNow(), 'confirmStaff_id' => $confirmStaffId, 'acceptStatus' => $acceptStatus]);
    }

    # hinh anh
    public function rootPathFullImage()
    {
        return 'public/images/tool-return/full';
    }

    public function rootPathSmallImage()
    {
        return 'public/images/tool-return/small';
    }

    # xóa 1 hình ảnh
    public function deleteImage($returnId = null)
    {
        $imageName = $this->image($returnId)[0];
        if (QcToolReturn::where('return_id', $returnId)->update(['image' => null])) {
            $this->dropImage($imageName);
        }
    }

    //upload image
    public function uploadImage($source_img, $imageName, $resize = 500)
    {
        $hFunction = new \Hfunction();
        $pathSmallImage = $this->rootPathSmallImage();
        $pathFullImage = $this->rootPathFullImage();
        if (!is_dir($pathFullImage)) mkdir($pathFullImage);
        if (!is_dir($pathSmallImage)) mkdir($pathSmallImage);
        return $hFunction->uploadSaveByFileName($source_img, $imageName, $pathSmallImage . '/', $pathFullImage . '/', $resize);
    }

    //drop image
    public function dropImage($imageName)
    {
        unlink($this->rootPathSmallImage() . '/' . $imageName);
        unlink($this->rootPathFullImage() . '/' . $imageName);
    }

    // get path image
    public function pathSmallImage($image)
    {
        if (empty($image)) {
            return null;
        } else {
            return asset($this->rootPathSmallImage() . '/' . $image);
        }
    }

    public function pathFullImage($image)
    {
        if (empty($image)) {
            return null;
        } else {
            return asset($this->rootPathFullImage() . '/' . $image);
        }
    }
    //========== ========= ========= RELATION ========== ========= ==========
    //---------- nhân viên tra -----------
    public function toolAllocationDetail()
    {
        return $this->belongsTo('App\Models\Ad3d\ToolAllocationDetail\QcToolAllocationDetail', 'detail_id', 'detail_id');
    }

    # lay thong tin cuoi dung bao tra
    public function lastInfoOfToolAllocationDetail($detailId)
    {
        return QcToolReturn::where('detail_id', $detailId)->orderBy('return_id', 'DESC')->first();
    }

    //---------- nhan vien xac nhan -----------
    public function confirmStaff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'confirmStaff_id', 'staff_id');
    }

    //========= ========== ========== lấy thông tin ========== ========== ==========
    # lay thong tin tra do nghe cua 1/nhieu nv lam viec tai cty
    public function infoOfListDetail($listDetailId, $confirmStatus = 100)
    {
        #$confirmStatus = 100 tat ca thong tin
        if ($confirmStatus == 100) {
            return QcToolReturn::whereIn('detail_id', $listDetailId)->get();
        } else {
            return QcToolReturn::whereIn('detail_id', $listDetailId)->where('confirmStatus', $confirmStatus)->get();
        }
    }

    # lay thong tin tra do nghe cua 1/nhieu nv lam viec tai cty chua xac nhan
    public function infoUnConfirmOfListDetail($listDetailId)
    {
        return $this->infoOfListDetail($listDetailId, 0);
    }

    # lay thong tin tra ve kho lan sau cung
    public function infoLastOfCompanyStore($storeId)
    {
        $modelToolAllocationDetail = new QcToolAllocationDetail();
        return $this->lastInfoOfToolAllocationDetail($modelToolAllocationDetail->lastIdOfCompanyStore($storeId));
    }

    public function getAllocationDetailListId()
    {
        return QcToolReturn::select('*')->pluck('detail_id');
    }

    public function getInfo($returnId = '', $field = '')
    {
        if (empty($returnId)) {
            return QcToolReturn::get();
        } else {
            $result = QcToolReturn::where('return_id', $returnId)->first();
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
            return QcToolReturn::where('return_id', $objectId)->pluck($column);
        }
    }

    public function returnId()
    {
        return $this->return_id;
    }

    public function returnDate($returnId = null)
    {

        return $this->pluck('returnDate', $returnId);
    }

    public function image($returnId = null)
    {

        return $this->pluck('image', $returnId);
    }

    public function acceptStatus($returnId = null)
    {

        return $this->pluck('acceptStatus', $returnId);
    }

    public function confirmStatus($returnId = null)
    {

        return $this->pluck('confirmStatus', $returnId);
    }

    public function confirmDate($returnId = null)
    {

        return $this->pluck('confirmDate', $returnId);
    }

    public function detailId($returnId = null)
    {
        return $this->pluck('detail_id', $returnId);
    }

    public function confirmStaffId($returnId = null)
    {
        return $this->pluck('confirmStaff_id', $returnId);
    }

    public function createdAt($returnId = null)
    {
        return $this->pluck('created_at', $returnId);
    }

    // last id
    public function lastId()
    {
        $result = QcToolReturn::orderBy('return_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->return_id;
    }

    # ========= ============ Kiem tra thong tin ============= ==============
    # kiem tra duoc xac nhan nhay chua
    public function checkConfirm($returnId = null)
    {
        $result = $this->confirmStatus($returnId);
        $result = (is_int($result)) ? $result : $result[0];
        return ($result == 0) ? false : true;
    }

    # kiem tra co duoc dong y hay khong
    public function checkAcceptStatus($returnId = null)
    {
        $result = $this->acceptStatus($returnId);
        $result = (is_int($result)) ? $result : $result[0];
        return ($result == 0) ? false : true;
    }
}
