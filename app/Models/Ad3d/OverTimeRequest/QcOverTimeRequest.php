<?php

namespace App\Models\Ad3d\OverTimeRequest;

use App\Models\Ad3d\Company\QcCompany;
use Illuminate\Database\Eloquent\Model;

class QcOverTimeRequest extends Model
{
    protected $table = 'qc_over_time_request';
    protected $fillable = ['request_id', 'requestDate', 'acceptStatus', 'note', 'action', 'created_at', 'work_id', 'requestStaff_id'];
    protected $primaryKey = 'request_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- them ----------
    public function insert($requestDate, $note, $workId, $staffId)
    {
        $hFunction = new \Hfunction();
        $model = new QcOverTimeRequest();
        $model->requestDate = $requestDate;
        $model->note = $note;
        $model->work_id = $workId;
        $model->requestStaff_id = $staffId;
        $model->created_at = $hFunction->createdAt();
        if ($model->save()) {
            $this->lastId = $model->request_id;
            return true;
        } else {
            return false;
        }
    }

    public function insertGetId()
    {
        return $this->lastId;
    }

    public function checkNullId($id)
    {
        return (empty($id)) ? $this->requestId() : $id;
    }

    public function deleteInfo($requestId)
    {
        return QcOverTimeRequest::where('request_id', $requestId)->delete();
    }

    //========== ========= ========= quan he cac bang ========== ========= ==========
    //----------  thong tin lam viec -----------
    public function companyStaffWork()
    {
        return $this->belongsTo('App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork', 'work_id', 'work_id');
    }

    public function infoOfCompanyStaffWork($workId)
    {
        return QcOverTimeRequest::where('work_id', $workId)->get();
    }

    # lay thong tin thong bao tang ca trong ngày
    public function getInfoOfCompanyStaffWorkAndDate($workId, $date)
    {
        $checkDate = date('Y-m-d', strtotime($date));
        return QcOverTimeRequest::where('work_id', $workId)->where('requestDate', 'like', "%$checkDate%")->first();
    }

    # kiem tra ton tai ngay thong bao tang ca
    public function checkExistDateOfCompanyStaffWork($workId, $date)
    {
        $checkDate = date('Y-m-d', strtotime($date));
        return QcOverTimeRequest::where('work_id', $workId)->where('requestDate', 'like', "%$checkDate%")->exists();
    }

    # lay thong tin yeu cau tang ca dang hoat dong
    public function getInfoActivityOfCompanyStaffWork($workId)
    {
        return QcOverTimeRequest::where('work_id', $workId)->where('action', 1)->get();
    }

    //========= ========== ========== lay thong tin ========== ========== ==========

    public function pluck($column, $objectId = null)
    {
        if (empty($objectId)) {
            return $this->$column;
        } else {
            return QcOverTimeRequest::where('request_id', $objectId)->pluck($column);
        }
    }

    public function requestId()
    {
        return $this->request_id;
    }

    public function requestDate($requestId = null)
    {
        return $this->pluck('requestDate', $requestId);
    }

    public function acceptStatus($requestId = null)
    {
        return $this->pluck('acceptStatus', $requestId);
    }

    public function note($requestId = null)
    {
        return $this->pluck('note', $requestId);
    }

    public function action($requestId = null)
    {
        return $this->pluck('action', $requestId);
    }

    public function createdAt($requestId = null)
    {
        return $this->pluck('created_at', $requestId);
    }

    public function workId($requestId = null)
    {
        return $this->pluck('work_id', $requestId);
    }

    public function requestStaffId($requestId = null)
    {
        return $this->pluck('requestStaff_id', $requestId);
    }

    // last id
    public function lastId()
    {
        $result = QcOverTimeRequest::orderBy('request_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->request_id;
    }

    # lay danh sach yeu cau dang hoat dong
    public function  getAllInfoActivity()
    {
        return QcOverTimeRequest::where('action', 1)->get();
    }

    public function getInfo($requestId = '', $field = '')
    {
        if (empty($requestId)) {
            return QcOverTimeRequest::get();
        } else {
            $result = QcOverTimeRequest::where('request_id', $requestId)->first();
            if (empty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }
    // ========== ========= Cap nhat thong tin =========== ========
    # xac nhan ket thuc yeu cau
    public function confirmFinish($requestId = null)
    {
        $hFunction = new \Hfunction();
        $dataRequest = $this->getInfo($this->checkNullId($requestId));
        $agreeStatus = 0;
        if ($hFunction->checkCount($dataRequest)) {
            $requestDate = $dataRequest->requestDate();
            $dataCompanyStaffWork = $dataRequest->companyStaffWork;
            $dataTimekeepingProvisional = $dataCompanyStaffWork->infoTimekeepingProvisionalInDate($requestDate);
            if ($hFunction->checkCount($dataTimekeepingProvisional)) {
                # kiem tra co tang ca hay khong
                if ($dataTimekeepingProvisional->checkHasOverTime()) $agreeStatus = 1;
            }
        }
        return QcOverTimeRequest::where('request_id', $requestId)->update([
            'acceptStatus' => $agreeStatus,
            'action' => 0
        ]);

    }

    # kiem tra ket thuc yeu cau tu dong
    public function checkAutoFinish()
    {
        $hFunction = new \Hfunction();
        $modelCompany = new QcCompany();
        $currentDateCheck = $hFunction->carbonNow();
        # lay thong tin dang hoa dong
        $dataRequest = $this->getAllInfoActivity();
        if ($hFunction->checkCount($dataRequest)) {
            foreach ($dataRequest as $request) {
                $requestDate = $request->requestDate();
                $checkDate = $hFunction->datetimePlusDay($modelCompany->getDefaultTimeBeginToWorkOfDate($requestDate), 1);
                if ($currentDateCheck > $checkDate) {# qua ngay tang ca
                    $this->confirmFinish($request->requestId());
                }
            }
        }

    }
}
