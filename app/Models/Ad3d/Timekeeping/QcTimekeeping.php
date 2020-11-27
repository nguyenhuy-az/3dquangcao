<?php

namespace App\Models\Ad3d\Timekeeping;

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

    # lay trang thai co tre nhan dinh
    public function getDefaultHasLateStatus()
    {
        return 1;
    }
    # lay trang thai khong tre mac dinh
    public function getDefaultNotLateStatus()
    {
        return 0;
    }

    # lay trang thai co lam trua mac dinh
    public function getDefaultHasAfternoonStatus()
    {
        return 1;
    }
    # lay trang thai khong co lam trua mac dinh
    public function getDefaultNotAfternoonStatus()
    {
        return 0;
    }

    # lay trang thai co lam viec mac dinh
    public function getDefaultHasWorkStatus()
    {
        return 1;
    }
    # lay trang thai khong con lam viec mac dinh
    public function getDefaultNotWorkStatus()
    {
        return 0;
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
        $timekeepingId = (empty($timekeepingId)) ? $this->timekeepingId() : $timekeepingId;
        return QcTimekeeping::where('timekeeping_id', $timekeepingId)->delete();
    }

    //kiển tra người nhập
    public function checkStaffInput($staffId, $timekeepingId = null)
    {
        $timekeepingId = (empty($timekeepingId)) ? $this->timekeepingId() : $timekeepingId;
        return (QcTimekeeping::where('staffCheck_id', $staffId)->where('timekeeping_id', $timekeepingId)->count() > 0) ? true : false;
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

    // disable of staff
    public function disableOfWork($workId)
    {
        return QcTimekeeping::where('work_id', $workId)->update(['action' => 0]);
    }

    public function infoActivityOfStaff($workId)
    {
        return QcTimekeeping::where(['work_id' => $workId, 'workStatus' => 1])->first();
    }

    public function infoOfWork($workId)
    {
        return QcTimekeeping::where(['work_id' => $workId])->orderBy('timeBegin', 'DESC')->orderBy('dateOff', 'DESC')->get();
    }

    public function existDateOfWork($workId, $dateYmd)
    {
        if ($this->existWorkOfDate($workId, $dateYmd) || $this->existOffOfDate($workId, $dateYmd)) {
            return true;
        } else {
            return false;
        }

    }

    public function existWorkOfDate($workId, $dateYmd)
    {
        $dateYmd = date('Y-m-d', strtotime($dateYmd));
        $result = QcTimekeeping::where('work_id', $workId)->where('timeBegin', 'like', "%$dateYmd%")->count();
        return ($result > 0) ? true : false;
    }

    public function existOffOfDate($workId, $dateYmd)
    {
        $dateYmd = date('Y-m-d', strtotime($dateYmd));
        $result = QcTimekeeping::where('work_id', $workId)->where('dateOff', 'like', "%$dateYmd%")->count();
        return ($result > 0) ? true : false;
    }

    public function timeEndIsNullOfWork($workId)
    {
        $result = QcTimekeeping::where('work_id', $workId)->whereNotNull('timeBegin')->whereNull('timeEnd')->get();
        return (count($result) > 0) ? true : false;
    }

    #tong gio lam chinh
    public function sumMainMinuteOfWork($workId)
    {
        return QcTimekeeping::where('work_id', $workId)->sum('mainMinute');
    }

    # tong gio tang ca
    public function sumPlusMinuteOfWork($workId)
    {
        return QcTimekeeping::where('work_id', $workId)->sum('plusMinute');
    }

    # tong so gio tru
    public function sumMinusMinuteOfWork($workId)
    {
        return QcTimekeeping::where('work_id', $workId)->sum('minusMinute');
    }

    # tong so ngay nghi
    public function sumOffWork($workId)
    {
        return QcTimekeeping::where('work_id', $workId)->whereNotNull('dateOff')->count();
    }

    # tong so ngay nghi co phep
    public function sumOffWorkTrue($workId)
    {
        return QcTimekeeping::where(['work_id' => $workId, 'permissionStatus' => 1])->whereNotNull('dateOff')->count();
    }

    //tong so ngay nghi khong phe
    public function sumOffWorkFalse($workId)
    {
        return QcTimekeeping::where(['work_id' => $workId, 'permissionStatus' => 0])->whereNotNull('dateOff')->count();
    }

    //============ =========== ============ GET INFO ============= =========== ==========
    public function getInfo($timekeepingId = '', $field = '')
    {
        if (empty($timekeepingId)) {
            return QcTimekeeping::where('workStatus', 1)->get();
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
            return QcTimekeeping::where('timekeeping_id', $objectId)->pluck($column);
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
        return ($this->workStatus($timekeepingId) == 1) ? true : false;
    }

    public function checkWorkLate($timekeepingId = null)
    {
        return ($this->lateStatus($timekeepingId) == 0) ? true : false;
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
        return ($this->permissionStatus($timekeepingId) == 1) ? true : false;
    }

    public function checkAfternoonStatus($timekeepingId = null)
    {
        return ($this->afternoonStatus($timekeepingId) == 1) ? true : false;
    }

}
