<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:32 PM
 */
$hFunction = new Hfunction();
$staffId = $dataStaff->staffId();
#======  ======== THU ========= ===============
// thu tien tu don hang
$totalMoneyOrderPay = $dataStaff->totalReceiveMoneyFromOrderPay($staffId, $statisticDate);
//tong tien nhan tu thanh toan mua vat tu
$totalMoneyImportPaidOfStaff = $dataStaff->totalMoneyImportOfStaff($staffId, $statisticDate, 1);
// tien duoc giao
$totalReceivedMoneyOfStaffAndDate = $dataStaff->totalMoneyReceivedTransferOfStaffAndDate($staffId, $statisticDate);

# ====== ======== CHI ========= =============
//  chi mua vạt tu da duoc duyet
$totalMoneyImport = $dataStaff->totalMoneyImportConfirmedAndAgreeOfStaff($staffId, $statisticDate);

// Chi GIAO TIEN va Da xac nhan
$totalMoneyTransfer = $dataStaff->totalMoneyTransferConfirmedOfStaffAndDate($staffId, $statisticDate);

//chi ứng luong
$totalMoneyPaidSalaryBeforePay = $dataStaff->totalMoneyPaidSalaryBeforePayOfStaffAndDate($staffId, $statisticDate);

//chi thanh toan luong
$totalMoneyPaidSalaryPay = $dataStaff->totalMoneyPaidSalaryPayOfStaffAndDateAndConfirmed($staffId, $statisticDate);

//chi hoat dong
$totalMoneyPayActivity = $dataStaff->totalMoneyPayActivityConfirmedAndInvalidOfStaff($staffId, $statisticDate);

// chi hoan tien don hang
$totalPaidOrderCancelOfStaffAndDate = $dataStaff->totalPaidOrderCancelOfStaffAndDate($staffId, $statisticDate);

?>
@extends('ad3d.statistic.revenue.company.index')
@section('qc_ad3d_index_content')
    <div class="row" style="padding-bottom: 50px;">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="padding-top:10px; padding-bottom: 10px; border-bottom: 2px dashed brown; ">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6"
                     style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-red" href="{!! $hFunction->getUrlReferer() !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-backward"></i>
                        Trở lại
                    </a><br/>
                    <label class="qc-font-size-20">{!! $dataStaff->fullName() !!}</label> - <em class="qc-color-red">Trong tháng:{!! date('m/Y', strtotime($statisticDate)) !!}</em>
                </div>
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-6 col-lg-6">
            <div class="qc_ad3d_statistic_revenue_company_detail row">
                <div class="qc-margin-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row qc-ad3d-table-container">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr style="background-color: whitesmoke;">
                                    <th class="text-center">STT</th>
                                    <th>Nội dung</th>
                                    <th class="text-right">Thu</th>
                                    <th class="text-right">Chi</th>
                                </tr>
                                <tr>
                                    <td class="text-center">
                                        1
                                    </td>
                                    <td>
                                        <a class="qc_detail_view qc-link" title="Click để xem chi tiết" data-href="{!! route('qc.ad3d.statistic.revenue.company.staff.order_pay.view',"$staffId/$statisticDate") !!}">
                                            Thu tiền từ đơn hàng
                                        </a>
                                    </td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($totalMoneyOrderPay)  !!}
                                    </td>
                                    <td class="text-right">
                                        0
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">
                                        2
                                    </td>
                                    <td>
                                        <a class="qc_detail_view qc-link" title="Click để xem chi tiết" data-href="{!! route('qc.ad3d.statistic.revenue.company.staff.import.view',"$staffId/$statisticDate") !!}">
                                            Nhận từ thanh toán mua vật tư
                                        </a>
                                    </td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($totalMoneyImportPaidOfStaff)  !!}
                                    </td>
                                    <td class="text-right">
                                        0
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">
                                        3
                                    </td>
                                    <td>
                                        <a class="qc_detail_view qc-link" title="Click để xem chi tiết" data-href="{!! route('qc.ad3d.statistic.revenue.company.staff.receive_money.view',"$staffId/$statisticDate") !!}">
                                            Được giao tiền - "ĐÃ XÁC NHẬN"
                                        </a>
                                    </td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($totalReceivedMoneyOfStaffAndDate)  !!}
                                    </td>
                                    <td class="text-right">
                                        0
                                    </td>
                                </tr>
                                <tr class="danger">
                                    <td class="text-center">
                                        4
                                    </td>
                                    <td>
                                        <a class="qc_detail_view qc-link" title="Click để xem chi tiết" data-href="{!! route('qc.ad3d.statistic.revenue.company.staff.import.view',"$staffId/$statisticDate") !!}">
                                            Chi Mua vật tư - "ĐƯỢC DUYỆT"
                                        </a>
                                    </td>
                                    <td class="text-right">
                                        0
                                    </td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($totalMoneyImport)  !!}
                                    </td>
                                </tr>
                                <tr class="danger">
                                    <td class="text-center">
                                        5
                                    </td>
                                    <td>
                                        <a class="qc_detail_view qc-link" title="Click để xem chi tiết" data-href="{!! route('qc.ad3d.statistic.revenue.company.staff.transfer_money.view',"$staffId/$statisticDate") !!}">
                                            Chi giao tiền - "ĐÃ XÁC NHẬN"
                                        </a>
                                    </td>
                                    <td class="text-right">
                                        0
                                    </td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($totalMoneyTransfer)  !!}
                                    </td>
                                </tr>
                                <tr class="danger">
                                    <td class="text-center">
                                        6
                                    </td>
                                    <td>
                                        <a class="qc_detail_view qc-link" title="Click để xem chi tiết" data-href="{!! route('qc.ad3d.statistic.revenue.company.staff.salary_before_pay.view',"$staffId/$statisticDate") !!}">
                                            Chi ứng lương
                                        </a>
                                    </td>
                                    <td class="text-right">
                                        0
                                    </td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($totalMoneyPaidSalaryBeforePay)  !!}
                                    </td>
                                </tr>
                                <tr class="danger">
                                    <td class="text-center">
                                        7
                                    </td>
                                    <td>
                                        <a class="qc_detail_view qc-link" title="Click để xem chi tiết" data-href="{!! route('qc.ad3d.statistic.revenue.company.staff.salary_pay.view',"$staffId/$statisticDate") !!}">
                                            Chi thanh toán lương - "ĐÃ XÁC NHẬN"
                                        </a>
                                    </td>
                                    <td class="text-right">
                                        0
                                    </td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($totalMoneyPaidSalaryPay)  !!}
                                    </td>
                                </tr>
                                <tr class="danger">
                                    <td class="text-center">
                                        8
                                    </td>
                                    <td>
                                        <a class="qc_detail_view qc-link" title="Click để xem chi tiết" data-href="{!! route('qc.ad3d.statistic.revenue.company.staff.pay_activity.view',"$staffId/$statisticDate") !!}">
                                            Chi hoạt động cty - "ĐÃ XÁC NHẬN"
                                        </a>
                                    </td>
                                    <td class="text-right">
                                        0
                                    </td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($totalMoneyPayActivity)  !!}
                                    </td>
                                </tr>
                                <tr class="danger">
                                    <td class="text-center">
                                        9
                                    </td>
                                    <td>
                                        <a class="qc_detail_view qc-link" title="Đang cập nhật" data-href="#">
                                            Chi hoàn tiền đơn hàng
                                        </a>
                                    </td>
                                    <td class="text-right">
                                        0
                                    </td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($totalPaidOrderCancelOfStaffAndDate)  !!}
                                    </td>
                                </tr>
                                <tr style="border-top: 2px solid brown;" >
                                    <td class="text-center" colspan="2">

                                    </td>
                                    <td class="text-right qc-color-red">
                                        {!! $hFunction->currencyFormat($totalMoneyOrderPay + $totalMoneyImportPaidOfStaff + $totalReceivedMoneyOfStaffAndDate )  !!}
                                    </td>
                                    <td class="text-right qc-color-red">
                                        {!! $hFunction->currencyFormat($totalPaidOrderCancelOfStaffAndDate + $totalMoneyImport + $totalMoneyTransfer + $totalMoneyPaidSalaryBeforePay + $totalMoneyPaidSalaryPay)  !!}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right qc-color-green" colspan="2">
                                        Lợi nhuận (trước thuế)
                                    </td>
                                    <td class="text-right qc-color-green" colspan="2" >
                                        {!! $hFunction->currencyFormat($totalMoneyOrderPay + $totalMoneyImportPaidOfStaff + $totalReceivedMoneyOfStaffAndDate - ($totalPaidOrderCancelOfStaffAndDate + $totalMoneyImport + $totalMoneyTransfer + $totalMoneyPaidSalaryBeforePay + $totalMoneyPaidSalaryPay) )  !!}
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
