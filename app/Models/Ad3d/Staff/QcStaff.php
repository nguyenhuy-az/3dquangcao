<?php

namespace App\Models\Ad3d\Staff;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\Department\QcDepartment;
use App\Models\Ad3d\Import\QcImport;
use App\Models\Ad3d\ImportPay\QcImportPay;
use App\Models\Ad3d\LicenseLateWork\QcLicenseLateWork;
use App\Models\Ad3d\LicenseOffWork\QcLicenseOffWork;
use App\Models\Ad3d\Order\QcOrder;
use App\Models\Ad3d\OrderAllocation\QcOrderAllocation;
use App\Models\Ad3d\OrderCancel\QcOrderCancel;
use App\Models\Ad3d\OrderPay\QcOrderPay;
use App\Models\Ad3d\PayActivityDetail\QcPayActivityDetail;
use App\Models\Ad3d\Payment\QcPayment;
use App\Models\Ad3d\PaymentType\QcPaymentType;
use App\Models\Ad3d\Product\QcProduct;
use App\Models\Ad3d\Rank\QcRank;
use App\Models\Ad3d\SalaryBeforePay\QcSalaryBeforePay;
use App\Models\Ad3d\SalaryPay\QcSalaryPay;
use App\Models\Ad3d\StaffKpi\QcStaffKpi;
use App\Models\Ad3d\StaffNotify\QcStaffNotify;
use App\Models\Ad3d\StaffSalaryBasic\QcStaffSalaryBasic;
use App\Models\Ad3d\StaffWorkMethod\QcStaffWorkMethod;
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

    }

    //========== ========= ========= INSERT && UPDATE ========== ========= =========
    public function checkFixSystem()
    {

    }

    # mac dinh dang lam viec
    public function getDefaultHasWorkStatus()
    {
        return 1;
    }

    # mac dinh khong con lam
    public function getDefaultNotWorkStatus()
    {
        return 0;
    }

    # mac dinh tat ca trang thai lam viec
    public function getDefaultAllWorkStatus()
    {
        return 100;
    }

    # mac dinh tai khoan root
    public function getDefaultHasRootStatus()
    {
        return 1;
    }

    # mac dinh tai khoan root
    public function getDefaultNotRootStatus()
    {
        return 0;
    }

    #mac dinh co xac nhan
    public function getDefaultHasConfirm()
    {
        return 1;
    }

    #mac dinh co xac nhan
    public function getDefaultNotConfirm()
    {
        return 0;
    }

    #mac dinh tai goc cua cty bang 0
    public function getDefaultRootLevel()
    {
        return 0;
    }
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
        $modelCompany = new QcCompany();
        $modelStaff = new QcStaff();
        //create code
        $nameCode = $hFunction->getTimeCode();
        if ($level == $this->getDefaultRootLevel()) {# mac dinh 0 la nhan vien quan ly he thong cua 1 cty
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
        $modelStaff->workStatus = $this->getDefaultHasWorkStatus();
        $modelStaff->rootStatus = $modelCompany->getDefaultNotRootOfStaff(); # lay mac dinh level la nhan vien he thong duoc xoa sua
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
        $hFunction = new \Hfunction();
        return ($hFunction->checkEmpty($staffId)) ? $this->staffId() : $staffId;
    }

    public function staffId()
    {
        return $this->staff_id;
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

    public function workStatus($staffId = null)
    {
        return $this->pluck('workStatus', $staffId);
    }

    public function bankAccount($staffId = null)
    {
        return $this->pluck('bankAccount', $staffId);
    }

    public function bankName($staffId = null)
    {
        return $this->pluck('bankName', $staffId);
    }

    # cap nhat tai khoan
    public function updateAccount($staffId, $newAccount)
    {
        return QcStaff::where('staff_id', $staffId)->update(['account' => $newAccount, 'confirmStatus' => $this->getDefaultHasConfirm()]);
    }

    # cap nhat thong tin tk ngan hang
    public function updateBankAccount($staffId, $bankAccount, $bankName)
    {
        return QcStaff::where('staff_id', $staffId)->update(['bankAccount' => $bankAccount, 'bankName' => $bankName]);
    }

    # phuc hoi trang thai lam viec
    public function restoreWorkStatus($staffId)
    {
        return QcStaff::where('staff_id', $staffId)->update(['workStatus' => $this->getDefaultHasWorkStatus()]);

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
        return QcStaff::where('staff_id', $staffId)->update(['account' => $newAccount, 'password' => $createPass, 'confirmStatus' => $this->getDefaultHasConfirm()]);
    }

    public function checkPassOfStaff($staffId, $password)
    {
        return $this->login($this->account($staffId), $password);
    }

    // delete
    public function actionDelete($staffId = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $staffId = $this->checkIdNull($staffId);
        if (QcStaff::where('staff_id', $staffId)->update(['workStatus' => $this->getDefaultNotWorkStatus()])) {
            $modelCompanyStaffWork->deleteOfStaff($staffId);
        }
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

    public function pathDefaultImage()
    {
        return asset('public/images/icons/people.jpeg');
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

    # lay duong dan anh avatar
    public function pathAvatar($image)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($image)) {
            return $this->pathDefaultImage();
        } else {
            return $this->pathFullImage($image);
        }
    }
    //========== ========= ========= mối quan hệ ========== ========= ==========
    //---------- phan hoi phat -----------
    public function minusMoneyFeedback()
    {
        return $this->hasMany('App\Models\Ad3d\MinusMoneyFeedback\QcMinusMoneyFeedback', 'confirmStaff_id', 'staff_id');
    }

    //---------- thong tin bao cao kiem tra do nghe dung chung -----------
    public function companyStoreCheckReport()
    {
        return $this->hasMany('App\Models\Ad3d\CompanyStoreCheckReport\QcCompanyStoreCheckReport', 'confirmStaff_id', 'staff_id');
    }

    #----------- ngay nghi he thong ------------
    public function importPay()
    {
        return $this->hasMany('App\Models\Ad3d\ImportPay\QcImportPay', 'staff_id', 'staff_id');
    }

    public function totalMoneyImportPayOfStaffAndDate($staffId, $filterDate = null)
    {
        $modelImportPay = new QcImportPay();
        return $modelImportPay->totalMoneyOfPayStaffAndDate($staffId, $filterDate);
    }

    public function totalMoneyImportPayConfirmOfStaffAndDate($staffId, $filterDate = null)
    {
        $modelImportPay = new QcImportPay();
        return $modelImportPay->totalMoneyConfirmedOfPayStaffAndDate($staffId, $filterDate);
    }


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

    # thong tin đang lam viec tai 1 cty
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
        $companyLoginId = $this->companyId($this->checkIdNull($staffId));
        return $modelCompany->getInfo($companyLoginId);
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

    public function payActivityDetailOfReceiveStaff($staffId, $date = null)
    {
        $modelPayActivityDetail = new QcPayActivityDetail();
        return $modelPayActivityDetail->infoConfirmedAndInvalidOfStaffAndDate($staffId, $date);
    }

    public function payActivityDetailConfirmedOfReceiveStaff($staffId, $date = null)
    {
        $modelPayActivityDetail = new QcPayActivityDetail();
        return $modelPayActivityDetail->infoConfirmedAndInvalidOfStaffAndDate($staffId, $date);
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

    #----------- ANH thiet ke  ------------
    public function productDesign()
    {
        return $this->hasMany('App\Models\Ad3d\ProductDesign\QcProductDesign', 'staff_id', 'staff_id');
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
    # ma bo phan cua nv dang lam
    public function departmentActivityOfStaff($staffId = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        return $modelCompanyStaffWork->listIdDepartmentActivityOfStaff($this->checkIdNull($staffId));
    }
    # thong tin bo phan nv dang lam

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

    public function selectOrderAllocationInfoOfReceiveStaff($staffId, $date, $finishStatus = 100)
    {
        $modelOrdersAllocation = new QcOrderAllocation();
        return $modelOrdersAllocation->selectInfoOfReceiveStaff($this->checkIdNull($staffId), $date, $finishStatus);
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

    #lay tat ca thong tin phan cong cua nguoi nhan
    public function workAllocationOfStaffReceive($staffId, $dateFilter = null)
    {
        $modelWorkAllocation = new QcWorkAllocation();
        return $modelWorkAllocation->infoOfStaffReceive($staffId, $dateFilter);
    }

    # chon so cong viec duoc giao cua 1 NV
    public function selectWorkAllocationOfStaffReceive($staffId, $finishStatus = 100, $dateFilter = null)
    {
        $modelWorkAllocation = new QcWorkAllocation();
        return $modelWorkAllocation->selectInfoOfStaffReceive($staffId, $finishStatus, $dateFilter);
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
    /*public function work()
    {
        return $this->hasMany('App\Models\Ad3d\Work\QcWork', 'staff_id', 'staff_id');
    }*/

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

    # danh sach ma bang cham cong lam viec cua 1 nhan vien
    public function allListWorkId($staffId)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelWork = new QcWork();
        $oldListWorkId = $modelWork->listIdOfListStaffId([$staffId])->toArray();//phien ban cu
        $listCompanyStaffWorkId = $modelCompanyStaffWork->listIdOfStaff($staffId);
        $newListWorkId = $modelWork->listIdOfListCompanyStaffWork($listCompanyStaffWorkId)->toArray();//phien ban moi
        return array_merge($oldListWorkId, $newListWorkId);
    }

    # danh sach ma bang cham cong lam viec cua 1 nhan vien
    public function allListWorkIdOfListStaffId($listStaffId)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelWork = new QcWork();
        $listCompanyStaffWorkId = $modelCompanyStaffWork->listIdOfListStaffId($listStaffId);
        return $modelWork->listIdOfListCompanyStaffWork($listCompanyStaffWorkId)->toArray();//phien ban moi
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

    # danh sach NV bo phan thi cong dang hoat dong
    public function infoActivityConstructionOfCompany($companyId, $level = 1000, $orderByName = 'ASC')
    {
        $modelDepartment = new QcDepartment();
        return $this->infoActivityOfCompany($companyId, $modelDepartment->constructionDepartmentId(), $level, $orderByName);
    }

    # lay danh sach tat ca nv cua 1 cty
    public function infoOfCompany($companyId, $departmentId = null, $level = 1000)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $listStaffId = $modelCompanyStaffWork->listStaffIdHasFilter($companyId, $departmentId, $level);
        return QcStaff::whereIn('staff_id', $listStaffId)->orderBy('lastName', 'ASC')->get();
    }

    # lay danh sach tat ca nv dang hoat dong cua 1 cty
    public function infoActivityOfCompany($companyId, $departmentId = null, $level = 1000, $orderByName = 'ASC') # mac dinh 1000 = tat ca level
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $listStaffId = $modelCompanyStaffWork->listStaffIdActivityHasFilter($companyId, $departmentId, $level);
        return QcStaff::whereIn('staff_id', $listStaffId)->where('workStatus', 1)->orderBy('lastName', $orderByName)->get();

    }

    # lay danh sach tat ca ma nv cua 1 cty
    public function listIdOfCompany($companyId)
    {
        $modelCompanyStaffWord = new QcCompanyStaffWork();
        return QcStaff::whereIn('staff_id', $modelCompanyStaffWord->staffIdOfCompany($companyId))->pluck('staff_id');
    }

    # lay danh sach tat ca ma nv dang hoat dong cua 1 cty
    public function listIdActivityOfCompany($companyId)
    {
        $modelCompanyStaffWord = new QcCompanyStaffWork();
        return QcStaff::whereIn('staff_id', $modelCompanyStaffWord->staffIdActivityOfCompany($companyId))->pluck('staff_id');
    }

    //lay danh sách mã Nv lọc theo 1 công ty và tên
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

    # kiem tra nv co cham cong hay khong
    public function checkTimekeepingProvisionalOfCurrentDate($staffId)
    {
        $hFunction = new \Hfunction();
        $dataWork = $this->firstInfoActivityToWork($staffId);
        $dataTimekeeping = $dataWork->timekeepingProvisionalOfDate($dataWork->workId(), date('Y-m-d'));
        return $hFunction->checkCount($dataTimekeeping);
    }

    //----------- ----------- xin nghi ------------ -----------
    public function timekeepingOffWork()
    {
        return $this->hasMany('App\Models\Ad3d\LicenseOffWork\QcLicenseOffWork', 'staffConfirm_id', 'staff_id');
    }

    # lay thong tin xin nghi cua 1 NV
    public function timekeepingOffWorkOfStaff($staffId, $dateFilter = null)
    {
        $modelOffWork = new QcLicenseOffWork();
        return $modelOffWork->selectInfoOfListStaffIdAndDate([$staffId], $dateFilter)->get();
    }

    # kiem tra NV xin nghi co duoc duyet hay khong
    public function existAcceptedOffWork($staffId, $date)
    {
        $modelOffWork = new QcLicenseOffWork();
        return $modelOffWork->existAcceptedDateOfStaff($staffId, $date);
    }

    //----------- ----------- xin di lam tre ------------ -----------
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

    // lay thong tin don hang da thanh toan hoac chưa thanh toan theo tg - khong huy
    public function selectOrderNoCancelAndPayInfoOfStaffReceive($staffId = null, $date = null, $paymentStatus = null, $finishStatus = null, $orderKeyword = null)
    {
        $modelOrder = new QcOrder();
        return $modelOrder->selectInfoNoCancelAndPayOfStaffReceive($this->checkIdNull($staffId), $date, $paymentStatus, $finishStatus, $orderKeyword);
    }

    // lay thong tin don hang da thanh toan hoac chưa thanh toan theo tg - khong huy cua 1 hoac nhieu nguoi
    public function selectOrderNoCancelAndPayInfoOfListStaffReceive($listStaffId, $date = null, $paymentStatus = null, $finishStatus = null, $orderKeyword = null)
    {
        $modelOrder = new QcOrder();
        return $modelOrder->selectInfoNoCancelAndPayOfListStaffReceive($listStaffId, $date, $paymentStatus, $finishStatus, $orderKeyword);
    }


    public function orderNoCancelAndPayInfoOfStaffReceive($staffId = null, $date = null, $paymentStatus = null, $orderKeyword = null)
    {
        $modelOrder = new QcOrder();
        return $modelOrder->infoNoCancelAndPayOfStaffReceive($this->checkIdNull($staffId), $date, $paymentStatus, $orderKeyword);
    }

    // lay thong tin don hang bao gia
    public function orderProvisionAndPayInfoOfStaffReceive($staffId = null, $date = null, $provisionalConfirm = null, $orderKeyword = null)
    {
        $modelOrder = new QcOrder();
        return $modelOrder->infoProvisionalOfStaffReceive($this->checkIdNull($staffId), $date, $provisionalConfirm, $orderKeyword);
    }

    // lay thong tin don hang bao gia - khong huy
    public function orderProvisionNoCancelAndPayInfoOfStaffReceive($staffId = null, $date = null, $provisionalConfirm = null, $orderKeyword = null)
    {
        $modelOrder = new QcOrder();
        return $modelOrder->infoProvisionalNoCancelOfStaffReceive($this->checkIdNull($staffId), $date, $provisionalConfirm, $orderKeyword);
    }

    //---------- san pham -----------
    public function product()
    {
        return $this->hasMany('App\Models\Ad3d\Product\QcProduct', 'staff_id', 'confirmStaff_id');
    }

    //---------- huy don hang -----------
    public function orderCancel()
    {
        return $this->hasMany('App\Models\Ad3d\OrderCancel\QcOrderCancel', 'staff_id', 'staff_id');
    }

    //---------- thanh toan don hang -----------
    public function orderPay()
    {
        return $this->hasMany('App\Models\Ad3d\OrderPay\QcOrderPay', 'staff_id', 'staff_id');
    }

    public function orderPayStaff()
    {
        return $this->hasMany('App\Models\Ad3d\OrderPay\QcOrderPay', 'staff_id', 'staff_id');
    }

    # tat ca tien thu don hang chua giao
    public function orderPayInfoOfStaff($staffId, $date, $orderBy = 'DESC')
    {
        $modelOrderPay = new QcOrderPay();
        return $modelOrderPay->infoOfStaff($this->checkIdNull($staffId), $date, $orderBy);
    }

    # tien thu don hang chua giao
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

    //---------- nguoi giao do nghe -----------
    public function toolAllocation()
    {
        return $this->hasMany('App\Models\Ad3d\ToolAllocation\QcToolAllocation', 'allocationStaff_id', 'staff_id');
    }


    //---------- xac nhan tra vat tu -----------
    public function toolReturn()
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

    # tat ca thong tin mua vat tu cua 1 nhan vie
    public function importGetInfo($companyId, $staffId = null, $date = null)
    {
        $modelImport = new QcImport();
        return $modelImport->getInfoOfStaff($companyId, $this->checkIdNull($staffId), $date);
    }

    # tong tien mua vat tu cua nhan vien - ta ca hoa don nhap
    public function importTotalMoney($companyId, $staffId, $date = null)
    {
        $modelImport = new QcImport();
        return $modelImport->totalMoneyImportOfStaff($companyId, $staffId, $date);
    }

    # tong tien mua vat tu cua nhan vien - da xac nhan dong y
    public function importTotalMoneyHasConfirmHasExactly($companyId, $staffId, $date = null)
    {
        $modelImport = new QcImport();
        return $modelImport->totalMoneyImportOfStaffHasConfirmHasExactly($companyId, $staffId, $date);
    }

    # tong tien mua vat tu thanh toan da xac nhan
    public function importTotalMoneyHasConfirmPay($companyId, $staffId, $date = null)
    {
        $modelImport = new QcImport();
        return $modelImport->totalMoneyImportOfStaffHasConfirmPay($companyId, $staffId, $date);
    }

    # tong tien mua vat tu thanh toan chua xac nhan
    public function importTotalMoneyNotConfirmPay($companyId, $staffId, $date = null)
    {
        $modelImport = new QcImport();
        return $modelImport->totalMoneyImportOfStaffNotConfirmPay($companyId, $staffId, $date);
    }

    # tong tien mua vat tu da duyet chua thanh toan
    public function importTotalMoneyHasConfirmNotPay($companyId, $staffId, $date = null)
    {
        $modelImport = new QcImport();
        return $modelImport->totalMoneyImportOfStaffHasConfirmNotPay($companyId, $staffId, $date);
    }

    //---------- ngươi xac nhan -----------
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

    public function transferConfirmedOfTransferStaff($staffId, $date = null)
    {
        $modelTransfers = new QcTransfers();
        return $modelTransfers->infoConfirmedOfTransferStaff($staffId, $date);
    }

    #tong tien da chuyen chua xac nhan
    public function totalMoneyTransferUnConfirmed($staffId, $dateFilter = null) // tồng tiền nhận
    {
        $modelTransfers = new QcTransfers();
        return $modelTransfers->totalMoneyUnConfirmOfTransferStaff($staffId, $dateFilter);
    }


    #tong tien da chuyen da xac nhan dong y
    public function totalMoneyTransferConfirmedAndAccepted($staffId, $dateFilter = null) // tồng tiền nhận
    {
        $modelTransfers = new QcTransfers();
        return $modelTransfers->totalMoneyConfirmedAndAcceptedOfTransferStaff($staffId, $dateFilter);
    }


    #tong tien da chuyen va da duoc xac nhan
    public function totalMoneyTransferConfirmedOfStaffAndDate($staffId, $dateFilter = null) // tồng tiền nhận
    {
        $modelTransfers = new QcTransfers();
        return $modelTransfers->totalMoneyConfirmedOfTransferStaffAndDate($staffId, $dateFilter);
    }

    //---------- nhan tiền -----------
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

    public function transferConfirmedOfReceiveStaff($staffId, $date = null)
    {
        $modelTransfers = new QcTransfers();
        return $modelTransfers->infoConfirmedOfReceiveStaff($staffId, $date);
    }

    # tong tien nhan chuyen tu thu don hang va da xac nhan
    public function totalMoneyReceiveTransferOrderPayConfirmed($staffId, $date = null)
    {
        $modelTransfer = new QcTransfers();
        return $modelTransfer->totalMoneyReceivedFromOrderPayOfStaffAndDate($staffId, $date);
    }

    # tong tien nop cho cong ty (Thu quy cap quan ly) va da xac nhan
    public function totalMoneyConfirmedTransferForTreasurerManage($staffId, $date = null)
    {
        $modelTransfer = new QcTransfers();
        return $modelTransfer->totalMoneyConfirmedTransfersForTreasurerManage($staffId, $date);
    }

    # tong tien nhan chuyen tu dau tu va da xac nhan
    public function totalMoneyReceiveTransferInvestmentConfirmed($staffId, $date = null)
    {
        $modelTransfer = new QcTransfers();
        return $modelTransfer->totalMoneyReceivedFromInvestmentOfStaffAndDate($staffId, $date);
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

    //---------- THONG BAO DON HANG MOI -----------
    public function staffNotify()
    {
        return $this->hasMany('App\Models\Ad3d\StaffNotify\QcStaffNotify', 'staff_id', 'staff_id');
    }

    public function infoStaffNotifyOfStaff($staffId, $date = null)
    {
        $modelStaffNotify = new QcStaffNotify();
        return $modelStaffNotify->infoOfStaff($staffId, $date);
    }


    public function selectAllNotify($staffId = null, $date = null)
    {
        $modelStaffNotify = new QcStaffNotify();
        return $modelStaffNotify->selectInfoOfStaff($this->checkIdNull($staffId), $date);
    }

    #tat ca thong bao moi chua xem
    public function totalNewNotify($staffId = null)
    {
        $modelStaffNotify = new QcStaffNotify();
        return $modelStaffNotify->totalNewNotifyOfStaff($this->checkIdNull($staffId));
    }

    # thong bao them don hang moi chua xem
    public function totalNotifyNewOrder($staffId = null)
    {
        $modelStaffNotify = new QcStaffNotify();
        return $modelStaffNotify->totalNotifyNewOrderOfStaff($this->checkIdNull($staffId));
    }

    public function checkViewNotifyNewOrder($staffId, $orderId)
    {
        $modelStaffNotify = new QcStaffNotify();
        return $modelStaffNotify->checkViewedNewOrderOfStaff($staffId, $orderId);
    }

    # thong bao ban giao cong trinh moi chua xem
    public function totalNotifyNewOrderAllocation($staffId = null)
    {
        $modelStaffNotify = new QcStaffNotify();
        return $modelStaffNotify->totalNotifyNewOrderAllocationOfStaff($this->checkIdNull($staffId));
    }

    # thong bao phan viec moi chua xem
    public function totalNotifyNewWorkAllocation($staffId = null)
    {
        $modelStaffNotify = new QcStaffNotify();
        return $modelStaffNotify->totalNotifyNewWorkAllocationOfStaff($this->checkIdNull($staffId));
    }

    # thong bao thuong moi chua xem
    public function totalNotifyNewBonus($staffId = null)
    {
        $modelStaffNotify = new QcStaffNotify();
        return $modelStaffNotify->totalNotifyNewBonusOfStaff($this->checkIdNull($staffId));
    }

    # thong bao phat moi chua xem
    public function totalNotifyNewMinusMoney($staffId = null)
    {
        $modelStaffNotify = new QcStaffNotify();
        return $modelStaffNotify->totalNotifyNewMinusMoneyOfStaff($this->checkIdNull($staffId));
    }

    //========== ========== ========== dang nhap ========== ========== ==========
    public function login($account, $password)
    {
        $modelCompany = new QcCompany();
        //$passLog = Hash::make($pass);
        $nameCode = QcStaff::where('account', $account)->pluck('nameCode');
        if (count($nameCode) > 0) {
            $passLog = $this->createStaffPass($password, $nameCode[0]);
            $staff = QcStaff::where('account', $account)->where('password', $passLog)->where('workStatus', 1)->first();
            if (count($staff) > 0) { // login success
                # KIEM TRA DU LIEU TU DONG
                $modelCompany->checkAutoInfo();
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
        if (Session::has('loginStaff')) {
            return true;
        } else {
            return false;
        }
    }

    // thong tin NV dang nhap
    public function loginStaffInfo($field = '')
    {
        $hFunction = new \Hfunction();
        if (Session::has('loginStaff')) {//da dang nhap
            $staff = Session::get('loginStaff');
            if ($hFunction->checkEmpty($field)) { // have not to select a field -> return all field
                return $staff;
            } else { // have not to select a field -> return one field
                return $staff->$field;
            }
        } else { // have not to login
            return null;
        }
    }

    // thong tin dang lam viec cua NV dang nhap
    public function loginCompanyStaffWork()
    {
        $hFunction = new \Hfunction();
        $dataLoginInfo = $this->loginStaffInfo();
        if ($hFunction->checkCount($dataLoginInfo)) {
            return $dataLoginInfo->companyStaffWorkInfoActivity();
        } else {
            return null;
        }
    }

    // ma nv dang nhap
    public function loginStaffId()
    {
        return $this->loginStaffInfo('staff_id');
    }

    // thong tin cty dang nhap
    public function companyLogin()
    {
        $hFunction = new \Hfunction();
        $loginCompanyStaffWork = $this->loginCompanyStaffWork();
        if ($hFunction->checkCount($loginCompanyStaffWork)) {
            return $loginCompanyStaffWork->company;
        } else {
            return null;
        }
    }

    //========== ========== ========== dang xuat ========= ========== ==========
    public function logout()
    {
        return Session::flush();
    }

    // ======== ====== ====== =========== BO PHAN KINH DOANH =========== =========== ===========
    # lay danh sanh NV bo phan kinh doanh cap quan ly cua 1 cong ty - ta ca
    public function infoStaffBusinessRankManage($companyId)
    {
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $listStaffId = $modelCompanyStaffWork->listStaffIdOfCompanyIdAndListDepartmentId($companyId, [$modelDepartment->businessDepartmentId()], $modelRank->manageRankId());
        return QcStaff::whereIn('staff_id', $listStaffId)->get();
    }

    # lay danh sanh NV bo phan kinh doanh cap quan ly cua 1 cong ty - dang hoat dong
    public function infoActivityStaffBusinessRankManage($companyId)
    {
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $listStaffId = $modelCompanyStaffWork->listStaffIdActivityOfCompanyIdAndListDepartmentId($companyId, [$modelDepartment->businessDepartmentId()], $modelRank->manageRankId());
        return QcStaff::whereIn('staff_id', $listStaffId)->get();
    }
    // ======== ====== ====== =========== BO PHAN THI CONG =========== =========== ===========
    # lay thong tin NV la  bo phan thi cong cap quan ly cua 1 cong ty
    public function infoStaffConstructionRankManage($companyId)
    {
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $listStaffId = $modelCompanyStaffWork->listStaffIdOfCompanyIdAndListDepartmentId($companyId, [$modelDepartment->constructionDepartmentId()], $modelRank->manageRankId());
        return QcStaff::whereIn('staff_id', $listStaffId)->get();
    }

    # lay thong tin NV la  bo phan thi cong cap quan ly cua 1 cong ty dang hoat dong
    public function infoActivityStaffConstructionRankManage($companyId)
    {
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $listStaffId = $modelCompanyStaffWork->listStaffIdActivityOfCompanyIdAndListDepartmentId($companyId, [$modelDepartment->constructionDepartmentId()], $modelRank->manageRankId());
        return QcStaff::whereIn('staff_id', $listStaffId)->get();
    }

    //========= ========== ========== lay thong tin ========== ========== ==========
    public function selectInfoAll($listStaffId = null, $workStatus = 100)
    {
        $hFunction = new \Hfunction();
        # chon tat ca thong tin
        $getAllWorkStatus = $this->getDefaultAllWorkStatus();
        if ($hFunction->checkEmpty($listStaffId) && $workStatus == $getAllWorkStatus) {
            return QcStaff::select('*');
        } else {
            if (!$hFunction->checkEmpty($listStaffId)) {
                if ($workStatus == $getAllWorkStatus) {
                    return QcStaff::whereIn('staff_id', $listStaffId)->select('*');
                } else {
                    return QcStaff::whereIn('staff_id', $listStaffId)->where('workStatus', $workStatus)->select('*');
                }
            } else {
                if ($workStatus < $getAllWorkStatus) {
                    return QcStaff::where('workStatus', $workStatus)->select('*');
                }
            }
        }
    }


    # lay thong tin NV  nhan thong bao khi them don hang moi cua 1 cty (chi bo phan cap quan ly)
    public function infoStaffReceiveNotifyNewOrder($companyId)
    {
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $listStaffId = $modelCompanyStaffWork->listStaffIdActivityOfCompanyIdAndListDepartmentId($companyId, $modelDepartment->listIdReceiveNotifyNewOrder(), $modelRank->manageRankId());
        return QcStaff::whereIn('staff_id', $listStaffId)->get();
    }

    public function getInfo($staffId = '', $field = '')
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($staffId)) {
            return QcStaff::where('workStatus', $this->getDefaultHasWorkStatus())->get();
        } else {
            $result = QcStaff::where('staff_id', $staffId)->first();
            if ($hFunction->checkEmpty($field)) {
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


    # lay thong tin nv them tai khoan
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

    public function pluck($column, $objectId = null)
    {
        $hFunction = new \Hfunction();
        if ($hFunction->checkEmpty($objectId)) {
            return $this->$column;
        } else {
            return QcStaff::where('staff_id', $objectId)->pluck($column)[0];
        }
    }


    // total records
    public function totalRecords()
    {
        return QcStaff::count();
    }

    // last id
    public function lastId()
    {
        $hFunction = new \Hfunction();
        $result = QcStaff::orderBy('staff_id', 'DESC')->first();
        return ($hFunction->checkEmpty($result)) ? 0 : $result->staff_id;
    }

    //========== ========== ========= KIEM TRA THONG TIN ========== ========= =========
    public function checkLoginAdmin($staffId = null)
    {
        return ($this->level($staffId) > 3) ? false : true;
    }

    public function checkWorkStatus($staffId = null)
    {
        return ($this->workStatus($staffId) == $this->getDefaultHasWorkStatus()) ? true : false;
    }

    // exist of account
    public function existAccount($account)
    {
        return QcStaff::where('account', $account)->exists();
    }

    public function checkRootStatus($staffId = null)
    {
        return ($this->rootStatus($staffId) == $this->getDefaultHasRootStatus()) ? true : false;
    }

    public function checkRootManage($staffId = null)
    {
        if ($this->checkRootStatus($staffId) && $this->level($staffId) == 0) {
            return true;
        } else {
            return false;
        }
    }

    #======== KIEM TRA CAC BO PHAN CUA NV ===============
    #--------- --------- Bo phan QUAN LY ----------- --------
    // kiểm tra nv thuộc bộ phận quản lý
    public function checkManageDepartment($staffId = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        return $modelCompanyStaffWork->checkCurrentDepartmentManageOfStaff($this->checkIdNull($staffId));
    }

    // kiem tra nv quan ly cap quan ly
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

    #--------- --------- Bo phan  THI CONG ----------- --------
    # kiem tra NV thuoc bo phan thi cong
    public function checkConstructionDepartment($staffId = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        return $modelCompanyStaffWork->checkCurrentDepartmentConstructionOfStaff($this->checkIdNull($staffId));
    }

    # kiem tra bo phan thi cong cap quan ly cua 1 NV
    public function checkConstructionDepartmentAndManageRank($staffId = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        return $modelCompanyStaffWork->checkConstructionDepartmentAndManageRank($this->checkIdNull($staffId));
    }
    #--------- --------- Bo phan NHAN SU ----------- --------
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

    #--------- --------- Bo phan THIET KE ----------- --------
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

    #--------- --------- Bo phan KINH DOANH ----------- --------
    // kiem tra nv thuoc bo phan kinh doanh
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

    #--------- --------- Bo phan THU QUY ----------- --------
    //kiem tra bo phan thu quy
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

    // kiem tra nv bo phan thu quy cap nhan vien
    public function checkTreasureDepartmentAndNormalRank($staffId = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        return $modelCompanyStaffWork->checkTreasureDepartmentAndNormalRank($this->checkIdNull($staffId));
    }

    #============ =========== ============ STATISTICAL ============= =========== ==========
    //  ========== ========= ======== THONG KE THU ========= ========== ==========
    public function totalReceivedMoneyForCompany($staffId, $date = null)
    {
        //  tong tien nhan tu thu don hang
        $totalMoneyOrderPay = 0;// $this->totalReceiveMoneyFromOrderPay($staffId, $date);
        // tien duoc giao
        $totalReceivedMoneyOfStaffAndDate = $this->totalMoneyReceivedTransferOfStaffAndDate($staffId, $date);
        return $totalMoneyOrderPay + $totalReceivedMoneyOfStaffAndDate;
    }

    #TONG THU
    public function totalReceivedMoney($staffId, $date = null)
    {
        ///$companyId = $this->companyId($staffId);
        //  tong tien nhan tu thu don hang
        $totalMoneyOrderPay = $this->totalReceiveMoneyFromOrderPay($staffId, $date);

        //tong tien nhan tu thanh toan mua vat tu
        $totalMoneyImportPaidOfStaff = 0;// $this->totalMoneyImportOfStaffHasConfirmPay($staffId, $date, 1);

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
        return $modelTransfers->totalMoneyConfirmedOfReceivedStaffAndDate($staffId, $dateFilter);
    }

    //  ========== ================= THONG KE CHI ===================
    # tong chi
    public function totalPaidMoney($staffId, $date = null) # thong tien nv giư cua cty
    {
        #bien phi
        $totalPaidMoneyVariable = $this->totalPaidMoneyVariable($staffId, $date);
        #phi co dinh
        $totalPaidMoneyPermanent = $this->totalPaidMoneyPermanent($staffId, $date);
        return $totalPaidMoneyVariable + $totalPaidMoneyPermanent;
    }

    # bien phi
    public function totalPaidMoneyVariable($staffId, $date = null)
    {
        #chi ứng luong
        $totalMoneyPaidSalaryBeforePay = $this->totalMoneyPaidSalaryBeforePayOfStaffAndDate($staffId, $date);

        #chi thanh toan luong - da duoc thanh toan
        $totalMoneyPaidSalaryPay = $this->totalMoneyPaidSalaryPayOfStaffAndDateAndConfirmed($staffId, $date);

        #chi thanh toan mua vat tu - da duoc duyet
        $totalMoneyPayImport = $this->totalMoneyImportPayConfirmOfStaffAndDate($staffId, $date);
        #hoan tien don hang
        $totalPaidOrderCancelOfStaffAndDate = $this->totalPaidOrderCancelOfStaffAndDate($staffId, $date);

        return $totalMoneyPaidSalaryBeforePay + $totalMoneyPaidSalaryPay + $totalMoneyPayImport + $totalPaidOrderCancelOfStaffAndDate;
    }

    #phi co dinh
    public function totalPaidMoneyPermanent($staffId, $date = null)
    {
        #chi hoat dong - da duoc duyet
        $totalMoneyPayActivity = $this->totalMoneyPayActivityConfirmedAndInvalidOfStaff($staffId, $date);
        return $totalMoneyPayActivity;
    }


    # ------- -------- TONG CHI -------- ------
    ##tong tien chi mua vat tu / dung cu da duoc duỵet
    public function totalMoneyImportConfirmedAndAgreeOfStaff($staffId, $date = null, $payStatus = 3)#  $payStatus: 3_tat ca/ 1_da thanh toan/0_chua thanh toan
    {
        $modelImport = new QcImport();
        return $modelImport->totalMoneyImportConfirmedAndAgreeOfStaff($staffId, $date, $payStatus);
    }

    ##Chi hoat dong cty
    public function totalMoneyPayActivityConfirmedAndInvalidOfStaff($staffId, $date = null)
    {
        $modelPayActivityDetail = new QcPayActivityDetail();
        return $modelPayActivityDetail->totalMoneyConfirmedAndInvalidOfStaffAndDate($staffId, $date);
    }

    #tong tien chi ưng luong
    public function totalMoneyPaidSalaryBeforePayOfStaffAndDate($staffId, $dateFilter = null) // tồng tiền nhận
    {
        $modelSalaryBeforePay = new QcSalaryBeforePay();
        return $modelSalaryBeforePay->totalMoneyConfirmedOfStaffAndDate($staffId, $dateFilter);
    }

    ##tong tien huy don hàng
    public function totalPaidOrderCancelOfStaffAndDate($staffId, $dateFilter = null) // tồng tiền nhận
    {
        $modelOrderCancel = new QcOrderCancel();
        return $modelOrderCancel->totalPaymentOfStaffAndDate($staffId, $dateFilter);
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
        return $modelSalaryPay->totalMoneyConfirmedOfStaffAndDate($staffId, $dateFilter);
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
    # kiem tra ton tai bang cham cong dang hoat dong
    public function checkActivityWork($staffId = null)
    {
        $hFunction = new \Hfunction();
        return ($hFunction->checkCount($this->workInfoActivityOfStaff($this->checkIdNull($staffId)))) ? true : false;
    }

}
