<?php

namespace App\Http\Controllers\Ad3d\System\Staff;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\CompanyStaffWorkEnd\QcCompanyStaffWorkEnd;
use App\Models\Ad3d\Department\QcDepartment;
use App\Models\Ad3d\DepartmentStaff\QcDepartmentStaff;
use App\Models\Ad3d\Rank\QcRank;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use App\Models\Ad3d\StaffSalaryBasic\QcStaffSalaryBasic;
use App\Models\Ad3d\StaffWorkDepartment\QcStaffWorkDepartment;
use App\Models\Ad3d\StaffWorkMethod\QcStaffWorkMethod;
use App\Models\Ad3d\StaffWorkSalary\QcStaffWorkSalary;
use App\Models\Ad3d\ToolPackage\QcToolPackage;
use App\Models\Ad3d\Work\QcWork;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Input;
use File;
use DB;
use Request;

class StaffController extends Controller
{
    public function index($companyFilterId = 0, $actionStatus = 1)
    {
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $dataCompanyLogin = $modelStaff->companyLogin();
        $companyLoginId = $dataCompanyLogin->companyId();
        $companyFilterId = ($companyFilterId == 'null') ? null : $companyFilterId;
        if ($companyFilterId == null || $companyFilterId == 0) {
            $companyFilterId = $companyLoginId;
        }
        # lay thong tin cong ty cung he thong
        $dataCompany = $modelCompany->getInfoSameSystemOfCompany($companyLoginId);
        # lay thong tin lam viec cua nv tai cty dang truy cap
        $dataCompanyStaffWork = $modelCompanyStaffWork->selectInfoOfCompanyAndActionStatus($companyFilterId, $actionStatus)->paginate(30);
        if ($actionStatus == 1) {
            $dataAccess = [
                'accessObject' => 'staff',
                'subObject' => 'staffOn'
            ];
            return view('ad3d.system.staff.list-on', compact('modelStaff', 'dataCompanyStaffWork', 'dataCompany', 'dataAccess', 'dataStaff', 'companyFilterId', 'actionStatus'));
        } else {
            $dataAccess = [
                'accessObject' => 'staff',
                'subObject' => 'staffOff'
            ];
            return view('ad3d.system.staff.list-off', compact('modelStaff', 'dataCompanyStaffWork', 'dataCompany', 'dataAccess', 'dataStaff', 'companyFilterId', 'actionStatus'));
        }


    }

    # thong tin thong ke
    public function getStatistical($companyStaffWorkId)
    {
        $hFunction = new \Hfunction();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelStaff = new QcStaff();
        $dataCompanyStaffWork = $modelCompanyStaffWork->getInfo($companyStaffWorkId);
        $dataAccess = [
            'accessObject' => 'staff',
            'subObject' => 'staffOn'
        ];
        if ($hFunction->checkCount($dataCompanyStaffWork)) {
            $dataStaff = $dataCompanyStaffWork->staff;
        } else {
            $dataStaff = null;
        }
        return view('ad3d.system.staff.statistical', compact('modelStaff', 'dataCompanyStaffWork', 'dataStaff', 'dataAccess'));
    }

    # them
    public function getAdd()
    {
        $modelStaff = new QcStaff();
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $dataDepartment = $modelDepartment->getInfo();
        $dataRank = $modelRank->getInfo();
        $dataAccess = [
            'accessObject' => 'staff',
            'subObject' => 'staffOn'
        ];
        return view('ad3d.system.staff.add', compact('modelStaff', 'dataDepartment', 'dataRank', 'dataAccess'));
    }

    public function getAddDepartment()
    {
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $dataDepartment = $modelDepartment->getInfo();
        $dataRank = $modelRank->getInfo();
        return view('ad3d.system.staff.add-department', compact('dataDepartment', 'dataRank'));
    }

    public function postAdd()
    {
        $hFunction = new \Hfunction();
        $modelRank = new QcRank();
        $modelStaff = new QcStaff();
        $modelDepartment = new QcDepartment();
        $modelToolPackage = new QcToolPackage();
        $modelStaffWorkMethod = new QcStaffWorkMethod();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $modelStaffWorkSalary = new QcStaffWorkSalary();
        $modelWork = new QcWork();
        $staffLoginId = $modelStaff->loginStaffId();
        $firstName = Request::input('txtFirstName');
        $lastName = Request::input('txtLastName');
        $txtIdentityCard = Request::input('txtIdentityCard');
        $birthDay = Request::input('txtBirthday');
        $gender = Request::input('cbGender');
        $phone = Request::input('txtPhone');
        $address = Request::input('txtAddress');
        $email = Request::input('txtEmail');
        $fromDateWork = Request::input('txtDateWork');
        $txtImage = Request::file('txtImage');
        $txtIdentityCardFront = Request::file('txtIdentityCardFront');
        $txtIdentityCardBack = Request::file('txtIdentityCardBack');

        #thong tin lam viec
        $level = Request::input('cbLevel');
        $companyId = Request::input('cbCompany');
        //$cbDepartment = Request::input('cbDepartment');
        $cbRank = Request::input('cbRank');
        $cbPermission = Request::input('cbPermission');
        $cbWorkMethod = Request::input('cbWorkMethod');
        $cbApplyRule = Request::input('cbApplyRule');

        # thong tin luong
        $txtTotalSalary = Request::input('txtTotalSalary');
        $txtTotalSalary = $hFunction->convertCurrencyToInt($txtTotalSalary);

        $txtSalary = Request::input('txtSalary');
        $txtSalary = $hFunction->convertCurrencyToInt($txtSalary);
        $txtResponsibility = Request::input('txtResponsibility');
        $txtResponsibility = $hFunction->convertCurrencyToInt($txtResponsibility);
        $txtInsurance = 21.5;//Request::input('txtInsurance'); //mmac dinh la 21.5%
        $txtUsePhone = Request::input('txtUsePhone');
        $txtUsePhone = $hFunction->convertCurrencyToInt($txtUsePhone);
        $txtFuel = Request::input('txtFuel');
        $txtFuel = $hFunction->convertCurrencyToInt($txtFuel);
        $txtDateOff = 1;// Request::input('txtDateOff');
        //$txtDateOff = $hFunction->convertCurrencyToInt($txtDateOff); //mac dinh 1 ngay
        $txtOvertimeHour = Request::input('txtOvertimeHour');
        $txtOvertimeHour = $hFunction->convertCurrencyToInt($txtOvertimeHour);

        // thong tin thanh toan
        $txtBankAccount = Request::input('txtBankAccount');
        $cbBankName = Request::input('cbBankName');

        #tai khoan dang nhap mac dinh
        $account = $txtIdentityCard;
        if ($modelStaff->existAccount($account)) { # exists account
            return Session::put('notifyAdd', "Thêm thất bại, tài khỏan '$account' tồn tại.");
        }
        $name_img = null;
        $name_img_front = null;
        $name_img_back = null;

        # lay danh sach bo phan
        $dataDepartment = $modelDepartment->getInfo();
        if (count($txtImage) > 0) {
            $name_img = stripslashes($_FILES['txtImage']['name']);
            $name_img = 'avatar' . $hFunction->getTimeCode() . '.' . $hFunction->getTypeImg($name_img);
            $source_img = $_FILES['txtImage']['tmp_name'];
            if (!$modelStaff->uploadImage($source_img, $name_img)) $name_img = null;
        }
        if (count($txtIdentityCardFront) > 0) {
            $name_img_front = stripslashes($_FILES['txtIdentityCardFront']['name']);
            $name_img_front = 'identity_front' . $hFunction->getTimeCode() . '.' . $hFunction->getTypeImg($name_img_front);
            $source_img = $_FILES['txtIdentityCardFront']['tmp_name'];
            if (!$modelStaff->uploadImage($source_img, $name_img_front)) $name_img_front = null;
        }
        if (count($txtIdentityCardBack) > 0) {
            $name_img_back = stripslashes($_FILES['txtIdentityCardBack']['name']);
            $name_img_back = 'identity_back' . $hFunction->getTimeCode() . '.' . $hFunction->getTypeImg($name_img_back);
            $source_img = $_FILES['txtIdentityCardBack']['tmp_name'];
            if (!$modelStaff->uploadImage($source_img, $name_img_back)) $name_img_back = null;
        }
        if ($modelStaff->insert($firstName, $lastName, $txtIdentityCard, $account, $birthDay, $gender, $name_img, $name_img_front, $name_img_back, $email, $address, $phone, $level, $txtBankAccount, $cbBankName)) {
            $newStaffId = $modelStaff->insertGetId();
            #them vao cong ty lam viec
            if ($modelCompanyStaffWork->insert($fromDateWork, $level, $newStaffId, $staffLoginId, $companyId)) {
                $newWorkId = $modelCompanyStaffWork->insertGetId();
                # them vi tri lam viec
                if ($hFunction->checkCount($dataDepartment)) {
                    foreach ($dataDepartment as $department) {
                        $departmentId = $department->departmentId();
                        $rankId = null;
                        $manageRank = Request::input('chkDepartmentManageRank_' . $departmentId);
                        # xet co chon cap quan ly - uu tien cap quan ly
                        if ($manageRank) {
                            $rankId = $modelRank->manageRankId();
                        } else {
                            # xet co chon cap nhan vien
                            $staffRank = Request::input('chkDepartmentStaffRank_' . $departmentId);
                            if ($staffRank) $rankId = $modelRank->staffRankId();
                        }
                        // co chon vi tri lam viẹc
                        if (!empty($rankId)) $modelStaffWorkDepartment->insert($newWorkId, $departmentId, $rankId, $fromDateWork);
                        // neu la bo phan thi cong thi phat tui do nghe
                        if ($modelDepartment->checkConstruction($departmentId)) {
                            # giao do nghe
                            $modelToolPackage->allocationForCompanyStaffWork($newWorkId);
                        }
                    }
                }

                # them luong cho nv
                $modelStaffWorkSalary->insert($txtTotalSalary, $txtSalary, $txtResponsibility, $txtUsePhone, $txtInsurance, $txtFuel, $txtDateOff, $txtOvertimeHour, $newWorkId);
                # thêm thông tin làm việc
                $toDateWork = $hFunction->lastDateOfMonthFromDate($fromDateWork);
                $modelWork->insert($fromDateWork, $toDateWork, $newWorkId);
            }

            # them phương thuc lam viec
            $modelStaffWorkMethod->insert($cbWorkMethod, $cbApplyRule, $newStaffId, $staffLoginId);

            return Session::put('notifyAdd', 'Thêm thành công');
        } else {
            return Session::put('notifyAdd', 'Thêm thất bại, Hãy kiểm tra lại thông tin nhập');
        }

    }

    //quan ly thong tin nhan vien
    public function getInfo($staffId = null)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $dataCompany = $modelCompany->getInfo();
        $dataDepartment = $modelDepartment->getInfo();
        $dataStaff = $modelStaff->getInfo($staffId);
        $dataRank = $modelRank->getInfo();
        if ($hFunction->checkCount($dataStaff)) {
            return view('ad3d.system.staff.info', compact('modelStaff', 'modelRank', 'dataStaff', 'dataCompany', 'dataDepartment', 'dataRank'));
        }
    }

    # ---------- ------- cap nhat thong tin co ban ---------- --------------
    public function getInfoBasicEdit($staffId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $dataStaff = $modelStaff->getInfo($staffId);
        if ($hFunction->checkCount($dataStaff)) {
            return view('ad3d.system.staff.info-basic-edit', compact('modelStaff', 'dataStaff'));
        }
    }

    public function postInfoBasicEdit($staffId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $firstName = Request::input('txtFirstName');
        $lastName = Request::input('txtLastName');
        $txtIdentityCard = Request::input('txtIdentityCard');
        $birthDay = Request::input('txtBirthday');
        $gender = Request::input('cbGender');
        $phone = Request::input('txtPhone');
        $address = Request::input('txtAddress');
        $email = Request::input('txtEmail');
        if (!$modelStaff->updateInfo($staffId, $firstName, $lastName, $txtIdentityCard, $birthDay, $gender, $email, $address, $phone)) {
            return 'Hệ thống đang cập nhật';
        }
    }

    //-------- ------------- cap nhat thong tin lam viec ------------- ----------------
    public function getCompanyWorkEdit($staffId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $dataDepartment = $modelDepartment->getInfo();
        $dataStaff = $modelStaff->getInfo($staffId);
        $dataRank = $modelRank->getInfo();
        if ($hFunction->checkCount($dataStaff)) {
            return view('ad3d.system.staff.info-work-edit', compact('modelStaff', 'modelRank', 'dataStaff', 'dataDepartment', 'dataRank'));
        }
    }

    public function postCompanyWorkEdit($staffId)
    {
        $hFunction = new \Hfunction();
        $modelRank = new QcRank();
        $modelDepartment = new QcDepartment();
        $modelStaff = new QcStaff();
        $modelWork = new QcWork();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $modelStaffWorkMethod = new QcStaffWorkMethod();
        $modelStaffWorkSalary = new QcStaffWorkSalary();
        $modelCompanyStaffWorkEnd = new QcCompanyStaffWorkEnd();

        $staffLoginId = $modelStaff->loginStaffId();

        $companyId = Request::input('cbCompany');
        $txtDateWork = Request::input('txtDateWork');
        $level = Request::input('cbLevel');
        $cbDepartment = Request::input('cbDepartment');
        $cbRank = Request::input('cbRank');
        $cbPermission = Request::input('cbPermission');
        //$salaryStatus = Request::input('salaryStatus');
        $cbWorkMethod = Request::input('cbWorkMethod');
        $cbApplyRule = Request::input('cbApplyRule');

        # lay danh sach bo phan
        $dataDepartment = $modelDepartment->getInfo();
        $manageRankId = $modelRank->manageRankId();
        $staffRankId = $modelRank->staffRankId();

        # thay doi phương thuc lam viec
        if (!$modelStaffWorkMethod->checkExistActivityMethodApplyRuleOfStaff($staffId, $cbWorkMethod, $cbApplyRule)) { # thong tin moi
            $modelStaffWorkMethod->disableInfoActivity($staffId);# vo hieu thong tin cũ
            $modelStaffWorkMethod->insert($cbWorkMethod, $cbApplyRule, $staffId, $staffLoginId);
        }

        # lay thong tin cty cu dang hoat dong
        $dataCompanyStaffWorkOld = $modelCompanyStaffWork->infoActivityOfStaff($staffId);
        if ($hFunction->checkCount($dataCompanyStaffWorkOld)) {
            $oldCompanyStaffWorkId = $dataCompanyStaffWorkOld->workId();
            $oldCompanyId = $dataCompanyStaffWorkOld->companyId();
            # lam lai cong ty cu
            if ($oldCompanyId == $companyId) {
                # cap nhat level truy cap admin
                $modelCompanyStaffWork->updateLevel($level, $oldCompanyStaffWorkId);
                # lay thong tin vi tri lam cu
                $dataStaffWorkDepartment = $dataCompanyStaffWorkOld->staffWorkDepartmentInfoActivity();
                # duỵet danh sach bo phan cua he thong
                if ($hFunction->checkCount($dataDepartment)) {
                    foreach ($dataDepartment as $department) {
                        $departmentId = $department->departmentId();
                        $rankId = null;
                        $manageRank = Request::input('chkDepartmentManageRank_' . $departmentId);
                        # lay cap bac duoc chon khi cap nhat
                        # xet co chon cap quan ly - uu tien cap quan ly
                        if ($manageRank) {
                            $rankId = $manageRankId;
                        } else {
                            # xet co chon cap nhan vien
                            $staffRank = Request::input('chkDepartmentStaffRank_' . $departmentId);
                            if ($staffRank) $rankId = $staffRankId;
                        }
                        # bo phan khong duoc chon - khi khong co chon cap quan ly/cap nhan vien (RankId = null)
                        if ($hFunction->checkEmpty($rankId)) {
                            if ($hFunction->checkCount($dataStaffWorkDepartment)) {
                                # duyet vi tri lam cu
                                foreach ($dataStaffWorkDepartment as $staffWorkDepartment) {
                                    $oldDepartmentId = $staffWorkDepartment->departmentId();
                                    #  vị tri cu duoc chon ma vi tri moi khong duoc chon
                                    if ($departmentId == $oldDepartmentId) {
                                        # vo hieu hoa vi tri cu
                                        $modelStaffWorkDepartment->disableWorkDepartment($oldCompanyStaffWorkId, $oldDepartmentId);
                                    }
                                }
                            }
                        } else {
                            # bo phan duoc chon
                            if ($hFunction->checkCount($dataStaffWorkDepartment)) {
                                # duyet vi tri lam cu
                                foreach ($dataStaffWorkDepartment as $staffWorkDepartment) {
                                    $oldDepartmentId = $staffWorkDepartment->departmentId();
                                    $oldRankId = $staffWorkDepartment->rankId();
                                    if ($departmentId == $oldDepartmentId) { # lam lai bo phan cu
                                        if ($rankId != $oldRankId) { # thay doi vi tri / tu cap quan ly sang cap nhan vien hoac nguoc lai
                                            # vo hieu hoa vi tri cu
                                            $modelStaffWorkDepartment->disableWorkDepartment($oldCompanyStaffWorkId, $departmentId);
                                            # thay vi tri moi
                                            if (!$modelStaffWorkDepartment->checkExistWorkActivityOfDepartmentAndRank($oldCompanyStaffWorkId, $departmentId, $rankId)) {
                                                $modelStaffWorkDepartment->insert($oldCompanyStaffWorkId, $departmentId, $rankId, $hFunction->carbonNow());
                                            }
                                        }
                                    } else {
                                        # lam bo phan moi
                                        if (!$modelStaffWorkDepartment->checkExistWorkActivityOfDepartmentAndRank($oldCompanyStaffWorkId, $departmentId, $rankId)) {
                                            $modelStaffWorkDepartment->insert($oldCompanyStaffWorkId, $departmentId, $rankId, $hFunction->carbonNow());
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                # thay doi cty lam viec
                $oldDataStaffWorkSalary = $modelStaffWorkSalary->infoActivityOfWork($oldCompanyStaffWorkId); # bang luong cu cua cty o cty cu
                if ($modelCompanyStaffWork->updateEndWork($oldCompanyStaffWorkId)) { # vo hieu hoa lam viec o cty cu
                    # TINH LUONG O CTY CU
                    $oldDataWork = $modelWork->infoActivityOfCompanyStaffWork($oldCompanyStaffWorkId); # bang cham cong cu
                    $oldDataWork->endWork(); # vo hieu hoa bang cham cong cu
                    # luu lai ly do dung lam viec
                    $modelCompanyStaffWorkEnd->insert($txtDateWork, 'Chuyển công tác', $oldCompanyStaffWorkId, $staffLoginId);
                    # vo hieu hoa bang luong cu
                    $modelStaffWorkSalary->disableOfWork($oldCompanyStaffWorkId);
                    # them thong tin lam viec tai cty moi
                    if ($modelCompanyStaffWork->insert($txtDateWork, $level, $staffId, $staffLoginId, $companyId)) {
                        $newCompanyStaffWorkId = $modelCompanyStaffWork->insertGetId();
                        # them bo phan lam viec tai cty moi
                        if ($hFunction->checkCount($dataDepartment)) {
                            foreach ($dataDepartment as $department) {
                                $departmentId = $department->departmentId();
                                $rankId = null;
                                $manageRank = Request::input('chkDepartmentManageRank_' . $departmentId);
                                # lay cap bac duoc chon khi cap nhat
                                # xet co chon cap quan ly - uu tien cap quan ly
                                if ($manageRank) {
                                    $rankId = $modelRank->manageRankId();
                                } else {
                                    # xet co chon cap nhan vien
                                    $staffRank = Request::input('chkDepartmentStaffRank_' . $departmentId);
                                    if ($staffRank) $rankId = $modelRank->staffRankId();
                                }
                                # bo phan khong duoc chon - khi khong co chon cap quan ly/cap nhan vien (RankId = null)
                                if (!$hFunction->checkEmpty($rankId)) {
                                    # kiem tra thong tin truoc khi them
                                    if (!$modelStaffWorkDepartment->checkExistWorkActivityOfDepartmentAndRank($newCompanyStaffWorkId, $departmentId, $rankId)) {
                                        $modelStaffWorkDepartment->insert($newCompanyStaffWorkId, $departmentId, $rankId, $hFunction->carbonNow());
                                    }

                                }
                            }
                        }


                        #them bang luong moi theo bang luong cua cty cu
                        $modelStaffWorkSalary->insert($oldDataStaffWorkSalary->totalSalary(), $oldDataStaffWorkSalary->salary(), $oldDataStaffWorkSalary->responsibility(), $oldDataStaffWorkSalary->usePhone(), $oldDataStaffWorkSalary->insurance(), $oldDataStaffWorkSalary->fuel(), $oldDataStaffWorkSalary->dateOff(), $oldDataStaffWorkSalary->overtimeHour(), $newCompanyStaffWorkId);
                        #---------  lap bang cham cong moi -----
                        $currentDate = date('Y-m-d');
                        $toDateWork = $hFunction->lastDateOfMonthFromDate($currentDate);
                        $modelWork->insert($currentDate, $toDateWork, $newCompanyStaffWorkId);
                    }

                }
            }

        } else {
            # them moi khi ko ton tai lam viec tai cty
            $dataStaffOld = $modelStaff->getInfo($staffId);
            # THONG TIN LUONG PHIEN BAN CU NEU CO
            $oldDataWork = $modelWork->infoActivityOfStaff($staffId);
            if ($modelCompanyStaffWork->insert($txtDateWork, $level, $staffId, $staffLoginId, $companyId)) {
                $newCompanyStaffWorkId = $modelCompanyStaffWork->insertGetId();
                //them bo phan cho NV
                if (count($cbDepartment) > 0) {
                    foreach ($cbDepartment as $key => $value) {
                        $departmentId = $value;
                        $rankId = $cbRank[$key];
                        $permission = $cbPermission[$key];
                        $modelStaffWorkDepartment->insert($hFunction->carbonNow(), $permission, $newCompanyStaffWorkId, $departmentId, $rankId);
                    }
                }

                # them bang cham cong moi
                $currentDate = date('Y-m-d');
                $toDateWork = $hFunction->lastDateOfMonthFromDate($currentDate);
                $modelWork->insert($currentDate, $toDateWork, $newCompanyStaffWorkId);
                # TINH LUONG PHIEN BAN CU NEU CO
                if (count($oldDataWork) > 0) {
                    $modelWork->endWork($oldDataWork->workId()); # vo hieu hoa bang cham cong cu
                }
                #Them luong cho nhan vien
                $salaryBasicOld = $dataStaffOld->salaryBasicOfStaff();
                $salaryBasicOld = (is_array($salaryBasicOld)) ? $salaryBasicOld[0] : $salaryBasicOld;
                $modelStaffWorkSalary->insert($salaryBasicOld, 0, 0, 0, 0, 1, 10000, $newCompanyStaffWorkId);
            }
        }
        return redirect()->back();
    }

    //-------- -------------  sửa thông tin lương -------- -------------
    public function getCompanySalaryEdit($staffId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $dataCompany = $modelCompany->getInfo();
        $dataDepartment = $modelDepartment->getInfo();
        $dataStaff = $modelStaff->getInfo($staffId);
        $dataRank = $modelRank->getInfo();
        if ($hFunction->checkCount($dataStaff)) {
            return view('ad3d.system.staff.info-salary-edit', compact('modelStaff', 'dataStaff', 'dataCompany', 'dataDepartment', 'dataRank'));
        }
    }

    public function postCompanySalaryEdit($staffId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelStaffWorkSalary = new QcStaffWorkSalary();
        $dataCompanyStaffWork = $modelCompanyStaffWork->infoActivityOfStaff($staffId);
        # thong tin luong
        $txtTotalSalary = Request::input('txtTotalSalary');
        $txtTotalSalary = $hFunction->convertCurrencyToInt($txtTotalSalary);
        $txtSalary = Request::input('txtSalary');
        $txtSalary = $hFunction->convertCurrencyToInt($txtSalary);
        $txtResponsibility = Request::input('txtResponsibility');
        $txtResponsibility = $hFunction->convertCurrencyToInt($txtResponsibility);
        $txtInsurance = 21.5;// Request::input('txtInsurance');
        $txtUsePhone = Request::input('txtUsePhone');
        $txtUsePhone = $hFunction->convertCurrencyToInt($txtUsePhone);
        $txtFuel = Request::input('txtFuel');
        $txtFuel = $hFunction->convertCurrencyToInt($txtFuel);
        $txtDateOff = 1;// Request::input('txtDateOff'); // mac dinh nghi 1 ngay
        //$txtDateOff = $hFunction->convertCurrencyToInt($txtDateOff);
        $txtOvertimeHour = Request::input('txtOvertimeHour');
        $txtOvertimeHour = $hFunction->convertCurrencyToInt($txtOvertimeHour);

        // cap nhat tk ngan hang
        $txtBankAccount = Request::input('txtBankAccount');
        $cbBankName = Request::input('cbBankName');
        $modelStaff->updateBankAccount($staffId, $txtBankAccount, $cbBankName);

        // cap nhat luong
        if (count($dataCompanyStaffWork) > 0) {
            $companyStaffWorkId = $dataCompanyStaffWork->workId();
            $oldStaffWorkSalary = $dataCompanyStaffWork->staffWorkSalaryActivity($companyStaffWorkId);
            if ($modelStaffWorkSalary->insert($txtTotalSalary, $txtSalary, $txtResponsibility, $txtUsePhone, $txtInsurance, $txtFuel, $txtDateOff, $txtOvertimeHour, $companyStaffWorkId)) {
                if (count($oldStaffWorkSalary) > 0) $oldStaffWorkSalary->disableStaffWorkSalary();
            } else {
                return "Hệ thống đang bảo trì";
            }
        } else {
            return "Phải phân bổ nơi làm việc";
        }
    }


    //=========== ========== thay đổi mật khẩu =========== ================
    public function getChangePass()
    {
        $modelStaff = new QcStaff();
        $dataStaff = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'accessObject' => 'staff'
        ];
        return view('ad3d.system.staff.change-pass', compact('modelStaff', 'dataStaff', 'dataAccess'));
    }

    public function postChangePass()
    {
        $modelStaff = new QcStaff();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $txtOldPass = Request::input('txtOldPass');
        $txtNewPass = Request::input('txtNewPass');
        $staffLoginId = $dataStaffLogin->staffId();
        if ($modelStaff->checkPassOfStaff($staffLoginId, $txtOldPass)) {
            if ($modelStaff->changeAccountAndPassword($staffLoginId, $dataStaffLogin->account(), $txtNewPass)) {
                $modelStaff->logout();
                return redirect()->route('qc.ad3d.login.get');
            } else {
                return Session::put('notifyChangePass', 'Tính năng đang bảo trì');
            }
        } else {
            return Session::put('notifyChangePass', 'Mật khẩu không đúng');
        }
    }

    // thay đổi tài khoản
    public function getChangeAccount()
    {
        $modelStaff = new QcStaff();
        $dataStaff = $modelStaff->loginStaffInfo();
        $dataAccess = [
            'accessObject' => 'staff'
        ];
        return view('ad3d.system.staff.change-account', compact('modelStaff', 'dataStaff', 'dataAccess'));
    }

    public function postChangeAccount()
    {
        $modelStaff = new QcStaff();
        $dataStaffLogin = $modelStaff->loginStaffInfo();
        $txtNewAccount = Request::input('txtNewAccount');
        $txtConfirmPass = Request::input('txtConfirmPass');
        $staffLoginId = $dataStaffLogin->staffId();
        if ($modelStaff->checkPassOfStaff($staffLoginId, $txtConfirmPass)) {
            if ($modelStaff->changeAccountAndPassword($staffLoginId, $txtNewAccount, $txtConfirmPass)) {
                $modelStaff->logout();
                return redirect()->route('qc.ad3d.login.get');
            } else {
                return Session::put('notifyChangeAccount', 'Tính năng đang bảo trì');
            }

        } else {
            return Session::put('notifyChangeAccount', 'Mật khẩu không đúng');
        }
    }

    //lấy lại mật khẩu mặc định
    public function resetPassWord($staffId)
    {
        $modelStaff = new QcStaff();
        $dataStaff = $modelStaff->getInfo($staffId);
        return view('ad3d.system.staff.reset-password', compact('dataStaff'));
    }

    public function postResetPassWord()
    {
        $modelStaff = new QcStaff();
        $staffId = Request::input('txtStaff');
        $modelStaff->resetPass($staffId);
    }

    //=========== ================ mo cham cong =========== ================
    public function openWork($companyStaffWorkId)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelCompanyStaffWork->openWork($companyStaffWorkId);
    }

    public function restoreWork($companyStaffWorkId)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        return $modelCompanyStaffWork->restoreWork($companyStaffWorkId);
    }

    //them hinh anh
    public function getAddImage($staffId)
    {
        $modelStaff = new QcStaff();
        $dataStaff = $modelStaff->getInfo($staffId);
        return view('ad3d.system.staff.add-image', compact('dataStaff'));
    }

    public function postAddImage($staffId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $txtImage = Request::file('txtImage');
        $txtIdentityCardFront = Request::file('txtIdentityCardFront');
        $txtIdentityCardBack = Request::file('txtIdentityCardBack');
        $dataStaff = $modelStaff->getInfo($staffId);
        if ($modelStaff->checkLogin()) {
            if (count($txtImage) > 0) {
                $name_img = stripslashes($_FILES['txtImage']['name']);
                $name_img = 'avatar' . $hFunction->getTimeCode() . '.' . $hFunction->getTypeImg($name_img);
                $source_img = $_FILES['txtImage']['tmp_name'];
                if ($modelStaff->uploadImage($source_img, $name_img)) {
                    $oldImage = $dataStaff->image();
                    if ($modelStaff->updateImage($staffId, $name_img)) {
                        if (!empty($oldImage)) $modelStaff->dropImage($oldImage);
                    } else {
                        $modelStaff->dropImage($name_img);
                    }
                }
            }
            if (count($txtIdentityCardFront) > 0) {
                $name_img_front = stripslashes($_FILES['txtIdentityCardFront']['name']);
                $name_img_front = 'identity_front' . $hFunction->getTimeCode() . '.' . $hFunction->getTypeImg($name_img_front);
                $source_img = $_FILES['txtIdentityCardFront']['tmp_name'];
                if ($modelStaff->uploadImage($source_img, $name_img_front)) {
                    $oldImageFront = $dataStaff->identityCardFront();
                    if ($modelStaff->updateIdentityCardFront($staffId, $name_img_front)) {
                        if (!empty($oldImageFront)) $modelStaff->dropImage($oldImageFront);
                    } else {
                        $modelStaff->dropImage($name_img_front);
                    }
                }
            }
            if (count($txtIdentityCardBack) > 0) {
                $name_img_back = stripslashes($_FILES['txtIdentityCardBack']['name']);
                $name_img_back = 'identity_back' . $hFunction->getTimeCode() . '.' . $hFunction->getTypeImg($name_img_back);
                $source_img = $_FILES['txtIdentityCardBack']['tmp_name'];
                if ($modelStaff->uploadImage($source_img, $name_img_back)) {
                    $oldImageBack = $dataStaff->identityCardBack();
                    if ($modelStaff->updateIdentityCardBack($staffId, $name_img_back)) {
                        if (!empty($oldImageBack)) $modelStaff->dropImage($oldImageBack);
                    } else {
                        $modelStaff->dropImage($name_img_back);
                    }
                }
            }
        }
    }

    // xoa hinh anh
    public function deleteImage($staffId, $type)
    {
        $modelStaff = new QcStaff();
        $dataStaff = $modelStaff->getInfo($staffId);
        $image = $dataStaff->image();
        $identityCardFront = $dataStaff->identityCardFront();
        $identityCardBack = $dataStaff->identityCardBack();
        if ($type == 'avatar') {
            if ($modelStaff->updateImage($staffId, null)) $modelStaff->dropImage($image);
        } elseif ($type == 'identityCardFront') {
            if ($modelStaff->updateIdentityCardFront($staffId, null)) $modelStaff->dropImage($identityCardFront);
        } elseif ($type == 'identityCardBack') {
            if ($modelStaff->updateIdentityCardBack($staffId, null)) $modelStaff->dropImage($identityCardBack);
        }
    }

    //xóa
    public function deleteStaff($staffId = null)
    {
        $modelStaff = new QcStaff();
        if (!empty($staffId)) {
            return $modelStaff->actionDelete($staffId);
        }
    }

    #======= ==========
}
