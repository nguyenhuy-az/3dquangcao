<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$dataWork = $dataSalary->work;

if (!empty($dataWork->companyStaffWorkId())) {
    $staffName = $dataWork->companyStaffWork->staff->fullName();
} else {
    $dataStaff = $dataWork->staff;
    $staffName = $dataStaff->fullName();
}
$salaryPay = $dataSalary->salary();
$totalPaid = $dataSalary->totalPaid();
?>
@extends('ad3d.components.container.container-10')
@section('qc_ad3d_container_content')
    <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="padding-top: 20px;padding-bottom: 20px; border-bottom: 2px dashed #C2C2C2;">
            <h3>THANH TOÁN LƯƠNG</h3>
        </div>
        {{-- chi tiêt --}}
        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row" style="margin-top: 20px; border-left: 3px solid #C2C2C2;">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <em>Nhân Viên: </em>
                            <b>{!! $staffName !!}</b>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-top: 20px;">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr class="qc-color-red">
                                    <th class="qc-padding-none" colspan="7">Thông tin lương</th>
                                </tr>
                                <tr style="background-color: whitesmoke;">
                                    <th class="text-center qc-padding-none">Lương</th>
                                    <th class="text-center qc-padding-none">Đã thanh toán</th>
                                    <th class="text-center qc-padding-none">Còn lại</th>
                                </tr>
                                <tr>
                                    <td class="text-center qc-padding-none">
                                        {!! $hFunction->currencyFormat($dataSalary->salary()) !!}
                                    </td>
                                    <td class="text-center qc-padding-none">
                                        {!! $hFunction->currencyFormat($totalPaid) !!}
                                    </td>
                                    <td class="text-center qc-padding-none">
                                        {!! $hFunction->currencyFormat($salaryPay-$totalPaid) !!}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 20px;">
                <?php
                $dataSalaryPayInfo = $dataSalary->infoSalaryPay();
                ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr class="qc-color-red" >
                                <th class="qc-padding-none" colspan="7">Chi tiết thanh toán</th>
                            </tr>
                            <tr style="background-color: whitesmoke;">
                                <th class="text-center qc-padding-none">STT</th>
                                <th class="text-center qc-padding-none">Ngày</th>
                                <th class="qc-padding-none">Thủ quỹ</th>
                                <th class="text-right qc-padding-none">Số tiền</th>

                            </tr>
                            @if(count($dataSalaryPayInfo) > 0)
                                @foreach($dataSalaryPayInfo as $salaryPay)
                                    <?php
                                    $n_o = (isset($n_o)) ? $n_o + 1 : 1
                                    ?>
                                    <tr>
                                        <td class="text-center qc-padding-none">
                                            {!! $n_o !!}
                                        </td>
                                        <td class="text-center qc-padding-none">
                                            {!! date('d/m/Y',strtotime($salaryPay->datePay()))  !!}
                                        </td>
                                        <td class="qc-padding-none">
                                            {!! $salaryPay->staff->fullName()  !!}
                                        </td>
                                        <td class="text-right qc-padding-none">
                                            {!! $hFunction->currencyFormat($salaryPay->money())  !!}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center qc-color-red qc-padding-none" colspan="5">
                                        không có thông tin thanh toán
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>

            </div>
        </div>

        <div class="row">
            <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <button type="button" class="qc_ad3d_container_close btn btn-sm btn-primary">
                    Đóng
                </button>
            </div>
        </div>
    </div>
@endsection
