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
@extends('work.orders.index')
@section('titlePage')
    In nghiệm thu
@endsection
@section('qc_master_header') @endsection
@section('qc_work_order_body')
    <div id="qc_work_order_order_print_confirm_wrap" class="row" style="width: 1094px;">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <table class="table" >
                <tr>
                    <td class="text-center" colspan="2" style="border: none;">
                        <b class="qc-font-size-16">CỘNG HOÀ XÃ HỘI CHỦ NGHĨA VIỆT NAM</b>
                        <br/>
                        <span>Độc lập – Tự do – Hạnh phúc</span>
                    </td>
                </tr>
                <tr>
                    <td class="text-center" colspan="2" style="border: none;">
                        <b class="qc-font-size-20">PHIẾU NGHIỆM THU</b>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="border: none;">
                        <p> - Căn cứ vào đơn hàng đã ký số: <b>{!! $orderCode !!}</b> giữa <b
                                    style="text-transform: capitalize;">Cty 3D Quảng Cáo</b> và
                            Ông/Bà <b style="text-transform: capitalize;">{!! $customerName !!}</b> vào Ngày
                            <b>{!! date('d/m/Y', strtotime($orderReceiveDate)) !!}</b>
                        </p>

                        <p>Hôm nay, Ngày .......... tháng .......... năm 20.......... tại Công ty ...................................................................., chúng tôi bao
                            gồm:</p>

                        <p>
                            <b>Bên A: ......................................................................................................................................................................................................................................................</b><br/>
                            <span>Mã số thuế: ...............................................................................................................................................................................................................................................</span><br/>
                            <span>Điên thoại: .................................................................................................................................................................................................................................................</span><br/>
                            <span>Địa chỉ: ......................................................................................................................................................................................................................................................</span><br/>
                        </p>

                        <p>
                            <b>Bên B: {!! $dataCompany->name() !!}</b><br/>
                            <span>Đại diện: {!! $dataStaffLogin->fullName() !!}</span><br/>
                            <span>Chức vụ: ..................................................................................................................................................................................................................................................</span><br/>
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
                            <br/>
                        <span>- Bên B bàn giao cho bên A</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="border: none; padding-top: 0; padding-bottom: 0;">
                        <div style="width: 1030px;">
                            <div class="table-responsive">
                                <table class="table table-bordered" style="margin: 0;">
                                    <tr style="background-color: whitesmoke;">
                                        <th></th>
                                        <th>Tên SP</th>
                                        <th>Thiết kế</th>
                                        <th>Ghi chú</th>
                                        <th class="text-center" style="width: 20px;">Dài (m)</th>
                                        <th class="text-center" style="width: 20px; padding: 0;">Rộng (m)</th>
                                        <th class="text-center" style="width: 20px; padding: 0;">Đơn vị</th>
                                        <th class="text-center">SL</th>
                                        <th class="text-right">Giá/SP</th>
                                        <th class="text-right">Thành tiền</th>
                                    </tr>
                                    <?php
                                    $dataProduct = $dataOrder->allProductOfOrder();
                                    $n_o = 0;
                                    ?>
                                    @if($hFunction->checkCount($dataProduct))
                                        @foreach($dataProduct as $product)
                                            <?php
                                            $designImage = $product->designImage();
                                            # thiet ke dang ap dung
                                            $dataProductDesign = $product->productDesignInfoApplyActivity();
                                            if ($hFunction->getCountFromData($dataProductDesign) == 0) {
                                                # thiet ke sau cung
                                                $dataProductDesign = $product->productDesignInfoLast();
                                            }
                                            ?>
                                            <tr>
                                                <td class="text-center">
                                                    {!! $n_o = $n_o+ 1  !!}
                                                </td>
                                                <td>
                                                    {!! $product->productType->name()  !!}
                                                </td>
                                                <td>
                                                    @if($hFunction->checkCount($dataProductDesign))
                                                        @if($dataProductDesign->checkApplyStatus())
                                                            <img style="width: 70px; height: auto;"
                                                                 title="Đang áp dụng"
                                                                 src="{!! $dataProductDesign->pathSmallImage($dataProductDesign->image()) !!}">
                                                        @else
                                                            <img style="width: 70px; height: 70px;"
                                                                 title="Không được áp dụng"
                                                                 src="{!! $dataProductDesign->pathSmallImage($dataProductDesign->image()) !!}">
                                                        @endif
                                                    @else
                                                        @if(!$hFunction->checkEmpty($designImage))
                                                            <img style="width: 70px; height: 70px;"
                                                                 src="{!! $product->pathSmallDesignImage($designImage) !!}">
                                                        @else
                                                            <em class="qc-color-grey">Gửi thiết kế sau</em>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>
                                                    {!! $product->description() !!}
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
                                    @else
                                        <tr>
                                            <td class="text-right" colspan="9">
                                                <em class="qc-color-red">Không có sản phẩm</em>
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="border: none;">
                        <p>
                            - Bên A bàn giao cho bên B <br/>
                            &emsp;+ Tổng tiền còn lại:
                            <b>{!! $hFunction->dotNumber($totalMoneyUnpaid) !!}</b><br/>
                            &emsp;+ Bằng chữ: <b>@if($totalMoneyUnpaid == 0)
                                    Không @else {!! $hFunction->convertNumberToWord($totalMoneyUnpaid) !!} @endif
                                đồng</b><br/>
                            (Chưa bao gồm 10 % thuế VAT )
                        </p>

                        <p>
                            - Bên A xác nhân B đã bàn giao đúng như thiết kế hai bên đã thỏa thuận. <br/>
                        </p>

                        <b>Điều 2: Kết luận:</b>

                        <p>
                            - Bên A đã kiểm tra, thẩm đinh kỹ lưỡng chất lượng sản phẩm mà bên B đã bàn giao. <br/>
                            - Kể từ khi bên A nhận đầy đủ số lượng sản phẩm. Bên B hoàn toàn không chiu trách nhiệm về lỗi bị hư bễ, trầy xướt, mất cắp, chất lượng sản phẩm đã bàn giao bị tác động do bên nhận bảo quản.. <br/>
                            - Bên A phải thanh toán hết cho bên B ngay sau khi biên bản nghiêm thu, thanh lý hợp đồng ký
                            kết.
                        </p>
                        <em>
                            (Biên bản nghiệm thu, thanh lý hợp đồng được lập thành 2 bản, mỗi bên giữ 1 bản, có giá trị pháp
                            lý như nhau)
                        </em>
                    </td>
                </tr>
                <tr>
                    <td class="text-center" style="border: none;">
                        <b>Đại diện bên nhận</b>
                        <br/>
                        <em class="qc-color-grey">(Ký và ghi rõ họ tên)</em>
                    </td>
                    <td class="text-center" style="border: none;">
                        <b>Đại diện bên giao</b>
                        <br/>
                        <em class="qc-color-grey">(Ký và ghi rõ tên)</em>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="border: none;">
                        <b>Ghi chú:</b>
                        <br/>
                        <span>..............................................................................................................................................................</span>
                        <br/>
                        <span>..............................................................................................................................................................</span>
                        <br/>
                        <span>..............................................................................................................................................................</span>
                        <br/>
                        <span>..............................................................................................................................................................</span>
                        <br/>
                        <span>..............................................................................................................................................................</span>
                    </td>
                </tr>
            </table>
        </div>
        <div id="qc_work_order_order_print_confirm_wrap_act" class="row">
            <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <a class="qc_print btn btn-sm btn-primary" >
                    In
                </a>
                <a class="btn btn-sm btn-default"
                   onclick="qc_main.page_back();">
                    Đóng
                </a>
            </div>
        </div>
    </div>
@endsection
