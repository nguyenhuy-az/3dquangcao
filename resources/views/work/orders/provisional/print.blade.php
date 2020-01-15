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
$dataProduct = $dataOrders->allProductOfOrder();
?>
@extends('work.orders.index')
@section('titlePage')
    In Báo giá
@endsection
@section('qc_master_header') @endsection
@section('qc_work_order_body')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-10">
            <div id="qc_work_order_print_wrap" class="row">
                <div class="qc-margin-bot-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
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
                            <label class="qc-font-size-24">BẢNG BÁO GIÁ THIẾT KẾ - THI CÔNG QUẢNG CÁO</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <em style="text-decoration:underline;">Kính gửi:</em> &nbsp;&nbsp;&nbsp;
                            <b>{!! ucwords($dataOrders->customer->name()) !!}</b> <br/>
                            <em style="text-decoration:underline;">Địa chỉ:</em>&nbsp;&nbsp;&nbsp;
                            <b>{!! $dataOrders->customer->address() !!}</b> <br/><br/>

                            <p>
                                Lời đầu tiên, xin trân trọng cảm ơn quý khách hàng đã quan tâm đến sản phẩm của công ty
                                chúng tôi. <b>3D quảng cáo</b> xin gửi đến quý khách hàng
                                bảng báo giá đơn hàng <b>"{!! ucwords($dataOrders->name()) !!}"</b> như sau:
                            </p>
                        </div>
                    </div>
                </div>
                {{-- chi tiết đơn hàng --}}
                <div class="qc-margin-bot-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="qc-container-table col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered" style="margin-bottom: 0;">
                                    <tr style="background-color: whitesmoke;">
                                        <th class="text-center">{!! $dataOrders->orderCode() !!}</th>
                                        <th>Tên SP</th>
                                        <th>Quy cách,mô tả phụ kiện,Ghi chú..</th>
                                        <th>Thiết kế</th>
                                        <th class="text-center">
                                            Dài <br/>(m)
                                        </th>
                                        <th class="text-center">
                                            Rộng <br/> (m)
                                        </th>
                                        <th class="text-center">
                                            Đơn vị
                                        </th>
                                        <th class="text-center">Số lượng</th>
                                        <th class="text-right">Giá/SP</th>
                                        <th class="text-right">
                                            Thành tiền <br/> (VNĐ)
                                        </th>
                                    </tr>
                                    @if(count($dataProduct) > 0)
                                        @foreach($dataProduct as $product)
                                            <?php
                                            $designImage = $product->designImage();
                                            ?>
                                            <tr>
                                                <td class="text-center">
                                                    {!! $n_o = (isset($n_o))?$n_o+1:1  !!}
                                                </td>
                                                <td>
                                                    {!! $product->productType->name()  !!}
                                                </td>
                                                <td>
                                                    {!! $product->description()  !!}
                                                </td>
                                                <td style="padding-bottom: 10px;">
                                                    @if(!empty($designImage))
                                                        <img style="margin-right: 10px; max-width: 70px; max-height: 70px; padding: 5px 5px;"
                                                             src="{!! $product->pathSmallDesignImage($designImage) !!}">
                                                    @else
                                                        <em class="qc-color-grey">Gửi thiết kế sau</em>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {!! $product->width()/1000 !!}
                                                </td>
                                                <td class="text-center">
                                                    {!! $product->height()/1000 !!}
                                                </td>
                                                <td class="text-center">
                                                    @if(!$hFunction->checkEmpty($product->productType->unit()))
                                                        {!! $product->productType->unit()  !!}
                                                    @else
                                                        <em class="qc-color-grey">...</em>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    {!! $product->amount() !!}
                                                </td>
                                                <td class="text-right">
                                                    {!! $hFunction->currencyFormat($product->price()) !!}
                                                </td>
                                                <td class="text-right">
                                                    {!! $hFunction->currencyFormat($product->price()*$product->amount()) !!}
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td class="text-center">
                                            </td>
                                            <td>
                                                <b>Giảm {!! $dataOrders->discount() !!}%</b>
                                            </td>
                                            <td colspan="7">

                                            </td>
                                            <td class="text-right">
                                                <b>{!! $hFunction->currencyFormat($dataOrders->totalMoneyDiscount()) !!}</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                            </td>
                                            <td>
                                                <b>VAT</b>
                                                @if($dataOrders->checkHasVAT())
                                                    <b>10%</b>
                                                @else
                                                    <b>0%</b>
                                                @endif
                                            </td>
                                            <td colspan="7">

                                            </td>
                                            <td class="text-right">
                                                <b>{!! $hFunction->currencyFormat($dataOrders->totalMoneyOfVat()) !!}</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">
                                            </td>
                                            <td>
                                                <label class="qc-color-red">Tổng thanh toán:</label>
                                            </td>
                                            <td colspan="7">

                                            </td>
                                            <td class="text-right">
                                                <b class="qc-color-red">{!! $hFunction->dotNumber($dataOrders->totalMoneyPayment()) !!}</b>
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td class="text-center" colspan="10">
                                                <em class="qc-color-red">Không có sản phẩm</em>
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row text-center">
                        <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <b>Lập báo giá</b>
                            <br/>
                            <em class="qc-color-grey">(Ký tên và ghi rõ họ tên)</em>
                            <br/><br/><br/>
                            <span>{!! $dataOrders->staff->fullName() !!}</span>
                            <br/><br/>

                            <p class="pull-left">

                            </p>

                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                            <b>Người nhận</b>
                            <br/>
                            <em class="qc-color-grey">(Ký tên và ghi rõ họ tên)</em>

                        </div>
                        <div class="text-left col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <label style="text-decoration:underline;">Ghi chú:</label> <br/>
                            <span>- Thời gian thi công trong ..... ngày, kể từ ngày nhận cọc.</span><br/>
                            <span>- 3D quảng cáo chỉ triển khai thi công khi nhận được ..... % tiền cọc</span><br/>
                            <span>- Các chi phí phát sinh khác nếu có: .......................................................</span><br/>
                            <em>Mọi thắc mắc xin liên hệ </em><b>{!! $dataOrders->staff->phone() !!}</b>,<em> cảm
                                ơn!</em>
                        </div>
                    </div>

                </div>


                <div id="qc_work_order_order_print_wrap_act" class="row">
                    <div class="qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <a class="qc_work_order_order_print btn btn-sm btn-primary">
                            In
                        </a>
                        {{--<a class="btn btn-sm btn-default" href="{!! route('qc.work.orders.info.get',$orderId) !!}">
                            Sửa
                        </a>--}}
                        <a class="btn btn-sm btn-default"
                           onclick="window.location.replace('{!! route('qc.work.orders.get') !!}');">
                            Đóng
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
