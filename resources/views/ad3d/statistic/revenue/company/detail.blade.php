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

// tien duoc giao
$totalReceivedMoneyOfStaffAndDate = $dataStaff->totalMoneyReceivedTransferOfStaffAndDate($staffId, $statisticDate);

# ====== ======== CHI ========= =============

//chi ứng luong - da xac nhan
$totalMoneyPaidSalaryBeforePay = $dataStaff->totalMoneyPaidSalaryBeforePayOfStaffAndDate($staffId, $statisticDate);

//chi thanh toan luong  - da xac nhan
$totalMoneyPaidSalaryPay = $dataStaff->totalMoneyPaidSalaryPayOfStaffAndDateAndConfirmed($staffId, $statisticDate);

//chi hoat dong - da duoc duyet
$totalMoneyPayActivity = $dataStaff->totalMoneyPayActivityConfirmedAndInvalidOfStaff($staffId, $statisticDate);

// chi hoan tien don hang
$totalPaidOrderCancelOfStaffAndDate = $dataStaff->totalPaidOrderCancelOfStaffAndDate($staffId, $statisticDate);

// chi thanh toan mua vat tu - da xac nhan
$totalMoneyImportPayOfStaffAndDate = $dataStaff->totalMoneyImportPayConfirmOfStaffAndDate($staffId, $statisticDate);

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
                    </a>
                    <br/>
                    <label>THU CHI CỦA CÔNG TY</label> - <em class="qc-color-red">Trong tháng:{!! date('m/Y', strtotime($statisticDate)) !!}</em>
                    <br/>
                    <label class="qc-font-size-20">Thủ Quỹ: {!! $dataStaff->fullName() !!}</label>
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
                                    <th class="text-center" style="width: 20px;"></th>
                                    <th>Nội dung</th>
                                    <th class="text-right">Thu</th>
                                    <th class="text-right">Chi</th>
                                </tr>
                                <tr>
                                    <td class="text-center">
                                        -
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
                                        -
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
                                        -
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
                                        -
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
                                        -
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
                                        -
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
                                <tr class="danger">
                                    <td class="text-center">
                                        -
                                    </td>
                                    <td>
                                        <a class= qc-link" title="Đang cập nhật" data-href="#">
                                            Chi thanh toán mua vật tư
                                        </a>
                                    </td>
                                    <td class="text-right">
                                        0
                                    </td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($totalMoneyImportPayOfStaffAndDate)  !!}
                                    </td>
                                </tr>
                                <tr style="border-top: 2px solid brown;" >
                                    <td class="text-center" colspan="2">

                                    </td>
                                    <td class="text-right qc-color-red">
                                        {!! $hFunction->currencyFormat($totalMoneyOrderPay + $totalReceivedMoneyOfStaffAndDate )  !!}
                                    </td>
                                    <td class="text-right qc-color-red">
                                        {!! $hFunction->currencyFormat($totalPaidOrderCancelOfStaffAndDate + $totalMoneyPayActivity +  $totalMoneyPaidSalaryBeforePay + $totalMoneyPaidSalaryPay + $totalMoneyImportPayOfStaffAndDate)  !!}
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
