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
$statisticStaffId = $dataStaff->staffId();
# lay gia tri mac dinh
$allMonthFilter = $modelCompany->getDefaultValueAllMonth();
$allYearFilter = $modelCompany->getDefaultValueAllYear();
$companyStaffWorkId = $dataCompanyStaffWork->workId();
$dataCompany = $dataCompanyStaffWork->company;
$indexHref = route('qc.ad3d.system.staff.statistical.get', $companyStaffWorkId);
//========  ============ CHUYEN CAN ======== ==========
# co lam viec
$dataAllTimekeeping = $modelStatistical->statisticGetHasWorkTimekeeping($statisticStaffId, $dateFilter);
# nghi lam co phep
$dataOffWorkHasPermission = $modelStatistical->statisticGetOffWorkHasPermissionTimekeeping($statisticStaffId, $dateFilter);
# nghi lam khong phep
$dataOffWorkNotPermission = $modelStatistical->statisticGetOffWorkNotPermissionTimekeeping($statisticStaffId, $dateFilter);
# di lam tre
$dataLateWork = $modelStatistical->statisticGetLateWork($statisticStaffId, $dateFilter);
# lam tang ca
$dataOverTimeWork = $modelStatistical->statisticGetOverTimeWork($statisticStaffId, $dateFilter);
# yeu cau tang ca
$dataOverTimeRequest = $modelStatistical->statisticGetAllOverTimeRequest($statisticStaffId, $dateFilter);
# co tang ca theo yeu cau
$dataOverTimeRequestHasAccept = $modelStatistical->statisticGetHasAcceptOverTimeRequest($statisticStaffId, $dateFilter);
# so lan thuong - duoc ap dung
$dataHasApplyBonus = $modelStatistical->statisticGetHasApplyBonus($statisticStaffId, $dateFilter);
# so lan phat - duoc ap dung
$dataHasApplyMinus = $modelStatistical->statisticGetHasApplyMinus($statisticStaffId, $dateFilter);

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
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <h3 style="color: red;">ĐANG LÀM ....</h3>
            </div>
        </div>
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
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
            <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
                <select class="qc_statistic_filter qc_statistic_filter_month col-sx-6 col-sm-6 col-md-6 col-lg-6"
                        style="padding: 0; height: 34px; color: red;" data-href="{!! $indexHref !!}">
                    <option value="{!! $allMonthFilter !!}"
                            @if($allMonthFilter == $monthFilter) selected="selected" @endif>
                        Tất cả
                    </option>
                    @for($m = 1; $m <=12; $m++)
                        <option @if($m == $monthFilter) selected="selected" @endif>
                            {!! $m !!}
                        </option>
                    @endfor
                </select>
                <select class="qc_statistic_filter qc_statistic_filter_year col-sx-6 col-sm-6 col-md-6 col-lg-6"
                        style="padding: 0; height: 34px; color: red;"
                        data-href="{!! $indexHref !!}">
                    <option value="{!! $allYearFilter !!}"
                            @if($allYearFilter == $yearFilter) selected="selected" @endif>
                        Tất cả Năm
                    </option>
                    @for($y = 2017; $y <=2050; $y++)
                        <option @if($y == $yearFilter) selected="selected" @endif>
                            {!! $y !!}
                        </option>
                    @endfor
                </select>
            </div>
        </div>
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
            <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
                {{--NĂNG LỰC CHUYÊN MÔN - THI CÔNG--}}
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
                                        <b style="color: green;">
                                            {!! $hFunction->currencyFormat($valueMoneyFromWorkAllocation) !!}
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
                                        <b style="color: green;">
                                            {!! $hFunction->currencyFormat($valueMoneyFromWorkAllocationHasLate) !!}
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
                                </tr>
                                <tr>
                                    <td>Hoàn thành đúng hẹn</td>
                                    <td class="text-center">
                                        <b style="color:brown;">
                                            {!! $hFunction->getCount($dataWorkAllocationHasFinishNotLate) !!}
                                        </b>
                                    </td>
                                    <td class="text-center">
                                        <b style="color: green;">
                                            {!! $hFunction->currencyFormat($valueMoneyFromWorkAllocationFinishNotLate) !!}
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
                                        <b style="color: green;">
                                            {!! $hFunction->currencyFormat($valueMoneyFromWorkAllocationFinishHasLate) !!}
                                        </b>
                                    </td>
                                </tr>

                            </table>

                        </div>
                    </div>
                </div>
                {{--NĂNG LỰC CHUYÊN MÔN - KINH DOANH--}}
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
                                        <b style="color: green;">
                                            {!! $hFunction->currencyFormat($valueMoneyFromAllOrder) !!}
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
                                        <b style="color: green;">
                                            {!! $hFunction->currencyFormat($valueMoneyFromOrderHasLate) !!}
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
                                </tr>
                                <tr>
                                    <td>Hoàn thành đúng hẹn</td>
                                    <td class="text-center">
                                        <b style="color:brown;">
                                            {!! $hFunction->getCount($dataWorkAllocationHasFinishNotLate) !!}
                                        </b>
                                    </td>
                                    <td class="text-center">
                                        <b style="color: green;">
                                            {!! $hFunction->currencyFormat($valueMoneyFromWorkAllocationFinishNotLate) !!}
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
                                        <b style="color: green;">
                                            {!! $hFunction->currencyFormat($valueMoneyFromWorkAllocationFinishHasLate) !!}
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
@endsection
