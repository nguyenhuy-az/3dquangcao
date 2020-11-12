<?php

namespace App\Models\Ad3d\LicenseOffWork;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\Timekeeping\QcTimekeeping;
use Illuminate\Database\Eloquent\Model;

class QcLicenseOffWork extends Model
{
    protected $table = 'qc_license_off_works';
    protected $fillable = ['license_id', 'dateOff', 'note', 'agreeStatus', 'confirmStatus', 'confirmNote', 'confirmDate', 'created_at', 'staff_id', 'staffConfirm_id'];
    protected $primaryKey = 'license_id';
    public $timestamps = false;

    private $lastId;

    //========== ========== ========== INSERT && UPDATE ========== ========== ==========
    //---------- thêm mới ----------
    public function insert($dateOff, $note, $staffId, $staffConfirmId)
    {
        $hFunction = new \Hfunction();
        $modelLicense = new QcLicenseOffWork();
        $modelLicense->dateOff = $dateOff;
        $modelLicense->note = $note;
        $modelLicense->staff_id = $staffId;
        $modelLicense->staffConfirm_id = $staffConfirmId;
        $modelLicense->created_at = $hFunction->createdAt();
        if ($modelLicense->save()) {
            $this->lastId = $modelLicense->license_id;
            return true;
        } else {
            return false;
        }
    }

    // lấy Id mới thêm
    public function insertGetId()
    {
        return $this->lastId;
    }

    public function confirmOffWork($licenseId, $agreeStatus, $confirmNote, $confirmStaff)
    {
        $hFunction = new \Hfunction();
        return QcLicenseOffWork::where(['license_id' => $licenseId])->update(
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
        return QcLicenseOffWork::where('license_id', $licenseId)->delete();
    }

    //========== ========== ========== RELATION ========== ========== ==========
    //----------- nhân viên nghi ------------
    public function staff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staff_id', 'staff_id');
    }

    public function selectInfoOfListStaffIdAndDate($listStaffId, $dateFilter = null, $orderBy = 'DESC')
    {
        if (empty($dateFilter)) {
            return QcLicenseOffWork::whereIn('staff_id', $listStaffId)->orderBy('created_at', $orderBy)->select('*');
        } else {
            // phat trien loc theo ngay
            return QcLicenseOffWork::whereIn('staff_id', $listStaffId)->where('created_at', 'like', "%$dateFilter%")->orderBy('dateOff', $orderBy)->select('*');
        }
    }

    # kien tra nhan vien co xin nghi theo ngay
    public function existDateOfStaff($staffId, $dateYmd)
    {
        $dateYmd = date('Y-m-d', strtotime($dateYmd));
        $result = QcLicenseOffWork::where('staff_id', $staffId)->where('dateOff', 'like', "%$dateYmd%")->count();
        return ($result > 0) ? true : false;
    }

    # kiem tra nv xin nghi theo ngay va duoc duyet
    public function existAcceptedDateOfStaff($staffId, $dateYmd)
    {
        $dateYmd = date('Y-m-d', strtotime($dateYmd));
        $result = QcLicenseOffWork::where('staff_id', $staffId)->where('dateOff', 'like', "%$dateYmd%")->where('agreeStatus', 1)->count();
        return ($result > 0) ? true : false;
    }

    public function infoOfStaffAndDate($staffId, $dateYmd)
    {
        $dateYmd = date('Y-m-d', strtotime($dateYmd));
        return QcLicenseOffWork::where('staff_id', $staffId)->where('dateOff', 'like', "%$dateYmd%")->first();
    }

    public function infoOfStaff($staffId, $orderBy = null)
    {
        $orderBy = (empty($orderBy)) ? 'DESC' : $orderBy;
        return QcLicenseOffWork::where(['staff_id' => $staffId])->orderBy('dateOff', "$orderBy")->get();
    }
    public function disableOfStaff($staffId)
    {
        return QcLicenseOffWork::where('staff_id', $staffId)->update(['action' => 0]);
    }

    //----------- nhân viên xac nhan ------------
    public function staffConfirm()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staffConfirm_id', 'staff_id');
    }

    //----------- làm việc ------------
    //============ =========== ============ lấy thông tin ============= =========== ==========
    public function getInfo($licenseId = '', $field = '')
    {
        if (empty($licenseId)) {
            return QcLicenseOffWork::get();
        } else {
            $result = QcLicenseOffWork::where('license_id', $licenseId)->first();
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
            return QcLicenseOffWork::where('license_id', $objectId)->pluck($column);
        }
    }

    //----------- GET INFO -------------
    public function licenseId()
    {
        return $this->license_id;
    }

    public function dateOff($licenseId = null)
    {
        return $this->pluck('dateOff', $licenseId);
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

    //======= check info =========
    public function checkConfirmStatus($licenseId = null)
    {
        return ($this->confirmStatus($licenseId) == 0) ? false : true;
    }

    public function checkAgreeStatus($licenseId = null)
    {
        return ($this->agreeStatus($licenseId) == 0) ? false : true;
    }

    //======= statistic info =========
    public function totalNewInfo($companyId)
    {
        $modelCompany = new QcCompany();
        $listStaffId = $modelCompany->staffIdOfListCompanyId([$companyId]);
        return QcLicenseOffWork::whereIn('staff_id', $listStaffId)->where('confirmStatus', 0)->count();
    }

}
