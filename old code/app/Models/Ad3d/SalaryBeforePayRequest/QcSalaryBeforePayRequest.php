<?php

namespace App\Models\Ad3d\SalaryBeforePayRequest;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\SalaryBeforePay\QcSalaryBeforePay;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\Work\QcWork;
use Illuminate\Database\Eloquent\Model;

class QcSalaryBeforePayRequest extends Model
{
    protected $table = 'qc_salary_before_pay_request';
    protected $fillable = ['request_id', 'moneyRequest', 'dateRequest', 'moneyConfirm', 'agreeStatus', 'transferStatus', 'confirmDate', 'confirmNote', 'confirmStatus', 'created_at', 'work_id', 'staffConfirm_id', 'staffTransfer_id'];
    protected $primaryKey = 'request_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== INSERT && UPDATE ========== ========== ==========
    #---------- Insert ----------
    public function insert($moneyRequest, $workId)
    {
        $hFunction = new \Hfunction();
        $modelSalaryBeforeRequest = new QcSalaryBeforePayRequest();
        $modelSalaryBeforeRequest->moneyRequest = $moneyRequest;
        $modelSalaryBeforeRequest->dateRequest = $hFunction->currentDateTimePlusDay(3);
        $modelSalaryBeforeRequest->work_id = $workId;
        $modelSalaryBeforeRequest->staffConfirm_id = null;
        $modelSalaryBeforeRequest->created_at = $hFunction->createdAt();
        if ($modelSalaryBeforeRequest->save()) {
            $this->lastId = $modelSalaryBeforeRequest->request_id;
            return true;
        } else {
            return false;
        }
    }

    // lấy id mới thêm
    public function insertGetId()
    {
        return $this->lastId;
    }

    // xác nhận lời yêu cầu
    public function confirmRequest($requestId, $moneyConfirm, $agreeStatus, $confirmNote, $staffConfirmId)
    {
        $hFunction = new \Hfunction();
        return QcSalaryBeforePayRequest::where('request_id', $requestId)->update(
            [
                'moneyConfirm' => $moneyConfirm,
                'agreeStatus' => $agreeStatus,
                'confirmDate' => $hFunction->createdAt(),
                'confirmNote' => $confirmNote,
                'confirmStatus' => 1,
                'staffConfirm_id' => $staffConfirmId
            ]);
    }

    //xác nhận chuyển tiền
    public function confirmTransfer($requestId, $dateSalaryBeforePay, $noteSalaryBeforePay, $staffTransferId)
    {
        $hFunction = new \Hfunction();
        $modelSalaryBeforePay = new QcSalaryBeforePay();
        if (QcSalaryBeforePayRequest::where('request_id', $requestId)->update(
            [
                'transferDate' => $hFunction->createdAt(),
                'transferStatus' => 1,
                'staffTransfer_id' => $staffTransferId
            ])
        ) {
            return $modelSalaryBeforePay->insert($this->moneyConfirm($requestId)[0], $dateSalaryBeforePay, $noteSalaryBeforePay, $this->workId($requestId)[0], $staffTransferId);
        } else {
            return false;
        }
    }

    public function updateInfo($requestId, $moneyConfirm, $confirmNote)
    {
        $hFunction = new \Hfunction();
        return QcSalaryBeforePayRequest::where('request_id', $requestId)->update(['moneyConfirm' => $moneyConfirm, 'confirmDate' => $hFunction->createdAt(), 'confirmNote' => $confirmNote]);
    }

    public function deleteInfo($requestId = null)
    {
        $requestId = (empty($requestId)) ? $this->requestId() : $requestId;
        return QcSalaryBeforePayRequest::where('request_id', $requestId)->delete();
    }

    //---------- nhận viên xác nhận ứng lương -----------
    public function staffConfirm()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staffConfirm_id', 'staff_id');
    }

    //---------- nhận viên chuyển tiền ứng lương -----------
    public function staffTransfer()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staffTransfer_id', 'staff_id');
    }

    //----------- làm việc ------------
    public function work()
    {
        return $this->belongsTo('App\Models\Ad3d\Work\QcWork', 'work_id', 'work_id');
    }

    public function totalMoneyRequestOfWork($workId)
    {
        return QcSalaryBeforePayRequest::where('work_id', $workId)->sum('moneyRequest');
    }

    public function totalMoneyConfirmOfWork($workId)
    {
        return QcSalaryBeforePayRequest::where('work_id', $workId)->sum('moneyConfirm');
    }

    public function infoOfWork($workId)
    {
        return QcSalaryBeforePayRequest::where('work_id', $workId)->orderBy('request_id', 'DESC')->get();
    }

    public function existUnconfirmedOfWork($workId)
    {
        $result = QcSalaryBeforePayRequest::where('work_id', $workId)->where('confirmStatus', 0)->count();
        return ($result > 0) ? true : false;
    }

    public function selectInfoOffListWorkAndDate($listWorkId,$dateFilter=null)
    {
        if (empty($dateFilter)) {
            return QcSalaryBeforePayRequest::whereIn('work_id', $listWorkId)->orderBy('created_at', 'DESC')->select('*');
        } else {
            return QcSalaryBeforePayRequest::whereIn('work_id', $listWorkId)->where('created_at', 'like', "%$dateFilter%")->orderBy('created_at', 'DESC')->select('*');
        }
    }

    #============ =========== ============ GET INFO ============= =========== ==========
    public function getInfo($requestId = '', $field = '')
    {
        if (empty($requestId)) {
            return QcSalaryBeforePayRequest::get();
        } else {
            $result = QcSalaryBeforePayRequest::where('request_id', $requestId)->first();
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
            return QcSalaryBeforePayRequest::where('request_id', $objectId)->pluck($column);
        }
    }

    public function requestId()
    {
        return $this->request_id;
    }

    public function moneyRequest($requestId = null)
    {
        return $this->pluck('moneyRequest', $requestId);
    }

    public function dateRequest($requestId = null)
    {
        return $this->pluck('dateRequest', $requestId);
    }

    public function moneyConfirm($requestId = null)
    {
        return $this->pluck('moneyConfirm', $requestId);
    }

    public function agreeStatus($requestId = null)
    {
        return $this->pluck('agreeStatus', $requestId);
    }

    public function transferStatus($requestId = null)
    {
        return $this->pluck('transferStatus', $requestId);
    }

    public function confirmDate($requestId = null)
    {
        return $this->pluck('confirmDate', $requestId);
    }

    public function confirmNote($requestId = null)
    {
        return $this->pluck('confirmNote', $requestId);
    }

    public function confirmStatus($requestId = null)
    {
        return $this->pluck('confirmStatus', $requestId);
    }

    public function createdAt($requestId = null)
    {
        return $this->pluck('created_at', $requestId);
    }

    public function workId($requestId = null)
    {
        return $this->pluck('work_id', $requestId);
    }

    public function staffConfirmId($requestId = null)
    {
        return $this->pluck('staffConfirm_id', $requestId);
    }

    public function staffTransferId($requestId = null)
    {
        return $this->pluck('staffConfirm_id', $requestId);
    }

    #============ =========== ============ kiểm tra thông tin ============= =========== ==========
    public function checkConfirmStatus($requestId = null)
    {
        return ($this->confirmStatus($requestId) == 0) ? false : true;
    }

    public function checkAgreeStatus($requestId = null)
    {
        return ($this->agreeStatus($requestId) == 0) ? false : true;
    }

    public function checkTransferStatus($requestId = null)
    {
        return ($this->transferStatus($requestId) == 0) ? false : true;
    }

    #============ =========== ============ thống kê ============= =========== ==========
    public function totalNewRequest($companyId = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelWork = new QcWork();
        if (empty($companyId)) {
            return QcSalaryBeforePayRequest::where('confirmStatus', 0)->count();
        } else {
            $listWorkId = $modelWork->listIdOfListCompanyStaffId($modelCompanyStaffWork->listIdOfListCompanyAndListStaff([$companyId]));
            return QcSalaryBeforePayRequest::whereIn('work_id', $listWorkId)->where('confirmStatus', 0)->count();
        }

    }

    public function totalSalaryBeforePayOfCompany($listCompanyId, $dateFilter = null)
    {
        $modelStaff = new QcStaff();
        $modelWork = new QcWork();
        $listWorkId = $modelWork->listIdOfListStaffId($modelStaff->listIdOfListCompanyAndName($listCompanyId, null));
        if (empty($dateFilter)) {
            return QcSalaryBeforePayRequest::whereIn('work_id', $listWorkId)->sum('money');
        } else {
            return QcSalaryBeforePayRequest::whereIn('work_id', $listWorkId)->where('created_at', 'like', "%$dateFilter%")->sum('money');
        }
    }

    public function totalSalaryBeforePayOfCompanyStaffDate($listCompanyId, $staffId, $dateFilter = null)
    {
        /*$modelStaff = new QcStaff();
        $modelWork = new QcWork();
        $listWorkId = $modelWork->listIdOfListStaffId($modelStaff->listIdOfListCompanyAndName($listCompanyId, null));
        if (empty($dateFilter)) {
            return QcSalaryBeforePayRequest::whereIn('work_id', $listWorkId)->where('staffRequest_id', $staffId)->sum('money');
        } else {
            return QcSalaryBeforePayRequest::whereIn('work_id', $listWorkId)->where('staffRequest_id', $staffId)->where('created_at', 'like', "%$dateFilter%")->sum('money');
        }*/
    }
}
