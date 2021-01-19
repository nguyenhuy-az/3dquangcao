<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataStaff
 */

$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$currentYear = $hFunction->currentYear();
$limitYear = $currentYear - 20;
$statisticStaffId = $dataStaff->staffId();
# lay gia tri mac dinh
$allMonthFilter = $modelCompany->getDefaultValueAllMonth();
$allYearFilter = $modelCompany->getDefaultValueAllYear();
$companyStaffWorkId = $dataCompanyStaffWork->workId();
$dataCompany = $dataCompanyStaffWork->company;
$indexHref = route('qc.ad3d.system.staff.statistical.get', $companyStaffWorkId);
# lay ta ca ca bang cham cong cua 1 ng
///$allDataWork = $modelStatistical->statisticGetWork($statisticStaffId, $hFunction->getDefaultNull());
# lay thong tin bang cham cong
if ($monthFilter > 0) { # lay theo thang nam - theo 1 bang cham cong
    $dataWorkSelected = $modelStatistical->statisticGetWork($statisticStaffId, $dateFilter)->first();
} else {
    # lay mac dinh trong 1 nam
    # bang cham cong dc chon - chi lay theo nam
    $dataWorkSelected = $modelStatistical->statisticGetWork($statisticStaffId, $yearFilter)->first();
}
//========  ============ CHUYEN CAN ======== ==========
# co lam viec
$dataAllTimekeeping = $dataWorkSelected->getInfoHasWorkTimekeeping();// $modelStatistical->statisticGetHasWorkTimekeeping($statisticStaffId, $dateFilter);
# nghi lam co phep
$dataOffWorkHasPermission = $dataWorkSelected->getInfoOffWorkHasPermissionTimekeeping();//$modelStatistical->statisticGetOffWorkHasPermissionTimekeeping($statisticStaffId, $dateFilter);
# nghi lam khong phep
$dataOffWorkNotPermission = $dataWorkSelected->getInfoOffWorkNotPermissionTimekeeping();// $modelStatistical->statisticGetOffWorkNotPermissionTimekeeping($statisticStaffId, $dateFilter);
# di lam tre
$dataLateWork = $dataWorkSelected->getInfoLateWork();//$modelStatistical->statisticGetLateWork($statisticStaffId, $dateFilter);
# lam tang ca
$dataOverTimeWork = $dataWorkSelected->getInfoOverTimeWork();//$modelStatistical->statisticGetOverTimeWork($statisticStaffId, $dateFilter);
# yeu cau tang ca
$dataOverTimeRequest = $modelStatistical->statisticGetAllOverTimeRequest($statisticStaffId, $dateFilter);
# co tang ca theo yeu cau
$dataOverTimeRequestHasAccept = $modelStatistical->statisticGetHasAcceptOverTimeRequest($statisticStaffId, $dateFilter);
# so lan thuong - duoc ap dung
$dataHasApplyBonus = $dataWorkSelected->getInfoHasApplyBonus();//$modelStatistical->statisticGetHasApplyBonus($statisticStaffId, $dateFilter);
# so lan phat - duoc ap dung
$dataHasApplyMinus = $dataWorkSelected->getInfoHasApplyMinusMoney();//$modelStatistical->statisticGetHasApplyMinus($statisticStaffId, $dateFilter);

//========== ========== NANG LUC LAM VIEC ========== ===========
//===== DUOC GIAO
# tong thi cong san pham - tat ca
$dataAllReceiveWorkAllocation = $modelStatistical->statisticGetReceiveWorkAllocation($statisticStaffId, $dateFilter);
# tong tien tren cong viec duoc phan cong
$valueMoneyFromWorkAllocation = $modelStatistical->totalValueMoneyFromListWorkAllocation($dataAllReceiveWorkAllocation);
# tong thi cong bi tre
$dataWorkAllocationHasLate = $modelStatistical->statisticGetWorkAllocationHasLate($statisticStaffId, $dateFilter);
$valueMoneyFromWorkAllocationHasLate = $modelStatistical->totalValueMoneyFromListWorkAllocation($dataWorkAllocationHasLate);

//==== DA HOAN THANH
# tong thi cong da hoan thanh
$dataWorkAllocationHasFinish = $modelStatistical->statisticGetWorkAllocationHasFinish($statisticStaffId, $dateFilter);
$valueMoneyFromWorkAllocationHasFinish = $modelStatistical->totalValueMoneyFromListWorkAllocation($dataWorkAllocationHasFinish);

# tong thi cong dung hen - khong tre
$dataWorkAllocationHasFinishNotLate = $modelStatistical->statisticGetWorkAllocationFinishNotLate($statisticStaffId, $dateFilter);
$valueMoneyFromWorkAllocationFinishNotLate = $modelStatistical->totalValueMoneyFromListWorkAllocation($dataWorkAllocationHasFinishNotLate);
# tong thi cong dung hen - tre hen
$dataWorkAllocationHasFinishHasLate = $modelStatistical->statisticGetWorkAllocationFinishHasLate($statisticStaffId, $dateFilter);
$valueMoneyFromWorkAllocationFinishHasLate = $modelStatistical->totalValueMoneyFromListWorkAllocation($dataWorkAllocationHasFinishHasLate);

//========== ========= NANG LUC LAM VIEC - KINH DOANH =========== ==========
# tat ca don hang
$dataAllOrder = $modelStatistical->statisticGetAllOrder($statisticStaffId, $dateFilter);
$valueMoneyFromAllOrder = $modelStatistical->totalValueMoneyFromListOrder($dataAllOrder);
# danh sach don hang bi tre
$dataOrderHasLate = $modelStatistical->statisticGetHasLateOrder($statisticStaffId, $dateFilter);
$valueMoneyFromOrderHasLate = $modelStatistical->totalValueMoneyFromListOrder($dataOrderHasLate);

# danh sach don hang da hoan thanh
$dataOrderHasFinish = $modelStatistical->statisticGetHasFinishOrder($statisticStaffId, $dateFilter);
$valueMoneyFromOrderHasFinish = $modelStatistical->totalValueMoneyFromListOrder($dataOrderHasFinish);

# danh sach don hang da hoan thanh dung hen
$dataOrderHasFinishNotLate = $modelStatistical->statisticGetHasFinishNotLateOrder($statisticStaffId, $dateFilter);
$valueMoneyFromOrderHasFinishNotLate = $modelStatistical->totalValueMoneyFromListOrder($dataOrderHasFinishNotLate);

# danh sach don hang da hoan thanh tre hen
$dataOrderHasFinishHasLate = $modelStatistical->statisticGetHasFinishHasLateOrder($statisticStaffId, $dateFilter);
$valueMoneyFromOrderHasFinishHasLate = $modelStatistical->totalValueMoneyFromListOrder($dataOrderHasFinishHasLate);
?>
@extends('ad3d.system.staff.index')
@section('qc_ad3d_index_content')
    <div class="qc_ad3d_sys_staff_statistical_wrap qc-padding-bot-30 row">
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <a class="qc-link-white-bold btn btn-sm btn-primary" onclick="qc_main.page_back_go();">
                    <i class="glyphicon glyphicon-backward"></i> Về trang trước
                </a>
            </div>
        </div>
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="media">
                    <a class="pull-left">
                        <img class="media-object"
                             style="background-color: white; width: 60px;height: 60px; border: 1px solid #d7d7d7;border-radius: 10px;"
                             src="{!! $dataStaff->pathAvatar($dataStaff->image()) !!}">
                    </a>

                    <div class="media-body">
                        <h5 class="media-heading">{!! $dataStaff->fullName() !!}</h5>
                        <em style="color: grey;">{!! $dataCompany->name() !!}</em>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sx-3 col-sm-2 col-md-2 col-lg-2">
                <div class="list-group">
                    @for($y = $currentYear; $y > $limitYear; $y = $y - 1)
                        <?php
                        $dataWork = $modelStatistical->statisticGetWork($statisticStaffId, $y);
                        ?>
                        @if($hFunction->checkCount($dataWork))
                            <?php
                            # lay bang thong tin login
                            $workSelectedId = $dataWorkSelected->workId();
                            ?>
                            @if($y == $yearFilter)
                                <a data-href="#" class="list-group-item" style="color: red;">
                                    <i class="glyphicon glyphicon-minus qc-font-size-12"></i>
                                    {!! $y !!}
                                    <span class="badge" style="background-color: red;">100%</span>
                                </a>
                                <ul class="list-group" style="margin-bottom: 0;">
                                    @foreach($dataWork as $work)
                                        <?php
                                        $workId = $work->workId();
                                        $fromDate = $work->fromDate();
                                        $workMonth = (int)$hFunction->getMonthFromDate($fromDate);
                                        $workYear = (int)$hFunction->getYearFromDate($fromDate);
                                        ?>
                                        <li class="list-group-item" style="border-top: none;">
                                            &nbsp;&nbsp;
                                            <a class="@if($workId == $workSelectedId) qc-link-red @else qc-link @endif"
                                               href="{!! "$indexHref/$workMonth/$workYear" !!}">
                                                <i class="glyphicon glyphicon-minus"></i>
                                                Tháng {!! $hFunction->getMonthFromDate($fromDate) !!}
                                            </a>
                                            <span class="badge" style="background-color: green;">100%</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <a data-href="#" class="list-group-item"
                                   onclick="qc_main.toggle('#qc_container_work_{!! $y !!}');">
                                    <i class="glyphicon glyphicon-plus qc-font-size-12"></i>
                                    {!! $y !!}
                                    <span class="badge" style="background-color: red;">100%</span>
                                </a>
                                <ul id="qc_container_work_{!! $y !!}" class="list-group qc-display-none"
                                    style="margin-bottom: 0;">
                                    @foreach($dataWork as $work)
                                        <?php
                                        $workId = $work->workId();
                                        $fromDate = $work->fromDate();
                                        $workMonth = (int)$hFunction->getMonthFromDate($fromDate);
                                        $workYear = (int)$hFunction->getYearFromDate($fromDate);
                                        ?>
                                        <li class="list-group-item" style="border-top: none;">
                                            &nbsp;&nbsp;
                                            <a class="qc-link"
                                               href="{!! "$indexHref/$workMonth/$workYear" !!}">
                                                <i class="glyphicon glyphicon-minus"></i>
                                                Tháng {!! $hFunction->getMonthFromDate($fromDate) !!}
                                            </a>
                                            <span class="badge" style="background-color: green;">100%</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        @endif
                    @endfor
                </div>
            </div>
            <div class="col-sx-3 col-sm-10 col-md-10 col-lg-10">
                <div class="row">
                    {{-- CHUYÊN CẦN --}}
                    <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <label style="color: blue; font-size: 1.5em;">
                                    CHUYÊN CẦN
                                </label>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td>Tổng số ngày làm</td>
                                            <td class="text-center">
                                                <b style="color:red;">
                                                    {!! $hFunction->getCount($dataAllTimekeeping) !!}
                                                </b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Nghỉ làm có phép</td>
                                            <td class="text-center">
                                                <b style="color:red;">
                                                    {!! $hFunction->getCount($dataOffWorkHasPermission) !!}
                                                </b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Nghỉ làm không phép</td>
                                            <td class="text-center">
                                                <b style="color:red;">
                                                    {!! $hFunction->getCount($dataOffWorkNotPermission) !!}
                                                </b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Đi làm trễ</td>
                                            <td class="text-center">
                                                <b style="color:red;">
                                                    {!! $hFunction->getCount($dataLateWork) !!}
                                                </b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Số lần tăng ca</td>
                                            <td class="text-center">
                                                <b style="color:red;">
                                                    {!! $hFunction->getCount($dataOverTimeWork) !!}
                                                </b>
                                            </td>
                                        </tr>
                                        <tr style="border-top: 2px solid black;">
                                            <td>Yêu cầu tăng ca</td>
                                            <td class="text-center">
                                                <b style="color:red;">
                                                    {!! $hFunction->getCount($dataOverTimeRequest) !!}
                                                </b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Có tăng ca theo yêu cầu</td>
                                            <td class="text-center">
                                                <b style="color:red;">
                                                    {!! $hFunction->getCount($dataOverTimeRequestHasAccept) !!}
                                                </b>
                                            </td>
                                        </tr>
                                        <tr style="border-top: 2px solid black;">
                                            <td>Số lần được thưởng</td>
                                            <td class="text-center">
                                                <b style="color:red;">
                                                    {!! $hFunction->getCount($dataHasApplyBonus) !!}
                                                </b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Số lần bị phạt</td>
                                            <td class="text-center">
                                                <b style="color:red;">
                                                    {!! $hFunction->getCount($dataHasApplyMinus) !!}
                                                </b>
                                            </td>
                                        </tr>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                    {{--NĂNG LỰC CHUYÊN MÔN - THI CÔNG--}}
                    <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <label style="color: blue; font-size: 1.5em;">
                                    NĂNG LỰC CHUYÊN MÔN - THI CÔNG
                                </label>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td>Tất cả sản phẩm được giao</td>
                                            <td class="text-center">
                                                <b style="color:red;">
                                                    {!! $hFunction->getCount($dataAllReceiveWorkAllocation) !!}
                                                </b>
                                            </td>
                                            <td class="text-center">
                                                <b>
                                                    {!! $hFunction->currencyFormat($valueMoneyFromWorkAllocation) !!}
                                                </b>
                                            </td>
                                            <td class="text-center">
                                                <b style="color: green;">
                                                    {!! $hFunction->currencyFormat($valueMoneyFromWorkAllocation*0.25) !!}
                                                </b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Thi công bị trễ</td>
                                            <td class="text-center">
                                                <b style="color:red;">
                                                    {!! $hFunction->getCount($dataWorkAllocationHasLate) !!}
                                                </b>
                                            </td>
                                            <td class="text-center">
                                                <b>
                                                    {!! $hFunction->currencyFormat($valueMoneyFromWorkAllocationHasLate) !!}
                                                </b>
                                            </td>
                                            <td class="text-center">
                                                <b style="color: green;">
                                                    {!! $hFunction->currencyFormat($valueMoneyFromWorkAllocationHasLate*0.25) !!}
                                                </b>
                                            </td>
                                        </tr>
                                        <tr style="border-top: 2px solid black;">
                                            <td>Sản phẩm đã hoàn thành</td>
                                            <td class="text-center">
                                                <b style="color:blue;">
                                                    {!! $hFunction->getCount($dataWorkAllocationHasFinish) !!}
                                                </b>
                                            </td>
                                            <td class="text-center">
                                                <b style="color: blue;">
                                                    {!! $hFunction->currencyFormat($valueMoneyFromWorkAllocationHasFinish) !!}
                                                </b>
                                            </td>
                                            <td class="text-center">
                                                <b style="color: blue;">
                                                    {!! $hFunction->currencyFormat($valueMoneyFromWorkAllocationHasFinish*0.25) !!}
                                                </b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Hoàn thành đúng hẹn</td>
                                            <td class="text-center">
                                                <b style="color:brown;">
                                                    {!! $hFunction->getCount($dataWorkAllocationHasFinishNotLate) !!}
                                                </b>
                                            </td>
                                            <td class="text-center">
                                                <b>
                                                    {!! $hFunction->currencyFormat($valueMoneyFromWorkAllocationFinishNotLate) !!}
                                                </b>
                                            </td>
                                            <td class="text-center">
                                                <b style="color: green;">
                                                    {!! $hFunction->currencyFormat($valueMoneyFromWorkAllocationFinishNotLate*0.25) !!}
                                                </b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Hoàn thành trễ hẹn</td>
                                            <td class="text-center">
                                                <b style="color:brown;">
                                                    {!! $hFunction->getCount($dataWorkAllocationHasFinishHasLate) !!}
                                                </b>
                                            </td>
                                            <td class="text-center">
                                                <b>
                                                    {!! $hFunction->currencyFormat($valueMoneyFromWorkAllocationFinishHasLate) !!}
                                                </b>
                                            </td>
                                            <td class="text-center">
                                                <b style="color: green;">
                                                    {!! $hFunction->currencyFormat($valueMoneyFromWorkAllocationFinishHasLate*0.25) !!}
                                                </b>
                                            </td>
                                        </tr>

                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                    {{--NĂNG LỰC CHUYÊN MÔN - KINH DOANH--}}
                    <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <label style="color: blue; font-size: 1.5em;">
                                    NĂNG LỰC CHUYÊN MÔN - KINH DOANH - THIẾT KẾ
                                </label>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td>Tất cả đơn hàng đã nhận</td>
                                            <td class="text-center">
                                                <b style="color:red;">
                                                    {!! $hFunction->getCount($dataAllOrder) !!}
                                                </b>
                                            </td>
                                            <td class="text-center">
                                                <b>
                                                    {!! $hFunction->currencyFormat($valueMoneyFromAllOrder) !!}
                                                </b>
                                            </td>
                                            <td class="text-center">
                                                <b style="color: green;">
                                                    {!! $hFunction->currencyFormat($valueMoneyFromAllOrder*0.25) !!}
                                                </b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Số đơn hàng bị trễ</td>
                                            <td class="text-center">
                                                <b style="color:red;">
                                                    {!! $hFunction->getCount($dataOrderHasLate) !!}
                                                </b>
                                            </td>
                                            <td class="text-center">
                                                <b>
                                                    {!! $hFunction->currencyFormat($valueMoneyFromOrderHasLate) !!}
                                                </b>
                                            </td>
                                            <td class="text-center">
                                                <b style="color: green;">
                                                    {!! $hFunction->currencyFormat($valueMoneyFromOrderHasLate*0.25) !!}
                                                </b>
                                            </td>
                                        </tr>
                                        <tr style="border-top: 2px solid black;">
                                            <td>Tổng đơn hàng đã hoàn thành</td>
                                            <td class="text-center">
                                                <b style="color:blue;">
                                                    {!! $hFunction->getCount($dataOrderHasFinish) !!}
                                                </b>
                                            </td>
                                            <td class="text-center">
                                                <b style="color: blue;">
                                                    {!! $hFunction->currencyFormat($valueMoneyFromOrderHasFinish) !!}
                                                </b>
                                            </td>
                                            <td class="text-center">
                                                <b style="color: blue;">
                                                    {!! $hFunction->currencyFormat($valueMoneyFromOrderHasFinish*0.25) !!}
                                                </b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Hoàn thành đúng hẹn</td>
                                            <td class="text-center">
                                                <b style="color:brown;">
                                                    {!! $hFunction->getCount($dataOrderHasFinishNotLate) !!}
                                                </b>
                                            </td>
                                            <td class="text-center">
                                                <b>
                                                    {!! $hFunction->currencyFormat($valueMoneyFromOrderHasFinishNotLate) !!}
                                                </b>
                                            </td>
                                            <td class="text-center">
                                                <b style="color: green;">
                                                    {!! $hFunction->currencyFormat($valueMoneyFromOrderHasFinishNotLate*0.25) !!}
                                                </b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Đã Hoàn thành trễ hẹn</td>
                                            <td class="text-center">
                                                <b style="color:brown;">
                                                    {!! $hFunction->getCount($dataOrderHasFinishHasLate) !!}
                                                </b>
                                            </td>
                                            <td class="text-center">
                                                <b>
                                                    {!! $hFunction->currencyFormat($valueMoneyFromOrderHasFinishHasLate) !!}
                                                </b>
                                            </td>
                                            <td class="text-center">
                                                <b style="color: green;">
                                                    {!! $hFunction->currencyFormat($valueMoneyFromOrderHasFinishHasLate*0.25) !!}
                                                </b>
                                            </td>
                                        </tr>

                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
