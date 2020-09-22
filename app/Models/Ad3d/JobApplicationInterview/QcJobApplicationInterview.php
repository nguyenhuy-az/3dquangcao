<?php

namespace App\Models\Ad3d\JobApplicationInterview;

use Illuminate\Database\Eloquent\Model;

class QcJobApplicationInterview extends Model
{
    protected $table = 'qc_job_application_interview';
    protected $fillable = ['interview_id', 'interviewConfirm', 'interviewDate', 'agreeStatus', 'action', 'created_at', 'staff_id', 'jobApplication_id'];
    protected $primaryKey = 'interview_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== INSERT && UPDATE ========== ========== ==========
    #---------- Insert ----------
    public function insert($interviewDate, $jobApplicationId)
    {
        $hFunction = new \Hfunction();
        $modelJobApplicationInterview = new QcJobApplicationInterview();
        $modelJobApplicationInterview->interviewDate = $interviewDate;
        $modelJobApplicationInterview->jobApplication_id = $jobApplicationId;
        $modelJobApplicationInterview->created_at = $hFunction->createdAt();
        if ($modelJobApplicationInterview->save()) {
            $this->lastId = $modelJobApplicationInterview->interview_id;
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
    public function deleteInfo($interviewId = null)
    {
        return QcJobApplicationInterview::where('interview_id', $interviewId)->delete();
    }

    #========== ========== ========== RELATION ========== ========== ==========
    #----------- nguoi phong van------------
    public function staff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staff_id', 'staff_id');
    }

    #----------- thong tin ho so ------------
    public function jobApplication()
    {
        return $this->belongsTo('App\Models\Ad3d\JobApplication\QcJobApplication', 'jobApplication_id', 'jobApplication_id');
    }

    public function lastInfoOfJobApplication($jobApplicationId)
    {
        return QcJobApplicationInterview::where('jobApplication_id', $jobApplicationId)->orderBy('interview_id', 'DESC')->first();
    }

    #============ =========== ============ GET INFO ============= =========== ==========
    public function selectInfoAll()
    {
        return QcJobApplicationInterview::select('*');
    }

    public function getInfo($interviewId = '', $field = '')
    {
        if (empty($interviewId)) {
            return QcJobApplicationInterview::get();
        } else {
            $result = QcJobApplicationInterview::where('interview_id', $interviewId)->first();
            if (empty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }

    public function pluck($column, $id = null)
    {
        if (empty($objectId)) {
            return $this->$column;
        } else {
            return QcJobApplicationInterview::where('interview_id', $objectId)->pluck($column);
        }
    }

    public function interviewId()
    {
        return $this->interview_id;
    }

    public function interviewDate($interviewId = null)
    {
        return $this->pluck('interviewDate', $interviewId);
    }

    public function interviewConfirm($interviewId = null)
    {
        return $this->pluck('interviewConfirm', $interviewId);
    }

    public function agreeStatus($interviewId = null)
    {
        return $this->pluck('agreeStatus', $interviewId);
    }

    public function staffId($interviewId = null)
    {
        return $this->pluck('staff_id', $interviewId);
    }

    public function jobApplicationId($interviewId)
    {
        return $this->pluck('jobApplication_id', $interviewId);
    }

    public function createdAt($interviewId = null)
    {
        return $this->pluck('created_at', $interviewId);
    }

    # total record
    public function totalRecords()
    {
        return QcJobApplicationInterview::count();
    }

    # kiem tra co duoc phong van hay chua
    public function checkInterviewConfirm($interviewId = null)
    {
        return ($this->interviewConfirm($interviewId) == 1) ? true : false;
    }

    # kiem tra duoc dong y hay khong
    public function checkAgreeStatus($interviewId = null)
    {
        return ($this->agreeStatus($interviewId) == 1) ? true : false;
    }

}
