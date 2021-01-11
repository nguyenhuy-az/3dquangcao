<?php

namespace App\Models\Ad3d\Company;

use App\Models\Ad3d\BonusDepartment\QcBonusDepartment;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\CompanyStore\QcCompanyStore;
use App\Models\Ad3d\Department\QcDepartment;
use App\Models\Ad3d\Import\QcImport;
use App\Models\Ad3d\ImportImage\QcImportImage;
use App\Models\Ad3d\ImportPay\QcImportPay;
use App\Models\Ad3d\LicenseLateWork\QcLicenseLateWork;
use App\Models\Ad3d\LicenseOffWork\QcLicenseOffWork;
use App\Models\Ad3d\Order\QcOrder;
use App\Models\Ad3d\OrderAllocation\QcOrderAllocation;
use App\Models\Ad3d\OrderBonusBudget\QcOrderBonusBudget;
use App\Models\Ad3d\OrderCancel\QcOrderCancel;
use App\Models\Ad3d\OrderPay\QcOrderPay;
use App\Models\Ad3d\OverTimeRequest\QcOverTimeRequest;
use App\Models\Ad3d\PayActivityDetail\QcPayActivityDetail;
use App\Models\Ad3d\Product\QcProduct;
use App\Models\Ad3d\ProductTypePrice\QcProductTypePrice;
use App\Models\Ad3d\Rank\QcRank;
use App\Models\Ad3d\SalaryBeforePay\QcSalaryBeforePay;
use App\Models\Ad3d\SalaryBeforePayRequest\QcSalaryBeforePayRequest;
use App\Models\Ad3d\SalaryPay\QcSalaryPay;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\SystemDateOff\QcSystemDateOff;
use App\Models\Ad3d\TimekeepingProvisional\QcTimekeepingProvisional;
use App\Models\Ad3d\TimekeepingProvisionalImage\QcTimekeepingProvisionalImage;
use App\Models\Ad3d\Transfers\QcTransfers;
use App\Models\Ad3d\Work\QcWork;
use App\Models\Ad3d\WorkAllocation\QcWorkAllocation;
use Illuminate\Database\Eloquent\Model;

class QcCompany extends Model
{
    protected $table = 'qc_companies';
    protected $fillable = ['company_id', 'root_id', 'companyCode', 'name', 'nameCode', 'address', 'phone', 'email', 'website', 'logo', 'companyType', 'action', 'created_at', 'parent_id'];
    protected $primaryKey = 'company_id';
    public $timestamps = false;

    private $lastId;

    # mac dinh co hoat dong
    public function getDefaultHasAction()
    {
        return 1;
    }

    # mac dinh khong co hoat dong
    public function getDefaultNotAction()
    {
        return 0;
    }

    # mac dinh loai cty me
    public function getDefaultParentCompanyType()
    {
        return 0;
    }

    # mac dinh loai cty con - chi nhanh
    public function getDefaultBranchCompanyType()
    {
        return 1;
    }

    //============== ================ giá tri mac dinh cua he thong

    # mac dinh gia tri tat cac ngay
    public function getDefaultValueAllDay()
    {
        return 100;
    }

    # mac dinh gia tri tat ca cac thang trong
    public function getDefaultValueAllMonth()
    {
        return 100;
    }

    # mac dinh gia tri ta ca cac nam
    public function getDefaultValueAllYear()
    {
        return 100;
    }

    # mac dinh lay tat ca trang thai xac nhan
    public function getDefaultValueAllConfirmStatus()
    {
        return 100;
    }

    # mac dinh da xac nhan cua he thong
    public function getDefaultValueHasConfirm()
    {
        return 1;
    }
    # mac dinh chua xac nhan cua he thong
    public function getDefaultValueNotConfirm()
    {
        return 0;
    }

    # mac dinh lay tat ca trang thai ket thuc
    public function getDefaultValueAllFinish()
    {
        return 100;
    }
    # mac dinh da ket thuc cua he thong
    public function getDefaultValueHasFinish()
    {
        return 1;
    }
    # mac dinh chua ket thuc cua he thong
    public function getDefaultValueNotFinish()
    {
        return 0;
    }

    # mac dinh cap nhan vien he thong cua 1 cty
    public function getDefaultHasRootOfStaff()
    {
        return 1;
    }
    public function getDefaultNotRootOfStaff()
    {
        return 0;
    }
    #========== ========== ========== TU DONG KIEM TRA DU LIEU CUA HE THONG ========== ========== ==========
    # KIEM TRA DU LIEU TU DONG
    /*
     * DUOC GOI TRONG FUNCTION "LOGIN" MODEL NHAN VIEN
     *
     * */
    public function checkAutoInfo()
    {
        $hFunction = new \Hfunction();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelWork = new QcWork();
        $modelOrderAllocation = new QcOrderAllocation();
        $modelWorkAllocation = new QcWorkAllocation();
        $modelOverTimeRequest = new QcOverTimeRequest();

        // ===== ===== DON HANG - SAN PHAM ====== ======
        # kiem tra thong tin ban giao don hang - cua bo phan thi cong cap quan ly
        $modelOrderAllocation->autoCheckMinusMoneyLateOrderAllocation();
        # kiem tra cap nhat tre cua thi cong san pham
        $modelWorkAllocation->checkUpdateLateStatus();

        # kiem tra ap dung phat thi cong san pham
        $modelWorkAllocation->autoCheckMinusMoneyLateWorkAllocation();

        // ===== ===== ĐO NGHE ====== ======
        # phan cong kiem tra do nghe
        $modelCompanyStaffWork->checkCompanyStoreOfCurrentDate();

        // ===== ===== CHAM CONG ====== ======
        # kiem tra cham cong theo ngay
        $modelWork->checkAutoTimekeepingProvisionalOfActivityWork();

        #kiểm tra đầu tháng để cho ra bang luong làm việc của tháng mới
        $modelWork->checkEndWorkOfMonth();

        # gio hien tai
        $currentHours = (int)$hFunction->currentHour();
        # sau 8h sang
        if ($currentHours > 8) {
            # chua kiem tra
            if (!$this->checkAutoInCurrentDate()) {
                # kiem tra hang tang ca
                $modelOverTimeRequest->checkAutoFinish();
                # cap nhat ngay kiem tra
                $this->updateCheckAutoDate();
            }
        }

    }

    # kiem tra ngay hien tai co kiem tra du lieu tu dong chua - cua tat ca cong ty
    public function checkAutoInCurrentDate()
    {
        $hFunction = new \Hfunction();
        $checkDate = $hFunction->currentDate();
        return QcCompany::where('checkAutoDate', 'like', "%$checkDate%")->exists();
    }

    # cap nhat ngay kiem tra du lieu tu dong cua hang hien tai - cua tat ca cong ty
    public function updateCheckAutoDate()
    {
        $hFunction = new \Hfunction();
        return QcCompany::where('action', $this->getDefaultHasAction())->update(
            [
                'checkAutoDate' => $hFunction->carbonNow()
            ]
        );
    }
    #========== ========== ========== THEM && CAP NHAT ========== ========== ==========
    #---------- thêm ----------
    public function insert($companyCode, $name, $nameCode, $address, $phone, $email, $website, $companyType = 1, $logo = null, $parentId = null)
    {
        $hFunction = new \Hfunction();
        $modelCompany = new QcCompany();
        $modelCompany->companyCode = $companyCode;
        $modelCompany->name = $name;
        $modelCompany->nameCode = $nameCode;
        $modelCompany->address = $address;
        $modelCompany->phone = $phone;
        $modelCompany->email = $email;
        $modelCompany->website = $website;
        $modelCompany->logo = $logo;
        $modelCompany->companyType = $companyType;
        $modelCompany->parent_id = $parentId;
        $modelCompany->created_at = $hFunction->createdAt();
        if ($modelCompany->save()) {
            $this->lastId = $modelCompany->company_id;
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

    public function checkIdNull($companyId)
    {
        return (empty($companyId)) ? $this->companyId() : $companyId;
    }

    #------ ------ lay gia tri mac dinh cua he thong ------ ----
    # thoi gian vao lam viec mac dinh cua ngay hien tai
    public function getDefaultTimeBeginToWorkOfCurrentDate() //return Y-m-d H:i
    {
        $hFunction = new \Hfunction();
        return $this->getDefaultTimeBeginToWorkOfDate($hFunction->carbonNow());
    }

    # thoi gian vao lam viec mac dinh theo ngay
    public function getDefaultTimeBeginToWorkOfDate($date) //return Y-m-d H:i
    {
        //$hFunction = new \Hfunction();
        return date('Y-m-d 08:00', strtotime($date));
    }

    # thoi gian lam viec ra mac dinh cua ngay hien tai
    public function getDefaultTimeEndToWorkOfCurrentDate() //return Y-m-d H:i
    {
        $hFunction = new \Hfunction();
        return $this->getDefaultTimeEndToWorkOfDate($hFunction->carbonNow());
    }

    # thoi gian lam viec ra mac dinh theo ngay
    public function getDefaultTimeEndToWorkOfDate($date) //return Y-m-d H:i
    {
        return date('Y-m-d 17:30', strtotime($date));
    }

    # gioi han bao cao gio ra cua ngay lam viec
    public function getReportLimitTimeEndOfDate($date)
    {
        $hFunction = new \Hfunction();
        # mac dinh la 8h sang ngay hom sau
        return date('Y-m-d 08:00', strtotime($hFunction->datetimePlusDay($date, 1)));
    }

    # kiem tra dang lam trong buoi sang
    public function checkWorkInMorningByCurrentTime()
    {
        $hours = (int)date('H');
        if (8 <= $hours && $hours < 14) { # tinh vao buoi sang
            return true;
        } else {
            return false;
        }
    }

    # kiem tra dang lam trong buoi sang
    public function checkWorkInAfternoonByCurrentTime()
    {
        $hours = (int)date('H');
        if (14 <= $hours && $hours < 18) { # tinh vao buoi chieu
            return true;
        } else {
            return false;
        }
    }

    # kiem tra dang lam trong buoi sang
    public function checkWorkInEveningByCurrentTime()
    {
        $hours = (int)date('H');
        if (1 <= $hours && $hours < 4) { # tang ca den ngay hom sau
            return true;
        } elseif (18 <= $hours) { # tinh vao buoi chieu
            return true;
        } else {
            return false;
        }
    }


    #----------- cập nhật ----------
    public function updateInfo($companyId, $companyCode, $name, $nameCode, $address, $phone, $email, $website, $companyType, $logo)
    {
        return QcCompany::where('company_id', $companyId)->update([
            'companyCode' => $companyCode,
            'name' => $name,
            'nameCode' => $nameCode,
            'address' => $address,
            'phone' => $phone,
            'email' => $email,
            'website' => $website,
            'logo' => $logo,
            'companyType' => $companyType,
        ]);
    }

    # status
    public function updateStatus($companyId, $status)
    {
        return QcCompany::where('company_id', $companyId)->update(['status' => $status]);
    }

    # delete
    public function actionDelete($companyId = null)
    {
        if (empty($companyId)) $companyId = $this->companyId();
        return QcCompany::where('company_id', $companyId)->delete();
    }

    public function rootPathFullImage()
    {
        return 'public/images/company/full';
    }

    public function rootPathSmallImage()
    {
        return 'public/images/company/small';
    }

    public function uploadImage($source_img, $imageName, $resize = 500)
    {
        $hFunction = new \Hfunction();
        $pathSmallImage = $this->rootPathSmallImage();
        $pathFullImage = $this->rootPathFullImage();
        if (!is_dir($pathFullImage)) mkdir($pathFullImage);
        if (!is_dir($pathSmallImage)) mkdir($pathSmallImage);
        return $hFunction->uploadSaveByFileName($source_img, $imageName, $pathSmallImage . '/', $pathFullImage . '/', $resize);
    }

    public function deleteImage($imageId = null)
    {
        if (empty($imageId)) $imageId = $this->imageId();
        $imageName = $this->name($imageId)[0];
        if (QcCompany::where('company_id', $imageId)->delete()) {
            $this->dropImage($imageName);
        }
    }
    #========== ========== ========== cac moi quan he ========== ========== ==========

    #============================= begin new ===============================
    # dung hasManyThrough
    /*public function staffWorkDepartment()
    {
        return $this->hasManyThrough('App\Models\Ad3d\StaffWorkDepartment\QcStaffWorkDepartment', 'App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork', 'company_id','work_id','detail_id');
    }*/

    #----------- cong ty me------------
    public function parent()
    {
        return $this->belongsTo('App\Models\Ad3d\Company\QcCompany', 'company_id', 'company_id');
    }

    #----------- ho so xin viec------------
    public function jobApplication()
    {
        return $this->hasMany('App\Models\Ad3d\JobApplication\QcJobApplication', 'company_id', 'company_id');
    }

    #----------- bo do nghe ------------
    public function toolPackage()
    {
        return $this->hasMany('App\Models\Ad3d\ToolPackage\QcToolPackage', 'company_id', 'company_id');
    }

    #----------- chuyen tien ------------
    public function transfer()
    {
        return $this->hasMany('App\Models\Ad3d\Transfers\QcTransfers', 'company_id', 'company_id');
    }

    #----------- ngay nghi he thong ------------
    public function systemDateOff()
    {
        return $this->hasMany('App\Models\Ad3d\SystemDateOff\QcSystemDateOff', 'company_id', 'company_id');
    }

    # lay ngay nghi cua 1 cong ty
    public function systemDateOfFOfCompanyAndDate($companyId, $date = null)
    {
        $modelSystemDateOff = new QcSystemDateOff();
        return $modelSystemDateOff->selectInfoOfCompanyAndDate($companyId, $date)->get();
    }

    # ngay bat buoc nghi cua 1 cong ty
    public function systemDateOffObligatoryOfCompanyAndDate($companyId, $date = null)
    {
        $modelSystemDateOff = new QcSystemDateOff();
        return $modelSystemDateOff->infoDateObligatoryOfCompanyAndDate($companyId, $date);
    }

    # ngay khong bat buoc nghi cua 1 cong ty
    public function systemDateOffOptionalOfCompanyAndDate($companyId, $date = null)
    {
        $modelSystemDateOff = new QcSystemDateOff();
        return $modelSystemDateOff->infoDateOptionalOfCompanyAndDate($companyId, $date);
    }

    #============================= end new ===============================
    #----------- thong tin NV lam viec ------------
    public function companyStaffWork()
    {
        return $this->hasMany('App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork', 'company_id', 'company_id');
    }

    #----------- nhân viên cũ ------------ x
    # danh sach tat ca nhan vien cua 1 cong ty
    public function staffOfCompany($companyId = null)
    {
        $modelStaff = new QcStaff();
        return $modelStaff->infoOfCompany((empty($companyId) ? $this->companyId() : $companyId));
    }

    # lay TAT CA danh sach ma nv theo danh sach cty
    public function staffIdOfListCompanyId($listCompanyId)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        return $modelCompanyStaffWork->listStaffIdOfListCompanyId($listCompanyId);
    }

    # lay danh sach nv DANG HOAT DONG cua MOT cty
    public function staffInfoActivityOfCompanyId($companyId)
    {
        $modelStaff = new QcStaff();
        return $modelStaff->getInfoActivityByListStaffId($this->staffIdOfListCompanyId([$companyId]));
    }

    # lay danh sach nv DANG HOAT DONG theo danh sach cong ty
    public function staffInfoActivityOfListCompanyId($listCompanyId)
    {
        $modelStaff = new QcStaff();
        return $modelStaff->getInfoActivityByListStaffId($this->staffIdOfListCompanyId($listCompanyId));
    }

    // ========= BO PHAN THU QUY =========
    # danh sach tat ca nhan vien BO PHAN THU QUY - dang hoat dong
    public function staffInfoActivityOfTreasurerDepartment($companyId)
    {
        $modelStaff = new QcStaff();
        $modelDepartment = new QcDepartment();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $listStaffId = $modelCompanyStaffWork->listStaffIdActivityOfCompanyIdAndListDepartmentId($companyId, [$modelDepartment->treasurerDepartmentId()]);
        return $modelStaff->getInfoByListStaffId($listStaffId);
    }

    # danh sach nhan vien BO PHAN THU QUY CAP QUA LY- dang hoat dong
    public function staffInfoActivityOfTreasurerManage($companyId)
    {
        $modelStaff = new QcStaff();
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $listStaffId = $modelCompanyStaffWork->listStaffIdActivityOfCompanyIdAndListDepartmentId($companyId, [$modelDepartment->treasurerDepartmentId()], $modelRank->manageRankId());
        return $modelStaff->getInfoByListStaffId($listStaffId);
    }

    # danh sach nhan vien BO PHAN THU QUY CAP NHAN VIEN- dang hoat dong
    public function staffInfoActivityOfTreasurerStaff($companyId)
    {
        $modelStaff = new QcStaff();
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $listStaffId = $modelCompanyStaffWork->listStaffIdActivityOfCompanyIdAndListDepartmentId($companyId, [$modelDepartment->treasurerDepartmentId()], $modelRank->staffRankId());
        return $modelStaff->getInfoByListStaffId($listStaffId);
    }

    // ========= BO PHAN THI CONG =========
    # danh sach tat ca nhan vien BO PHAN THI CONG - dang hoat dong
    public function staffInfoActivityOfConstructionDepartment($companyId)
    {
        $modelStaff = new QcStaff();
        $modelDepartment = new QcDepartment();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $listStaffId = $modelCompanyStaffWork->listStaffIdActivityOfCompanyIdAndListDepartmentId($companyId, [$modelDepartment->constructionDepartmentId()]);
        return $modelStaff->getInfoByListStaffId($listStaffId);
    }

    # danh sach nhan vien BO PHAN THI CONG CAP QUA LY- dang hoat dong
    public function staffInfoActivityOfConstructionManage($companyId)
    {
        $modelStaff = new QcStaff();
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $listStaffId = $modelCompanyStaffWork->listStaffIdActivityOfCompanyIdAndListDepartmentId($companyId, [$modelDepartment->constructionDepartmentId()], $modelRank->manageRankId());
        return $modelStaff->getInfoByListStaffId($listStaffId);
    }

    # danh sach nhan vien BO PHAN THI CONG CAP NHAN VIEN- dang hoat dong
    public function staffInfoActivityOfConstructionStaff($companyId)
    {
        $modelStaff = new QcStaff();
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $listStaffId = $modelCompanyStaffWork->listStaffIdActivityOfCompanyIdAndListDepartmentId($companyId, [$modelDepartment->constructionDepartmentId()], $modelRank->staffRankId());
        return $modelStaff->getInfoByListStaffId($listStaffId);
    }

    #----------- chi - PHIEN BAN CU ------------
    public function payment()
    {
        return $this->hasMany('App\Models\Ad3d\Payment\QcPayment', 'company_id', 'company_id');
    }

    #----------- chi hoat dong  ------------
    public function payActivityDetail()
    {
        return $this->hasMany('App\Models\Ad3d\PayActivityDetail\QcPayActivityDetail', 'company_id', 'company_id');
    }

    public function totalPayActivityNotConfirmOfCompany($companyId = null)
    {
        $modelPayActivityDetail = new QcPayActivityDetail();
        return $modelPayActivityDetail->totalPayActivityNotConfirmOfCompany($this->checkIdNull($companyId));
    }

    #----------- bang gia ------------
    public function productTypePrice()
    {
        return $this->hasMany('App\Models\Ad3d\ProductTypePrice\QcProductTypePrice', 'company_id', 'company_id');
    }

    # danh sanh bang gia dang dung cua cty
    public function productTypePriceInfoActivity($companyId = null)
    {
        $modelProductTypePrice = new QcProductTypePrice();
        return $modelProductTypePrice->selectInfoActivityOfCompany($this->checkIdNull($companyId))->get();
    }

    #----------- đơn hàng ------------
    public function order()
    {
        return $this->hasMany('App\Models\Ad3d\Order\QcOrder', 'company_id', 'company_id');
    }

    # danh sach don hang chua ket thu
    public function orderInfoNotFinish($companyId = null)
    {
        $modelOrder = new QcOrder();
        return $modelOrder->getInfoNotFinishOfCompany($companyId);
    }

    #----------- nhập kho ------------
    public function import()
    {
        return $this->hasMany('App\Models\Ad3d\Import\QcImport', 'company_id', 'company_id');
    }

    public function totalImportNotConfirmOfCompany($companyId = null)
    {
        $modelImport = new QcImport();
        return $modelImport->totalImportNotConfirmOfCompany($this->checkIdNull($companyId));
    }

    #----------- kho ------------
    public function companyStore()
    {
        return $this->hasMany('App\Models\Ad3d\CompanyStore\QcCompanyStore', 'company_id', 'company_id');
    }

    # tong loai dung cu cua 1 cty
    public function totalTool($companyId, $toolId)
    {
        $modelCompanyStore = new QcCompanyStore();
        return $modelCompanyStore->totalToolOfCompany($companyId, $toolId);
    }

    # tong so luong dung cu cua cty da dang giao
    public function totalToolAllocationActivity($companyId, $toolId)
    {
        $hFunction = new \Hfunction();
        $modelToolAllocationDetail = new QcToolAllocationDetail();
        return $hFunction->getCount($modelToolAllocationDetail->infoActivityOfToolAndCompany($toolId, $companyId));
    }
    #============ =========== ============ GET INFO ============= =========== ==========
    # danh sach bang cham cong cua cty
    public function getWorkActivityInfo($companyId)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelWork = new QcWork();
        return $modelWork->infoActivityOfListCompanyStaffWork($modelCompanyStaffWork->listIdOfCompany($companyId));
    }

    # thong tin cty me
    public function infoRootActivity()
    {
        return QcCompany::where('companyType', $this->getDefaultParentCompanyType())->where('action', $this->getDefaultHasAction())->get();
    }

    public function getRootActivityCompanyId()
    {
        return QcCompany::where('companyType', $this->getDefaultParentCompanyType())->where('action', $this->getDefaultHasAction())->pluck('company_id');
    }

    # lay hotline cua bo phan thi cong cua cong ty
    public function hotlineConstructionDepartment($companyId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        #lay thong tin lam viec cua bo phan thi cong cap quan ly
        $listStaffId = $modelCompanyStaffWork->listStaffIdActivityOfCompanyIdAndListDepartmentId($companyId, [$modelDepartment->constructionDepartmentId()], $modelRank->manageRankId());
        if ($hFunction->checkCount($listStaffId)) {
            return $modelStaff->phone($listStaffId[0]);
        } else {
            return null;
        }
    }

    public function hotlineInfoOfConstructionDepartment($companyId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        #lay thong tin lam viec cua bo phan thi cong cap quan ly
        $listStaffId = $modelCompanyStaffWork->listStaffIdActivityOfCompanyIdAndListDepartmentId($companyId, [$modelDepartment->constructionDepartmentId()], $modelRank->manageRankId());
        if ($hFunction->checkCount($listStaffId)) {
            return $modelStaff->getInfo($listStaffId[0]);
        } else {
            return null;
        }
    }

    public function infoActivity()
    {
        return QcCompany::where('action', $this->getDefaultHasAction())->get();
    }

    public function infoByListId($listId)
    {
        return QcCompany::whereIn('company_id', $listId)->get();
    }

    # chon thong tin cong ty cung he thong
    public function selectInfoSameSystemOfCompany($companyId)
    {
        return QcCompany::where('parent_id', $companyId)->orWhere('company_id', $companyId)->select();
    }

    # lay he thong cty lien quan cua 1 cty dang nhap
    public function getInfoSameSystemOfCompany($companyId)
    {
        return QcCompany::where('parent_id', $companyId)->orWhere('company_id', $companyId)->get();
    }

    # thong tin cua 1 cong ty hoac tat ca cong ty
    public function getInfo($companyId = '', $field = '')
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($companyId)) {
            return QcCompany::get();
        } else {
            $result = QcCompany::where('company_id', $companyId)->first();
            if ($hFunction->checkEmpty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }

    public function selectInfo($companyId)
    {
        return QcCompany::where('company_id', $companyId)->select('*');
    }

    # create option
    public function getOption($selected = '')
    {
        $hFunction = new \Hfunction();
        $result = QcCompany::select('company_id as optionKey', 'name as optionValue')->get()->toArray();
        return $hFunction->option($result, $selected);
    }

    public function pluck($column, $objectId = null)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($objectId)) {
            return $this->$column;
        } else {
            return QcCompany::where('company_id', $objectId)->pluck($column);
        }
    }

    #-----------  INFO -------------
    public function listIdActivity()
    {
        return QcCompany::where('action', $this->getDefaultHasAction())->pluck('company_id');
    }

    public function companyId()
    {
        return $this->company_id;
    }

    public function name($companyId = null)
    {
        return $this->pluck('name', $companyId);
    }

    public function nameCode($companyId = null)
    {
        return $this->pluck('nameCode', $companyId);
    }

    public function companyCode($companyId = null)
    {
        return $this->pluck('companyCode', $companyId);
    }

    public function address($companyId = null)
    {
        return $this->pluck('address', $companyId);
    }

    public function phone($companyId = null)
    {
        return $this->pluck('phone', $companyId);
    }

    public function email($companyId = null)
    {
        return $this->pluck('email', $companyId);
    }

    public function website($companyId = null)
    {
        return $this->pluck('website', $companyId);
    }

    public function logo($companyId = null)
    {
        return $this->pluck('logo', $companyId);
    }

    public function companyType($companyId = null)
    {
        return $this->pluck('companyType', $companyId);
    }


    public function createdAt($companyId = null)
    {
        return $this->pluck('created_at', $companyId);
    }

    # total record
    public function totalRecords()
    {
        return QcCompany::count();
    }

    //lay hinh anh
    public function pathSmallImage($image)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($image)) {
            return null;
        } else {
            return asset($this->rootPathSmallImage() . '/' . $image);
        }
    }

    public function pathFullImage($image)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($image)) {
            return null;
        } else {
            return asset($this->rootPathFullImage() . '/' . $image);
        }
    }

    # lay ma cty ty me (Tru so)
    public function rootCompanyId()
    {
        return QcCompany::where('companyType', $this->getDefaultParentCompanyType())->pluck('company_id');
    }

    #============ =========== ============ KIEM TRA THONG TIN ============= =========== ==========
    # kiem tra thong bao nhan vien kiem tra thong tin truoc khi xuat bang luong - cuoi thang
    public function checkNotifyForEndOfMonth()
    {
        $hFunction = new \Hfunction();
        $currentDay = $hFunction->currentDay();
        $totalDayInMonth = $hFunction->totalDayInMonth($hFunction->currentMonth(), $hFunction->currentYear());
        return (($totalDayInMonth - $currentDay) <= 3) ? true : false;
    }

    # kiem tra cty co phai cong ty me hay khong
    public function checkRoot($companyId = null)
    {
        return ($this->companyType($companyId) == $this->getDefaultParentCompanyType()) ? true : false;
    }

    # kiem tra co phai la cty con
    public function checkBranch($companyId = null)
    {
        return ($this->companyType($companyId) == $this->getDefaultBranchCompanyType()) ? true : false;
    }

    # ten cong ty da ton tai
    public function existName($name)
    {
        return QcCompany::where('name', $name)->exists();
    }

    # ma cty
    public function existCompanyCode($departmentCode)
    {
        return QcCompany::where('companyCode', $departmentCode)->exists();
    }

    public function existEditName($companyId, $name)
    {
        return QcCompany::where('name', $name)->where('company_id', '<>', $companyId)->exists();
    }

    public function existEditCompanyCode($companyId, $companyCode)
    {
        return QcCompany::where('companyCode', $companyCode)->where('company_id', '<>', $companyId)->exists();
    }

    public function existEditNameCode($companyId, $nameCode)
    {
        return QcCompany::where('nameCode', $nameCode)->where('company_id', '<>', $companyId)->exists();
    }

    public function existEditCode($companyId, $code)
    {
        return QcCompany::where('companyCode', $code)->where('company_id', '<>', $companyId)->exists();
    }


    #============ =========== ============ BANG TIN HE THONG ============= =========== ==========
    # Thong tin cham cong trong ngay
    public function timekeepingProvisionalOfCompanyAndDate($companyId, $dateFilter)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelWork = new QcWork();
        $modelTimekeepingProvisional = new QcTimekeepingProvisional();
        $listWorkId = $modelWork->listIdOfListCompanyStaffWork($modelCompanyStaffWork->listIdOfListCompanyAndListStaff([$companyId]));
        return $modelTimekeepingProvisional->selectInfoByListWorkAndDate($listWorkId, $dateFilter)->get();
    }
    # ---------- ------------ THONG KE THONG TIN CUA 1 CONG TY ---------- -------- -------
    # thong tin cham cong chua duyet cua thang hien hanh
    public function totalTimekeepingProvisionalUnconfirmed($companyId = null)
    {
        $modelTimekeepingProvisional = new QcTimekeepingProvisional();
        return $modelTimekeepingProvisional->totalInfoUnconfirmed($this->checkIdNull($companyId));
    }

    # thong tin xin nghi chua duoc duyet
    public function totalLicenseOffWorkUnconfirmed($companyId = null)
    {
        $modelLicenseOffWork = new QcLicenseOffWork();
        return $modelLicenseOffWork->totalNewInfo($this->checkIdNull($companyId));
    }

    # thong tin xin tre chua duoc duyet
    public function totalLicenseLateWorkUnconfirmed($companyId = null)
    {
        $modelLicenseLateWork = new QcLicenseLateWork();
        return $modelLicenseLateWork->totalNewInfo($this->checkIdNull($companyId));
    }

    public function totalSalaryBeforePayRequestUnconfirmed($companyId = null)
    {
        $modelRequest = new QcSalaryBeforePayRequest();
        return $modelRequest->totalNewRequest($this->checkIdNull($companyId));
    }
    #============ =========== ============ THONG KE TIEN CUA 1 NHAN VIEN ============= =========== ==========
    # thong ke tong tien dang giua cua 1 thu quy trong cty
    public function totalKeepMoneyOfTreasurerStaff($staffId, $dateFilter = null)
    {
        $totalReceiveMoney = 0;
        $totalPaymentMoney = 0;
        //-------- Nhan
        # tien nhan tu bo phan kinh doanh thu cua don hang
        $totalReceiveMoney = $totalReceiveMoney + $this->totalReceiveMoneyOfTreasurerFromBusiness($staffId, $dateFilter);
        # nhan tien dau tu - xac nhan
        $totalReceiveMoney = $totalReceiveMoney + $this->totalReceiveMoneyOfTreasurerFromTreasurerManage($staffId, $dateFilter);
        //------- Chi
        # tong tien thanh toan vat tu da xac nhan
        $totalPaymentMoney = $totalPaymentMoney + $this->totalMoneyConfirmedImportOfTreasurer($staffId, $dateFilter);
        # tong tien chi hoat dong da xac nhan
        $totalPaymentMoney = $totalPaymentMoney + $this->totalMoneyConfirmedPayActivityOfTreasurer($staffId, $dateFilter);
        # tong tien thanh toan luong da xac nhan
        $totalPaymentMoney = $totalPaymentMoney + $this->totalMoneyConfirmedSalaryPayOfTreasurer($staffId, $dateFilter);
        # tong tien ung luong da xac nhan
        $totalPaymentMoney = $totalPaymentMoney + $this->totalMoneyConfirmedSalaryBeforePayOfTreasurer($staffId, $dateFilter);
        # tong tien tra lai khi huy don hang
        $totalPaymentMoney = $totalPaymentMoney + $this->totalMoneyPaidOrderCancelOfTreasurer($staffId, $dateFilter);
        # tong tien da nop cho cong ty
        $totalPaymentMoney = $totalPaymentMoney + $this->totalMoneyConfirmedForTreasureManageOfTreasurer($staffId, $dateFilter);
        return $totalReceiveMoney - $totalPaymentMoney;
    }

    # thong ke tien da nhan tu bo phan kinh doanh giao cua 1 thu quy trong cty
    public function totalReceiveMoneyOfTreasurerFromBusiness($staffId, $dateFilter = null)
    {
        $modelTransfer = new QcTransfers();
        return $modelTransfer->totalMoneyReceivedFromOrderPayOfStaffAndDate($staffId, $dateFilter);
    }

    # thong ke tien dau tu da nhan tu thu quy cap quan ly, cua 1 thu quy  trong cty
    public function totalReceiveMoneyOfTreasurerFromTreasurerManage($staffId, $dateFilter = null)
    {
        $modelTransfer = new QcTransfers();
        return $modelTransfer->totalMoneyReceivedFromInvestmentOfStaffAndDate($staffId, $dateFilter);
    }

    # thong ke tien thanh toan mua vat tu da duoc xac nhan, cua 1 thu quy  trong cty
    public function  totalMoneyConfirmedImportOfTreasurer($staffId, $dateFilter = null)
    {
        $modelImportPay = new QcImportPay();
        return $modelImportPay->totalMoneyConfirmedOfPayStaffAndDate($staffId, $dateFilter);
    }

    # thong ke tien chi hoat dong da duoc xac nhan, cua 1 thu quy trong cty
    public function totalMoneyConfirmedPayActivityOfTreasurer($staffId, $dateFilter = null)
    {
        $modelPayActivityDetail = new QcPayActivityDetail();
        return $modelPayActivityDetail->totalMoneyConfirmedAndInvalidOfStaffAndDate($staffId, $dateFilter);
    }

    # thong ke tien thanh toan luong da duoc xac nhan, cua 1 thu quy trong cty
    public function totalMoneyConfirmedSalaryPayOfTreasurer($staffId, $dateFilter = null)
    {
        $modelSalaryPay = new QcSalaryPay();
        return $modelSalaryPay->totalMoneyConfirmedOfStaffAndDate($staffId, $dateFilter);
    }

    # thong ke tien cho ung luong da duoc xac nhan, cua 1 thu quy trong cty
    public function totalMoneyConfirmedSalaryBeforePayOfTreasurer($staffId, $dateFilter = null)
    {
        $modelSalaryBeforePay = new QcSalaryBeforePay();
        return $modelSalaryBeforePay->totalMoneyConfirmedOfStaffAndDate($staffId, $dateFilter);
    }

    # thong ke tien tra lai khi huy don hang
    public function totalMoneyPaidOrderCancelOfTreasurer($staffId, $dateFilter = null)
    {
        $modelOrderCancel = new QcOrderCancel();
        return $modelOrderCancel->totalPaymentOfStaffAndDate($staffId, $dateFilter);
    }

    # thong ke tien chuyen cho thu quy cap quan ly - nop cong ty
    public function totalMoneyConfirmedForTreasureManageOfTreasurer($staffId, $dateFilter = null)
    {
        $modelTransfers = new QcTransfers();
        return $modelTransfers->totalMoneyConfirmedTransfersForTreasurerManage($staffId, $dateFilter);
    }
    #========= ============== =========== THONG KE CUA 1 CONG TY ==================  ========

    # tong tien doanh so
    public function statisticalTotalMoneyOrder($companyId, $dateFilter = null)
    {
        $modelOrder = new QcOrder();
        $modelProduct = new QcProduct();
        $listOrderId = $modelOrder->listIdOfListCompany([$companyId], $dateFilter);
        return $modelProduct->totalPriceOfListOrder($listOrderId);
    }

    # tong tien da thu cua cong ty theo thoi gian
    public function statisticalTotalCollectMoney($companyId, $dateFilter = null)
    {
        # thu tien tu đơn hàng
        $statisticalTotalCollectMoneyFromOrderPay = $this->statisticalTotalCollectMoneyFromOrderPay($companyId, $dateFilter);
        # thu tu cong ty me
        $statisticalTotalCollectMoneyFromInvestment = $this->statisticalTotalCollectMoneyFromInvestment($companyId, $dateFilter);
        return $statisticalTotalCollectMoneyFromOrderPay + $statisticalTotalCollectMoneyFromInvestment;
    }

    # tong tien thu tu don hang cua cty
    public function statisticalTotalCollectMoneyFromOrderPay($companyId, $dateFilter = null)
    {
        $modelTransfer = new QcTransfers();
        return $modelTransfer->totalMoneyReceivedFromOrderPayOfCompanyAndDate($companyId, $dateFilter);
    }

    # tong tien thu tu dau tu cua cty
    public function statisticalTotalCollectMoneyFromInvestment($companyId, $dateFilter = null)
    {
        $modelTransfer = new QcTransfers();
        return $modelTransfer->totalMoneyReceivedFromInvestmentOfCompanyAndDate($companyId, $dateFilter);
    }


    # tong tien da thanh toan cua cong ty theo thoi gian
    public function statisticalTotalPaymentMoney($companyId, $dateFilter = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelImportPay = new QcImportPay();
        $modelPayActivityDetail = new QcPayActivityDetail();
        $modelSalaryPay = new QcSalaryPay();
        $modelSalaryBeforePay = new QcSalaryBeforePay();
        # danh sach  NV thuoc cty
        $listStaffId = $modelCompanyStaffWork->listStaffIdOfListCompanyId([$companyId]);

        # thanh toan mua vat tu - nguoi mua da xac nhan
        $totalMoneyOfPayListStaffAndDate = $modelImportPay->totalMoneyConfirmedOfPayListStaffAndDate($listStaffId, $dateFilter);

        # chi hoat dong
        $totalMoneyPayActivityDetailOfStaff = $modelPayActivityDetail->totalMoneyOfListStaffAndDate($listStaffId, $dateFilter);

        # thanh toan luong - da xac nhan
        $totalMoneySalaryPayOfStaff = $modelSalaryPay->totalMoneyConfirmedOfListStaffAndDate($listStaffId, $dateFilter);

        # chi ung luong luong - dã xac nhan
        $totalMoneySalaryBeforePayOfStaff = $modelSalaryBeforePay->totalMoneyConfirmedOfListStaffAndDate($listStaffId, $dateFilter);

        return $totalMoneyOfPayListStaffAndDate + $totalMoneyPayActivityDetailOfStaff + $totalMoneySalaryPayOfStaff + $totalMoneySalaryBeforePayOfStaff;
    }

    # tong tien da thu don hang cua cong ty
    public function statisticalTotalMoneyOrderPaid($companyId, $dateFilter = null)
    {
        /*$modelOrderPay = new QcOrderPay();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $listStaffId = $modelCompanyStaffWork->listStaffIdOfListCompanyId([$companyId]);
        return $modelOrderPay->totalMoneyOfListStaffAndDate($listStaffId,$dateFilter);*/
    }

    #============ =========== ============ QUAN LY THU - CHI ============= =========== ==========
    //tong thu theo dơn hang
    public function totalOrderPayOfCompany($listCompanyId, $dateFilter = null)
    {
        $modelOrderPay = new QcOrderPay();
        return $modelOrderPay->totalOrderPayOfCompany($listCompanyId, $dateFilter);
    }

    // tong thu theo don hang cua nhan vien
    public function totalOrderPayOfCompanyStaffDate($listCompanyId, $staffId, $dateFilter = null)
    {
        $modelOrderPay = new QcOrderPay();
        return $modelOrderPay->totalOrderPayOfCompanyStaffDate($listCompanyId, $staffId, $dateFilter);
    }


}
