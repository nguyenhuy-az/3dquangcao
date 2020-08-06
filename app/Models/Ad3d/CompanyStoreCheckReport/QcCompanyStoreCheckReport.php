<?php

namespace App\Models\Ad3d\CompanyStoreCheckReport;

use Illuminate\Database\Eloquent\Model;

class QcCompanyStoreCheckReport extends Model
{
    protected $table = 'qc_company_store_check_report';
    protected $fillable = ['report_id', 'useStatus', 'reportImage', 'reportNumber', 'reportDate', 'confirmDate', 'confirmStatus', 'confirmNote', 'created_at', 'store_id', 'check_id', 'confirmStaff_id'];
    protected $primaryKey = 'report_id';
    public $timestamps = false;

    private $lastId;

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- thêm ----------
    public function insert($useStatus, $reportImage, $storeId, $checkId, $confirmStatus = 0, $confirmNote = null, $confirmDate = null)
    {
        $hFunction = new \Hfunction();
        $modelCompanyStoreCheckReport = new QcCompanyStoreCheckReport();
        # lay lan bao sau cung cua do ngh
        $dataReport = $this->lastInfoOfCompanyStore($storeId);
        #ton tai thong tin bao cao
        if ($hFunction->checkCount($dataReport)) {
            $reportNumber = $dataReport->reportNumber();
            $reportNumber = (is_int($reportNumber)) ? $reportNumber : $reportNumber[0];
        } else {
            $reportNumber = 0; // bao cao lan dau
        }
        $modelCompanyStoreCheckReport->useStatus = $useStatus;
        $modelCompanyStoreCheckReport->reportImage = $reportImage;
        $modelCompanyStoreCheckReport->reportNumber = $reportNumber + 1;
        $modelCompanyStoreCheckReport->reportDate = $hFunction->carbonNow();
        $modelCompanyStoreCheckReport->confirmStatus = $confirmStatus;
        $modelCompanyStoreCheckReport->confirmNote = $confirmNote;
        $modelCompanyStoreCheckReport->confirmDate = $confirmDate;
        $modelCompanyStoreCheckReport->store_id = $storeId;
        $modelCompanyStoreCheckReport->check_id = $checkId;
        $modelCompanyStoreCheckReport->created_at = $hFunction->createdAt();
        if ($modelCompanyStoreCheckReport->save()) {
            $this->lastId = $modelCompanyStoreCheckReport->report_id;
            return true;
        } else {
            return false;
        }
    }

    public function insertGetId()
    {
        return $this->lastId;
    }

    public function checkNullId($id = null)
    {
        return (empty($id)) ? $this->detailId() : $id;
    }

    # hinh anh
    public function rootPathFullImage()
    {
        return 'public/images/company-store-check-report/full';
    }

    public function rootPathSmallImage()
    {
        return 'public/images/company-store-check-report/small';
    }

    # xóa 1 hình ảnh
    public function deleteReportImage($reportId = null)
    {
        $imageName = $this->reportImage($reportId)[0];
        if (QcCompanyStoreCheckReport::where('report_id', $reportId)->update(['reportImage' => null])) {
            $this->dropReportImage($imageName);
        }
    }

    //upload image
    public function uploadImage($source_img, $imageName, $resize = 500)
    {
        $hFunction = new \Hfunction();
        $pathSmallImage = $this->rootPathSmallImage();
        $pathFullImage = $this->rootPathFullImage();
        if (!is_dir($pathFullImage)) mkdir($pathFullImage);
        if (!is_dir($pathSmallImage)) mkdir($pathSmallImage);
        return $hFunction->uploadSaveByFileName($source_img, $imageName, $pathSmallImage . '/', $pathFullImage . '/', $resize);
    }

    //drop image
    public function dropReportImage($imageName)
    {
        unlink($this->rootPathSmallImage() . '/' . $imageName);
        unlink($this->rootPathFullImage() . '/' . $imageName);
    }

    // get path image
    public function pathSmallImage($image)
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
    //========== ========= =========  CAC MOI QUAN HE ========== ========= ==========
    //Kho
    public function companyStore()
    {
        return $this->belongsTo('App\Models\Ad3d\CompanyStore\QcCompanyStore', 'store_id', 'store_id');
    }

    # lay thong tin bao cao mat dung cu cua bao cao bi phat
    public function infoReportLostOfReport($reportId)
    {
        $dataReport = $this->getInfo($reportId);
        $storeId = $dataReport->storeId();
        $reportNumber = $dataReport->reportNumber();
        $reportNumber = (is_int($reportNumber)) ? $reportNumber : $reportNumber[0];
        $getReportNumber = (int)$reportNumber + 1;
        return QcCompanyStoreCheckReport::where('store_id', $storeId)->where('reportNumber', $getReportNumber)->first();
    }

    # lay thong tin bao cao sau cung co hinh anh trong sanh sach cua trươc 1 bao cao nhan vao cua 1 do nghe trong kho
    public function lastInfoHasImageOfPreviousReportAndCompanyStore($reportId, $storeId)
    {
        return QcCompanyStoreCheckReport::where('report_id', '<', $reportId)->whereNotNull('reportImage')->where('store_id', $storeId)->orderBy('report_id', 'DESC')->first();
    }

    # lay thong tin bao cao sau cun co hinh anh cua 1 do nghe
    public function lastInfoOfCompanyStoreHasImage($storeId)
    {
        return QcCompanyStoreCheckReport::where('store_id', $storeId)->whereNotNull('reportImage')->orderBy('report_id', 'DESC')->first();
    }

    # lay thong tin bao cao sau cung cua 1 do nghe trong kho trong kho
    public function lastInfoOfCompanyStore($storeId)
    {
        return QcCompanyStoreCheckReport::where('store_id', $storeId)->orderBy('report_id', 'DESC')->first();
    }

    # lay ma ban giao sau cung cua dung cu trong kho
    public function lastIdOfCompanyStore($storeId)
    {
        $lastInfo = $this->lastInfoOfCompanyStore($storeId);
        return (!empty($lastInfo)) ? $lastInfo->reportId() : null;
    }

    # lay thong tin bao cao sau cung cua dung cu trong kho da xac nhan su dung binh thuong
    public function lastInfoNormalUseOfCompanyStore($storeId)
    {
        return QcCompanyStoreCheckReport::where('store_id', $storeId)->where('useStatus', 1)->orderBy('report_id', 'DESC')->first();
    }

    //---------- kiem tra do nghe -----------
    public function companyStoreCheck()
    {
        return $this->belongsTo('App\Models\Ad3d\CompanyStoreCheck\QcCompanyStoreCheck', 'check_id', 'check_id');
    }

    # thong tin bao cao cua 1 lan kiem tra
    public function infoOfCompanyStoreCheck($checkId)
    {
        return QcCompanyStoreCheckReport::where('check_id', $checkId)->get();
    }

    #kiem tra cong cu co ton tai bao cao chua
    public function checkExistReportOfCompanyCheck($storeId, $checkId)
    {
        return QcCompanyStoreCheckReport::where('store_id', $storeId)->where('check_id', $checkId)->exists();
    }

    //---------- nhan vien xac nhan -----------
    public function confirmStaff()
    {
        return $this->belongsTo('App\Models\Ad3d\Staff\QcStaff', 'staff_id', 'confirmStaff_id');
    }

    //---------- phat mat do nghe -----------
    public function minusMoney()
    {
        return $this->hasOne('App\Models\Ad3d\MinusMoney\QcMinusMoney', 'companyStoreCheckReport_id', 'report_id');
    }

    //========= ========== ========== lấy thông tin ========== ========== ==========
    public function getInfo($reportId = '', $field = '')
    {
        if (empty($reportId)) {
            return QcCompanyStoreCheckReport::get();
        } else {
            $result = QcCompanyStoreCheckReport::where('report_id', $reportId)->first();
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
            return QcCompanyStoreCheckReport::where('report_id', $objectId)->pluck($column);
        }
    }

    public function reportId()
    {
        return $this->report_id;
    }

    public function useStatus($reportId = null)
    {
        return $this->pluck('useStatus', $reportId);
    }

    public function labelUseStatus($reportId = null)
    {
        $useStatus = $this->useStatus($reportId);
        if ($useStatus == 1) {
            return 'Có - dùng được';
        } elseif ($useStatus == 2) {
            return 'Bị hư';
        } elseif ($useStatus == 3) {
            return 'Mất';
        } else {
            return null;
        }
    }


    public function reportImage($reportId = null)
    {
        return $this->pluck('reportImage', $reportId);
    }


    public function reportNumber($reportId = null)
    {
        return $this->pluck('reportNumber', $reportId);
    }

    public function reportDate($reportId = null)
    {
        return $this->pluck('reportDate', $reportId);
    }

    public function confirmStatus($reportId = null)
    {
        return $this->pluck('confirmStatus', $reportId);
    }

    public function confirmDate($reportId = null)
    {
        return $this->pluck('confirmDate', $reportId);
    }

    public function confirmNote($reportId = null)
    {
        return $this->pluck('confirmNote', $reportId);
    }

    public function confirmRight($reportId = null)
    {
        return $this->pluck('confirmRight', $reportId);
    }

    public function createdAt($reportId = null)
    {
        return $this->pluck('created_at', $reportId);
    }

    public function checkId($reportId = null)
    {
        return $this->pluck('check_id', $reportId);
    }

    public function storeId($reportId = null)
    {
        return $this->pluck('store_id', $reportId);
    }

    public function confirmStaffId($reportId = null)
    {
        return $this->pluck('confirmStaff_id', $reportId);
    }

    // last id
    public function lastId()
    {
        $result = QcCompanyStoreCheckReport::orderBy('report_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->report_id;
    }

    //kiem tra xac nhan
    public function checkConfirmStatus($reportId = null)
    {
        return ($this->confirmStatus($reportId) == 1) ? true : false;
    }

    //xac nhan chinh xac hay ko
    public function checkConfirmRight($reportId = null)
    {
        return ($this->confirmRight($reportId) == 1) ? true : false;
    }
}
