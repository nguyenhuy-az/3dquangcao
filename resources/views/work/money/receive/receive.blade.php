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
$totalReceiveMoney = 0;
# tong chi
//$totalPaidMoney = $dataStaffLogin->totalPaidMoney($loginStaffId, $dateFilter);

# tong tien giao chua xac nhan
//$totalMoneyTransferUnConfirm = $dataStaffLogin->totalMoneyTransferUnConfirmed($loginStaffId, $dateFilter);
# tong tien giao da xac nhan dong y
//$totalMoneyTransferConfirmedAndAccepted = $dataStaffLogin->totalMoneyTransferConfirmedAndAccepted($loginStaffId, $dateFilter);
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
                </div>
                <div class="qc_work_money_receive_list_content row">
                    <div class="qc-container-table col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <form class="qc_work_frm_transfer_receive" enctype="multipart/form-data"
                              name="qc_work_frm_transfer_receive" role="form" method="post"
                              action="{!! route('qc.work.money.receive.transfer.post') !!}">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <tr style="background-color: black;color: yellow;">
                                        <th style="width: 20px;"></th>
                                        <th>Ngày thu</th>
                                        <th>Mã ĐH</th>
                                        <th>Tên ĐH</th>
                                        <th>Điện thoại</th>
                                        <th class="text-right">Số tiền</th>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td style="padding: 0">
                                            <select class="qc_work_money_receive_filter_month" style="height: 25px;"
                                                    data-href="{!! route('qc.work.money.receive.get') !!}">
                                                <option value="100" @if($monthFilter == 100) selected="selected" @endif>
                                                    Tất cả
                                                </option>
                                                @for($m = 1; $m <=12; $m++)
                                                    <option @if($monthFilter == $m) selected="selected" @endif>
                                                        {!! $m !!}
                                                    </option>
                                                @endfor
                                            </select>
                                            <span>/</span>
                                            <select class="qc_work_money_receive_filter_year" style="height: 25px;"
                                                    data-href="{!! route('qc.work.money.receive.get') !!}">
                                                @for($y = 2017; $y <=2050; $y++)
                                                    <option @if($yearFilter == $y) selected="selected" @endif>
                                                        {!! $y !!}
                                                    </option>
                                                @endfor
                                            </select>
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @if($hFunction->checkCount($dataOrderPay))
                                        <?php
                                        $n_o = 0;
                                        ?>
                                        @foreach($dataOrderPay as $orderPay)
                                            <?php
                                            $totalReceiveMoney = $totalReceiveMoney + $orderPay->money();
                                            $n_o = $n_o + 1;
                                            ?>
                                            <tr @if($n_o%2) class="info" @endif>
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
                                                    @if(!empty($orderPay->payerPhone()))
                                                        <span>{!! $orderPay->payerPhone() !!}</span>
                                                    @else
                                                        <span>{!! $orderPay->order->customer->phone() !!}</span>
                                                    @endif
                                                </td>
                                                <td class="text-right">
                                                    {!! $hFunction->currencyFormat($orderPay->money()) !!}
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td class="text-right qc-color-red"
                                                style="background-color: whitesmoke;" colspan="5">
                                            </td>
                                            <td class="text-right qc-color-red">
                                                {!! $hFunction->currencyFormat($totalReceiveMoney)  !!}
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="7" style="padding: 10px !important;">
                                                <em class="qc-color-red">Không có thông tin thu chưa giao</em>
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                            @if($totalReceiveMoney > 0)
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-group form-group-sm"
                                             style="border-bottom: 1px dashed #d7d7d7;">
                                            <b style="background-color: red; padding: 5px; color: white;">
                                                GIAO TIỀN CHO THỦ QUỸ
                                            </b>
                                        </div>
                                    </div>
                                    @if($hFunction->checkCount($dataStaffReceiveTransfer))
                                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                            <div class="form-group form-group-sm">
                                                <label>Tiền đã thu:</label>
                                                <input class="form-control" type="text" name="txtTotalReceiveMoney"
                                                       readonly
                                                       value="{!! $hFunction->currencyFormat($totalReceiveMoney) !!}">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                            <div class="form-group form-group-sm">
                                                <label>Số tiền giao:</label>
                                                <input class="form-control" type="text" name="txtTransferMoney" readonly
                                                       value="{!! $hFunction->currencyFormat($totalReceiveMoney) !!}">
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                            <div class="form-group form-group-sm">
                                                <label>Người nhận</label> <em style="color: red;">(Là thủ quỹ cty)</em>
                                                <select class="form-control" name="cbStaffReceive">
                                                    <option value="">Chọn người nhận</option>
                                                    @if($hFunction->checkCount($dataStaffReceiveTransfer))
                                                        @foreach($dataStaffReceiveTransfer as $staff)
                                                            <option value="{!! $staff->staffId() !!}">{!! $staff->fullname() !!}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                            <div class="form-group form-group-sm">
                                                <label>Ảnh xác nhận:</label>
                                                <input class="txtTransferImage" type="file" name="txtTransferImage">
                                            </div>
                                        </div>
                                        @if($totalReceiveMoney > 0)
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="form-group form-group-sm">
                                                    <label>Ghi chú:</label>
                                                    <input class="form-control" type="text" name="txtNote" value="">
                                                </div>
                                            </div>
                                            <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="text-center form-group form-group-sm">
                                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                                    <button type="button"
                                                            class="qc_transfer_save btn btn-sm btn-primary">
                                                        Giao
                                                    </button>
                                                    <button type="reset" class="btn btn-sm btn-default">Nhập lại
                                                    </button>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="form-group form-group-sm">
                                                    <span style="font-size: 1.5em; color: deeppink;">
                                                        Không có tiền để giao
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group form-group-sm">
                                                <span style="color: deeppink;">Không có thông tin người nhận</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
