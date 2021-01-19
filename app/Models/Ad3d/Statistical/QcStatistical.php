<?php

namespace App\Models\Ad3d\Statistical;

use App\Models\Ad3d\Bonus\QcBonus;
use App\Models\Ad3d\Company\QcCompany;
use App\Models\Ad3d\CompanyStaffWork\QcCompanyStaffWork;
use App\Models\Ad3d\JobApplication\QcJobApplication;
use App\Models\Ad3d\JobApplicationInterview\QcJobApplicationInterview;
use App\Models\Ad3d\MinusMoney\QcMinusMoney;
use App\Models\Ad3d\Order\QcOrder;
use App\Models\Ad3d\OverTimeRequest\QcOverTimeRequest;
use App\Models\Ad3d\Timekeeping\QcTimekeeping;
use App\Models\Ad3d\Transfers\QcTransfers;
use App\Models\Ad3d\Work\QcWork;
use App\Models\Ad3d\WorkAllocation\QcWorkAllocation;
use Illuminate\Database\Eloquent\Model;

class QcStatistical extends Model
{
    # lay tong so luong ho so tuyen dung chua duyet
    public function totalJobApplicationUnconfirmed()
    {
        $modelJobApplication = new QcJobApplication();
        return $modelJobApplication->totalUnconfirmed();
    }

    # ho so phong van chua duoc phong van
    public function totalJobApplicationInterviewUnconfirmed()
    {
        $modelJobApplicationInterview = new QcJobApplicationInterview();
        return $modelJobApplicationInterview->totalUnconfirmed();
    }

    # tong so lan nhan tien chua xac nhan cua 1 nha nv
    public function sumReceiveMoneyUnconfirmedOfStaff($staffId, $date = null)
    {
        $modelTransfer = new QcTransfers();
        return $modelTransfer->sumReceiveUnconfirmedOfStaff($staffId, $date);
    }

    // =========== ======= THONG KE THONG TIN CA NHAN ======== ========
    # thong tin bang cham cong
    public function statisticGetWork($staffId, $dateFilter = null)
    {
        $modelWork = new QcWork();
        return $modelWork->getInfoOfStaff($staffId, $dateFilter);
    }
    //======= THONG TIN CHUYEN CAN ===========
    # lay danh sach bang cham cong cua 1 nhan vien theo thang nam
    public function listWorkIdOfListCompanyStaffWork($staffId, $dateFilter = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelWork = new QcWork();
        $listCompanyStaffWorkId = $modelCompanyStaffWork->listIdOfStaff($staffId);
        return $modelWork->listIdOfListCompanyStaffWorkBeginDate($listCompanyStaffWorkId, $dateFilter);
    }

    #thong tim co cham cong cua 1 nhan vien
    public function statisticGetHasWorkTimekeeping($staffId, $dateFilter = null)
    {
        $modelTimekeeping = new QcTimekeeping();
        return $modelTimekeeping->getInfoHasWorkFromListWork($this->listWorkIdOfListCompanyStaffWork($staffId, $dateFilter));
    }

    # ngay nghi co phep cua 1 nhan vien
    public function statisticGetOffWorkHasPermissionTimekeeping($staffId, $dateFilter = null)
    {
        $modelTimekeeping = new QcTimekeeping();
        return $modelTimekeeping->getInfoOffWorkHasPermissionFromListWork($this->listWorkIdOfListCompanyStaffWork($staffId, $dateFilter));
    }

    # ngay nghi khong phep cua 1 nhan vien
    public function statisticGetOffWorkNotPermissionTimekeeping($staffId, $dateFilter = null)
    {
        $modelTimekeeping = new QcTimekeeping();
        return $modelTimekeeping->getInfoOffWorkNotPermissionFromListWork($this->listWorkIdOfListCompanyStaffWork($staffId, $dateFilter));
    }

    # di lam tre cua 1 nhan vien
    public function statisticGetLateWork($staffId, $dateFilter = null)
    {
        $modelTimekeeping = new QcTimekeeping();
        return $modelTimekeeping->getInfoLateWork($this->listWorkIdOfListCompanyStaffWork($staffId, $dateFilter));
    }

    # lam tang ca
    public function statisticGetOverTimeWork($staffId, $dateFilter = null)
    {
        $modelTimekeeping = new QcTimekeeping();
        return $modelTimekeeping->getInfoOverTimeWork($this->listWorkIdOfListCompanyStaffWork($staffId, $dateFilter));
    }

    #yeu cau tang ca
    public function statisticGetAllOverTimeRequest($staffId, $dateFilter = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelOverTimeRequest = new QcOverTimeRequest();
        $listCompanyStaffWorkId = $modelCompanyStaffWork->listIdOfStaff($staffId);
        return $modelOverTimeRequest->infoOfListCompanyStaffWork($listCompanyStaffWorkId, $dateFilter);
    }

    #co tang ca theo yeu cau
    public function statisticGetHasAcceptOverTimeRequest($staffId, $dateFilter = null)
    {
        $modelCompanyStaffWork = new QcCompanyStaffWork();
        $modelOverTimeRequest = new QcOverTimeRequest();
        $listCompanyStaffWorkId = $modelCompanyStaffWork->listIdOfStaff($staffId);
        return $modelOverTimeRequest->infoHasAcceptOfListCompanyStaffWork($listCompanyStaffWorkId, $dateFilter);
    }

    #so lan duoc thuong (co ap dung)
    public function statisticGetHasApplyBonus($staffId, $dateFilter = null)
    {
        $modelBonus = new QcBonus();
        return $modelBonus->getInfoHasApplyFromListWorkId($this->listWorkIdOfListCompanyStaffWork($staffId, $dateFilter));
    }

    #so lan phat (co ap dung)
    public function statisticGetHasApplyMinus($staffId, $dateFilter = null)
    {
        $modelMinus = new QcMinusMoney();
        return $modelMinus->getInfoHasApplyFromListWorkId($this->listWorkIdOfListCompanyStaffWork($staffId, $dateFilter));
    }

    //=========== THONG TIN CHUYEN MON - THI CONG =========
    # tong tin gia tri mang ve tu thi cong san pham
    public function totalValueMoneyFromListWorkAllocation($dataListWorkAllocation)
    {
        $modelWorkAllocation = new QcWorkAllocation();
        return $modelWorkAllocation->valueMoneyFromListWorkAllocation($dataListWorkAllocation);
    }

    # tong so luong cong viec duoc giao theo thoi gian
    public function statisticGetReceiveWorkAllocation($staffId, $dateFilter = null)
    {
        $modelCompany = new QcCompany();
        $modelWorkAllocation = new QcWorkAllocation();
        return $modelWorkAllocation->selectInfoOfStaffReceive($staffId, $modelCompany->getDefaultValueAllFinish(), $dateFilter)->get();
    }

    # tong so luong cong viec duoc giao theo thoi gian va bi tre
    public function statisticGetWorkAllocationHasLate($staffId, $dateFilter = null)
    {
        $modelWorkAllocation = new QcWorkAllocation();
        return $modelWorkAllocation->selectInfoHasLateOfStaffReceive($staffId, $dateFilter)->get();
    }

    # tong so cong viec duoc giao da hoan thanh
    public function statisticGetWorkAllocationHasFinish($staffId, $dateFilter = null)
    {
        $modelCompany = new QcCompany();
        $modelWorkAllocation = new QcWorkAllocation();
        return $modelWorkAllocation->selectInfoOfStaffReceive($staffId, $modelCompany->getDefaultValueHasFinish(), $dateFilter)->get();
    }

    # tong so luong cong viec duoc giao hoan thanh dung hen
    public function statisticGetWorkAllocationFinishNotLate($staffId, $dateFilter = null)
    {
        $modelWorkAllocation = new QcWorkAllocation();
        return $modelWorkAllocation->selectInfoFinishNotLateOfStaffReceive($staffId, $dateFilter)->get();
    }

    # tong so luong cong viec duoc giao hoan thanh tre hen
    public function statisticGetWorkAllocationFinishHasLate($staffId, $dateFilter = null)
    {
        $modelWorkAllocation = new QcWorkAllocation();
        return $modelWorkAllocation->selectInfoFinishHasLateOfStaffReceive($staffId, $dateFilter)->get();
    }

    //=========== THONG TIN CHUYEN MON - KINH DOANH =========
    # tong tin gia tri mang ve tu thi cong san pham
    public function totalValueMoneyFromListOrder($dataListOrder)
    {
        $modelOrder = new QcOrder();
        return $modelOrder->totalMoneyOfListOrder($dataListOrder);
    }

    # danh sach don hang da nhan khong huy cua 1 nhan vien
    public function statisticGetAllOrder($staffId, $dateFilter = null)
    {
        $modelOrder = new QcOrder();
        return $modelOrder->getInfoAllOfStaff($staffId, $dateFilter);
    }

    # danh sach don hang bi tre
    public function statisticGetHasLateOrder($staffId, $dateFilter = null)
    {
        $modelOrder = new QcOrder();
        return $modelOrder->getInfoHasLateOfStaff($staffId, $dateFilter);
    }

    # danh sach don hang da hoan thanh
    public function statisticGetHasFinishOrder($staffId, $dateFilter = null)
    {
        $modelOrder = new QcOrder();
        return $modelOrder->getInfoHasFinishOfStaff($staffId, $dateFilter);
    }

    # danh sach don hang da hoan thanh va dung hen
    public function statisticGetHasFinishNotLateOrder($staffId, $dateFilter = null)
    {
        $modelOrder = new QcOrder();
        return $modelOrder->getInfoHasFinishNotLateOfStaff($staffId, $dateFilter);
    }

    # danh sach don hang da hoan thanh va tre hen
    public function statisticGetHasFinishHasLateOrder($staffId, $dateFilter = null)
    {
        $modelOrder = new QcOrder();
        return $modelOrder->getInfoHasFinishHasLateOfStaff($staffId, $dateFilter);
    }
}
