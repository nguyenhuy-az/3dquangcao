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

    #mac dinh khong biet lam
    public function getDefaultNotSkill()
    {
        return 1;
    }

    # mac dinh biet lam
    public function getDefaultMediumSkill()
    {
        return 2;
    }

    #mac dinh lam gioi
    public function getDefaultGoodSkill()
    {
        return 3;
    }

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
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($detailId)) {
            return QcJobApplicationWork::get();
        } else {
            $result = QcJobApplicationWork::where('detail_id', $detailId)->first();
            if ($hFunction->checkEmpty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }

    public function pluck($column, $objectId = null)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($objectId)) {
            return $this->$column;
        } else {
            return QcJobApplicationWork::where('detail_id', $objectId)->pluck($column)[0];
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
        if ($skillStatus == $this->getDefaultNotSkill()) {
            return 'Không biết';
        } elseif ($skillStatus == $this->getDefaultMediumSkill()) {
            return 'Biêt';
        } elseif ($skillStatus == $this->getDefaultGoodSkill()) {
            return 'Giỏi';
        } else {
            return 'Chưa xác định';
        }
    }

    public function workId($detailId = null)
    {
        return $this->pluck('work_id', $detailId);
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
