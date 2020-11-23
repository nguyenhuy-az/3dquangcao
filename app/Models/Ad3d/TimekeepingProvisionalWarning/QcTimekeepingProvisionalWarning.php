<?php

namespace App\Models\Ad3d\TimekeepingProvisionalWarning;

use Illuminate\Database\Eloquent\Model;

class QcTimekeepingProvisionalWarning extends Model
{
    protected $table = 'qc_timekeeping_provisional_warning';
    protected $fillable = ['warning_id', 'updateDate', 'note', 'image', 'waringType', 'action', 'created_at', 'timekeeping_provisional_id', 'warningStaff_id'];
    protected $primaryKey = 'warning_id';
    public $timestamps = false;

    private $lastId;

    #============ =========== ============ FIELD INFO ============= =========== ==========
    public function getInfo($warningId = '', $field = '')
    {
        if (empty($warningId)) {
            return QcTimekeepingProvisionalWarning::get();
        } else {
            $result = QcTimekeepingProvisionalWarning::where('warning_id', $warningId)->first();
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
            return QcTimekeepingProvisionalWarning::where('warning_id', $objectId)->pluck($column)[0];
        }
    }

    #----------- Filed INFO -------------
    public function warningId()
    {
        return $this->warning_id;
    }

    public function updateDate($warningId = null)
    {
        return $this->pluck('updateDate', $warningId);
    }

    public function warningType($warningId = null)
    {
        return $this->pluck('warningType', $warningId);
    }

    public function note($warningId = null)
    {
        return $this->pluck('note', $warningId);
    }

    public function image($warningId = null)
    {
        return $this->pluck('image', $warningId);
    }

    public function warningStaffId($warningId = null)
    {
        return $this->pluck('warningStaff_id', $warningId);
    }

    public function timekeepingProvisionalId($warningId = null)
    {
        return $this->pluck('timekeeping_provisional_id', $warningId);
    }

    public function action($warningId = null)
    {
        return $this->pluck('action', $warningId);
    }

    public function createdAt($warningId = null)
    {
        return $this->pluck('created_at', $warningId);
    }
    #========== ========== ========== THEM  && CAP NHAT ========== ========== ==========
    # lay kieu canh bao gio ra mac dinh
    public function getDefaultWarningTypeTimeEnd()
    {
        return 2;
    }

    # lay kieu canh bao gio vao mac dinh
    public function getDefaultWarningTypeTimeBegin()
    {
        return 1;
    }

    #---------- them moi ----------
    /*them khi bao gio ra*/
    public function insert($note, $image, $warningType, $timekeepingProvisionalId, $warningStaffId = null)
    {
        $hFunction = new \Hfunction();
        $model = new QcTimekeepingProvisionalWarning();
        $model->note = $note;
        $model->image = $image;
        $model->warningType = $warningType;
        $model->timekeeping_provisional_id = $timekeepingProvisionalId;
        $model->warningStaff_id = $warningStaffId;
        $model->created_at = $hFunction->createdAt();
        if ($model->save()) {
            $this->lastId = $model->warning_id;
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

    # check id
    public function checkNullId($id)
    {
        return (empty($id)) ? $this->warningId() : $id;
    }

    #----------- cap nhat thong tin ----------
    public function rootPathFullImage()
    {
        return 'public/images/timekeeping-provisional-warning/full';
    }

    public function rootPathSmallImage()
    {
        return 'public/images/timekeeping-provisional-warning/small';
    }


    # huy canh bao
    public function deleteWarning($warningId = null)
    {
        $image = $this->image($warningId);
        if (QcTimekeepingProvisionalWarning::where('warning_id', $this->checkNullId($warningId))->delete()) {
            $this->dropImage($image);
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
        $hFunction = new \Hfunction();
        if (!$hFunction->checkEmpty($imageName)) {
            unlink($this->rootPathSmallImage() . '/' . $imageName);
            unlink($this->rootPathFullImage() . '/' . $imageName);
        }
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
    #========== ========== ========== CAC MOI QUAN HE DU LIEU ========== ========== ==========
    #----------- timekeeping-provisional ------------
    public function timekeepingProvisional()
    {
        return $this->belongsTo('App\Models\Ad3d\TimekeepingProvisional\QcTimekeepingProvisional', 'timekeeping_provisional_id', 'timekeeping_provisional_id');
    }

    # lay thong tin canh bao cua cham cong
    public function infoOfTimekeepingProvisional($timekeepingProvisionalId)
    {
        return QcTimekeepingProvisionalWarning::where('timekeeping_provisional_id', $timekeepingProvisionalId)->get();
    }

    # lay thong canh bao gio vao cua cham cong
    public function infoTimeBeginOfTimekeepingProvisional($timekeepingProvisionalId)
    {
        $warningType = $this->getDefaultWarningTypeTimeBegin();
        return QcTimekeepingProvisionalWarning::where('timekeeping_provisional_id', $timekeepingProvisionalId)->where('warningType', $warningType)->first();
    }

    # kiem tra da ton tai canh bao gio vao
    public function checkExistWarningTimeBeginOfTimekeepingProvisional($timekeepingProvisionalId)
    {
        $warningType = $this->getDefaultWarningTypeTimeBegin();
        return QcTimekeepingProvisionalWarning::where('timekeeping_provisional_id', $timekeepingProvisionalId)->where('warningType', $warningType)->exists();
    }

    # cap nhat ngay canh bao gio vao cua cham cong
    public function updateTimeBeginOfTimekeepingProvisional($timekeepingProvisionalId, $updateDate)
    {
        $warningType = $this->getDefaultWarningTypeTimeBegin();
        return QcTimekeepingProvisionalWarning::where('timekeeping_provisional_id', $timekeepingProvisionalId)->where('warningType', $warningType)->update([
            'updateDate' => $updateDate
        ]);
    }

    # kiem tra da cap nhat canh bao gio vao
    public function checkUpdateTimeBegin($warningId = null)
    {
        $warningType = $this->getDefaultWarningTypeTimeBegin();
        return QcTimekeepingProvisionalWarning::where('warning_id', $this->checkNullId($warningId))->whereNotNull('updateDate')->where('warningType', $warningType)->exists();
    }

    # kiem tra da ton tai canh bao gio ra
    public function checkExistWarningTimeEndOfTimekeepingProvisional($timekeepingProvisionalId)
    {
        $warningType = $this->getDefaultWarningTypeTimeEnd();
        return QcTimekeepingProvisionalWarning::where('timekeeping_provisional_id', $timekeepingProvisionalId)->where('warningType', $warningType)->exists();
    }

    # lay thong tin canh bao gio ra cua cham cong
    public function infoTimeEndOfTimekeepingProvisional($timekeepingProvisionalId)
    {
        $warningType = $this->getDefaultWarningTypeTimeEnd();
        return QcTimekeepingProvisionalWarning::where('timekeeping_provisional_id', $timekeepingProvisionalId)->where('warningType', $warningType)->first();
    }

    # cap nhat ngay canh bao gio ra cua cham cong
    public function updateTimeEndOfTimekeepingProvisional($timekeepingProvisionalId, $updateDate)
    {
        $warningType = $this->getDefaultWarningTypeTimeEnd();
        return QcTimekeepingProvisionalWarning::where('timekeeping_provisional_id', $timekeepingProvisionalId)->where('warningType', $warningType)->update([
            'updateDate' => $updateDate
        ]);
    }

    # kiem tra da cap nhat canh bao gio ra
    public function checkUpdateTimeEnd($warningId = null)
    {
        $warningType = $this->getDefaultWarningTypeTimeEnd();
        return QcTimekeepingProvisionalWarning::where('warning_id', $this->checkNullId($warningId))->whereNotNull('updateDate')->where('warningType', $warningType)->exists();
    }
    #------------ -------- KIEM TRA LOAI CUA BAO CAO --------- -----
    # kiem tra la canh bao gio vao
    public function checkWarningTimeBegin($warningId = null)
    {
        return ($this->getDefaultWarningTypeTimeBegin() == $this->warningType($warningId)) ? true : false;
    }

    # kiem tra la canh bao gio ra
    public function checkWarningTimeEnd($warningId = null)
    {
        return ($this->getDefaultWarningTypeTimeEnd() == $this->warningType($warningId)) ? true : false;
    }

    #----------- anh bao cao tren phan viec ------------
    public function warningStaff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'warningStaff_id', 'staff_id');
    }


}
