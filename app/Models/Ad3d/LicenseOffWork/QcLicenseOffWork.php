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

    # mac dinh ghi chu xin
    public function getDefaultNote()
    {
        return null;
    }

    #mac dinh co dong y
    public function getDefaultHasAgree()
    {
        return 1;
    }

    #mac dinh khong dong y
    public function getDefaultNotAgree()
    {
        return 0;
    }

    #mac dinh co xac nhan
    public function getDefaultHasConfirm()
    {
        return 1;
    }

    #mac dinh khong xac nhan
    public function getDefaultNotConfirm()
    {
        return 0;
    }

    # mac dinh tat ca trang thai xac nhan
    public function getDefaultAllConfirm()
    {
        return 100;
    }

    # mac dinh ghi chu xac nhan
    public function getDefaultConfirmNote()
    {
        return null;
    }

    #mac dinh ngay xac nhan
    public function getDefaultConfirmDate()
    {
        return null;
    }

    # mac dinh nguoi xac nhan
    public function getDefaultConfirmStaffId()
    {
        return null;
    }
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

    # kiem tra id
    public function checkNullId($id)
    {
        $hFunction = new \Hfunction();
        return ($hFunction->checkEmpty($id)) ? $this->licenseId() : $id;
    }

    public function confirmOffWork($licenseId, $agreeStatus, $confirmNote, $confirmStaff)
    {
        $hFunction = new \Hfunction();
        return QcLicenseOffWork::where(['license_id' => $licenseId])->update(
            [
                'agreeStatus' => $agreeStatus,
                'staffConfirm_id' => $confirmStaff,
                'confirmNote' => $confirmNote,
                'confirmStatus' => $this->getDefaultHasConfirm(),
                'confirmDate' => $hFunction->createdAt(),
            ]);
    }

    public function deleteInfo($licenseId = null)
    {
        return QcLicenseOffWork::where('license_id', $this->checkNullId($licenseId))->delete();
    }

    //========== ========== ========== RELATION ========== ========== ==========
    //----------- nhân viên nghi ------------
    public function staff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staff_id', 'staff_id');
    }

    public function selectInfoOfListStaffIdAndDate($listStaffId, $dateFilter = null, $orderBy = 'DESC')
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($dateFilter)) {
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
        return QcLicenseOffWork::where('staff_id', $staffId)->where('dateOff', 'like', "%$dateYmd%")->exists();
    }

    # kiem tra nv xin nghi theo ngay va duoc duyet
    public function existAcceptedDateOfStaff($staffId, $dateYmd)
    {
        $dateYmd = date('Y-m-d', strtotime($dateYmd));
        return QcLicenseOffWork::where('staff_id', $staffId)->where('dateOff', 'like', "%$dateYmd%")->where('agreeStatus', $this->getDefaultHasAgree())->exists();
    }

    public function infoOfStaffAndDate($staffId, $dateYmd)
    {
        $dateYmd = date('Y-m-d', strtotime($dateYmd));
        return QcLicenseOffWork::where('staff_id', $staffId)->where('dateOff', 'like', "%$dateYmd%")->first();
    }

    public function infoOfStaff($staffId, $orderBy = null)
    {
        $hFunction = new \Hfunction();
        $orderBy = ($hFunction->checkEmpty($orderBy)) ? 'DESC' : $orderBy;
        return QcLicenseOffWork::where(['staff_id' => $staffId])->orderBy('dateOff', "$orderBy")->get();
    }

    # xoa tat ca cua 1 nhan vien
    public function disableOfStaff($staffId)
    {
        return false;// QcLicenseOffWork::where('staff_id', $staffId)->delete();
    }

    //----------- nhân viên xac nhan ------------
    public function staffConfirm()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staffConfirm_id', 'staff_id');
    }

    //----------- làm việc ------------
    //============ =========== ============ lấy thông tin ============= =========== ==========
    # lay tat ca thong tin so nguoi nghi trong ngay cua 1 cong ty
    public function getInfoOffInDateOffCompany($companyId, $dateYmd)
    {
        $modelCompany = new QcCompany();
        $dateYmd = date('Y-m-d', strtotime($dateYmd));
        $listStaffId = $modelCompany->staffIdOfListCompanyId([$companyId]);
        return QcLicenseOffWork::whereIn('staff_id', $listStaffId)->where('dateOff', 'like', "%$dateYmd%")->get();
    }

    public function getInfo($licenseId = '', $field = '')
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($licenseId)) {
            return QcLicenseOffWork::get();
        } else {
            $result = QcLicenseOffWork::where('license_id', $licenseId)->first();
            if ($hFunction->checkEmpty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }

    # lay 1 gia tri
    public function pluck($column, $objectId = null)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($objectId)) {
            return $this->$column;
        } else {
            return QcLicenseOffWork::where('license_id', $objectId)->pluck($column)[0];
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
        return ($this->confirmStatus($licenseId) == $this->getDefaultNotConfirm()) ? false : true;
    }

    public function checkAgreeStatus($licenseId = null)
    {
        return ($this->agreeStatus($licenseId) == $this->getDefaultNotAgree()) ? false : true;
    }

    //======= statistic info =========
    public function totalNewInfo($companyId)
    {
        $modelCompany = new QcCompany();
        $listStaffId = $modelCompany->staffIdOfListCompanyId([$companyId]);
        return QcLicenseOffWork::whereIn('staff_id', $listStaffId)->where('confirmStatus', $this->getDefaultNotConfirm())->count();
    }

}
