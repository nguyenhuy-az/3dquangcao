<?php

namespace App\Models\Ad3d\Import;

use App\Models\Ad3d\ImportDetail\QcImportDetail;
use App\Models\Ad3d\ImportImage\QcImportImage;
use App\Models\Ad3d\ImportPay\QcImportPay;
use App\Models\Ad3d\Staff\QcStaff;
use Illuminate\Database\Eloquent\Model;

class QcImport extends Model
{
    protected $table = 'qc_import';
    protected $fillable = ['import_id', 'importDate', 'confirmDate', 'confirmStatus', 'confirmNote', 'payStatus', 'exactlyStatus', 'created_at', 'company_id', 'confirmStaff_id', 'importStaff_id'];
    protected $primaryKey = 'import_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- thêm mới ----------

    // insert
    public function insert($importDate, $companyId, $confirmStaffId = null, $importStaffId)
    {
        $hFunction = new \Hfunction();
        $modelImport = new QcImport();
        $modelImport->importDate = $importDate;
        $modelImport->company_id = $companyId;
        $modelImport->confirmStaff_id = $confirmStaffId;
        $modelImport->importStaff_id = $importStaffId;
        $modelImport->created_at = $hFunction->createdAt();
        if ($modelImport->save()) {
            $this->lastId = $modelImport->import_id;
            return true;
        } else {
            return false;
        }
    }

    // get new id after insert
    public function insertGetId()
    {
        return $this->lastId;
    }

    // xác nhận
    public function confirmImport($importId, $payStatus, $exactlyStatus, $confirmStaffId, $confirmNote = null)
    {
        $hFunction = new \Hfunction();
        $confirmDate = $hFunction->carbonNow();
        return QcImport::where('import_id', $importId)->update([
            'exactlyStatus' => $exactlyStatus,
            'confirmDate' => $confirmDate,
            'payStatus' => $payStatus,
            'confirmStatus' => 1,
            'confirmNote' => $confirmNote,
            'confirmStaff_id' => $confirmStaffId
        ]);
    }

    public function checkIdNull($importId)
    {
        return (empty($importId)) ? $this->importId() : $importId;
    }

    public function confirmPaid($importId)
    {
        return QcImport::where('import_id', $importId)->update(['payStatus' => 1]);
    }

    //xóa
    public function deleteImport($importId = null)
    {
        return QcImport::where('import_id', $this->checkIdNull($importId))->delete();
    }
    //========== ========= ========= RELATION ========== ========= =========='
    //---------- nhap kho -----------
    public function companyStore()
    {
        return $this->hasMany('App\Models\Ad3d\CompanyStore\QcCompanyStore', 'import_id', 'import_id');
    }
    //---------- nhân viên nhập -----------
    public function staffImport()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'importStaff_id', 'staff_id');
    }

    # lay danh sach ma vat tu da thanh toan cua 1 nhan vien nhap
    public function listImportIdPaidOfStaffImport($staffId)
    {
        return $this->listIdOfStaff($staffId, null, 1);
    }

    # thong tin tat ca don hang mua vat tu cua 1 nhan vien
    public function infoOfStaff($staffId = null, $date = null, $payStatus = 3, $order = 'DESC')#  $payStatus: 3_tat ca/ 1_da thanh toan/0_chua thanh toan
    {
        $modelImportPay = new QcImportPay();
        if ($payStatus < 3) $listImportId = $modelImportPay->listImportId();
        if (!empty($date)) {
            if ($payStatus == 0) {# chua thanh toan
                return QcImport::where(['importStaff_id' => $staffId])->whereNotIn('import_id', $listImportId)->where('importDate', 'like', "%$date%")->orderBy('importDate', $order)->get();
            } elseif ($payStatus == 1) { # da thanh toan
                return QcImport::where(['importStaff_id' => $staffId])->whereIn('import_id', $listImportId)->where('importDate', 'like', "%$date%")->orderBy('importDate', $order)->get();
            } else {
                return QcImport::where(['importStaff_id' => $staffId])->where('importDate', 'like', "%$date%")->orderBy('importDate', $order)->get();
            }
        } else {
            if ($payStatus == 0) {
                return QcImport::where(['importStaff_id' => $staffId])->whereNotIn('import_id', $listImportId)->orderBy('importDate', $order)->get();
            } elseif ($payStatus == 1) {
                return QcImport::where(['importStaff_id' => $staffId])->whereIn('import_id', $listImportId)->orderBy('importDate', $order)->get();
            } else {
                return QcImport::where(['importStaff_id' => $staffId])->orderBy('importDate', $order)->get();
            }
        }
    }

    public function selectInfoOfStaffAndConfirmedAndUnpaid($staffId, $order = 'DESC')#  $payStatus: 3_tat ca/ 1_da thanh toan/0_chua thanh toan
    {
        $modelImportPay = new QcImportPay();
        $listImportId = $modelImportPay->listImportId();
        return QcImport::where(['importStaff_id' => $staffId])->where('confirmStatus', 1)->where('exactlyStatus', 1)->whereNotIn('import_id', $listImportId)->orderBy('importDate', $order)->select('*');
    }

    public function listIdOfStaff($staffId, $date = null, $payStatus = 3)#  $payStatus: 3_tat ca/ 1_da thanh toan/0_chua thanh toan / 2_da xac nhan chua thanh toan
    {
        $modelImportPay = new QcImportPay();
        if ($payStatus < 3) $listImportId = $modelImportPay->listImportId();
        if (!empty($date)) {
            if ($payStatus == 0) {# chua thanh toan
                return QcImport::where(['importStaff_id' => $staffId])->whereNotIn('import_id', $listImportId)->where('importDate', 'like', "%$date%")->pluck('import_id');
            } elseif ($payStatus == 1) { # da thanh toan
                return QcImport::where(['importStaff_id' => $staffId])->whereIn('import_id', $listImportId)->where('importDate', 'like', "%$date%")->pluck('import_id');
            } elseif ($payStatus == 2) {
                return QcImport::where(['importStaff_id' => $staffId])->whereNotIn('import_id', $listImportId)->where('confirmStatus', 1)->where('importDate', 'like', "%$date%")->pluck('import_id');
            } else {
                return QcImport::where(['importStaff_id' => $staffId])->where('importDate', 'like', "%$date%")->pluck('import_id');
            }
        } else {
            if ($payStatus == 0) {
                return QcImport::where(['importStaff_id' => $staffId])->whereNotIn('import_id', $listImportId)->pluck('import_id');
            } elseif ($payStatus == 1) {
                return QcImport::where(['importStaff_id' => $staffId])->whereIn('import_id', $listImportId)->pluck('import_id');
            } elseif ($payStatus == 2) {
                return QcImport::where(['importStaff_id' => $staffId])->whereNotIn('import_id', $listImportId)->where('confirmStatus', 1)->pluck('import_id');
            } else {
                return QcImport::where(['importStaff_id' => $staffId])->pluck('import_id');
            }
        }
    }

    # tong tien tat ca don hang mua vat tu cua 1 nhan vien
    public function totalMoneyImportOfStaff($staffId, $date = null, $payStatus = 3)
    {
        $modelImportDetail = new QcImportDetail();
        return $modelImportDetail->totalMoneyOfListImport($this->listIdOfStaff($staffId, $date, $payStatus));
    }

    public function listIdConfirmedAndAgreeOfStaff($staffId, $date = null, $payStatus = 3)#  $payStatus: 3_tat ca/ 1_da thanh toan/0_chua thanh toan
    {
        $modelImportPay = new QcImportPay();
        if ($payStatus < 3) $listImportId = $modelImportPay->listImportId();
        if (!empty($date)) {
            if ($payStatus == 0) {# chua thanh toan
                return QcImport::where(['importStaff_id' => $staffId])->whereNotIn('import_id', $listImportId)->where('confirmStatus', 1)->where('exactlyStatus', 1)->where('importDate', 'like', "%$date%")->pluck('import_id');
            } elseif ($payStatus == 1) { # da thanh toan
                return QcImport::where(['importStaff_id' => $staffId])->whereIn('import_id', $listImportId)->where('confirmStatus', 1)->where('exactlyStatus', 1)->where('importDate', 'like', "%$date%")->pluck('import_id');
            } else {
                return QcImport::where(['importStaff_id' => $staffId])->where('confirmStatus', 1)->where('exactlyStatus', 1)->where('importDate', 'like', "%$date%")->pluck('import_id');
            }
        } else {
            if ($payStatus == 0) {
                return QcImport::where(['importStaff_id' => $staffId])->whereNotIn('import_id', $listImportId)->where('confirmStatus', 1)->where('exactlyStatus', 1)->pluck('import_id');
            } elseif ($payStatus == 1) {
                return QcImport::where(['importStaff_id' => $staffId])->whereIn('import_id', $listImportId)->where('confirmStatus', 1)->where('exactlyStatus', 1)->pluck('import_id');
            } else {
                return QcImport::where(['importStaff_id' => $staffId])->where('confirmStatus', 1)->where('exactlyStatus', 1)->pluck('import_id');
            }
        }
    }


    public function infoConfirmedAndAgreeOfStaff($staffId, $date = null, $payStatus = 3)#  $payStatus: 3_tat ca/ 1_da thanh toan/0_chua thanh toan
    {
        $modelImportPay = new QcImportPay();
        if ($payStatus < 3) $listImportId = $modelImportPay->listImportId();
        if (!empty($date)) {
            if ($payStatus == 0) {# chua thanh toan
                return QcImport::where(['importStaff_id' => $staffId])->whereNotIn('import_id', $listImportId)->where('confirmStatus', 1)->where('exactlyStatus', 1)->where('importDate', 'like', "%$date%")->get();
            } elseif ($payStatus == 1) { # da thanh toan
                return QcImport::where(['importStaff_id' => $staffId])->whereIn('import_id', $listImportId)->where('confirmStatus', 1)->where('exactlyStatus', 1)->where('importDate', 'like', "%$date%")->get();
            } else {
                return QcImport::where(['importStaff_id' => $staffId])->where('confirmStatus', 1)->where('exactlyStatus', 1)->where('importDate', 'like', "%$date%")->get();
            }
        } else {
            if ($payStatus == 0) {
                return QcImport::where(['importStaff_id' => $staffId])->whereNotIn('import_id', $listImportId)->where('confirmStatus', 1)->where('exactlyStatus', 1)->get();
            } elseif ($payStatus == 1) {
                return QcImport::where(['importStaff_id' => $staffId])->whereIn('import_id', $listImportId)->where('confirmStatus', 1)->where('exactlyStatus', 1)->get();
            } else {
                return QcImport::where(['importStaff_id' => $staffId])->where('confirmStatus', 1)->where('exactlyStatus', 1)->get();
            }
        }
    }

    public function totalMoneyOfListImportId($listImportId)
    {
        $modelImportDetail = new QcImportDetail();
        return $modelImportDetail->totalMoneyOfListImport($listImportId);
    }

    public function totalMoneyImportConfirmedAndAgreeOfStaff($staffId, $date = null, $payStatus = 3)
    {
        return $this->totalMoneyOfListImportId($this->listIdConfirmedAndAgreeOfStaff($staffId, $date, $payStatus));
    }

    public function infoNoPayOfStaff($staffId = null, $date = null, $order = 'DESC')
    {

    }

    //---------- nhân viên xác nhận -----------
    public function staffConfirm()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'confirmStaff_id', 'staff_id');
    }

    //---------- công ty -----------
    public function company()
    {
        return $this->belongsTo('App\Models\Ad3d\Company\QcCompany', 'company_id', 'company_id');
    }

    public function listIdOfListCompany($listCompanyId)
    {
        return QcImport::whereIn('company_id', $listCompanyId)->pluck('import_id');
    }

    public function listIdOfListCompanyAndImportDate($companyId, $importDate)
    {
        return QcImport::where('importDate', 'like', "%$importDate%")->whereIn('company_id', $companyId)->pluck('import_id');
    }

    public function totalImportNotConfirmOfCompany($companyId)
    {
        return QcImport::where('confirmStatus', 0)->where('company_id', $companyId)->count('import_id');
    }

    // --------------- hình ảnh ------------
    public function importImage()
    {
        return $this->hasMany('App\Models\Ad3d\ImportImage\QcImportImage', 'import_id', 'import_id');
    }

    public function importImageInfoOfImport($importId = null)
    {
        $modelImportImage = new QcImportImage();
        return $modelImportImage->infoOfImport($this->checkIdNull($importId));
    }

    //---------- chi tiết nhập -----------
    public function importDetail()
    {
        return $this->hasMany('App\Models\Ad3d\ImportDetail\QcImportDetail', 'import_id', 'import_id');
    }

    public function infoDetailOfImport($importId = null)
    {
        $modelImportDetail = new QcImportDetail();
        return $modelImportDetail->infoOfImport($this->checkIdNull($importId));
    }

    public function totalMoneyOfImport($importId = null)
    {
        $modelImportDetail = new QcImportDetail();
        return $modelImportDetail->totalMoneyOfImport($this->checkIdNull($importId));

    }

    public function totalMoneyOfListImport($dataImport)
    {
        $totalMoney = 0;
        if (count($dataImport) > 0) {
            foreach ($dataImport as $import) {
                $totalMoney = $totalMoney + $import->totalMoneyOfImport();
            }
        }
        return $totalMoney;
    }

    //---------- chi tiết thanh toán -----------
    public function importPay()
    {
        return $this->hasMany('App\Models\Ad3d\ImportPay\QcImportPay', 'import_id', 'import_id');
    }

    public function importPayInfo($importId = null)
    {
        $modelImportPay = new QcImportPay();
        return $modelImportPay->infoOfImport($this->checkIdNull($importId));
    }

    public function checkPayConfirmOfImport($importId = null)
    {
        $modelImportPay = new QcImportPay();
        return $modelImportPay->checkPayOfImport($this->checkIdNull($importId));
    }

    public function updateConfirmPayOfImport($importId = null)
    {
        $modelImportPay = new QcImportPay();
        return $modelImportPay->updateConfirmPayOfImport($this->checkIdNull($importId));
    }

    //========= ========== ========== GET INFO ========== ========== ==========
    public function getInfoHaveFilter($listStaffId, $searchCompanyFilterId, $dateFilter, $payStatus = 3, $orderBy = 'DESC')
    {
        $modelImportPay = new QcImportPay();
        # $payStatus: 0 - chua thanh toan | 1 - da thanh toan | 2 - da thanh toan chua xac nhan | 3 - da thanh da xac nhan
        if ($payStatus == 4) {
            return QcImport::whereIn('importStaff_id', $listStaffId)->whereIn('company_id', $searchCompanyFilterId)->where('importDate', 'like', "%$dateFilter%")->orderBy('importDate', $orderBy)->orderBy('import_id', 'DESC')->select('*');
        } elseif ($payStatus == 3) {
            return QcImport::whereIn('importStaff_id', $listStaffId)->whereIn('company_id', $searchCompanyFilterId)->where('importDate', 'like', "%$dateFilter%")->whereIn('import_id', $modelImportPay->listImportIdConfirmed())->orderBy('importDate', $orderBy)->orderBy('import_id', 'DESC')->select('*');
        } elseif ($payStatus == 2) {
            return QcImport::whereIn('importStaff_id', $listStaffId)->whereIn('company_id', $searchCompanyFilterId)->where('importDate', 'like', "%$dateFilter%")->whereIn('import_id', $modelImportPay->listImportIdUnconfirmed())->orderBy('importDate', $orderBy)->orderBy('import_id', 'DESC')->select('*');
        } else {
            return QcImport::where('payStatus', $payStatus)->whereIn('importStaff_id', $listStaffId)->whereIn('company_id', $searchCompanyFilterId)->where('importDate', 'like', "%$dateFilter%")->orderBy('importDate', $orderBy)->orderBy('import_id', 'DESC')->select('*');
        }
    }

    public function getInfo($importId = '', $field = '')
    {
        if (empty($importId)) {
            return QcImport::get();
        } else {
            $result = QcImport::where('import_id', $importId)->first();
            if (empty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }

    // ---------- ---------- lấy thông tin --------- -------
    public function pluck($column, $objectId = null)
    {
        if (empty($objectId)) {
            return $this->$column;
        } else {
            return QcImport::where('import_id', $objectId)->pluck($column);
        }
    }

    public function importId()
    {
        return $this->import_id;
    }

    public function importDate($importId = null)
    {
        return $this->pluck('importDate', $importId);
    }


    public function confirmDate($importId = null)
    {

        return $this->pluck('confirmDate', $importId);
    }

    public function confirmStatus($importId = null)
    {

        return $this->pluck('confirmStatus', $importId);
    }

    public function confirmNote($importId = null)
    {

        return $this->pluck('confirmNote', $importId);
    }

    public function payStatus($importId = null)
    {

        return $this->pluck('payStatus', $importId);
    }

    public function exactlyStatus($importId = null)
    {
        return $this->pluck('exactlyStatus', $importId);
    }

    public function companyId($importId = null)
    {
        return $this->pluck('company_id', $importId);
    }

    public function confirmStaffId($importId = null)
    {
        return $this->pluck('customer_id', $importId);
    }

    public function importStaffId($importId = null)
    {
        return $this->pluck('importStaff_id', $importId);
    }

    public function createdAt($importId = null)
    {
        return $this->pluck('created_at', $importId);
    }

// tổng mẫu tin
    public function totalRecords()
    {
        return QcImport::count();
    }

    public function lastId()
    {
        $result = QcImport::orderBy('import_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->import_id;
    }

    //------------------- kiểm tra thông tin -------------------//
    public function checkPay($importId = null)
    {
        return ($this->payStatus($importId) == 0) ? false : true;
    }

    public function checkConfirm($importId = null)
    {
        return ($this->confirmStatus($importId) == 0) ? false : true;
    }

    public function checkExactlyStatus($importId = null)
    {
        $exactlyStatus = $this->exactlyStatus($importId);
        $exactlyStatus = (is_int($exactlyStatus)) ?$exactlyStatus:$exactlyStatus[0] ;
        return ($exactlyStatus == 0) ? false : true;
    }
}
