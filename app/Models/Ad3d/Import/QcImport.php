<?php

namespace App\Models\Ad3d\Import;

use App\Models\Ad3d\ImportDetail\QcImportDetail;
use App\Models\Ad3d\ImportPay\QcImportPay;
use Illuminate\Database\Eloquent\Model;

class QcImport extends Model
{
    protected $table = 'qc_import';
    protected $fillable = ['import_id', 'image', 'importDate', 'confirmDate', 'confirmStatus', 'confirmNote', 'payStatus', 'exactlyStatus', 'created_at', 'company_id', 'confirmStaff_id', 'importStaff_id'];
    protected $primaryKey = 'import_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    # mac dinh da thanh thanh toan
    public function getDefaultHasPay()
    {
        return 1;
    }

    # mac dinh chua thanh toan
    public function getDefaultNotPay()
    {
        return 0;
    }

    # mac dinh da xac nhan
    public function getDefaultHasConfirm()
    {
        return 1;
    }

    # mac dinh chua xac nhan
    public function getDefaultNotConfirm()
    {
        return 0;
    }

    # mac dinh nhap chinh xa
    public function getDefaultHasExactly()
    {
        return 1;
    }

    # mac dinh khong chinh xa
    public function getDefaultNotExactly()
    {
        return 0;
    }

    // insert
    public function insert($image = null, $importDate, $companyId, $confirmStaffId = null, $importStaffId)
    {
        $hFunction = new \Hfunction();
        $modelImport = new QcImport();
        $modelImport->image = $image;
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

    # cap nhat anh hoa don
    public function updateImage($importId, $image)
    {
        return QcImport::where('import_id', $importId)->update([
            'image' => $image
        ]);
        # PHAI XOA ANH CU - CAP NHAT SAU
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
            'confirmStatus' => $this->getDefaultHasConfirm(),
            'confirmNote' => $confirmNote,
            'confirmStaff_id' => $confirmStaffId
        ]);
    }

    public function checkIdNull($importId)
    {
        return (empty($importId)) ? $this->importId() : $importId;
    }

    # xac nhan da thanh toan
    public function confirmPaid($importId)
    {
        return QcImport::where('import_id', $importId)->update(['payStatus' => $this->getDefaultHasPay()]);
    }

    //xóa
    public function deleteImport($importId = null)
    {
        return QcImport::where('import_id', $this->checkIdNull($importId))->delete();
    }

    #----------- update ----------
    public function rootPathFullImage()
    {
        return 'public/images/import-image/full';
    }

    public function rootPathSmallImage()
    {
        return 'public/images/import-image/small';
    }

    //thêm hình ảnh
    public function uploadImage($file, $imageName, $size = 500)
    {
        $hFunction = new \Hfunction();
        $pathSmallImage = $this->rootPathSmallImage();
        $pathFullImage = $this->rootPathFullImage();
        if (!is_dir($pathFullImage)) mkdir($pathFullImage);
        if (!is_dir($pathSmallImage)) mkdir($pathSmallImage);
        return $hFunction->uploadSaveByFileName($file, $imageName, $pathSmallImage . '/', $pathFullImage . '/', $size);
    }

    //Xóa ảnh
    public function dropImage($imageName)
    {
        unlink($this->rootPathSmallImage() . '/' . $imageName);
        unlink($this->rootPathFullImage() . '/' . $imageName);
    }

    public function pathSmallImage($image = null)
    {
        if (empty($image)) {
            return null;
        } else {
            return asset($this->rootPathSmallImage() . '/' . $image);
        }
    }

    public function pathFullImage($image)
    {
        if (empty($image)) {
            return null;
        } else {
            return asset($this->rootPathFullImage() . '/' . $image);
        }
    }

    public function pluck($column, $objectId = null)
    {
        if (empty($objectId)) {
            return $this->$column;
        } else {
            return QcImport::where('import_id', $objectId)->pluck($column)[0];
        }
    }

    public function importId()
    {
        return $this->import_id;
    }

    public function image($importId = null)
    {
        return $this->pluck('image', $importId);
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

    # tong tien mua cua danh sanh hoa don - tat ca
    public function totalMoneyOfListImport($dataImport)
    {
        $hFunction = new \Hfunction();
        $totalMoney = 0;
        if ($hFunction->checkCount($dataImport)) {
            foreach ($dataImport as $import) {
                $totalMoney = $totalMoney + $import->totalMoneyOfImport();
            }
        }
        return $totalMoney;
    }

    # tong tien mua cua danh sanh hoa don - da xac nhan thanh toan
    public function totalMoneyOfListImportHasConfirmPay($dataImport)
    {
        $hFunction = new \Hfunction();
        $totalMoney = 0;
        if ($hFunction->checkCount($dataImport)) {
            foreach ($dataImport as $import) {
                # da xac nhan
                if ($this->importPayCheckHasConfirm($import->importId())) {
                    $totalMoney = $totalMoney + $import->totalMoneyOfImport();
                }

            }
        }
        return $totalMoney;
    }
    //------------------- kiểm tra thông tin -------------------//
    # kiem tra da thanh toan
    public function checkHasPay($importId = null)
    {
        return ($this->payStatus($importId) == $this->getDefaultHasPay()) ? true : false;
    }

    #  kiem tra da xac nhan
    public function checkHasConfirm($importId = null)
    {
        return ($this->confirmStatus($importId) == $this->getDefaultHasConfirm()) ? true : false;
    }

    # kiem tra nhap chanh xac khong
    public function checkHasExactlyStatus($importId = null)
    {
        return ($this->exactlyStatus($importId) == $this->getDefaultHasExactly()) ? true : false;
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

    # chon tat ca hoa don mua theo danh sach ma NV - cua 1 cty
    public function selectAllInfoOfListStaffId($companyId, $listStaffId, $date = null, $order = 'DESC')
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($date)) {
            return QcImport::where('company_id', $companyId)->whereIn('importStaff_id', $listStaffId)->orderBy('importDate', $order)->select('*');
        } else {
            return QcImport::where('company_id', $companyId)->whereIn('importStaff_id', $listStaffId)->where('importDate', 'like', "%$date%")->orderBy('importDate', $order)->select('*');
        }
    }

    #chon thong tin hoa don theo danh sach ma NV va trang thai thanh toan
    public function selectInfoOfListStaffIdAndPayStatus($companyId, $listStaffId, $payStatus, $date = null, $order = 'DESC')
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($date)) {
            return QcImport::where('company_id', $companyId)->whereIn('importStaff_id', $listStaffId)->where('payStatus', $payStatus)->orderBy('importDate', $order)->select('*');
        } else {
            return QcImport::where('company_id', $companyId)->whereIn('importStaff_id', $listStaffId)->where('payStatus', $payStatus)->where('importDate', 'like', "%$date%")->orderBy('importDate', $order)->select('*');
        }
    }

    #chon thong tin hoa don theo danh sach ma NV da xac nhan dong y va chua thanh toan
    public function selectInfoOfListStaffIdAndHasConfirmNotPay($companyId, $listStaffId, $date = null, $order = 'DESC')
    {
        $hFunction = new \Hfunction();
        # chua thanh toan
        $payStatus = $this->getDefaultNotPay();
        # da xac nhan
        $confirmStatus = $this->getDefaultHasConfirm();
        # nhap dung - dong y duyet
        $exactlyStatus = $this->getDefaultHasExactly();
        if ($hFunction->checkEmpty($date)) {
            return QcImport::where('company_id', $companyId)->whereIn('importStaff_id', $listStaffId)->where([
                'payStatus' => $payStatus,
                'confirmStatus' => $confirmStatus,
                'exactlyStatus' => $exactlyStatus
            ])->orderBy('importDate', $order)->select('*');
        } else {
            return QcImport::where('company_id', $companyId)->whereIn('importStaff_id', $listStaffId)->where([
                'payStatus' => $payStatus,
                'confirmStatus' => $confirmStatus,
                'exactlyStatus' => $exactlyStatus
            ])->where('importDate', 'like', "%$date%")->orderBy('importDate', $order)->select('*');
        }
    }

    #chon thong tin hoa don theo danh sach ma NV da xac nhan dong y
    public function selectInfoOfListStaffIdAndHasConfirmHasExactly($companyId, $listStaffId, $date = null, $order = 'DESC')
    {
        $hFunction = new \Hfunction();
        # da xac nhan
        $confirmStatus = $this->getDefaultHasConfirm();
        # nhap dung - dong y duyet
        $exactlyStatus = $this->getDefaultHasExactly();
        if ($hFunction->checkEmpty($date)) {
            return QcImport::where('company_id', $companyId)->whereIn('importStaff_id', $listStaffId)->where([
                'confirmStatus' => $confirmStatus,
                'exactlyStatus' => $exactlyStatus
            ])->orderBy('importDate', $order)->select('*');
        } else {
            return QcImport::where('company_id', $companyId)->whereIn('importStaff_id', $listStaffId)->where([
                'confirmStatus' => $confirmStatus,
                'exactlyStatus' => $exactlyStatus
            ])->where('importDate', 'like', "%$date%")->orderBy('importDate', $order)->select('*');
        }
    }

    # chon tat ca hoa don mua cua 1 nhan vien - cua 1 cong ty
    public function selectAllInfoOfStaff($companyId, $staffId, $date = null, $order = 'DESC')
    {
        return $this->selectAllInfoOfListStaffId($companyId, [$staffId], $date, $order);
    }

    #chon thong tin hoa don cua 1 nhan vien theo tang thai thanh toan - cua 1 cty
    public function selectInfoOfStaffAndPayStatus($companyId, $staffId, $payStatus, $date = null, $order = 'DESC')
    {
        return $this->selectInfoOfListStaffIdAndPayStatus($companyId, [$staffId], $payStatus, $date, $order);
    }

    # lay tat ca thong tin hoa don cua 1 nhan vien cua 1 cty
    public function getInfoOfStaff($companyId, $staffId, $date = null, $orderBy = 'DESC')
    {
        return $this->selectAllInfoOfStaff($companyId, $staffId, $date, $orderBy)->get();
    }

    #lay thong tin hoa don cua 1 nhan vien theo tang thai thanh toan
    public function getInfoOfStaffAndPayStatus($companyId, $staffId, $payStatus, $date = null, $orderBy = 'DESC')
    {
        return $this->selectInfoOfStaffAndPayStatus($companyId, $staffId, $payStatus, $date, $orderBy)->get();
    }

    # thong tin hoa don mua chua thanh toan
    /*public function getInfoNotPayOfStaff($companyId, $staffId, $date = null, $order = 'DESC')
    {
        return $this->getInfoOfStaffAndPayStatus($companyId, $staffId, $this->getDefaultNotPay(), $date, $order);
    }*/

    # thong tin hoa don mua da thanh toan
    public function getInfoHastPayOffStaff($companyId, $staffId, $date = null, $order = 'DESC')
    {
        return $this->getInfoOfStaffAndPayStatus($companyId, $staffId, $this->getDefaultHasPay(), $date, $order);
    }

    # tong tien tat ca don hang mua vat tu cua 1 nhan vien
    public function totalMoneyImportOfStaff($companyId, $staffId, $date = null)
    {
        return $this->totalMoneyOfListImport( $this->getInfoOfStaff($companyId, $staffId, $date));
    }

    # tong tien hang mua vat tu cua 1 nhan vien - da xac nhan thanh toan
    public function totalMoneyImportOfStaffHasConfirmPay($companyId, $staffId, $date = null)
    {
        return $this->totalMoneyOfListImportHasConfirmPay($this->getInfoHastPayOffStaff($companyId, $staffId, $date));
    }

    # tong tien hang mua vat tu cua 1 nhan vien - da xac nhan va chu thanh toan
    public function totalMoneyImportOfStaffHasConfirmNotPay($companyId, $staffId, $date = null)
    {
        return $this->totalMoneyOfListImport($this->selectInfoOfListStaffIdAndHasConfirmNotPay($companyId, [$staffId], $date)->get());
    }

    # tong tien hang mua vat tu cua 1 nhan vien - da xac nhan va dong y
    public function totalMoneyImportOfStaffHasConfirmHasExactly($companyId, $staffId, $date = null)
    {
        return $this->totalMoneyOfListImport($this->selectInfoOfListStaffIdAndHasConfirmHasExactly($companyId, [$staffId], $date)->get());
    }

    # tong tien hang mua vat tu cua 1 nhan vien - chua xac nhan thanh toan
    /*public function totalMoneyImportOfStaffNotConfirmPay($companyId, $staffId, $date = null)
    {
        $dataImport = $this->getInfoHastPayOffStaff($companyId, $staffId, $date);
        return $this->totalMoneyOfListImportHasConfirmPay($dataImport);
    }*/

    //---------- chi tiết nhập -----------
    public function importDetail()
    {
        return $this->hasMany('App\Models\Ad3d\ImportDetail\QcImportDetail', 'import_id', 'import_id');
    }

    # tong tien nhap cua 1 hoa don
    public function totalMoneyOfImport($importId = null)
    {
        $modelImportDetail = new QcImportDetail();
        return $modelImportDetail->totalMoneyOfImport($this->checkIdNull($importId));

    }

    # chi tiet nhap cua hoa don
    public function importDetailGetInfo($importId = null)
    {
        $modelImportDetail = new QcImportDetail();
        return $modelImportDetail->infoOfImport($this->checkIdNull($importId));
    }

    //---------- chi tiết thanh toán -----------
    public function importPay()
    {
        return $this->hasMany('App\Models\Ad3d\ImportPay\QcImportPay', 'import_id', 'import_id');
    }

    # lay thong tin thanh toan
    public function importPayGetInfo($importId = null)
    {
        $modelImportPay = new QcImportPay();
        return $modelImportPay->infoOfImport($this->checkIdNull($importId));
    }

    # kiem tra da xac nhan da nhan tien thanh toan
    public function importPayCheckHasConfirm($importId = null)
    {
        $modelImportPay = new QcImportPay();
        return $modelImportPay->checkHasConfirmOfImport($this->checkIdNull($importId));
    }

    # xac nhan da nhan tien
    public function confirmPayment($importId = null)
    {
        $modelImportPay = new QcImportPay();
        return $modelImportPay->updateConfirmPayOfImport($this->checkIdNull($importId));
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
        return QcImport::where('confirmStatus', $this->getDefaultNotConfirm())->where('company_id', $companyId)->count('import_id');
    }

    # lay danh sach mua vat tu da thanh toan cua 1 nhan vien nhap
    public function listImportIdPaidOfStaffImport($staffId)
    {
        return $this->listIdOfStaff($staffId, null, $this->getDefaultHasPay());
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
    /*
        # thong tin tat ca hoa don mua vat tu cua 1 nhan vien
        public function infoOfStaff($staffId, $date = null, $payStatus = null, $order = 'DESC')#  $payStatus: 3_tat ca/ 1_da thanh toan/0_chua thanh toan
        {
            $modelImportPay = new QcImportPay();
            if ($payStatus < 3) $listImportId = $modelImportPay->listImportId();
            if (!empty($date)) {
                if ($payStatus == 0) {
                    # chua thanh toan
                    return QcImport::where(['importStaff_id' => $staffId])->whereNotIn('import_id', $listImportId)->where('importDate', 'like', "%$date%")->orderBy('importDate', $order)->get();
                } elseif ($payStatus == 1) {
                    # da thanh toan
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


        # chon thong tin da xac nhan chua thanh toan cua 1 nhan vien
        public function selectInfoOfStaffAndConfirmedAndUnpaid($staffId, $order = 'DESC')
            #  $payStatus: 3_tat ca/ 1_da thanh toan/0_chua thanh toan
        {
            $modelImportPay = new QcImportPay();
            $listImportId = $modelImportPay->listImportId();
            return QcImport::where(['importStaff_id' => $staffId])->where('confirmStatus', 1)->where('exactlyStatus', 1)->whereNotIn('import_id', $listImportId)->orderBy('importDate', $order)->select('*');
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



        // --------------- hình ảnh ------------
        public function importImage()
        {
            return $this->hasMany('App\Models\Ad3d\ImportImage\QcImportImage', 'import_id', 'import_id');
        }

        # lay tat ca anh
        public function importImageInfoOfImport($importId = null)
        {
            $modelImportImage = new QcImportImage();
            return $modelImportImage->infoOfImport($this->checkIdNull($importId));
        }

        # lay 1 hinh anh - tat tinh nang cho up nhieu anh hoa don
        public function getOneImportImage($importId = null)
        {
            $modelImportImage = new QcImportImage();
            return $modelImportImage->oneInfoOfImport($this->checkIdNull($importId));
        }



        public function infoDetailOfImport($importId = null)
        {
            $modelImportDetail = new QcImportDetail();
            return $modelImportDetail->infoOfImport($this->checkIdNull($importId));
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
        */

}
