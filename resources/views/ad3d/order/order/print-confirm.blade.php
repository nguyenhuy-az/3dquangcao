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

//thong tin cong ty
$dataCompany = $dataOrder->company;


//thong tin đon hang
$orderCode = $dataOrder->orderCode();
$orderReceiveDate = $dataOrder->receiveDate();
$totalMoneyUnpaid = $dataOrder->totalMoneyUnpaid();

//thong tin khach hang
$dataCustomer = $dataOrder->customer;
$customerName = $dataCustomer->name();

?>
@extends('ad3d.order.order.index')
@section('titlePage')
    In nghiệm thu
@endsection
@section('qc_ad3d_order_order')
    <div id="qc_ad3d_order_order_print_wrap" class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-8 col-lg-offset-2" style="border-bottom: 1px dashed #d7d7d7;">
            <div class="row">
                <div class="qc-padding-bot-5 text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <b>CỘNG HOÀ XÃ HỘI CHỦ NGHĨA VIỆT NAM</b>
                    <br/>
                    <span>Độc lập – Tự do – Hạnh phúc</span>
                </div>
                <div class="text-center qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <b class="qc-font-size-20">PHIẾU NGHIỆM THU</b>
                </div>
                <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <p> - Căn cứ vào đơn hàng đã ký số: <b>{!! $orderCode !!}</b> giữa <b
                                style="text-transform: capitalize;">Cty 3D Quảng Cáo</b> và
                        Ông/Bà <b style="text-transform: capitalize;">{!! $customerName !!}</b> vào Ngày
                        <b>{!! date('d/m/Y', strtotime($orderReceiveDate)) !!}</b>
                    </p>

                    <p>Hôm nay, Ngày ..... tháng ..... năm 20..... tại Công ty ........................., chúng tôi bao
                        gồm:</p>

                    <p>
                        <b>Bên A: ..................................................................................</b><br/>
                        <span>Mã số thuế: ...........................................................................</span><br/>
                        <span>Điên thoại: ............................................................................</span><br/>
                        <span>Địa chỉ: .................................................................................</span><br/>
                    </p>

                    <p>
                        <b>Bên B: {!! $dataCompany->name() !!}</b><br/>
                        <span>Đại diện: {!! $dataStaffLogin->fullName() !!}</span><br/>
                        <span>Chức vụ: .........................................................................</span><br/>
                        <span>Địa chỉ: {!! $dataCompany->address() !!}</span><br/>
                        <span>Điện thoại: {!! $dataCompany->phone() !!}</span><br/>
                        <span>Mã số thuế: {!! $dataCompany->companyCode() !!}</span><br/>
                    </p>

                    <p>
                        Hai bên đã nhất trí lập biên bản nghiệm thu và bàn giao sản phẩm/dịch vụ theo thỏa thuận đã ký
                        của đơn hàng số <b>{!! $orderCode !!}</b>
                        vào ngày <b>{!! date('d/m/Y', strtotime($orderReceiveDate)) !!}</b>
                    </p>
                    <b>Điều 1: Nội dung:</b>

                    <p>- Bên B bàn giao cho bên A</p>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr style="background-color: whitesmoke;">
                                        <th></th>
                                        <th>Tên SP</th>
                                        <th>Thiết kế</th>
                                        <th>Ghi chú</th>
                                        <th class="text-center">Dài(m)</th>
                                        <th class="text-center">Rộng (m)</th>
                                        <th class="text-center">Số lượng</th>
                                        <th class="text-right">Giá/SP</th>
                                        <th class="text-right">Thành tiền</th>
                                    </tr>
                                    <?php
                                    $dataProduct = $dataOrder->allProductOfOrder();
                                    $n_o = 0;
                                    ?>
                                    @if(count($dataProduct) > 0)
                                        @foreach($dataProduct as $product)
                                            <?php
                                            $designImage = $product->designImage();
                                            ?>
                                            <tr>
                                                <td class="text-center">
                                                    {!! $n_o = $n_o+ 1  !!}
                                                </td>
                                                <td>
                                                    {!! $product->productType->name()  !!}
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
                                                    {!! $product->description() !!}
                                                </td>
                                                <td class="text-center">
                                                    {!! $product->width()/1000 !!}
                                                </td>
                                                <td class="text-center" >
                                                    {!! $product->height()/1000 !!}
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
                                    @else
                                        <tr>
                                            <td class="text-right" colspan="8">
                                                <em class="qc-color-red">Không có sản phẩm</em>
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                    <p>
                        - Bên A bàn giao cho bên B <br/>
                        &emsp;+ Tổng tiền còn lại:
                        <b>{!! $hFunction->dotNumber($totalMoneyUnpaid) !!}</b><br/>
                        &emsp;+ Bằng chữ: <b>@if($totalMoneyUnpaid == 0) Không @else {!! $hFunction->convertNumberToWord($totalMoneyUnpaid) !!} @endif đồng</b><br/>
                        (Chưa bao gồm 10 % thuế VAT )
                    </p>
                    <p>
                        - Bên A xác nhân B đã bàn giao đúng như thiết kế hai bên đã thỏa thuận. <br/>
                    </p>

                    <b>Điều 2: Kết luận:</b>
                    <p>
                        - Bên A đã kiểm tra, thẩm đinh kỹ lưỡng chất lượng sản phẩm mà bên B đã bàn giao. <br/>
                        - Kể từ khi bên A nhận đầy đủ số lượng sản phẩm. Bên B hoàn toàn không chiu trách nhiệm về lỗi, chất lượng sản phẩm đã bàn giao. <br/>
                        - Bên A phải thanh toán hết cho bên B ngay sau khi biên bản nghiêm thu, thanh lý hợp đồng ký kết.
                    </p>
                    <em>
                        (Biên bản nghiệm thu, thanh lý hợp đồng được lập thành 2 bản, mỗi bên giữ 1 bản, có giá trị pháp lý như nhau)
                    </em>
                </div>
            </div>
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row text-center">
                    <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <b>Đại diện bên nhận</b>
                        <br/>
                        <em class="qc-color-grey">(Ký và ghi rõ họ tên)</em>
                    </div>

                    <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <b>Đại diện bên giao</b>
                        <br/>
                        <em class="qc-color-grey">(Ký và ghi rõ tên)</em>

                    </div>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <a class="qc_ad3d_order_order_print btn btn-sm btn-primary" onclick="window.print();">
                    In
                </a>
                <a class="btn btn-sm btn-default"
                   onclick="window.location.replace('{!! route('qc.ad3d.order.order.get') !!}');">
                    Đóng
                </a>
            </div>
        </div>
    </div>
@endsection
