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
$dataCompanyStaffWork = $dataWork->companyStaffWork;
$fromDate = $dataWork->fromDate();
$dateFilter = date('Y-m', strtotime($fromDate));
$companyStaffWorkId = $dataCompanyStaffWork->workId();
$dataStaff = $dataCompanyStaffWork->staff;
$statisticStaffId = $dataStaff->staffId();
$dataCompany = $dataCompanyStaffWork->company;

# tong thi cong san pham - tat ca
if ($orderStatus == 'get-all') {
    $dataOrder = $modelStatistical->statisticGetAllOrder($statisticStaffId, $dateFilter);
    $title = 'TẤT CẢ ĐƠN HÀNG ĐÃ NHẬN';
} elseif ($orderStatus == 'get-all-late') {
    $dataOrder = $modelStatistical->statisticGetHasLateOrder($statisticStaffId, $dateFilter);
    $title = 'TẤT CẢ ĐƠN HÀNG BỊ TRỄ';
} elseif ($orderStatus == 'get-all-finish') {
    $dataOrder = $modelStatistical->statisticGetHasFinishOrder($statisticStaffId, $dateFilter);
    $title = 'TẤT CẢ ĐƠN HÀNG ĐÃ HOÀN THÀNH';
}elseif ($orderStatus == 'get-finish-not-late') {
    $dataOrder = $modelStatistical->statisticGetHasFinishNotLateOrder($statisticStaffId, $dateFilter);
    $title = 'ĐƠN HÀNG BÀN GIAO ĐÚNG HẸN';
}
elseif ($orderStatus == 'get-finish-has-late') {
    $dataOrder = $modelStatistical->statisticGetHasFinishHasLateOrder($statisticStaffId, $dateFilter);
    $title = 'ĐƠN HÀNG BÀN GIAO TRỄ HẸN';
}else {
    $dataOrder = null;
    $title = 'KHÔNG TÌM THẤY THÔNG TIN';
}
?>
@extends('ad3d.system.staff.index')
@section('qc_ad3d_index_content')
    <div class="qc_ad3d_sys_staff_statistical_wrap qc-padding-bot-30 row">
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <a class="qc-link-white-bold btn btn-sm btn-primary" onclick="qc_main.page_back_go();">
                    <i class="glyphicon glyphicon-backward"></i> Về trang trước
                </a>
            </div>
        </div>
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
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
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <label style="color: red; font-size: 1.5em;">
                    {!! $title !!} - {!! $hFunction->getCount($dataOrder) !!}
                </label>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: black; color: yellow;">
                            <th style="width: 300px;">ĐƠN HÀNG</th>
                            <th>Giá</th>
                        </tr>
                        @if($hFunction->checkCount($dataOrder))
                            @foreach($dataOrder as $order)
                                <?php
                                $n_o = (isset($n_o)) ? $n_o + 1 : 1;
                                $totalMoney = $order->totalMoney();
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2 == 0) info @endif">
                                    <td>
                                        <b>{!! $n_o !!}).</b>
                                        <b>{!! $order->name() !!} </b>
                                        <br/>&emsp;
                                        <em style="color: grey;">{!! $hFunction->convertDateDMYFromDatetime($order->receiveDate()) !!}</em>
                                    </td>
                                    <td style="color: blue;">
                                        {!! $hFunction->currencyFormat($totalMoney) !!}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center" colspan="2">
                                    <em class="qc-color-red">Không tìm thấy thông tin phù hợp</em>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
