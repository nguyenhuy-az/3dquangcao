<?php

namespace App\Models\Ad3d\SystemDateOff;

use Illuminate\Database\Eloquent\Model;

class QcSystemDateOff extends Model
{
    protected $table = 'qc_system_date_off';
    protected $fillable = ['dateOff_id', 'dateOff', 'description', 'type', 'created_at', 'staff_id', 'company_id'];
    protected $primaryKey = 'dateOff_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= =========  THEM MOI VA CAP NHAT========== ========= =========
    // them moi
    public function insert($dateOff, $description, $type = 1, $staffId, $companyId)
    {
        $hFunction = new \Hfunction();
        $modelSystemDateOff = new QcSystemDateOff();
        $modelSystemDateOff->dateOff = $dateOff;
        $modelSystemDateOff->description = $description;
        $modelSystemDateOff->type = $type;
        $modelSystemDateOff->staff_id = $staffId;
        $modelSystemDateOff->company_id = $companyId;
        $modelSystemDateOff->created_at = $hFunction->createdAt();
        if ($modelSystemDateOff->save()) {
            $this->lastId = $modelSystemDateOff->dateOff_id;
            return true;
        } else {
            return false;
        }
    }

    //lay id moi them
    public function insertGetId()
    {
        return $this->lastId;
    }

    #cap nhat ngay nghi
    public function updateInfo($dateOffId, $type, $description)
    {
        return QcSystemDateOff::where('dateOff_id', $dateOffId)->update([
            'type' => $type,
            'description' => $description
        ]);
    }

    //xoa ngay nghi
    public function deleteDateOff($dateOffId = null)
    {
        $dateOffId = (empty($dateOffId)) ? $this->dateOffId() : $dateOffId;
        return QcSystemDateOff::where('dateOff_id', $dateOffId)->delete();
    }

    //========== ========= ========= CAC MOI QUAN HE DU LIEU ========== ========= ==========
    //---------- nha vien chi -----------
    public function staff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staff_id', 'staff_id');
    }

    //---------- cong ty -----------
    public function company()
    {
        return $this->belongsTo('App\Models\Ad3d\Company\QcCompany', 'company_id', 'company_id');
    }

    public function checkExistsDateOfCompany($companyId, $dateOff)
    {
        return QcSystemDateOff::where('dateOff', 'like', "%$dateOff%")->where('company_id', $companyId)->exists();
    }

    # ngay bat buoc nghi
    public function infoDateObligatoryOfCompanyAndDate($companyId, $dateOff = null)
    {
        if (empty($dateOff)) {
            return QcSystemDateOff::where('company_id', $companyId)->where('type', 1)->orderBy('dateOff', 'DESC')->get();
        } else {
            return QcSystemDateOff::where('company_id', $companyId)->where('type', 1)->where('dateOff', 'like', "%$dateOff%")->orderBy('dateOff', 'DESC')->get();
        }

    }

    # ngay khong bat buoc nghi
    public function infoDateOptionalOfCompanyAndDate($companyId, $dateOff = null)
    {
        if (empty($dateOff)) {
            return QcSystemDateOff::where('company_id', $companyId)->where('type', 2)->orderBy('dateOff', 'DESC')->get();
        } else {
            return QcSystemDateOff::where('company_id', $companyId)->where('type', 2)->where('dateOff', 'like', "%$dateOff%")->orderBy('dateOff', 'DESC')->get();
        }

    }

    public function selectInfoOfCompanyAndDate($companyId, $dateOff = null)
    {
        if (empty($dateOff)) {
            return QcSystemDateOff::where('company_id', $companyId)->orderBy('dateOff', 'DESC')->select('*');
        } else {
            return QcSystemDateOff::where('dateOff', 'like', "%$dateOff%")->where('company_id', $companyId)->orderBy('dateOff', 'DESC')->select('*');
        }

    }

//========= ========== ========== LAY THONG TIN========== ========== ==========

    public function getInfo($dateOffId = '', $field = '')
    {
        if (empty($dateOffId)) {
            return QcSystemDateOff::get();
        } else {
            $result = QcSystemDateOff::where('dateOff_id', $dateOffId)->first();
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
            return QcSystemDateOff::where('dateOff_id', $objectId)->pluck($column);
        }
    }

    public function dateOffId()
    {
        return $this->dateOff_id;
    }

    public function description($dateOffId = null)
    {

        return $this->pluck('description', $dateOffId);
    }

    public function type($dateOffId = null)
    {

        return $this->pluck('type', $dateOffId);
    }

    public function dateOff($dateOffId = null)
    {

        return $this->pluck('dateOff', $dateOffId);
    }

    public function createdAt($dateOffId = null)
    {
        return $this->pluck('created_at', $dateOffId);
    }

    public function companyId($dateOffId = null)
    {
        return $this->pluck('company_id', $dateOffId);
    }

    public function staffId($dateOffId = null)
    {
        return $this->pluck('staff_id', $dateOffId);
    }

    public function typeLabel($dateOffId = null)
    {
        return ($this->type($dateOffId)[0] == 1) ? 'Cố dịnh' : 'Không cố định';
    }

// tong so thanh toan
    public function totalRecords()
    {
        return QcSystemDateOff::count();
    }

// lay id cuoi
    public function lastId()
    {
        $result = QcSystemDateOff::orderBy('dateOff_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->dateOff_id;
    }

}
