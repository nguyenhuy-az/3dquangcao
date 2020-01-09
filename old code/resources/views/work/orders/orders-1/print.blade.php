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
$dataStaffLogin = $modelStaff->loginStaffInfo();
$dataCompany = $dataOrders->company;
$orderId = $dataOrders->orderId();
?>
@extends('work.index')
@section('titlePage')
    In đơn hàng
@endsection
@section('qc_master_header') @endsection
@section('qc_work_body')
    <div id="qc_work_order_print_wrap" class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    @if(!empty($dataCompany->logo()))
                        <img alt="..." src="{!! $dataCompany->pathSmallImage($dataCompany->logo()) !!}"
                             style="width: 100px; height: auto;">
                    @endif
                    <br/>
                    <span>{!! $dataCompany->nameCode().':'.$dataCompany->name() !!}</span>
                    <br/>
                    <em>Đc/:{!! $dataCompany->address() !!}</em>
                </div>
                <div class="text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <b class="qc-font-size-20">HÓA ĐƠN</b>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <b class="qc-font-size-20">{!! $dataOrders->name() !!}</b>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    <em>Mã HĐ:</em>
                    <b class="pull-right">{!! $dataOrders->orderCode() !!}</b>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    <em class="qc-color-grey">Nhận:</em>
                    <b class="pull-right"
                       style="color: brown;">{!! date('d/m/Y', strtotime($dataOrders->receiveDate())) !!}</b>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    <em class="qc-color-grey">Giao:</em>
                    <b class="pull-right"
                       style="color: brown;">{!! date('d/m/Y', strtotime($dataOrders->deliveryDate())) !!}</b>
                </div>
                <div class="qc-padding-bot-5 col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    <em class="qc-color-grey">Người Lập:</em>
                    <b class="pull-right">{!! $dataOrders->staff->fullName() !!}</b>
                    <br/>
                    <span class="pull-right">ĐT: {!! $dataOrders->staff->phone() !!}</span>
                </div>
            </div>

        </div>
        {{-- thông tin khách hàng --}}
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <i class="glyphicon glyphicon-star-empty "></i>
                    <b class="qc-font-size-16">Khách Hàng</b>
                </div>
            </div>
            <div class="row">
                <div class="qc-container-table col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" style="margin-bottom: 0;">
                            <tr style="background-color: whitesmoke;">
                                <th>Tên</th>
                                <th class="text-center">Mã KH</th>
                                <th class="text-center">Điện thoại</th>
                                <th>Địa chỉ</th>
                            </tr>
                            <tr>
                                <td>
                                    {!! $dataOrders->customer->name() !!}
                                </td>
                                <td class="text-center">
                                    {!! $dataOrders->customer->nameCode() !!}
                                </td>
                                <td class="text-center">
                                    {!! $dataOrders->customer->phone() !!}
                                </td>
                                <td>
                                    {!! $dataOrders->customer->address() !!}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- chi tiết đơn hàng --}}
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <i class="glyphicon glyphicon-star-empty"></i>
                    <b class="qc-font-size-16 ">Sản phẩm</b>
                </div>
            </div>
            <div class="row">
                <?php
                $dataProduct = $dataOrders->allProductOfOrder();
                $n_o = 0;
                ?>
                @if(count($dataProduct) > 0)
                    <div class="qc-container-table col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-bordered" style="margin-bottom: 0;">
                                <tr style="background-color: whitesmoke;">
                                    <th class="text-center">STT</th>
                                    <th>Tên SP</th>
                                    <th>Kích thước</th>
                                    <th>THiết kế</th>
                                    <th>Chú thích</th>
                                    <th class="text-right">Số lượng</th>
                                    <th class="text-right">Giá/SP</th>
                                    <th class="text-right">Thành tiền</th>
                                </tr>
                                @foreach($dataProduct as $product)
                                    <?php
                                    $designImage = $product->designImage();
                                    ?>
                                    <tr>
                                        <td class="text-center">
                                            {!! $n_o+=1  !!}
                                        </td>
                                        <td>
                                            {!! $product->productType->name()  !!}
                                        </td>
                                        <td>
                                            {!! $product->width() !!} x {!! $product->height() !!}
                                            x {!! (empty($product->depth())?0:$product->depth()) !!} mm
                                        </td>
                                        <td>
                                            @if(!empty($designImage))
                                                <div style="margin-right: 10px; max-width: 70px; max-height: 70px; padding: 5px 5px; ">
                                                    <img style="max-width: 100%; max-height: 100%;"
                                                         src="{!! $product->pathSmallDesignImage($designImage) !!}">
                                                </div>
                                            @else
                                                <em class="qc-color-grey">Gửi thiết kế sau</em>
                                            @endif
                                        </td>
                                        <td>
                                            {!! $product->description()  !!}
                                        </td>
                                        <td class="text-right">
                                            {!! $product->amount() !!}
                                        </td>
                                        <td class="text-right">
                                            {!! $hFunction->dotNumber($product->price()) !!}
                                        </td>
                                        <td class="text-right">
                                            {!! $hFunction->dotNumber($product->price()*$product->amount()) !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                @else
                    <div class="qc-padding-top-5 text-center col-xs-12 col-sm-12 col-md-6 col-lg-6"
                         style="border: 1px solid #d7d7d7;">
                        <em class="qc-color-red">Không có sản phẩm</em>
                    </div>
                @endif
            </div>
        </div>

        {{-- chi tiết Thanh toán --}}
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <i class="glyphicon glyphicon-star-empty"></i>
                    <b class="qc-font-size-16">Chi tiết thanh toán</b>
                </div>
            </div>
            <div class="row">
                <?php
                $dataOrderPay = $dataOrders->infoOrderPayOfOrder();
                $n_o = 0;
                ?>
                @if(count($dataOrderPay) > 0)
                    <div class="qc-container-table col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-bordered" style="margin-bottom: 0;">
                                <tr style="background-color: whitesmoke;">
                                    <th class="text-center">STT</th>
                                    <th>Ngày</th>
                                    <th>Tên</th>
                                    <th>Điện thoại</th>
                                    <th class="text-right">Số tiền</th>
                                </tr>
                                @foreach($dataOrderPay as $orderPay)
                                    <tr>
                                        <td class="text-center">
                                            {!! $n_o+=1  !!}
                                        </td>
                                        <td>
                                            {!! date('d/m/Y',strtotime($orderPay->datePay()))  !!}
                                        </td>
                                        <td>
                                            @if(!empty($orderPay->payerName()))
                                                <span>{!! $orderPay->payerName() !!}</span>
                                            @else
                                                <span>{!! $orderPay->order->customer->name() !!}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(!empty($orderPay->payerPhone()))
                                                <span>{!! $orderPay->payerPhone() !!}</span>
                                            @else
                                                <span>{!! $orderPay->order->customer->phone() !!}</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            {!! $hFunction->dotNumber($orderPay->money()) !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                @else
                    <div class="text-left qc-padding-top-10 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <em class="qc-color-red">Chưa thanh toán</em>
                    </div>
                @endif
            </div>
        </div>

        {{-- thông tin đơn hàng --}}
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                    <em class="qc-color-grey">Tổng:</em>
                    <b class="pull-right">{!! $hFunction->dotNumber($dataOrders->totalPrice()) !!}</b>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                    <em class="qc-color-grey">Giảm:</em>
                    <b class="pull-right">{!! $hFunction->dotNumber($dataOrders->totalMoneyDiscount()) !!}</b>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                    <em class="qc-color-grey">Thuế:</em>
                    <b class="pull-right">{!! $hFunction->dotNumber($dataOrders->totalMoneyOfVat()) !!}</b>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                    <em class="qc-color-grey">Tổng thanh toán:</em>
                    <b class="pull-right qc-color-red">{!! $hFunction->dotNumber($dataOrders->totalMoneyPayment()) !!}</b>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                    <em class="qc-color-grey">Đã thanh tóan:</em>
                    <b class="pull-right">{!! $hFunction->dotNumber($dataOrders->totalPaid()) !!}</b>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                    <em class="qc-color-grey">Còn lại:</em>
                    <b class="pull-right qc-color-red">{!! $hFunction->dotNumber($dataOrders->totalMoneyUnpaid()) !!}</b>
                </div>
            </div>

        </div>

        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row text-center">
                <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <b>Khách hàng</b>
                    <br/>
                    <em class="qc-color-grey">(Ký tên và ghi rõ họ tên)</em>
                </div>

                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                    <b>Người nhận</b>
                    <br/>
                    <em class="qc-color-grey">(Ký tên và ghi rõ họ tên)</em>

                </div>
            </div>

        </div>


        <div id="qc_work_order_order_print_wrap_act" class="row">
            <div class="qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <a class="qc_work_order_order_print btn btn-sm btn-primary">
                    In
                </a>
                <a class="btn btn-sm btn-default" href="{!! route('qc.work.orders.info.get',$orderId) !!}">
                    Sửa
                </a>
                <a class="btn btn-sm btn-default"
                   onclick="window.location.replace('{!! route('qc.work.orders.get') !!}');">
                    Đóng
                </a>
            </div>
        </div>
    </div>
@endsection
