<?php

namespace App\Models\Ad3d\Staff;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\Department\QcDepartment;
use App\Models\Ad3d\Import\QcImport;
use App\Models\Ad3d\LicenseLateWork\QcLicenseLateWork;
use App\Models\Ad3d\LicenseOffWork\QcLicenseOffWork;
use App\Models\Ad3d\Order\QcOrder;
use App\Models\Ad3d\OrderAllocation\QcOrderAllocation;
use App\Models\Ad3d\OrderPay\QcOrderPay;
use App\Models\Ad3d\PayActivityDetail\QcPayActivityDetail;
use App\Models\Ad3d\Payment\QcPayment;
use App\Models\Ad3d\PaymentType\QcPaymentType;
use App\Models\Ad3d\Product\QcProduct;
use App\Models\Ad3d\Salary\QcSalary;
use App\Models\Ad3d\SalaryBeforePay\QcSalaryBeforePay;
use App\Models\Ad3d\SalaryBeforePayRequest\QcSalaryBeforePayRequest;
use App\Models\Ad3d\SalaryPay\QcSalaryPay;
use App\Models\Ad3d\StaffKpi\QcStaffKpi;
use App\Models\Ad3d\StaffSalaryBasic\QcStaffSalaryBasic;
use App\Models\Ad3d\StaffWorkDepartment\QcStaffWorkDepartment;
use App\Models\Ad3d\StaffWorkMethod\QcStaffWorkMethod;
use App\Models\Ad3d\TimekeepingProvisional\QcTimekeepingProvisional;
use App\Models\Ad3d\ToolAllocation\QcToolAllocation;
use App\Models\Ad3d\Transfers\QcTransfers;
use App\Models\Ad3d\Work\QcWork;
use App\Models\Ad3d\WorkAllocation\QcWorkAllocation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use DB;

class QcStaff extends Model
{
    protected $table = 'qc_staffs';
    protected $fillable = ['staff_id', 'nameCode', 'firstName', 'lastName', 'identityCard', 'account', 'password', 'birthday', 'gender', 'image', 'identityCardFront', 'identityCardBack', 'email', 'address'
        , 'phone', 'workStatus', 'rootStatus', 'confirmStatus', 'bankAccount', 'bankName', 'created_at'];
    protected $primaryKey = 'staff_id';
    public $timestamps = false;

    private $lastId;

    function __construct()
    {
        $this->checkAutoInfo();
    }
    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    //---------- Insert ----------
    //tạo mật khẩu cho người dùng
    public function createStaffPass($password, $nameCode)
    {
        return md5($nameCode . '3DQC') . md5('3DQC' . $password . $nameCode);
    }

    //lấy lại mật khẫu mặc định
    public function resetPass($staffId)
    {
        $dataStaff = $this->getInfo($staffId);
        if (count($dataStaff) > 0) {
            $identityCard = $this->identityCard($staffId);
            $newPass = $this->createStaffPass("3d$identityCard", $this->nameCode($staffId));
            return QcStaff::where('staff_id', $staffId)->update(['account' => $identityCard, 'password' => $newPass]);
        }
    }

    // thêm mới
    public function insert($firstName, $lastName, $identityCard, $account, $birthday = null, $gender, $image = null, $identityCardFront, $identityCardBack, $email, $address = null, $phone = null, $level, $bankAccount = null, $bankName = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        //create code
        $nameCode = $hFunction->getTimeCode();
        if ($level == 0) { // root staff of system
            $pass = '3dtfquangcao';
            $newPass = $this->createStaffPass($pass, $nameCode);
        } else {
            $newPass = "3d$identityCard";
            $newPass = $this->createStaffPass($newPass, $nameCode);
        }

        // insert
        $modelStaff->nameCode = $nameCode;
        $modelStaff->firstName = $hFunction->convertValidHTML($firstName);
        $modelStaff->lastName = $hFunction->convertValidHTML($lastName);
        $modelStaff->identityCard = $identityCard;
        $modelStaff->account = $account;
        $modelStaff->password = $newPass;
        $modelStaff->birthday = $birthday;
        $modelStaff->gender = $gender;
        $modelStaff->image = $image;
        $modelStaff->identityCardFront = $identityCardFront;
        $modelStaff->identityCardBack = $identityCardBack;
        $modelStaff->email = $email;
        $modelStaff->address = $hFunction->convertValidHTML($address);
        $modelStaff->phone = $phone;
        $modelStaff->bankAccount = $bankAccount;
        $modelStaff->bankName = $bankName;
        $modelStaff->workStatus = 1;
        $modelStaff->rootStatus = 0;
        $modelStaff->created_at = $hFunction->createdAt();
        if ($modelStaff->save()) {
            $this->lastId = $modelStaff->staff_id;
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

    public function checkIdNull($staffId)
    {
        return (empty($staffId)) ? $this->staffId() : $staffId;
    }

    public function updateAccount($staffId, $newAccount)
    {
        return QcStaff::where('staff_id', $staffId)->update(['account' => $newAccount, 'confirmStatus' => 1]);
    }

    public function updateBankAccount($staffId, $bankAccount, $bankName)
    {
        return QcStaff::where('staff_id', $staffId)->update(['bankAccount' => $bankAccount, 'bankName' => $bankName]);
    }

    // cap nhat thong tin co ban
    public function updateInfo($staffId, $firstName, $lastName, $identityCard, $birthday = null, $gender, $email, $address = null, $phone = null)
    {
        $hFunction = new \Hfunction();
        return QcStaff::where('staff_id', $staffId)->update([
            'firstName' => $hFunction->convertValidHTML($firstName),
            'lastName' => $hFunction->convertValidHTML($lastName),
            'identityCard' => $identityCard,
            'birthday' => $birthday,
            'gender' => $gender,
            'email' => $email,
            'address' => $hFunction->convertValidHTML($address),
            'phone' => $phone,
        ]);
    }

    public function changeAccountAndPassword($staffId, $newAccount, $newPass)
    {
        $nameCode = $this->nameCode($staffId);
        $createPass = $this->createStaffPass($newPass, $nameCode);
        return QcStaff::where('staff_id', $staffId)->update(['account' => $newAccount, 'password' => $createPass, 'confirmStatus' => 1]);
    }

    public function checkPassOfStaff($staffId, $password)
    {
        return $this->login($this->account($staffId), $password);
    }

    // delete
    public function actionDelete($staffId = null)
    {
        if (empty($staffId)) $staffId = $this->staffId();
        return QcStaff::where('staff_id', $staffId)->update(['workStatus' => 0]);
    }

    // up hinh anh
    public function rootPathFullImage()
    {
        return 'public/images/staff/full';
    }

    public function rootPathSmallImage()
    {
        return 'public/images/staff/small';
    }

    //upload image
    public function uploadImage($file, $imageName, $size = 500)
    {
        $hFunction = new \Hfunction();
        $pathSmallImage = $this->rootPathSmallImage();
        $pathFullImage = $this->rootPathFullImage();
        if (!is_dir($pathFullImage)) mkdir($pathFullImage);
        if (!is_dir($pathSmallImage)) mkdir($pathSmallImage);
        return $hFunction->uploadSaveByFileName($file, $imageName, $pathSmallImage . '/', $pathFullImage . '/', $size);
    }

    //drop image
    public function dropImage($imageName)
    {
        if (is_file($this->rootPathSmallImage() . '/' . $imageName)) unlink($this->rootPathSmallImage() . '/' . $imageName);
        if (is_file($this->rootPathFullImage() . '/' . $imageName)) unlink($this->rootPathFullImage() . '/' . $imageName);
    }

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

    public function updateImage($staffId, $image)
    {
        return QcStaff::where('staff_id', $staffId)->update(['image' => $image]);
    }

    public function updateIdentityCardFront($staffId, $image)
    {
        return QcStaff::where('staff_id', $staffId)->update(['identityCardFront' => $image]);
    }

    public function updateIdentityCardBack($staffId, $image)
    {
        return QcStaff::where('staff_id', $staffId)->update(['identityCardBack' => $image]);
    }
    //========== ========= ========= mối quan hệ ========== ========= ==========

    #----------- ngay nghi he thong ------------
    public function systemDateOff()
    {
        return $this->hasMany('App\Models\Ad3d\SystemDateOff\QcSystemDateOff', 'staff_id', 'staff_id');
    }

    #----------- Chi tiet cty cua nv lam viec ------------
    public function companyStaffWork()
    {
        return $this->hasMany('App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork', 'staff_id', 'staff_id');
    }

    public function companyStaffWorkInfoActivity($staffId = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        return $modelCompanyStaffWork->infoActivityOfStaff($this->checkIdNull($staffId));
    }

    public function level($staffId = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $staffId = $this->checkIdNull($staffId);
        $dataCompanyStaffWork = $modelCompanyStaffWork->infoActivityOfStaff($staffId);
        if (count($dataCompanyStaffWork) > 0) { # du lieu phien ban moi
            return $dataCompanyStaffWork->level();
        } else { # du lieu phien ban cu
            return $this->pluck('level', $staffId);
        }
    }

    public function companyId($staffId = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $staffId = $this->checkIdNull($staffId);
        $dataCompanyStaffWork = $modelCompanyStaffWork->infoActivityOfStaff($staffId);
        if (count($dataCompanyStaffWork) > 0) {
            return $dataCompanyStaffWork->companyId();
        } else { # du lieu cu
            return $this->pluck('company_id', $staffId);
        }
    }

    public function companyInfoActivity($staffId = null)
    {
        $modelCompany = new QcCompany();
        return $modelCompany->getInfo($this->companyId($staffId));
    }

    #thong tin luong
    public function staffWorkSalaryActivityOfStaff($staffId = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        return $modelCompanyStaffWork->staffWorkSalaryActivityOfStaff($this->checkIdNull($staffId));
    }

    #----------- Chi tiet nv quan ly them nhan su ------------
    public function companyStaffWorkAdd()
    {
        return $this->hasMany('App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork', 'staffAdd_id', 'staff_id');
    }

    #----------- Chi hoat dong - PHIEN BAN CU ------------
    public function payment()
    {
        return $this->hasMany('App\Models\Ad3d\Payment\QcPayment', 'staff_id', 'staff_id');
    }

    #----------- Chi hoat dong ------------
    public function payActivityDetail()
    {
        return $this->hasMany('App\Models\Ad3d\PayActivityDetail\QcPayActivityDetail', 'staff_id', 'staff_id');
    }

    public function payActivityDetailInfoOfStaff($staffId = null, $confirmStatus = 3, $date = null)
    {
        $modelPayActivityDetail = new QcPayActivityDetail();
        return $modelPayActivityDetail->infoOfStaff($this->checkIdNull($staffId), $date, $confirmStatus);
    }

    public function payActivityDetailConfirmedOfReceiveStaff($staffId, $date=null)
    {
        $modelPayActivityDetail = new QcPayActivityDetail();
        return $modelPayActivityDetail->infoConfirmAndInvalidOfStaffAndDate($staffId, $date);
    }

    #----------- lương cơ bản ------------
    public function staffSalaryBasic()
    {
        return $this->hasMany('App\Models\Ad3d\StaffSalaryBasic\QcStaffSalaryBasic', 'staff_id', 'staff_id');
    }

    public function salaryBasicOfStaff($staffId = null, $date = null)
    {
        $modelStaffSalaryBasic = new QcStaffSalaryBasic();
        return $modelStaffSalaryBasic->salaryOfStaff($this->checkIdNull($staffId), $date);
    }

    public function infoActivityOfStaff($staffId = null)
    {
        $modelStaffSalaryBasic = new QcStaffSalaryBasic();
        return $modelStaffSalaryBasic->infoActivityOfStaff($this->checkIdNull($staffId));
    }

    #----------- thanh toán lương ------------
    public function salaryPay()
    {
        return $this->hasMany('App\Models\Ad3d\SalaryPay\QcSalaryPay', 'staff_id', 'staff_id');
    }

    public function salaryPayInfoConfirmedOfStaff($staffId, $date = null)
    {
        $modelSalaryPay = new QcSalaryPay();
        return $modelSalaryPay->infoConfirmedOfStaffAndDate($staffId, $date);
    }

    #----------- ứng lương ------------
    public function salaryBeforePay()
    {
        return $this->hasMany('App\Models\Ad3d\SalaryBeforePay\QcSalaryBeforePay', 'staff_id', 'staffPay_id');
    }

    public function salaryBeforePayInfo($staffId, $date = null)
    {
        $modelSalaryBeforePay = new QcSalaryBeforePay();
        return $modelSalaryBeforePay->infoOfStaffAndDate($staffId, $date);
    }

    //----------- phạt ------------
    public function minusMoney()
    {
        return $this->hasMany('App\Models\Ad3d\MinusMoney\QcMinusMoney', 'staff_id', 'staff_id');
    }

    #----------- Bo phan lam viec  ------------
    public function departmentActivityOfStaff($staffId = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        return $modelCompanyStaffWork->listIdDepartmentActivityOfStaff($this->checkIdNull($staffId));
    }

    //----------- Phan cong trinh -----------
    public function orderAllocationReceiveStaff()
    {
        return $this->hasMany('App\Models\Ad3d\OrderAllocation\QcOrderAllocation', 'receiveStaff_id', 'staff_id');
    }

    public function orderAllocationInfoOfReceiveStaff($staffId = null, $date = null)
    {
        $modelOrdersAllocation = new QcOrderAllocation();
        return $modelOrdersAllocation->infoOfReceiveStaff($this->checkIdNull($staffId), $date);
    }

    public function orderAllocationAllocationStaff()
    {
        return $this->hasMany('App\Models\Ad3d\OrderAllocation\QcOrderAllocation', 'allocationStaff_id', 'staff_id');
    }

    public function orderAllocationConfirmStaff()
    {
        return $this->hasMany('App\Models\Ad3d\OrderAllocation\QcOrderAllocation', 'confirmStaff_id', 'staff_id');
    }

    //----------- phân việc ------------
    public function workAllocationReceiveStaff()
    {
        return $this->hasMany('App\Models\Ad3d\WorkAllocation\QcWorkAllocation', 'receiveStaff_id', 'staff_id');
    }

    #lay thong tin phan cong dang nhan
    public function workAllocationActivityOfStaffReceive($staffId = null)
    {
        $modelWorkAllocation = new QcWorkAllocation();
        return $modelWorkAllocation->infoActivityOfStaffReceive($this->checkIdNull($staffId));
    }

    #lay thong tin phan cong da ket thuc
    public function workAllocationFinishOfStaffReceive($staffId = null)
    {
        $modelWorkAllocation = new QcWorkAllocation();
        return $modelWorkAllocation->infoFinishOfStaffReceive($this->checkIdNull($staffId));
    }

    public function getInfoProductOfWorkAllocationOf($staffId = null)
    {
        $modelProduct = new QcProduct();
        $modelWorkAllocation = new QcWorkAllocation();
        $listProductId = $modelWorkAllocation->listProductIdActivityOfReceiveStaff($this->checkIdNull($staffId));
        return $modelProduct->infoFromListId($listProductId);
    }

    public function workAllocationAllocationStaff()
    {
        return $this->hasMany('App\Models\Ad3d\WorkAllocation\QcWorkAllocation', 'allocationStaff_id', 'staff_id');
    }


    #----------- làm việc ------------
    public function work()
    {
        return $this->hasMany('App\Models\Ad3d\Work\QcWork', 'staff_id', 'staff_id');
    }

    public function firstInfoToWork($staffId = null, $date = null)
    {
        $modelWork = new QcWork();
        return $modelWork->firstInfoOfStaff($this->checkIdNull($staffId), $date);
    }

    public function firstInfoActivityToWork($staffId = null, $date = null)
    {
        $modelWork = new QcWork();
        return $modelWork->infoActivityOfStaff($this->checkIdNull($staffId), $date);
    }

    public function firstInfoToWorkOld($staffId = null, $date = null) # du lieu cu
    {
        $modelWork = new QcWork();
        return $modelWork->firstInfoOfStaff($this->checkIdNull($staffId), $date);
    }

    public function workInfoActivityOfStaff($staffId = null, $date = null) # du lieu cu
    {
        $modelWork = new QcWork();
        return $modelWork->infoActivityOfStaff($this->checkIdNull($staffId), $date);
    }

    //---------- bộ phận cty -----------
    public function department()
    {
        //return $this->belongsTo('App\Models\Ad3d\Company\QcCompany', 'company_id', 'company_id');
    }


    //---------- công ty -----------
    /*public function company()
    {
        return $this->belongsTo('App\Models\Ad3d\Company\QcCompany', 'company_id', 'company_id');
    }*/

    public function infoOfCompany($companyId)
    {
        $modelCompanyStaffWord = new QcCompanyStaffWork();
        return QcStaff::whereIn('staff_id', $modelCompanyStaffWord->staffIdOfCompany($companyId))->orderBy('lastName', 'ASC')->get();
    }

    public function infoActivityOfCompany($companyId, $departmentId = null, $level = 1000, $orderByName = 'ASC') # mac dinh 1000 = tat ca level
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $listStaffId = $modelCompanyStaffWork->listStaffIdActivityHasFilter($companyId, $departmentId, $level);
        return QcStaff::whereIn('staff_id', $listStaffId)->where('workStatus', 1)->orderBy('lastName', $orderByName)->get();

    }

    public function listIdOfCompany($companyId)
    {
        $modelCompanyStaffWord = new QcCompanyStaffWork();
        return QcStaff::whereIn('staff_id', $modelCompanyStaffWord->staffIdOfCompany($companyId))->pluck('staff_id');
    }

    public function listIdActivityOfCompany($companyId)
    {
        $modelCompanyStaffWord = new QcCompanyStaffWork();
        return QcStaff::whereIn('staff_id', $modelCompanyStaffWord->staffIdActivityOfCompany($companyId))->pluck('staff_id');
    }

    //danh sách mã Nv lọc theo 1 công ty và tên
    public function listIdOfCompanyAndName($companyId, $name)
    {
        $modelCompanyStaffWord = new QcCompanyStaffWork();
        return QcStaff::whereIn('staff_id', $modelCompanyStaffWord->staffIdOfCompany($companyId))->where('lastName', 'like', "%$name%")->pluck('staff_id');
    }

    //danh sách mã Nv lọc theo công ty
    public function listIdOfListCompany($ListCompanyId)
    {
        $modelCompanyStaffWord = new QcCompanyStaffWork();
        return QcStaff::whereIn('staff_id', $modelCompanyStaffWord->staffIdOfListCompany($ListCompanyId))->pluck('staff_id');
    }

    public function listIdOfListCompanyOld($ListCompanyId) # phien ban cu
    {
        $modelCompanyStaffWord = new QcCompanyStaffWork();
        return QcStaff::whereIn('staff_id', $modelCompanyStaffWord->staffIdOfListCompany($ListCompanyId))->pluck('staff_id');
    }

    //danh sách mã Nv lọc theo danh sách công ty và tên
    public function listIdOfListCompanyAndName($ListCompanyId, $name)
    {
        $modelCompanyStaffWord = new QcCompanyStaffWork();
        return QcStaff::whereIn('staff_id', $modelCompanyStaffWord->staffIdOfListCompany($ListCompanyId))->where('lastName', 'like', "%$name")->pluck('staff_id');
    }

    public function getInfoActivityOfListCompanyAndDepartment($ListCompanyId, $departmentId)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $listStaffId = $modelCompanyStaffWork->listStaffIdActivityOfCompanyAndDepartment($ListCompanyId, $departmentId);
        return QcStaff::whereIn('staff_id', $listStaffId)->orderBy('lastName', 'DESC')->get();
    }

    //---------- phuong thuc lam viec -----------
    public function staffWorkMethod()
    {
        return $this->hasMany('App\Models\Ad3d\StaffWorkMethod\QcStaffWorkMethod', 'staff_id', 'staff_id');
    }

    # kiem tra co ap dung noi quy cho NV hay khong
    public function infoActivityStaffWorkMethod($staffId = null)
    {
        $modelStaffWorkMethod = new QcStaffWorkMethod();
        return $modelStaffWorkMethod->infoActivityOfStaff($this->checkIdNull($staffId));
    }

    # kiem tra co ap dung noi quy cho NV hay khong
    public function checkApplyRule($staffId = null)
    {
        $dataStaffWorkMethod = $this->infoActivityStaffWorkMethod($staffId);
        if (count($dataStaffWorkMethod) > 0) {
            return $dataStaffWorkMethod->checkApplyRule();
        } else {
            return true; # mac dinh ap dung noi quy
        }
    }

    # kiem tra nhan vien lam chinh thuc hay khong
    public function checkOfficialMethodWork($staffId = null)
    {
        $dataStaffWorkMethod = $this->infoActivityStaffWorkMethod($staffId);
        if (count($dataStaffWorkMethod) > 0) {
            return $dataStaffWorkMethod->checkOfficialMethod();
        } else {
            return true; # mac dinh la NV chinh thu
        }
    }

    public function staffWorkMethodConfirm()
    {
        return $this->hasMany('App\Models\Ad3d\StaffWorkMethod\QcStaffWorkMethod', 'confirmStaff_id', 'staff_id');
    }

    //----------- báo chấm công ------------
    public function timekeepingProvisional()
    {
        return $this->hasMany('App\Models\Ad3d\TimekeepingProvisional\QcTimekeepingProvisional', 'staffConfirm_id', 'staff_id');
    }

    //----------- xin nghi ------------
    public function timekeepingOffWork()
    {
        return $this->hasMany('App\Models\Ad3d\LicenseOffWork\QcLicenseOffWork', 'staffConfirm_id', 'staff_id');
    }

    public function timekeepingOffWorkOfStaff($staffId, $dateFilter = null)
    {
        $modelOffWork = new QcLicenseOffWork();
        return $modelOffWork->selectInfoOfListStaffIdAndDate([$staffId], $dateFilter)->get();
    }

    //----------- xin di lam tre ------------
    public function timekeepingLateWork()
    {
        return $this->hasMany('App\Models\Ad3d\LicenseLateWork\QcLicenseLateWork', 'staffConfirm_id', 'staff_id');
    }

    public function timekeepingLateWorkOfStaff($staffId, $dateFilter = null)
    {
        $modelLateWork = new QcLicenseLateWork();
        return $modelLateWork->selectInfoOfListStaffIdAndDate([$staffId], $dateFilter)->get();
    }

    #----------- bang gia ------------
    public function productTypePrice()
    {
        return $this->hasMany('App\Models\Ad3d\ProductTypePrice\QcProductTypePrice', 'staff_id', 'staff_id');
    }

    //---------- don hang -----------
    public function order()
    {
        return $this->hasMany('App\Models\Ad3d\Order\QcOrder', 'staff_id', 'staff_id');
    }

    public function orderReceive()
    {
        return $this->hasMany('App\Models\Ad3d\Order\QcOrder', 'staffReceive_id', 'staff_id');
    }

    public function orderConfirm()
    {
        return $this->hasMany('App\Models\Ad3d\Order\QcOrder', 'staffConfirm_id', 'staff_id');
    }

    public function orderInfoOfStaffReceive($staffId = null, $date = null, $confirmStatus = null, $orderKeyword = null)
    {
        $modelOrder = new QcOrder();
        return $modelOrder->infoOfStaffReceive($this->checkIdNull($staffId), $date, $confirmStatus, $orderKeyword);
    }

    // lay thong tin don hang da thanh toan hoac chưa thanh toan theo tg
    public function orderAndPayInfoOfStaffReceive($staffId = null, $date = null, $paymentStatus = null, $orderKeyword = null)
    {
        $modelOrder = new QcOrder();
        return $modelOrder->infoAndPayOfStaffReceive($this->checkIdNull($staffId), $date, $paymentStatus, $orderKeyword);
    }

    // lay thong tin don hang bao gia
    public function orderProvisionAndPayInfoOfStaffReceive($staffId = null, $date = null, $provisionalConfirm = null, $orderKeyword = null)
    {
        $modelOrder = new QcOrder();
        return $modelOrder->infoProvisionalOfStaffReceive($this->checkIdNull($staffId), $date, $provisionalConfirm, $orderKeyword);
    }
    //---------- san pham -----------
    public function product()
    {
        return $this->hasMany('App\Models\Ad3d\Product\QcProduct', 'staff_id', 'confirmStaff_id');
    }

    //---------- thanh toan don hang -----------
    public function orderPay()
    {
        return $this->hasMany('App\Models\Ad3d\OrderPay\QcOrderPay', 'order_id', 'order_id');
    }

    public function orderPayStaff()
    {
        return $this->hasMany('App\Models\Ad3d\OrderPay\QcOrderPay', 'staff_id', 'staff_id');
    }

    public function orderPayInfoOfStaff($staffId, $date, $orderBy = 'DESC')
    {
        $modelOrderPay = new QcOrderPay();
        return $modelOrderPay->infoOfStaff($this->checkIdNull($staffId), $date, $orderBy);
    }

    public function orderPayNoTransferOfStaff($staffId, $date = null, $orderBy = 'DESC')
    {
        $modelOrderPay = new QcOrderPay();
        return $modelOrderPay->infoNoTransferOfStaff($this->checkIdNull($staffId), $date, $orderBy);
    }

    //---------- vật tư -----------
    public function toolStaff()
    {
        return $this->hasMany('App\Models\Ad3d\ToolStaff\QcToolStaff', 'staff_id', 'staff_id');
    }

    //---------- phiếu giao vật tư -----------
    public function toolAllocation()
    {
        return $this->hasMany('App\Models\Ad3d\ToolAllocation\QcToolAllocation', 'allocationStaff_id', 'staff_id');
    }

    public function toolAllocationReceive()
    {
        return $this->hasMany('App\Models\Ad3d\ToolAllocation\QcToolAllocation', 'receiveStaff_id ', 'staff_id');
    }

    public function toolAllocationOfReceiveStaffInfo($staffId = null)
    {
        $modelToolAllocation = new QcToolAllocation();
        return $modelToolAllocation->infoOfReceiveStaff($this->checkIdNull($staffId));
    }

    public function toolAllocationListIdOfReceiveStaff($receiveStaffId = null)
    {
        $modelToolAllocation = new QcToolAllocation();
        return $modelToolAllocation->listIdOfReceiveStaff($this->checkIdNull($receiveStaffId));
    }

    //---------- trả vật tư -----------
    public function toolReturnStaff()
    {
        return $this->hasMany('App\Models\Ad3d\ToolReturn\QcToolReturn', 'returnStaff_id', 'staff_id');
    }

    //---------- xác nhận trả vật tư -----------
    public function toolReturnConfirmStaff()
    {
        return $this->hasMany('App\Models\Ad3d\ToolReturn\QcToolReturn', 'confirmStaff_id', 'staff_id');
    }


    //---------- don hang -----------
    public function importStaff()
    {
        return $this->hasMany('App\Models\Ad3d\Order\QcOrder', 'staff_id', 'importStaff_id');
    }

    public function importInfoOfStaff($staffId = null, $payStatus = 3, $date = null)
    {
        $modelImport = new QcImport();
        return $modelImport->infoOfStaff($this->checkIdNull($staffId), $date, $payStatus);
    }

    public function totalMoneyImportOfStaff($staffId, $date = null, $payStatus = 3)#  $payStatus: 3_tat ca/ 1_da thanh toan/0_chua thanh toan
    {
        $modelImport = new QcImport();
        return $modelImport->totalMoneyImportOfStaff($staffId, $date, $payStatus);
    }

    public function confirmStaff()
    {
        return $this->hasMany('App\Models\Ad3d\Order\QcOrder', 'staff_id', 'importStaff_id');
    }

    //---------- giao tiền -----------
    # thong tin chuyển
    public function transferTransfer()
    {
        return $this->hasMany('App\Models\Ad3d\Transfers\QcTransfers', 'transfersStaff_id', 'staff_id');
    }

    public function transferOfTransferStaff($staffId, $date, $orderBy = 'DESC')
    {
        $modelTransfers = new QcTransfers();
        return $modelTransfers->infoOfTransferStaff($this->checkIdNull($staffId), $date, $orderBy);
    }

    public function transferConfirmedOfTransferStaff($staffId, $date=null)
    {
        $modelTransfers = new QcTransfers();
        return $modelTransfers->infoConfirmedOfTransferStaff($staffId, $date);
    }

    # thông tin nhan
    public function transferReceive()
    {
        return $this->hasMany('App\Models\Ad3d\Transfers\QcTransfers', 'receiveStaff_id', 'staff_id');
    }

    public function transferOfReceiveStaff($staffId, $date, $orderBy = 'DESC')
    {
        $modelTransfers = new QcTransfers();
        return $modelTransfers->infoOfReceiveStaff($this->checkIdNull($staffId), $date, $orderBy);
    }

    public function transferConfirmedOfReceiveStaff($staffId, $date=null)
    {
        $modelTransfers = new QcTransfers();
        return $modelTransfers->infoConfirmedOfReceiveStaff($staffId, $date);
    }
    //----------- GIU TIEN ------------
    public function keepMoney()
    {
        return $this->hasMany('App\Models\Ad3d\KeepMoney\QcKeepMoney', 'confirmStaff_id', 'staff_id');
    }

    //---------- CHAY KPI -----------
    public function staffKpi()
    {
        return $this->hasMany('App\Models\Ad3d\StaffKpi\QcStaffKpi', 'returnStaff_id', 'staff_id');
    }

    public function staffKpiInfoActivity($staffId = null)
    {
        $modelStaffKpi = new QcStaffKpi();
        return $modelStaffKpi->infoActivityOfStaff($this->checkIdNull($staffId));
    }

    //---------- DANG KY KPI -----------
    public function staffKpiRegister()
    {
        return $this->hasMany('App\Models\Ad3d\StaffKpiRegister\QcStaffKpiRegister', 'staff_id', 'staff_id');
    }

    public function staffKpiRegisterConfirm()
    {
        return $this->hasMany('App\Models\Ad3d\StaffKpiRegister\QcStaffKpiRegister', 'confirmStaff_id', 'staff_id');
    }

    //========== ========== ========== dang nhap ========== ========== ==========
    public function login($account, $password)
    {
        //$passLog = Hash::make($pass);
        $nameCode = QcStaff::where('account', $account)->pluck('nameCode');
        if (count($nameCode) > 0) {
            $passLog = $this->createStaffPass($password, $nameCode[0]);
            $staff = QcStaff::where('account', $account)->where('password', $passLog)->first();
            if (count($staff) > 0) { // login success
                Session::put('loginStaff', $staff);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    // kiem tra dang nhap
    public function checkLogin()
    {
        if (Session::has('loginStaff')) return true; else return false;
    }

    // thong tin NV dang nhap
    public function loginStaffInfo($field = '')
    {

        if (Session::has('loginStaff')) {//da dang nhap
            $staff = Session::get('loginStaff');
            if (empty($field)) { // have not to select a field -> return all field
                return $staff;
            } else { // have not to select a field -> return one field
                return $staff->$field;
            }
        } else { // have not to login

            return null;
        }
    }

    // ma nv dang nhap
    public function loginStaffId()
    {
        return $this->loginStaffInfo('staff_id');
    }

    //========== ========== ========== dang xuat ========= ========== ==========
    public function logout()
    {
        return Session::flush();
    }

    //========= ========== ========== lay thong tin ========== ========== ==========

    public function getInfo($staffId = '', $field = '')
    {
        if (empty($staffId)) {
            return QcStaff::where('workStatus', 1)->get();
        } else {
            $result = QcStaff::where('staff_id', $staffId)->first();
            if (empty($field)) {
                return $result;
            } else {
                return $result->$field;
            }
        }
    }

    public function getInfoActivityOrderByName($orderBy = 'ASC')
    {
        return QcStaff::where('workStatus', 1)->orderBy('lastName', $orderBy)->get();
    }

    // tao danh muc chon dang select box
    public function getOption($selected = '')
    {
        $hFunction = new \Hfunction();
        $dataStaff = DB::select("select staff_id as optionKey, CONCAT(firstName,lastName) as optionValue from tf_staffs ");
        return $hFunction->option($dataStaff, $selected);
    }


    public function getInfoActivity()
    {
        return QcStaff::where('workStatus', 1)->get();
    }

    public function getInfoByListStaffId($listStaffId)
    {
        return QcStaff::whereIn('staff_id', $listStaffId)->get();
    }

    public function getInfoActivityByListStaffId($listStaffId)
    {
        return QcStaff::whereIn('staff_id', $listStaffId)->where('workStatus', 1)->get();
    }


    # tat ca nhan trong he thong theo level
    public function getInfoActivityByLevel($level)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        return QcStaff::whereIn('staff_id', $modelCompanyStaffWork->listStaffIdActivityByLevel($level))->where('workStatus', 1)->get();
    }

    # tat ca nhan trong he thong theo level
    public function getTreasureInfoActivity($companyId, $level = 1000)# 1000 = tat ca level
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        return QcStaff::whereIn('staff_id', $modelCompanyStaffWork->listStaffIdTreasure($companyId, $level))->where('workStatus', 1)->get();
    }


    // lay thong tin nv them tai khoan
    public function infoFromAccount($account)
    {
        return QcStaff::where('account', $account)->first();
    }

    //la thong tin nv theo cmnnd
    public function infoFromIdentityCard($identityCard)
    {
        return QcStaff::where('identityCard', $identityCard)->first();
    }

    //danh sach ma nv dang hoat dong
    public function listStaffIdByName($name)
    {
        return QcStaff::where('lastName', 'like', "%$name%")->pluck('staff_id');
    }

    //danh sach ma nv dang hoat dong
    public function listStaffIdActivity()
    {
        return QcStaff::where('workStatus', 1)->pluck('staff_id');
    }

    public function staffId()
    {
        return $this->staff_id;
    }

    public function pluck($column, $objectId = null)
    {
        if (empty($objectId)) {
            return $this->$column;
        } else {
            $result = QcStaff::where('staff_id', $objectId)->pluck($column);
            return $result[0];
        }
    }

    public function fullName($staffId = null)
    {
        return $this->firstName($staffId) . ' ' . $this->lastName();
    }

    public function firstName($staffId = null)
    {
        $hFunction = new \Hfunction();
        return $hFunction->htmlEntities($this->pluck('firstName', $staffId));
    }

    public function lastName($staffId = null)
    {
        $hFunction = new \Hfunction();
        return $hFunction->htmlEntities($this->pluck('lastName', $staffId));
    }

    public function identityCard($staffId = null)
    {
        return $this->pluck('identityCard', $staffId);
    }

    public function nameCode($staffId = null)
    {
        return $this->pluck('nameCode', $staffId);
    }

    public function account($staffId = null)
    {
        return $this->pluck('account', $staffId);
    }

    public function birthday($staffId = null)
    {

        return $this->pluck('birthday', $staffId);
    }

    public function email($staffId = null)
    {

        return $this->pluck('email', $staffId);
    }

    public function phone($staffId = null)
    {

        return $this->pluck('phone', $staffId);
    }

    public function address($staffId = null)
    {
        $hFunction = new \Hfunction();
        return $hFunction->htmlEntities($this->pluck('address', $staffId));
    }


    public function createdAt($staffId = null)
    {
        return $this->pluck('created_at', $staffId);
    }


    public function image($staffId = null)
    {
        return $this->pluck('image', $staffId);
    }

    public function identityCardBack($staffId = null)
    {
        return $this->pluck('identityCardBack', $staffId);
    }

    public function identityCardFront($staffId = null)
    {
        return $this->pluck('identityCardFront', $staffId);
    }

    public function rootStatus($staffId = null)
    {
        return $this->pluck('rootStatus', $staffId);
    }

    public function confirmStatus($staffId = null)
    {
        return $this->pluck('confirmStatus', $staffId);
    }

    public function gender($staffId = null)
    {
        return $this->pluck('gender', $staffId);
    }

    public function bankAccount($staffId = null)
    {
        return $this->pluck('bankAccount', $staffId);
    }

    public function bankName($staffId = null)
    {
        return $this->pluck('bankName', $staffId);
    }

    // total records
    public function totalRecords()
    {
        return QcStaff::count();
    }

    // last id
    public function lastId()
    {
        $result = QcStaff::orderBy('staff_id', 'DESC')->first();
        return (empty($result)) ? 0 : $result->staff_id;
    }

    //========== ========== ========= KIEM TRA THONG TIN ========== ========= =========
    public function checkLoginAdmin($staffId = null)
    {
        return ($this->level($staffId) > 3) ? false : true;
    }

    public function checkWorkStatus($staffId = null)
    {
        return ($this->workStatus($this->checkIdNull($staffId)) == 1) ? true : false;
    }

    // exist of account
    public function existAccount($account)
    {
        $staff = QcStaff::where('account', $account)->count();
        return ($staff > 0) ? true : false;
    }

    public function checkRootStatus($staffId = null)
    {
        return ($this->rootStatus($staffId) == 1) ? true : false;
    }

    public function checkRootManage($staffId = null)
    {
        if ($this->checkRootStatus($staffId) && $this->level($staffId) == 0) {
            return true;
        } else {
            return false;
        }
    }

    // kiểm tra nv thuộc bộ phận quản lý
    public function checkManageDepartment($staffId = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        return $modelCompanyStaffWork->checkCurrentDepartmentManageOfStaff($staffId);
    }

    // kiểm tra nv thuộc bộ phận quản lý cấp quản lý
    public function checkManageDepartmentAndManageRank($staffId = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        return $modelCompanyStaffWork->checkManageDepartmentAndManageRank($this->checkIdNull($staffId));
    }

    // kiểm tra nv thuộc bộ phận quản lý cấp thông thường
    public function checkManageDepartmentAndNormalRank($staffId = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        return $modelCompanyStaffWork->checkManageDepartmentAndNormalRank($this->checkIdNull($staffId));
    }

    // kiểm tra nv thuộc bộ phận nhân sự
    public function checkPersonnelDepartment($staffId = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        return $modelCompanyStaffWork->checkCurrentDepartmentPersonnelOfStaff($this->checkIdNull($staffId));
    }

    // kiểm tra nv thuộc bộ phận nhân sự cấp quản lý
    public function checkPersonnelDepartmentAndManageRank($staffId = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        return $modelCompanyStaffWork->checkPersonnelDepartmentAndManageRank($this->checkIdNull($staffId));
    }

    // kiểm tra nv thuộc bộ phận nhân sự cấp thông thường
    public function checkPersonnelDepartmentAndNormalRank($staffId = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        return $modelCompanyStaffWork->checkPersonnelDepartmentAndNormalRank($this->checkIdNull($staffId));
    }

    // kiểm tra nv thuộc bộ phận thiết kế
    public function checkDesignDepartment($staffId = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        return $modelCompanyStaffWork->checkCurrentDepartmentDesignOfStaff($this->checkIdNull($staffId));
    }

    // kiểm tra nv thuộc bộ phận thiết kế cấp quản lý
    public function checkDesignDepartmentAndManageRank($staffId = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        return $modelCompanyStaffWork->checkDesignDepartmentAndManageRank($this->checkIdNull($staffId));
    }

    // kiểm tra nv thuộc bộ phận thiết kế cấp thông thường
    public function checkDesignDepartmentAndNormalRank($staffId = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        return $modelCompanyStaffWork->checkDesignDepartmentAndNormalRank($this->checkIdNull($staffId));
    }

    // -------- kiểm tra nv thuộc bộ phận thiết kế  ----------
    public function checkBusinessDepartment($staffId = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        return $modelCompanyStaffWork->checkCurrentDepartmentBusinessOfStaff($this->checkIdNull($staffId));
    }

    // kiểm tra nv thuộc bộ phận kinh doanh
    public function checkBusinessDepartmentAndManageRank($staffId = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        return $modelCompanyStaffWork->checkBusinessDepartmentAndManageRank($this->checkIdNull($staffId));
    }

    // kiểm tra nv thuộc bộ phận kinh doanh
    public function checkBusinessDepartmentAndNormalRank($staffId = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        return $modelCompanyStaffWork->checkBusinessDepartmentAndNormalRank($this->checkIdNull($staffId));
    }

    # kiem tra bo phan thu quy
    public function checkTreasureDepartment($staffId = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        return $modelCompanyStaffWork->checkCurrentDepartmentTreasureOfStaff($this->checkIdNull($staffId));
    }

    // kiem tra bo phan thu quy cap quan ly
    public function checkTreasureDepartmentAndManageRank($staffId = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        return $modelCompanyStaffWork->checkTreasureDepartmentAndManageRank($this->checkIdNull($staffId));
    }

    // kiem tra bo phan thu quy cap nhan vien
    public function checkTreasureDepartmentAndNormalRank($staffId = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        return $modelCompanyStaffWork->checkTreasureDepartmentAndNormalRank($this->checkIdNull($staffId));
    }

    #============ =========== ============ STATISTICAL ============= =========== ==========
    //  ========== ================= THONG KE THU ===================
    public function totalReceivedMoney($staffId, $date = null)
    {
        //  tong tien nhan tu thu don hang
        $totalMoneyOrderPay = $this->totalReceiveMoneyFromOrderPay($staffId, $date);

        //tong tien nhan tu thanh toan mua vat tu
        $totalMoneyImportPaidOfStaff = $this->totalMoneyImportOfStaff($staffId, $date, 1);

        // tien duoc giao
        $totalReceivedMoneyOfStaffAndDate = $this->totalMoneyReceivedTransferOfStaffAndDate($staffId, $date);
        return $totalMoneyOrderPay + $totalReceivedMoneyOfStaffAndDate + $totalMoneyImportPaidOfStaff;
    }

    //  tong tien nhan tu thu don hang
    public function totalReceiveMoneyFromOrderPay($staffId, $date = null)
    {
        $modelOrderPay = new QcOrderPay();
        return $modelOrderPay->totalMoneyOfStaffAndDate($staffId, $date);
    }

    //tong tien nhan tu chuyen tien va da xac nhan
    public function totalMoneyReceivedTransferOfStaffAndDate($staffId, $dateFilter = null) // tồng tiền nhận
    {
        $modelTransfers = new QcTransfers();
        return $modelTransfers->totalMoneyOfReceivedStaffAndDate($staffId, $dateFilter);
    }

    //  ========== ================= THONG KE CHI ===================
    public function totalPaidMoney($staffId, $date = null)
    {
        //  chi mua vạt tu da duoc duyet
        $totalMoneyImport = $this->totalMoneyImportConfirmedAndAgreeOfStaff($staffId, $date);

        // Chi GIAO TIEN va Da xac nha
        $totalMoneyTransfer = $this->totalMoneyTransferConfirmedOfStaffAndDate($staffId, $date);

        //chi ứng luong
        $totalMoneyPaidSalaryBeforePay = $this->totalMoneyPaidSalaryBeforePayOfStaffAndDate($staffId, $date);

        //chi thanh toan luong - da duoc thanh toan
        $totalMoneyPaidSalaryPay = $this->totalMoneyPaidSalaryPayOfStaffAndDateAndConfirmed($staffId, $date);

        //chi hoat dong
        $totalMoneyPayActivity = $this->totalMoneyPayActivityConfirmedAndInvalidOfStaff($staffId, $date);

        return $totalMoneyTransfer + $totalMoneyImport + $totalMoneyPaidSalaryBeforePay + $totalMoneyPaidSalaryPay + $totalMoneyPayActivity;
    }

    //tong tien da chuyen va da duoc xac nhan
    public function totalMoneyTransferConfirmedOfStaffAndDate($staffId, $dateFilter = null) // tồng tiền nhận
    {
        $modelTransfers = new QcTransfers();
        return $modelTransfers->totalMoneyConfirmedOfTransferStaffAndDate($staffId, $dateFilter);
    }

    //tong tien chi mua vat tu / dung cu da duoc duỵet
    public function totalMoneyImportConfirmedAndAgreeOfStaff($staffId, $date = null, $payStatus = 3)#  $payStatus: 3_tat ca/ 1_da thanh toan/0_chua thanh toan
    {
        $modelImport = new QcImport();
        return $modelImport->totalMoneyImportConfirmedAndAgreeOfStaff($staffId, $date, $payStatus);
    }

    //Chi hoat dong cty
    public function totalMoneyPayActivityConfirmedAndInvalidOfStaff($staffId, $date = null)
    {
        $modelPayActivityDetail = new QcPayActivityDetail();
        return $modelPayActivityDetail->totalMoneyConfirmedAndInvalidOfStaffAndDate($staffId, $date);
    }
    //tong tien chi ưng luong
    public function totalMoneyPaidSalaryBeforePayOfStaffAndDate($staffId, $dateFilter = null) // tồng tiền nhận
    {
        $modelSalaryBeforePay = new QcSalaryBeforePay();
        return $modelSalaryBeforePay->totalMoneyOfStaffAndDate($staffId, $dateFilter);
    }

    //tong tien chi thanh toan lương
    public function totalMoneyPaidSalaryPayOfStaffAndDate($staffId, $dateFilter = null) // tồng tiền nhận
    {
        $modelSalaryPay = new QcSalaryPay();
        return $modelSalaryPay->totalMoneyOfStaffAndDate($staffId, $dateFilter);
    }

    //tong tien chi thanh toan lương va da duoc xac nhan
    public function totalMoneyPaidSalaryPayOfStaffAndDateAndConfirmed($staffId, $dateFilter = null) // tồng tiền nhận
    {
        $modelSalaryPay = new QcSalaryPay();
        return $modelSalaryPay->totalMoneyOfStaffAndDateAndConfirmed($staffId, $dateFilter);
    }

    public function paymentTypeInfo()
    {
        $modelPaymentType = new QcPaymentType();
        return $modelPaymentType->infoActivity();
    }

    public function staffPaidInfo($listCompanyId, $dateFilter = null)
    {
        $modelPayment = new QcPayment();
        $listStaffId = $modelPayment->infoStaffPayment($listCompanyId, $dateFilter);
        if (count($listStaffId) > 0) {
            return QcStaff::whereIn('staff_id', $listStaffId)->get();
        } else {
            return null;
        }
    }

    // ứng lương
    public function totalSalaryBeforeOfCompany($listCompanyId, $dateFilter = null)
    {
        $modelSalaryBeforePay = new QcSalaryBeforePay();
        return $modelSalaryBeforePay->totalSalaryBeforeOfCompany($listCompanyId, $dateFilter);
    }

    public function totalSalaryBeforeOfCompanyStaffDate($listCompanyId, $staffId, $dateFilter = null)
    {
        $modelSalaryBeforePay = new QcSalaryBeforePay();
        return $modelSalaryBeforePay->totalSalaryBeforeOfCompanyStaffDate($listCompanyId, $staffId, $dateFilter);
    }

    // thanh toán lương
    public function totalSalaryPaidOfCompany($listCompanyId, $dateFilter = null)
    {
        $modelSalaryPay = new QcSalaryPay();
        return $modelSalaryPay->totalSalaryPaidOfCompany($listCompanyId, $dateFilter);
    }

    public function totalSalaryPaidCompanyStaffDate($listCompanyId, $staffId, $dateFilter = null)
    {
        $modelSalaryPay = new QcSalaryPay();
        return $modelSalaryPay->totalSalaryPaidOfCompanyStaffDate($listCompanyId, $staffId, $dateFilter);
    }

    //doanh thu
    public function staffOrderPayInfo($listCompanyId, $dateFilter = null)
    {
        $modelOrderPay = new QcOrderPay();
        $listStaffId = $modelOrderPay->infoStaffOrderPay($listCompanyId, $dateFilter);
        if (count($listStaffId) > 0) {
            return QcStaff::whereIn('staff_id', $listStaffId)->get();
        } else {
            return null;
        }
    }

    public function infoManagerOfCompany($listCompanyId)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $listManageStaffId = $modelCompanyStaffWork->listStaffIdManageOfListCompany($listCompanyId);
        return QcStaff::whereIn('staff_id', $listManageStaffId)->get();
    }

    //chuyen tien tu cong ty me
    public function totalReceiveMoneyOfStaffAndCompany($listCompanyId, $staffId, $dateFilter = null) // tồng tiền nhận
    {
        $modelTransfers = new QcTransfers();
        return $modelTransfers->totalReceiveMoneyOfCompany($listCompanyId, $staffId, $dateFilter);
    }

    public function totalTransfersMoneyOfStaff($staffId, $dateFilter = null) // tổng tiền giao
    {
        $modelTransfers = new QcTransfers();
        return $modelTransfers->totalTransfersMoneyOfStaff($staffId, $dateFilter);
    }

    //======= thống kê =========
    public function totalNewTimekeepingProvisional($companyId = null)
    {
        $modelTimekeepingProvisional = new QcTimekeepingProvisional();
        return $modelTimekeepingProvisional->totalNewTimekeepingProvisional($companyId);
    }

    public function totalNewLicenseOffWork($companyId = null)
    {
        $modelLicense = new QcLicenseOffWork();
        return $modelLicense->totalNewLicenseOffWork($companyId);
    }

    public function totalNewLicenseLateWork($companyId = null)
    {
        $modelLicense = new QcLicenseLateWork();
        return $modelLicense->totalNewLicenseLateWork($companyId);
    }

    public function totalNewSalaryBeforePayRequest($companyId = null)
    {
        $modelRequest = new QcSalaryBeforePayRequest();
        return $modelRequest->totalNewRequest($companyId);
    }

    public function checkAutoInfo()
    {
        $modelWork = new QcWork();
        # kiem tra cham cong
        //$modelWork->checkAutoTimekeepingOfActivityWork();
        #kiểm tra đầu tháng để cho ra bảng làm việc của tháng mới
        $modelWork->checkEndWorkOfMonth();
    }
}
