<?php

namespace App\Http\Controllers\Ad3d\System\Company;

use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\Department\QcDepartment;
use App\Models\Ad3d\Rank\QcRank;
use App\Models\Ad3d\Staff\QcStaff;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ad3d\StaffWorkDepartment\QcStaffWorkDepartment;
use App\Models\Ad3d\StaffWorkMethod\QcStaffWorkMethod;
use App\Models\Ad3d\Work\QcWork;
use Illuminate\Support\Facades\Session;
use Input;
use File;
use DB;
use Request;

class CompanyController extends Controller
{
    public function index()
    {
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $dataCompanyLogin = $modelStaff->companyLogin();
        $companyLoginId = $dataCompanyLogin->companyId();
        $dataAccess = [
            'accessObject' => 'company',
            'subObject' => 'company'
        ];
        # lay thong tin cong ty cung he thong
        $dataCompany = $modelCompany->selectInfoSameSystemOfCompany($companyLoginId)->paginate(30);
        return view('ad3d.system.company.company.list', compact('modelStaff', 'modelCompany', 'dataCompany', 'dataAccess'));

    }

    # cap nhat nguoi quan lý
    public function getUpdateManager($companyId, $selectObject = null)
    {
        $hFunction = new \Hfunction();
        $modelCompany = new QcCompany();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelStaff = new QcStaff();
        $dataAccess = [
            'accessObject' => 'company',
            'subObject' => 'company'
        ];
        $dataCompany = $modelCompany->getInfo($companyId);
        $selectObject = ($hFunction->checkNull($selectObject)) ? 'selectSystem' : $selectObject;
        if ($hFunction->checkCount($dataCompany) && !$hFunction->checkEmpty($selectObject)) {
            # nguoi quan ly hien tai
            $dataOldCompanyStaffWorkRoot = $dataCompany->companyStaffWorkActivityLevelRoot();
            if ($hFunction->checkCount($dataOldCompanyStaffWorkRoot)) {
                $oldRootWorkId[] = $dataOldCompanyStaffWorkRoot->workId();
            } else {
                # chua co lanh dao
                $oldRootWorkId = $hFunction->getDefaultNull();
            }
            $dataCompanyStaffWork = $modelCompanyStaffWork->getActivityInfoOfCompanyAndExceptListWork($companyId, $oldRootWorkId);
            return view('ad3d.system.company.company.add-manager', compact('modelStaff', 'dataAccess', 'dataCompany', 'dataCompanyStaffWork', 'selectObject'));
        } else {
            return redirect()->back();
        }

    }

    # cap nhat nguoi quan lý
    public function postUpdateManager($companyId)
    {
        $hFunction = new \Hfunction();
        $modelCompany = new QcCompany();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelDepartment = new QcDepartment();
        $modelRank = new QcRank();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $modelStaff = new QcStaff();
        $modelWork = new QcWork();
        $modelStaffWorkMethod = new QcStaffWorkMethod();
        $staffLoginId = $modelStaff->loginStaffId();
        # thong tin nguoi lanh dao cu cua cty
        $dataOldCompanyStaffWorkRoot = $modelCompany->companyStaffWorkActivityLevelRoot($companyId);
        # cap quan ly
        $manageRankId = $modelRank->manageRankId();
        # bo phan quan ly
        $manageDepartmentId = $modelDepartment->manageDepartmentId();
        $txtSelectObject = Request::input('txtSelectObject');
        if ($txtSelectObject == 'selectNew') {
            # thong tin nguoi quan ly
            $txtFirstName = Request::input('txtFirstName');
            $txtLastName = Request::input('txtLastName');
            $txtIdentityCard = Request::input('txtIdentityCard');
            $cbGender = Request::input('cbGender');
            $txtStaffPhone = Request::input('txtStaffPhone');
            $txtStaffAddress = Request::input('txtStaffAddress');
            $txtStaffEmail = Request::input('txtStaffEmail');
            if ($modelStaff->existIdentityCard($txtIdentityCard)) {
                Session::put('notifyAdd', "Chứng minh thư <b>'$txtIdentityCard'</b> đã tồn tại.");
                return redirect()->back();
            } else {
                $staffAccount = $txtIdentityCard;
                # lay gia tri mac dinh
                $staffBirthday = $modelStaff->getDefaultBirthday();
                $staffImage = $modelStaff->getDefaultImage();
                $identityCardFront = $modelStaff->getDefaultIdentityCardFront();
                $identityCardBack = $modelStaff->getDefaultIdentityCardBack();
                $bankAccount = $modelStaff->getDefaultBankAccount();
                $bankName = $modelStaff->getDefaultBankName();
                if ($modelStaff->insert($txtFirstName, $txtLastName, $txtIdentityCard, $staffAccount, $staffBirthday, $cbGender, $staffImage, $identityCardFront, $identityCardBack, $txtStaffEmail, $txtStaffAddress, $txtStaffPhone, $bankAccount, $bankName)) {
                    $newStaffId = $modelStaff->insertGetId();
                    # them vao cong ty lam viec
                    $fromDateWork = $hFunction->carbonNow();
                    $level = $modelCompanyStaffWork->getDefaultLevelRoot();
                    if ($modelCompanyStaffWork->insert($fromDateWork, $level, $newStaffId, $staffLoginId, $companyId)) {
                        $newWorkId = $modelCompanyStaffWork->insertGetId();
                        # them bo phan lam viec
                        if ($modelStaffWorkDepartment->insert($newWorkId, $manageDepartmentId, $manageRankId, $fromDateWork)) {
                            # neu ton tai lanh dao cu
                            if ($hFunction->checkCount($dataOldCompanyStaffWorkRoot)) $modelCompanyStaffWork->downLevelRoot($dataOldCompanyStaffWorkRoot->workId());
                        }

                        # them thong tin bang cham cong
                        $toDateWork = $hFunction->lastDateOfMonthFromDate($fromDateWork);
                        $modelWork->insert($fromDateWork, $toDateWork, $newWorkId);
                    }

                    # them phương thuc lam viec
                    $modelStaffWorkMethod->insert($modelStaffWorkMethod->getDefaultMethodHasMain(), $modelStaffWorkMethod->getDefaultNotApplyRule(), $newStaffId, $staffLoginId);
                    return redirect()->route('qc.ad3d.system.company.get');
                }
            }
        } elseif ($txtSelectObject == 'selectSystem') {
            $radSelectWorkId = Request::input('radSelectWork');
            if ($hFunction->checkEmpty($radSelectWorkId)) {
                Session::put('notifyAdd', "PHẢI CHỌN 1 NGƯỜI QUẢN LÝ.");
                return redirect()->back();
            } else {
                $updateStatus = true;
                # khong co lam trong bo phan quan ly
                if (!$modelStaffWorkDepartment->checkExistWorkOfDepartment($radSelectWorkId, $manageDepartmentId)) {
                    # them bo phan lam viec
                    if (!$modelStaffWorkDepartment->insert($radSelectWorkId, $manageDepartmentId, $manageRankId, $hFunction->carbonNow())) {
                        $updateStatus = false;
                    };
                }
                if ($updateStatus) {
                    # nang len root level
                    if ($modelCompanyStaffWork->upLevelRoot($radSelectWorkId)) {
                        # neu ton tai lanh dao cu
                        if ($hFunction->checkCount($dataOldCompanyStaffWorkRoot)) $modelCompanyStaffWork->downLevelRoot($dataOldCompanyStaffWorkRoot->workId());
                        return redirect()->route('qc.ad3d.system.company.get');
                    } else {
                        Session::put('notifyAdd', "HỆ THỐNG ĐANG CẬP NHẬT TÍNH NĂNG.");
                        return redirect()->back();
                    }
                } else {
                    Session::put('notifyAdd', "HỆ THỐNG ĐANG CẬP NHẬT TÍNH NĂNG.");
                    return redirect()->back();
                }

            }
        } else {
            Session::put('notifyAdd', "HỆ THỐNG ĐANG CẬP NHẬT TÍNH NĂNG.");
            return redirect()->back();
        }

    }

    # xem chi tiet
    public function view($companyId)
    {
        $modelCompany = new QcCompany();
        $dataCompany = $modelCompany->getInfo($companyId);
        return view('ad3d.system.company.company.view', compact('dataCompany'));
    }

    # lay link tuyen dung
    public function getRecruitmentLink($companyId)
    {
        $modelCompany = new QcCompany();
        $dataCompany = $modelCompany->getInfo($companyId);
        return view('ad3d.system.company.company.get-link', compact('dataCompany'));
    }

    #them cong cty
    public function getAdd()
    {
        $modelStaff = new QcStaff();
        $dataAccess = [
            'accessObject' => 'company',
            'subObject' => 'company'
        ];
        return view('ad3d.system.company.company.add', compact('modelStaff', 'dataAccess'));
    }

    public function postAdd()
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelRank = new QcRank();
        $modelCompany = new QcCompany();
        $modelDepartment = new QcDepartment();
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelStaffWorkDepartment = new QcStaffWorkDepartment();
        $modelStaffWorkMethod = new QcStaffWorkMethod();
        $modelWork = new QcWork();
        $staffLoginId = $modelStaff->loginStaffId();
        # thong tin cong ty
        $name = Request::input('txtName');
        $companyCode = Request::input('txtCompanyCode');
        $txtNameCode = Request::input('txtNameCode');
        $address = Request::input('txtAddress');
        $phone = Request::input('txtPhone');
        $email = Request::input('txtEmail');
        $website = Request::input('txtWebsite');
        $logo = Request::file('txtLogo');;
        $companyType = Request::input('cbCompanyType');;// chi nhanh
        # thong tin nguoi quan ly
        $txtFirstName = Request::input('txtFirstName');
        $txtLastName = Request::input('txtLastName');
        $txtIdentityCard = Request::input('txtIdentityCard');
        $cbGender = Request::input('cbGender');
        $txtStaffPhone = Request::input('txtStaffPhone');
        $txtStaffAddress = Request::input('txtStaffAddress');
        $txtStaffEmail = Request::input('txtStaffEmail');
        # kiem tra ten cty da ton tai
        if ($modelCompany->existName($name)) {
            Session::put('notifyAdd', "Thêm thất bại <b>'$name'</b> đã tồn tại.");
        } else {
            if ($modelStaff->existIdentityCard($txtIdentityCard)) {
                Session::put('notifyAdd', "Chứng minh thư <b>'$txtIdentityCard'</b> đã tồn tại.");
            } else {
                if ($hFunction->checkCount($logo)) {
                    $imageName = $logo->getClientOriginalName();
                    $imageName = "$companyCode-" . $hFunction->getTimeCode() . "." . $hFunction->getTypeImg($imageName);
                    $source_img = $_FILES['txtLogo']['tmp_name'];
                    if (!$modelCompany->uploadImage($source_img, $imageName, 500)) {
                        $imageName = null;
                    }
                }
                $dataCompanyLogin = $modelStaff->companyLogin();
                if ($hFunction->checkCount($dataCompanyLogin)) {
                    $parentId = $dataCompanyLogin->companyId();
                } else {
                    $parentId = $modelCompany->getDefaultParentId();
                }
                if ($modelCompany->insert($companyCode, $name, $txtNameCode, $address, $phone, $email, $website, $companyType, $imageName, $parentId)) {
                    $newCompanyId = $modelCompany->insertGetId();
                    $staffAccount = $txtIdentityCard;
                    # lay gia tri mac dinh
                    $staffBirthday = $modelStaff->getDefaultBirthday();
                    $staffImage = $modelStaff->getDefaultImage();
                    $identityCardFront = $modelStaff->getDefaultIdentityCardFront();
                    $identityCardBack = $modelStaff->getDefaultIdentityCardBack();
                    $bankAccount = $modelStaff->getDefaultBankAccount();
                    $bankName = $modelStaff->getDefaultBankName();
                    if ($modelStaff->insert($txtFirstName, $txtLastName, $txtIdentityCard, $staffAccount, $staffBirthday, $cbGender, $staffImage, $identityCardFront, $identityCardBack, $txtStaffEmail, $txtStaffAddress, $txtStaffPhone, $bankAccount, $bankName)) {
                        $newStaffId = $modelStaff->insertGetId();
                        # them vao cong ty lam viec
                        $fromDateWork = $hFunction->carbonNow();
                        $level = $modelCompanyStaffWork->getDefaultLevelRoot();
                        if ($modelCompanyStaffWork->insert($fromDateWork, $level, $newStaffId, $staffLoginId, $newCompanyId)) {
                            $newWorkId = $modelCompanyStaffWork->insertGetId();
                            # cap quan ly
                            $rankId = $modelRank->manageRankId();
                            # bo phan quan ly
                            $departmentId = $modelDepartment->manageDepartmentId();
                            # them bo phan lam viec
                            $modelStaffWorkDepartment->insert($newWorkId, $departmentId, $rankId, $fromDateWork);

                            # them thong tin bang cham cong
                            $toDateWork = $hFunction->lastDateOfMonthFromDate($fromDateWork);
                            $modelWork->insert($fromDateWork, $toDateWork, $newWorkId);
                        }
                        # them phương thuc lam viec
                        $modelStaffWorkMethod->insert($modelStaffWorkMethod->getDefaultMethodHasMain(), $modelStaffWorkMethod->getDefaultNotApplyRule(), $newStaffId, $staffLoginId);

                    }
                    Session::put('notifyAdd', 'Thêm thành công, Nhập thông tin để tiếp tục');
                } else {
                    Session::put('notifyAdd', 'Thêm thất bại, Nhập thông tin để tiếp tục');
                }
            }

        }
    }

    # edit
    public function getEdit($companyId)
    {
        $hFunction = new \Hfunction();
        $modelStaff = new QcStaff();
        $modelCompany = new QcCompany();
        $dataCompany = $modelCompany->getInfo($companyId);
        if ($hFunction->checkCount($dataCompany)) {
            return view('ad3d.system.company.company.edit', compact('modelStaff', 'dataCompany'));
        }
    }

    public function postEdit($companyId)
    {
        $hFunction = new \Hfunction();
        $modelCompany = new QcCompany();
        $name = Request::input('txtName');
        $companyCode = Request::input('txtCompanyCode');
        $txtNameCode = Request::input('txtNameCode');
        $address = Request::input('txtAddress');
        $phone = Request::input('txtPhone');
        $email = Request::input('txtEmail');
        $website = Request::input('txtWebsite');

        $txtNewLogo = Request::file('txtNewLogo');;
        $companyType = Request::input('cbCompanyType');;// chi nhanh

        $notifyContent = null;
        if ($modelCompany->existEditName($companyId, $name)) {
            $notifyContent = "Tên <b>'$name'</b> đã tồn tại.";
        }
        if ($modelCompany->existEditCompanyCode($companyId, $companyCode)) {
            if ($hFunction->checkEmpty($notifyContent)) {
                $notifyContent = "Mã số thuế <b>'$companyCode'</b> đã tồn tại.";
            } else {
                $notifyContent = $notifyContent . "<br/> Mã số thuế <b>'$companyCode'</b> đã tồn tại.";
            }
        }
        if ($modelCompany->existEditNameCode($companyId, $txtNameCode)) {
            if ($hFunction->checkEmpty($notifyContent)) {
                $notifyContent = "Mã Cty <b>'$txtNameCode'</b> đã tồn tại.";
            } else {
                $notifyContent = $notifyContent . "<br/> Mã Cty <b>'$txtNameCode'</b> đã tồn tại.";
            }
        }
        if (!$hFunction->checkEmpty($notifyContent)) {
            return $notifyContent;
        } else {
            $oldLogo = $modelCompany->logo($companyId);
            if ($hFunction->checkCount($txtNewLogo)) {
                $imageName = $txtNewLogo->getClientOriginalName();
                $imageName = "$companyCode-" . $hFunction->getTimeCode() . "." . $hFunction->getTypeImg($imageName);
                $source_img = $_FILES['txtNewLogo']['tmp_name'];
                if (!$modelCompany->uploadImage($source_img, $imageName, 500)) {
                    $imageName = $oldLogo;
                }
            } else {
                $imageName = $oldLogo;
            }
            $modelCompany->updateInfo($companyId, $companyCode, $name, $txtNameCode, $address, $phone, $email, $website, $companyType, $imageName);
        }
    }

    # delete
    public function deleteCompany($companyId)
    {
        $modelCompany = new QcCompany();
        $modelCompany->actionDelete($companyId);
    }

    #========= ========= PARTNER =========== ============
    public function indexPartner()
    {
        $modelStaff = new QcStaff();
        $dataAccess = [
            'accessObject' => 'company',
            'subObject' => 'partner'
        ];
        return view('ad3d.system.company.partner.list', compact('modelStaff', 'modelCompany', 'dataAccess'));
    }
}
