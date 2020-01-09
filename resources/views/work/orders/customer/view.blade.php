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
$customerId = $dataCustomer->customerId();
?>
@extends('components.container.container-8')
@section('qc_container_content')
    <div class="qc-padding-top-20 qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <h3>{!! $dataCustomer->name() !!}</h3>
        </div>
        {{-- thông tin khách hàng --}}
        <div class="row">
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-8 col-lg-6">
                <div class="table-responsive">
                    <table class="table table-hover qc-margin-bot-none">
                        <tr>
                            <td>
                                <em class="qc-color-grey">Điện thoại:</em>
                            </td>
                            <td class="text-right">
                                <b>{!! $dataCustomer->phone() !!}</b>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <em class="qc-color-grey">Zalo:</em>
                            </td>
                            <td class="text-right">
                                <b>{!! $dataCustomer->zalo() !!}</b>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <em class="qc-color-grey">ĐC\:</em>
                            </td>
                            <td class="text-right">
                                <b>{!! $dataCustomer->address() !!}</b>
                            </td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>

        {{-- chi tiết Thanh toán --}}
        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row qc-padding-top-10">
                <div class="qc-container-table col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr style="background-color: whitesmoke;">
                                <th colspan="8">
                                    <i class="qc-font-size-16 glyphicon glyphicon-shopping-cart"></i>
                                    <b class="qc-color-red">ĐƠN HÀNG</b>
                                </th>
                            </tr>
                            <tr>
                                <th class="text-center">STT</th>
                                <th>Mã ĐH</th>
                                <th>Tên ĐH</th>
                                <th>Ngày đặt</th>
                                <th>Thông liên hệ</th>
                                <th class="text-right">Tổng tiền<br/>VNĐ</th>
                                <th class="text-right">Giàm<br/>VNĐ</th>
                                <th class="text-right">VAT<br/>VNĐ</th>
                                <th class="text-right">Doanh thu<br/>VNĐ</th>
                            </tr>
                            @if(count($dataOrders) > 0)
                                <?php
                                $n_o = 0;
                                $sumOrderMoney = 0;
                                $sumDiscountMoney = 0;
                                $sumVatMoney = 0;
                                $sumPayMoney = 0;
                                ?>
                                @foreach($dataOrders as $orders)
                                    <?php
                                    $orderId = $orders->orderId();
                                    $orderReceiveDate = $orders->receiveDate();
                                    $totalPrice = $orders->totalPrice();
                                    $sumOrderMoney = $sumOrderMoney + $totalPrice;
                                    $totalVAT = $orders->totalMoneyOfVat(); // tien thue
                                    $sumVatMoney = $sumVatMoney + $totalVAT;
                                    $totalDiscount = $orders->totalMoneyDiscount(); // giam gia
                                    $sumDiscountMoney = $sumDiscountMoney + $totalDiscount;
                                    $totalPay = $totalPrice - $totalDiscount +$sumVatMoney;
                                    $sumPayMoney = $sumPayMoney + $totalPay;
                                    $finishStatus = $orders->checkFinishStatus();
                                    $cancelStatus = $orders->checkCancelStatus();
                                    ?>
                                    <tr class="@if($n_o%2) info @endif">
                                        <td class="text-center">
                                            {!! $n_o += 1 !!}
                                        </td>
                                        <td>
                                            <span class="qc-color-grey">{!! $orders->orderCode() !!}</span><br/>
                                        </td>
                                        <td>
                                            {!! $orders->name() !!}
                                        </td>
                                        <td class="text-center">
                                            {!! date('d/m/Y', strtotime($orderReceiveDate)) !!}
                                        </td>
                                        <td>
                                            @if(!empty($orders->constructionAddress()))  <em>Đ/C:</em>
                                            <span> {!! $orders->constructionAddress() !!}</span> <br/> @endif
                                            @if(!empty($orders->constructionPhone())) <em>Đ/T:</em>
                                            <span>Đ/T: {!! $orders->constructionPhone() !!}</span> <br/> @endif
                                            @if(!empty($orders->constructionContact())) <em>Tên:</em>
                                            <span> {!! $orders->constructionContact() !!}</span> <br/> @endif
                                        </td>
                                        <td class="text-right">
                                            {!! $hFunction->currencyFormat($totalPrice) !!}
                                        </td>
                                        <td class="text-right" style="color: brown;">
                                            {!! $hFunction->currencyFormat($totalDiscount) !!}
                                        </td>
                                        <td class="text-right" style="color: brown;">
                                            {!! $hFunction->currencyFormat($totalVAT) !!}
                                        </td>
                                        <td class="text-right">
                                            {!! $hFunction->currencyFormat($totalPay) !!}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="text-right" colspan="5"
                                        style="background-color: whitesmoke;">
                                    </td>
                                    <td class="text-right qc-color-red">
                                        <b>{!! $hFunction->currencyFormat($sumOrderMoney)  !!}</b>
                                    </td>
                                    <td class="text-right" style="color: blue;">
                                        <b>{!! $hFunction->currencyFormat($sumDiscountMoney)  !!}</b>
                                    </td>
                                    <td class="text-right" style="color: blue;">
                                        <b>{!! $hFunction->currencyFormat($sumVatMoney)  !!}</b>
                                    </td>
                                    <td class="text-right qc-color-red">
                                        <b>{!! $hFunction->currencyFormat($sumPayMoney)  !!}</b>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td class="text-center" colspan="9">
                                        <em class="qc-color-red">Không đơn hàng</em>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="qc-padding-top-10 text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <a class="qc_container_close btn btn-sm btn-primary">Đóng</a>
        </div>
    </div>

@endsection
