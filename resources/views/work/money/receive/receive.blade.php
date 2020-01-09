<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 5/15/2018
 * Time: 10:12 AM
 *
 * dataStaff
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$dataStaffLogin = $modelStaff->loginStaffInfo();
$loginStaffId = $dataStaffLogin->staffId();

$dataOrderPay = null;
$currentMonth = $hFunction->currentMonth();
If (empty($loginDay)) {
    $loginDate = date('Y-m', strtotime("$loginYear-$loginMonth"));
} else {
    $loginDate = date('Y-m-d', strtotime("$loginYear-$loginMonth-$loginDay"));
}
$dataOrderPay = $dataStaffLogin->orderPayNoTransferOfStaff($loginStaffId, null);
$totalMoney = 0;
?>
@extends('work.index')
@section('titlePage')
    Thu
@endsection
@section('qc_work_body')
    <div class="row">
        <div class="qc_work_money_receive_wrap qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            @include('work.money.money-menu')

            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <label class="qc-color-red">Thu chưa bàn giao</label>
                    </div>
                    {{--<div class="text-right qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">

                        <select class="qc_work_money_receive_login_year" style="height: 25px;"
                                data-href="{!! route('qc.work.money.receive.get') !!}">
                            <option @if($loginDay == null) selected="selected" @endif>
                                Trong tháng
                            </option>
                            @for($i = 1; $i <=31; $i++)
                                <option @if($loginDay == $i) selected="selected" @endif>
                                    {!! $i !!}
                                </option>
                            @endfor
                        </select>
                        <span>/</span>
                        <select class="qc_work_money_receive_login_month" style="height: 25px;"
                                data-href="{!! route('qc.work.money.receive.get') !!}">
                            @for($i = 1; $i <=12; $i++)
                                <option @if($loginMonth == $i) selected="selected" @endif>
                                    {!! $i !!}
                                </option>
                            @endfor
                        </select>
                        <span>/</span>
                        <select class="qc_work_money_receive_login_year" style="height: 25px;"
                                data-href="{!! route('qc.work.money.receive.get') !!}">
                            @for($i = 2017; $i <=2050; $i++)
                                <option @if($loginYear == $i) selected="selected" @endif>
                                    {!! $i !!}
                                </option>
                            @endfor
                        </select>
                    </div>--}}
                </div>
                <div class="qc_work_money_receive_list_content row">
                    <div class="qc-container-table col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <form class="qc_work_frm_transfer_receive"
                              name="qc_work_frm_transfer_receive" role="form" method="post"
                              enctype="multipart/form-data"
                              action="{!! route('qc.work.money.receive.transfer.post') !!}">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <tr>
                                        <th></th>
                                        <th>Ngày</th>
                                        <th>Mã ĐH</th>
                                        <th>Tên ĐH</th>
                                        <th>Ngày nhận ĐH</th>
                                        <th>Điện thoại</th>
                                        <th class="text-right">Số tiền</th>
                                    </tr>
                                    @if(count($dataOrderPay) > 0)
                                        <?php
                                        $n_o = 0;
                                        ?>
                                        @foreach($dataOrderPay as $orderPay)
                                            <?php
                                            $totalMoney = $totalMoney + $orderPay->money();
                                            ?>
                                            <tr>
                                                <td class="text-center">
                                                    <input type="checkbox" checked="checked" name="txtPayShow[]"
                                                           disabled value="{!! $orderPay->payId() !!}">
                                                    <input type="hidden" name="txtOrderPay[]"
                                                           value="{!! $orderPay->payId() !!}">
                                                </td>
                                                <td>
                                                    {!! date('d/m/Y',strtotime($orderPay->datePay()))  !!}
                                                </td>
                                                <td>
                                                    <span class="qc-color-grey">{!! $orderPay->order->orderCode() !!}</span>
                                                </td>
                                                <td>
                                                    <span>{!! $orderPay->order->name() !!}</span>
                                                </td>
                                                <td>
                                                    <span class="qc-color-grey">{!! date('d/m/Y',strtotime($orderPay->order->receiveDate()))  !!}</span>
                                                </td>
                                                <td>
                                                    @if(!empty($orderPay->payerPhone()))
                                                        <span>{!! $orderPay->payerPhone() !!}</span>
                                                    @else
                                                        <span>{!! $orderPay->order->customer->phone() !!}</span>
                                                    @endif
                                                </td>
                                                <td class="text-right">
                                                    {!! number_format($orderPay->money()) !!}
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td class="text-right qc-color-red"
                                                style="background-color: whitesmoke;" colspan="6">
                                            </td>
                                            <td class="text-right qc-color-red">
                                                {!! number_format($totalMoney)  !!}
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td class="qc-padding-top-20 qc-padding-bot-20 text-center" colspan="7">
                                                <em class="qc-color-red">Không có thông tin thu</em>
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                            @if($totalMoney > 0)
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group form-group-sm"
                                             style="border-bottom: 1px dashed #d7d7d7;">
                                            <label class="qc-color-red">Giao tiền</label>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
                                        <div class="form-group form-group-sm">
                                            <label>Số tiền:</label>
                                            <input class="form-control" type="text" name="txtTotalMoney" readonly
                                                   value="{!! number_format($totalMoney) !!}">
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
                                        <div class="form-group form-group-sm">
                                            <label>Người nhận:</label>
                                            <select class="form-control" name="cbStaffReceive">
                                                <option value="">Chọn người nhận</option>
                                                @if(count($dataStaffReceiveTransfer) > 0)
                                                    @foreach($dataStaffReceiveTransfer as $staff)
                                                        <option value="{!! $staff->staffId() !!}">{!! $staff->fullname() !!}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
                                        <div class="form-group form-group-sm">
                                            <label>Ảnh xác nhận:</label>
                                            <input class="txtTransferImage" type="file" name="txtTransferImage">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group form-group-sm">
                                            <label>Ghi chú:</label>
                                            <input class="form-control" type="text" name="txtNote" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="text-center form-group form-group-sm">
                                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                            <button type="button" class="qc_transfer_save btn btn-sm btn-primary">
                                                Giao
                                            </button>
                                            <button type="reset" class="btn btn-sm btn-default">Nhập lại</button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
