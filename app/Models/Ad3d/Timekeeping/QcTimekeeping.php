<?php

namespace App\Models\Ad3d\Timekeeping;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\TimekeepingImage\QcTimekeepingImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class QcTimekeeping extends Model
{
    protected $table = 'qc_timekeeping';
    protected $fillable = ['timekeeping_id', 'timeBegin', 'timeEnd', 'dateOff', 'afternoonStatus', 'mainMinute', 'plusMinute', 'minusMinute', 'note', 'confirmNote', 'lateStatus', 'permissionStatus', 'workStatus', 'created_at', 'staffCheck_id', 'work_id'];
    protected $primaryKey = 'timekeeping_id';
    public $timestamps = false;

    private $lastId;

    //========== ========== ========== INSERT && UPDATE ========== ========== ==========
    # lay trang thai co phep mac dinh
    public function getDefaultHasPermissionStatus()
    {
        return 1;
    }

    # lay trang thai khong co phep mac dinh
    public function getDefaultNotPermissionStatus()
    {
        return 0;
    }

    # lay trang thai bao gio chinh xac mac dinh
    public function getDefaultHasAccuracyStatus()
    {
        return 1;
    }

    # lay trang thai bao gio chinh xac mac dinh
    public function getDefaultNotAccuracyStatus()
    {
        return 0;
    }

    # nhan dinh co tre
    public function getDefaultHasLateStatus()
    {
        return 1;
    }

    # mac dinh khong tre
    public function getDefaultNotLateStatus()
    {
        return 0;
    }

    # mac dinh co lam trua
    public function getDefaultHasAfternoonStatus()
    {
        return 1;
    }

    # mac dinh khong co lam trua
    public function getDefaultNotAfternoonStatus()
    {
        return 0;
    }

    # mac dinh co lam viec
    public function getDefaultHasWorkStatus()
    {
        return 1;
    }

    # mac dinh khong con lam viec
    public function getDefaultNotWorkStatus()
    {
        return 0;
    }

    #mac dinh gio vao
    public function getDefaultTimeBegin()
    {
        return null;
    }

    #mac dinh gio ra
    public function getDefaultTimeEnd()
    {
        return null;
    }

    #mac dinh ngay nghi
    public function getDefaultDateOff()
    {
        return null;
    }

    #mac dinh gio lam chinh
    public function getDefaultMainMinute()
    {
        return 0;
    }

    #mac dinh gio tang ca
    public function getDefaultPlusMinute()
    {
        return 0;
    }

    #mac dinh gio tru
    public function getDefaultMinusMinute()
    {
        return 0;
    }

    # mac dinh ghi chu
    public function getDefaultNote()
    {
        return null;
    }

    # mac dinh ghi chu xac nhan
    public function getDefaultConfirmNote()
    {
        return null;
    }

    #mac dinh nguoi duyet
    public function getDefaultConfirmStaffId()
    {
        return null;
    }


    //---------- Insert ----------
    public function insert($timeBegin, $timeEnd, $dateOff, $afternoonStatus, $mainMinute, $plusMinute, $minusMinute, $note, $confirmNote, $lateStatus, $permissionStatus, $workStatus, $staffCheckId, $workId)
    {
        $hFunction = new \Hfunction();
        $modelTimekeeping = new QcTimekeeping();
        $modelTimekeeping->timeBegin = $timeBegin;
        $modelTimekeeping->timeEnd = $timeEnd;
        $modelTimekeeping->dateOff = $dateOff;
        $modelTimekeeping->afternoonStatus = $afternoonStatus;
        $modelTimekeeping->mainMinute = $mainMinute;
        $modelTimekeeping->plusMinute = $plusMinute;
        $modelTimekeeping->minusMinute = $minusMinute;
        $modelTimekeeping->note = $note;
        $modelTimekeeping->confirmNote = $confirmNote;
        $modelTimekeeping->lateStatus = $lateStatus;
        $modelTimekeeping->permissionStatus = $permissionStatus;
        $modelTimekeeping->workStatus = $workStatus;
        $modelTimekeeping->staffCheck_id = $staffCheckId;
        $modelTimekeeping->work_id = $workId;
        $modelTimekeeping->created_at = $hFunction->createdAt();
        if ($modelTimekeeping->save()) {
            $this->lastId = $modelTimekeeping->timekeeping_id;
            return true;
        } else {
            return false;
        }
    }

    // get new id
    public function insertGetId()
    {
        return $this->lastId;
    }

    public function checkNullId($id = null)
    {
        return (empty($id)) ? $this->timekeepingId() : $id;
    }

    public function confirmWork($timekeepingId, $timeEnd, $mainMinute, $plusMinute, $note, $afternoonStatus = 0)
    {
        return QcTimekeeping::where('timekeeping_id', $timekeepingId)->update(['timeEnd' => $timeEnd, 'mainMinute' => $mainMinute, 'plusMinute' => $plusMinute, 'afternoonStatus' => $afternoonStatus, 'workStatus' => 0, 'note' => $note]);
    }

    public function deleteInfo($timekeepingId = null)
    {
        return QcTimekeeping::where('timekeeping_id', $this->checkNullId($timekeepingId))->delete();
    }

    //kiển tra người nhập
    public function checkStaffInput($staffId, $timekeepingId = null)
    {
        return QcTimekeeping::where('staffCheck_id', $staffId)->where('timekeeping_id', $this->checkNullId($timekeepingId))->exists();
    }

    //========== ========== ========== RELATION ========== ========== ==========
    //----------- Work ------------
    public function timekeepingImage()
    {
        return $this->hasMany('App\Models\Ad3d\TimekeepingImage\QcTimekeepingImage', 'timekeeping_id', 'timekeeping_id');
    }

    public function imageOfTimekeeping($timekeepingId)
    {
        $modelTimekeepingImage = new QcTimekeepingImage();
        return $modelTimekeepingImage->infoOfTimekeeping($timekeepingId);
    }

    # lay thông tin anh bao cao cua buoi sang
    public function infoTimekeepingImageInMorning($id = null)
    {
        $model = new QcTimekeepingImage();
        return $model->infoOfTimekeepingInMorning($this->checkNullId($id));
    }

    # kiem tra co ton tại anh bao cao cua buoi sang
    public function checkExistTimekeepingImageInMorning($id = null)
    {
        $hFunction = new \Hfunction();
        return ($hFunction->checkCount($this->infoTimekeepingImageInMorning($id))) ? true : false;
    }

    # lay thông tin anh bao cao cua buoi chieu
    public function infoTimekeepingImageInAfternoon($id = null)
    {
        $model = new QcTimekeepingImage();
        return $model->infoOfTimekeepingInAfternoon($this->checkNullId($id));
    }

    # kiem tra co ton tại anh bao cao cua buoi chieu
    public function checkExistTimekeepingImageInAfternoon($id = null)
    {
        $hFunction = new \Hfunction();
        return ($hFunction->checkCount($this->infoTimekeepingImageInAfternoon($id))) ? true : false;
    }

    # lay thông tin anh bao cao tang ca
    public function infoTimekeepingImageInEvening($id = null)
    {
        $model = new QcTimekeepingImage();
        return $model->infoOfTimekeepingInEvening($this->checkNullId($id));
    }

    # kiem tra co ton tại anh bao cao cua buoi toi
    public function checkExistTimekeepingImageInEvening($id = null)
    {
        $hFunction = new \Hfunction();
        return ($hFunction->checkCount($this->infoTimekeepingImageInEvening($id))) ? true : false;
    }

    //----------- STAFF ------------
    public function staff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staffCheck_id', 'staff_id');
    }

    //----------- TF-Work ------------
    public function work()
    {
        return $this->belongsTo('App\Models\Ad3d\Work\QcWork', 'work_id', 'work_id');
    }

    # disable of staff
    public function disableOfWork($workId)
    {
        return QcTimekeeping::where('work_id', $workId)->update(['workStatus' => $this->getDefaultNotWorkStatus()]);
    }

    public function infoActivityOfStaff($workId)
    {
        return QcTimekeeping::where(['work_id' => $workId, 'workStatus' => $this->getDefaultHasWorkStatus()])->first();
    }

    # tat ca thong tin cham cong cua 1 bang cham cong
    public function infoOfWork($workId)
    {
        return QcTimekeeping::where('work_id', $workId)->orderBy('timeBegin', 'DESC')->orderBy('dateOff', 'DESC')->get();
    }

    # lat tat ca thong tin lam viec theo danh sach bang cham cong
    public function getInfoAllOfListWork($listWorkId)
    {
        return QcTimekeeping::whereIn('work_id', $listWorkId)->orderBy('timeBegin', 'DESC')->orderBy('dateOff', 'DESC')->get();
    }

    # thong tin lam viec co cham cong
    public function getInfoHasWorkFromListWork($listWorkId)
    {
        return QcTimekeeping::whereIn('work_id', $listWorkId)->whereNull('dateOff')->orderBy('timeBegin', 'DESC')->orderBy('dateOff', 'DESC')->get();
    }

    # thong tin nghi co phep
    public function getInfoOffWorkHasPermissionFromListWork($listWorkId)
    {
        return QcTimekeeping::whereIn('work_id', $listWorkId)->whereNotNull('dateOff')->where('permissionStatus', $this->getDefaultHasPermissionStatus())->orderBy('timeBegin', 'DESC')->orderBy('dateOff', 'DESC')->get();
    }

    # thong tin nghi khong phep
    public function getInfoOffWorkNotPermissionFromListWork($listWorkId)
    {
        return QcTimekeeping::whereIn('work_id', $listWorkId)->whereNotNull('dateOff')->where('permissionStatus', $this->getDefaultNotPermissionStatus())->orderBy('timeBegin', 'DESC')->orderBy('dateOff', 'DESC')->get();
    }

    # di lam tre
    public function getInfoLateWork($listWorkId)
    {
        return QcTimekeeping::whereIn('work_id', $listWorkId)->whereNotNull('timeBegin')->where('lateStatus', $this->getDefaultHasLateStatus())->orderBy('timeBegin', 'DESC')->get();
    }

    # lam tang ca
    public function getInfoOverTimeWork($listWorkId)
    {
        return QcTimekeeping::whereIn('work_id', $listWorkId)->where('plusMinute', '>', 0)->orderBy('timeBegin', 'DESC')->get();
    }

    # da ton tai cham cong cua 1 ngay
    public function existDateOfWork($workId, $dateYmd)
    {
        if ($this->existWorkOfDate($workId, $dateYmd) || $this->existOffOfDate($workId, $dateYmd)) {
            return true;
        } else {
            return false;
        }

    }

    # co bao gio vao cua 1 bang cham cong - cua 1 ngay
    public function existWorkOfDate($workId, $dateYmd)
    {
        $dateYmd = date('Y-m-d', strtotime($dateYmd));
        return QcTimekeeping::where('work_id', $workId)->where('timeBegin', 'like', "%$dateYmd%")->exists();
    }

    # la ngay nghi cua 1 bang cham cong - cua 1 ngay
    public function existOffOfDate($workId, $dateYmd)
    {
        $dateYmd = date('Y-m-d', strtotime($dateYmd));
        return QcTimekeeping::where('work_id', $workId)->where('dateOff', 'like', "%$dateYmd%")->exists();
    }

    # kiem tra khong co bao gio ra cua 1 bang cham cong - cua 1 ngay
    public function timeEndIsNullOfWork($workId)
    {
        return QcTimekeeping::where('work_id', $workId)->whereNotNull('timeBegin')->whereNull('timeEnd')->exists();
    }

    #tong gio lam chinh cua 1 bang cham cong - cua 1 ngay
    public function sumMainMinuteOfWork($workId)
    {
        return QcTimekeeping::where('work_id', $workId)->sum('mainMinute');
    }

    # tong gio tang ca cua 1 bang cham cong - cua 1 ngay
    public function sumPlusMinuteOfWork($workId)
    {
        return QcTimekeeping::where('work_id', $workId)->sum('plusMinute');
    }

    # tong so gio tru cua 1 bang cham cong - cua 1 ngay
    public function sumMinusMinuteOfWork($workId)
    {
        return QcTimekeeping::where('work_id', $workId)->sum('minusMinute');
    }

    # tong so ngay nghi cua 1 bang cham cong - cua 1 ngay
    public function sumOffWork($workId)
    {
        return QcTimekeeping::where('work_id', $workId)->whereNotNull('dateOff')->count();
    }

    # tong so ngay nghi co phep cua 1 bang cham cong - cua 1 ngay
    public function sumOffWorkTrue($workId)
    {
        return QcTimekeeping::where(['work_id' => $workId, 'permissionStatus' => $this->getDefaultHasPermissionStatus()])->whereNotNull('dateOff')->count();
    }

    #tong so ngay nghi khong phe cua 1 bang cham cong - cua 1 ngay
    public function sumOffWorkFalse($workId)
    {
        return QcTimekeeping::where(['work_id' => $workId, 'permissionStatus' => $this->getDefaultNotPermissionStatus()])->whereNotNull('dateOff')->count();
    }

    //============ =========== ============ GET INFO ============= =========== ==========
    public function getInfo($timekeepingId = '', $field = '')
    {
        if (empty($timekeepingId)) {
            return QcTimekeeping::where('workStatus', $this->getDefaultHasWorkStatus())->get();
        } else {
            $result = QcTimekeeping::where('timekeeping_id', $timekeepingId)->first();
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
            return QcTimekeeping::where('timekeeping_id', $objectId)->pluck($column)[0];
        }
    }

    //----------- GET INFO -------------
    public function timekeepingId()
    {
        return $this->timekeeping_id;
    }

    public function timeBegin($timekeepingId = null)
    {
        return $this->pluck('timeBegin', $timekeepingId);
    }

    public function timeEnd($timekeepingId = null)
    {
        return $this->pluck('timeEnd', $timekeepingId);
    }

    public function dateOff($timekeepingId = null)
    {
        return $this->pluck('dateOff', $timekeepingId);
    }

    public function afternoonStatus($timekeepingId = null)
    {
        return $this->pluck('afternoonStatus', $timekeepingId);
    }

    public function mainMinute($timekeepingId = null)
    {
        return $this->pluck('mainMinute', $timekeepingId);
    }

    public function plusMinute($timekeepingId = null)
    {
        return $this->pluck('plusMinute', $timekeepingId);
    }

    public function minusMinute($timekeepingId = null)
    {
        return $this->pluck('minusMinute', $timekeepingId);
    }

    public function note($timekeepingId = null)
    {
        return $this->pluck('note', $timekeepingId);
    }

    public function confirmNote($timekeepingId = null)
    {
        return $this->pluck('confirmNote', $timekeepingId);
    }

    public function lateStatus($timekeepingId = null)
    {
        return $this->pluck('lateStatus', $timekeepingId);
    }

    public function permissionStatus($timekeepingId = null)
    {
        return $this->pluck('permissionStatus', $timekeepingId);
    }

    public function workStatus($timekeepingId = null)
    {
        return $this->pluck('workStatus', $timekeepingId);
    }

    public function workId($timekeepingId = null)
    {
        return $this->pluck('work_id', $timekeepingId);
    }

    public function staffCheckId($timekeepingId = null)
    {
        return $this->pluck('staffCheck_id', $timekeepingId);
    }

    public function createdAt($timekeepingId = null)
    {
        return $this->pluck('created_at', $timekeepingId);
    }


    //======= check info =========
    # chon danh sach cham cong theo thoi gian va ma cham cong
    public function selectInfoByListWorkAndDate($listWorkId, $dateFilter)
    {
        $query = QcTimekeeping::whereIn('work_id', $listWorkId);
        $query = $query->where(function ($q) use ($dateFilter) {
            $q->orWhere('timeBegin', 'LIKE', '%' . $dateFilter . '%')
                ->orWhere('dateOff', 'LIKE', '%' . $dateFilter . '%');
        });
        return $query->orderBy('timeBegin', 'DESC')->orderBy('dateOff', 'DESC')->select('*');
    }

    public function checkWorking($timekeepingId = null)
    {
        return ($this->workStatus($timekeepingId) == $this->getDefaultHasWorkStatus()) ? true : false;
    }

    public function checkWorkLate($timekeepingId = null)
    {
        return ($this->lateStatus($timekeepingId) == $this->getDefaultHasLateStatus()) ? true : false;
    }

    public function checkOvertime($timekeepingId = null)
    {
        return ($this->plusMinute($timekeepingId) > 0) ? true : false;
    }

    public function checkOff($timekeepingId = null)
    {
        return ($this->dateOff($timekeepingId) == null) ? false : true;
    }

    public function checkPermissionStatus($timekeepingId = null)
    {
        return ($this->permissionStatus($timekeepingId) == $this->getDefaultHasPermissionStatus()) ? true : false;
    }

    public function checkAfternoonStatus($timekeepingId = null)
    {
        return ($this->afternoonStatus($timekeepingId) == $this->getDefaultHasAfternoonStatus()) ? true : false;
    }

}
