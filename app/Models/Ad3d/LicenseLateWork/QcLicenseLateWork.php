<?php

namespace App\Models\Ad3d\LicenseLateWork;

use App\Models\Ad3d\Company\QcCompany;
use Illuminate\Database\Eloquent\Model;

class QcLicenseLateWork extends Model
{
    protected $table = 'qc_license_late_works';
    protected $fillable = ['license_id', 'dateLate', 'note', 'agreeStatus', 'confirmStatus', 'confirmNote', 'confirmDate', 'created_at', 'staff_id', 'staffConfirm_id'];
    protected $primaryKey = 'license_id';
    public $timestamps = false;

    private $lastId;

    //========== ========== ========== THÊM && CẬP NHẬT ========== ========== ==========
    public function insert($dateLate, $note, $staffId, $staffCheckId)
    {
        $hFunction = new \Hfunction();
        $modelLicense = new QcLicenseLateWork();
        $modelLicense->dateLate = $dateLate;
        $modelLicense->note = $note;
        $modelLicense->staff_id = $staffId;
        $modelLicense->staffConfirm_id = $staffCheckId;
        $modelLicense->created_at = $hFunction->createdAt();
        if ($modelLicense->save()) {
            $this->lastId = $modelLicense->license_id;
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

    public function confirmLateWork($licenseId, $agreeStatus, $confirmNote, $confirmStaff)
    {
        $hFunction = new \Hfunction();
        return QcLicenseLateWork::where(['license_id' => $licenseId])->update(
            [
                'agreeStatus' => $agreeStatus,
                'staffConfirm_id' => $confirmStaff,
                'confirmNote' => $confirmNote,
                'confirmStatus' => 1,
                'confirmDate' => $hFunction->createdAt(),
            ]);
    }

    public function deleteInfo($licenseId = null)
    {
        $licenseId = (empty($licenseId)) ? $this->licenseId() : $licenseId;
        return QcLicenseLateWork::where('license_id', $licenseId)->delete();
    }

    //========== ========== ========== RELATION ========== ========== ==========
    //----------- nhân viên tre ------------
    public function staff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staff_id', 'staff_id');
    }

    public function selectInfoOfListStaffIdAndDate($listStaffId, $dateFilter = null, $orderBy = 'DESC')
    {
        if (empty($dateFilter)) {
            return QcLicenseLateWork::whereIn('staff_id', $listStaffId)->orderBy('created_at', $orderBy)->select('*');
        } else {
            // phat trien loc theo ngay
            return QcLicenseLateWork::whereIn('staff_id', $listStaffId)->where('created_at', 'like', "%$dateFilter%")->orderBy('dateLate', $orderBy)->select('*');
        }
    }

    public function existDateOfStaff($staffId, $dateYmd)
    {
        $dateYmd = date('Y-m-d', strtotime($dateYmd));
        $result = QcLicenseLateWork::where('staff_id', $staffId)->where('dateLate', 'like', "%$dateYmd%")->count();
        return ($result > 0) ? true : false;
    }

    public function infoOfStaffAndDate($staffId, $dateYmd)
    {
        $dateYmd = date('Y-m-d', strtotime($dateYmd));
        return QcLicenseLateWork::where('staff_id', $staffId)->where('dateLate', 'like', "%$dateYmd%")->first();
    }

    public function infoOfStaff($staffId, $orderBy = null)
    {
        $orderBy = (empty($orderBy)) ? 'DESC' : $orderBy;
        return QcLicenseLateWork::where(['staff_id' => $staffId])->orderBy('dateLate', "$orderBy")->get();
    }
    public function disableOfStaff($staffId)
    {
        return QcLicenseLateWork::where('staff_id', $staffId)->update(['action' => 0]);
    }
    //----------- nhân viên xac nhan ------------
    public function staffConfirm()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staffConfirm_id', 'staff_id');
    }

    //============ =========== ============ GET INFO ============= =========== ==========
    public function getInfo($licenseId = '', $field = '')
    {
        if (empty($licenseId)) {
            return QcLicenseLateWork::get();
        } else {
            $result = QcLicenseLateWork::where('license_id', $licenseId)->first();
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
            return QcLicenseLateWork::where('license_id', $objectId)->pluck($column);
        }
    }

    //----------- lấy thông tin -------------
    public function licenseId()
    {
        return $this->license_id;
    }

    public function dateLate($licenseId = null)
    {
        return $this->pluck('dateLate', $licenseId);
    }

    public function note($licenseId = null)
    {
        return $this->pluck('note', $licenseId);
    }

    public function agreeStatus($licenseId = null)
    {
        return $this->pluck('agreeStatus', $licenseId);
    }

    public function confirmStatus($licenseId = null)
    {
        return $this->pluck('confirmStatus', $licenseId);
    }

    public function confirmNote($licenseId = null)
    {
        return $this->pluck('confirmNote', $licenseId);
    }

    public function confirmDate($licenseId = null)
    {
        return $this->pluck('confirmDate', $licenseId);
    }

    public function staffId($licenseId = null)
    {
        return $this->pluck('staff_id', $licenseId);
    }

    public function staffConfirmId($licenseId = null)
    {
        return $this->pluck('staffConfirm_id', $licenseId);
    }

    public function createdAt($licenseId = null)
    {
        return $this->pluck('created_at', $licenseId);
    }

    //======= kiểm tra thông tin =========
    public function checkConfirmStatus($licenseId = null)
    {
        return ($this->confirmStatus($licenseId) == 0) ? false : true;
    }

    public function checkDateLateWork($date)
    {
        $date = date('Y-m-d', strtotime($date));
        $result = QcLicenseLateWork::where('dateLate', "like", "%$date%")->where('agreeStatus', 1)->count();
        return ($result > 0) ? true : false;
    }

    public function checkAgreeStatus($licenseId = null)
    {
        return ($this->agreeStatus($licenseId) == 0) ? false : true;
    }

    //======= statistic info =========
    public function totalNewLicenseLateWork($companyId = null)
    {
        $modelCompany = new QcCompany();
        if (empty($companyId)) {
            return QcLicenseLateWork::where('confirmStatus', 0)->count();
        } else {
            $listStaffId = $modelCompany->staffIdOfListCompanyId([$companyId]);
            return QcLicenseLateWork::whereIn('staff_id', $listStaffId)->where('confirmStatus', 0)->count();
        }
    }


}
