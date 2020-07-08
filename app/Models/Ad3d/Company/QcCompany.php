<?php

namespace App\Models\Ad3d\Company;

use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\CompanyStore\QcCompanyStore;
use App\Models\Ad3d\Department\QcDepartment;
use App\Models\Ad3d\Import\QcImport;
use App\Models\Ad3d\ImportPay\QcImportPay;
use App\Models\Ad3d\Order\QcOrder;
use App\Models\Ad3d\OrderPay\QcOrderPay;
use App\Models\Ad3d\PayActivityDetail\QcPayActivityDetail;
use App\Models\Ad3d\Payment\QcPayment;
use App\Models\Ad3d\Product\QcProduct;
use App\Models\Ad3d\ProductTypePrice\QcProductTypePrice;
use App\Models\Ad3d\Rank\QcRank;
use App\Models\Ad3d\SalaryBeforePay\QcSalaryBeforePay;
use App\Models\Ad3d\SalaryPay\QcSalaryPay;
use App\Models\Ad3d\Staff\QcStaff;
use App\Models\Ad3d\StaffWorkDepartment\QcStaffWorkDepartment;
use App\Models\Ad3d\SystemDateOff\QcSystemDateOff;
use App\Models\Ad3d\TimekeepingProvisional\QcTimekeepingProvisional;
use App\Models\Ad3d\ToolAllocationDetail\QcToolAllocationDetail;
use App\Models\Ad3d\Transfers\QcTransfers;
use App\Models\Ad3d\Work\QcWork;
use Illuminate\Database\Eloquent\Model;

class QcCompany extends Model
{
    protected $table = 'qc_companies';
    protected $fillable = ['company_id', 'root_id', 'companyCode', 'name', 'nameCode', 'address', 'phone', 'email', 'website', 'logo', 'companyType', 'action', 'created_at'];
    protected $primaryKey = 'company_id';
    public $timestamps = false;

    private $lastId;

    #========== ========== ========== THEM && CAP NHAT ========== ========== ==========
    #---------- thêm ----------
    public function insert($companyCode, $name, $nameCode, $address, $phone, $email, $website, $companyType = 1, $logo = null)
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

    public function systemDateOfFOfCompanyAndDate($companyId, $date = null)
    {
        $modelSystemDateOff = new QcSystemDateOff();
        return $modelSystemDateOff->selectInfoOfCompanyAndDate($companyId, $date)->get();
    }

    # ngay bat buoc nghi
    public function systemDateOffObligatoryOfCompanyAndDate($companyId, $date = null)
    {
        $modelSystemDateOff = new QcSystemDateOff();
        return $modelSystemDateOff->infoDateObligatoryOfCompanyAndDate($companyId, $date);
    }

    # ngay khong bat buoc nghi
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

    #----------- nhân viên cũ ------------ xoa
    public function staff()
    {
        return $this->hasMany('App\Models\Ad3d\Staff\QcStaff', 'company_id', 'company_id');
    }

    public function staffOfCompany($companyId = null)
    {
        $modelStaff = new QcStaff();
        return $modelStaff->infoOfCompany((empty($companyId) ? $this->companyId() : $companyId));
    }

    public function staffIdOfListCompanyId($listCompanyId)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        return $modelCompanyStaffWork->listStaffIdOfListCompanyId($listCompanyId);
    }

    public function staffInfoActivityOfListCompanyId($listCompanyId)
    {
        $modelStaff = new QcStaff();
        return $modelStaff->getInfoActivityByListStaffId($this->staffIdOfListCompanyId($listCompanyId));
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
        return QcCompany::where('companyType', 0)->where('action', 1)->get();
    }

    public function getRootActivityCompanyId()
    {
        return QcCompany::where('companyType', 0)->where('action', 1)->pluck('company_id');
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
        return QcCompany::where('action', 1)->get();
    }

    public function infoByListId($listId)
    {
        return QcCompany::whereIn('company_id', $listId)->get();
    }

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

    #----------- DEPARTMENT INFO -------------
    public function listIdActivity()
    {
        return QcCompany::where('action', 1)->pluck('company_id');
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
        return QcCompany::where('companyType', 0)->pluck('company_id');
    }

    #============ =========== ============ KIEM TRA THONG TIN ============= =========== ==========
    public function checkRoot($companyId = null)
    {
        $hFunction = new \Hfunction();
        return ($hFunction->checkEmpty($this->rootId($companyId))) ? true : false;
    }

    # ten cong ty da ton tai
    public function existName($name)
    {
        $result = QcCompany::where('name', $name)->count();
        return ($result > 0) ? true : false;
    }

    # ma cty
    public function existCompanyCode($departmentCode)
    {
        $result = QcCompany::where('companyCode', $departmentCode)->count();
        return ($result > 0) ? true : false;
    }

    public function existEditName($companyId, $name)
    {
        $result = QcCompany::where('name', $name)->where('company_id', '<>', $companyId)->count();
        return ($result > 0) ? true : false;
    }

    public function existEditCompanyCode($companyId, $companyCode)
    {
        $result = QcCompany::where('companyCode', $companyCode)->where('company_id', '<>', $companyId)->count();
        return ($result > 0) ? true : false;
    }

    public function existEditNameCode($companyId, $nameCode)
    {
        $result = QcCompany::where('nameCode', $nameCode)->where('company_id', '<>', $companyId)->count();
        return ($result > 0) ? true : false;
    }

    public function existEditCode($companyId, $code)
    {
        $result = QcCompany::where('companyCode', $code)->where('company_id', '<>', $companyId)->count();
        return ($result > 0) ? true : false;
    }

    public function checkBranch($companyId = null)
    {
        return ($this->companyType($companyId) == 1) ? true : false;
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
    #============ =========== ============ THONG KE DOANH THU ============= =========== ==========
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
