<?php

namespace App\Models\Ad3d\JobApplicationWork;

use Illuminate\Database\Eloquent\Model;

class QcJobApplicationWork extends Model
{
    protected $table = 'qc_job_application_work';
    protected $fillable = ['detail_id', 'skillStatus', 'created_at', 'work_id', 'jobApplication_id'];
    protected $primaryKey = 'detail_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== INSERT && UPDATE ========== ========== ==========
    #---------- Insert ----------
    public function insert($skillStatus, $workId, $jobApplicationId)
    {
        $hFunction = new \Hfunction();
        $modelJobApplicationWork = new QcJobApplicationWork();
        $modelJobApplicationWork->skillStatus = $skillStatus;
        $modelJobApplicationWork->work_id = $workId;
        $modelJobApplicationWork->jobApplication_id = $jobApplicationId;
        $modelJobApplicationWork->created_at = $hFunction->createdAt();
        if ($modelJobApplicationWork->save()) {
            $this->lastId = $modelJobApplicationWork->detail_id;
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

    # delete
    public function deleteInfo($detailId = null)
    {
        return QcJobApplicationWork::where('detail_id', $detailId)->delete();
    }

    #========== ========== ========== RELATION ========== ========== ==========
    #----------- cong viec cua bo phan ------------
    public function departmentWork()
    {
        return $this->belongsTo('App\Models\Ad3d\DepartmentWork\QcDepartmentWork', 'work_id', 'work_id');
    }

    #----------- thong tin ho so ------------
    public function jobApplication()
    {
        return $this->belongsTo('App\Models\Ad3d\JobApplication\QcJobApplication', 'jobApplication_id', 'jobApplication_id');
    }

    # lay thong tin theo 1 ho so
    public function getInfoOfJobApplication($jobApplicationId)
    {
        return QcJobApplicationWork::where('jobApplication_id', $jobApplicationId)->get();
    }

    #============ =========== ============ GET INFO ============= =========== ==========
    public function selectInfoAll()
    {
        return QcJobApplicationWork::select('*');
    }

    public function getInfo($detailId = '', $field = '')
    {
        if (empty($detailId)) {
            return QcJobApplicationWork::get();
        } else {
            $result = QcJobApplicationWork::where('detail_id', $detailId)->first();
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
            return QcJobApplicationWork::where('detail_id', $objectId)->pluck($column);
        }
    }

    public function detailId()
    {
        return $this->detail_id;
    }

    public function skillStatus($detailId = null)
    {
        return $this->pluck('skillStatus', $detailId);
    }

    public function skillStatusLabel($skillStatus)
    {
        if($skillStatus == 1){
            return 'Không biết';
        }elseif($skillStatus == 2){
            return 'Biêt';
        }elseif($skillStatus == 3){
            return 'Giỏi';
        }else{
            return 'Chưa xác định';
        }
    }

    public function workId($detailId = null)
    {
        return $this->pluck('workId', $detailId);
    }

    public function jobApplicationId($detailId)
    {
        return $this->pluck('jobApplication_id', $detailId);
    }

    public function createdAt($detailId = null)
    {
        return $this->pluck('created_at', $detailId);
    }

    # total record
    public function totalRecords()
    {
        return QcJobApplicationWork::count();
    }

    #============ =========== ============ CHECK INFO ============= =========== ==========
    public function existJobApplicationAndWork($jobApplicationId, $workId)
    {
        return QcJobApplicationWork::where('jobApplication_id', $jobApplicationId)->where('work_id', $workId)->exists();
    }
}
