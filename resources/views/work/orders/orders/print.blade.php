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
$dataStaffHotline = $dataCompany->hotlineInfoOfConstructionDepartment($dataCompany->companyId());
$hotlineName = $dataStaffHotline->lastName();
$hotlinePhone = $dataStaffHotline->phone();
$hotlineName = (empty($hotlineName))?'Null':$hotlineName;
$hotlinePhone = (empty($hotlinePhone))?'Null':$hotlinePhone;
// san pham cua don hang
$dataProduct = $dataOrders->productActivityOfOrder();
#anh thiet ke tong quat
$dataOrderImage = $dataOrders->orderImageInfoActivity();
?>
@extends('work.orders.index')
@section('titlePage')
    In đơn hàng
@endsection
@section('qc_master_header') @endsection
@section('qc_work_order_body')
    <div class="row">
        <div style="width: 547px;">
            <div id="qc_work_order_print_wrap" class="row" style="padding-top: 10px;">
                <div class="qc-container-table qc-container-table-border-none col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <table class="table" style="margin: 0;">
                        <tr>
                            <td class="text-center" style="width: 200px;">
                                @if(!$hFunction->checkEmpty($dataCompany->logo()))
                                    <img alt="..." src="{!! $dataCompany->pathSmallImage($dataCompany->logo()) !!}"
                                         style="width: 70px; height: auto;">
                                @endif
                                <br/>
                                <span>{!! $dataCompany->name() !!}</span>
                                <br/>
                                <em>Đc/:{!! $dataCompany->address() !!}</em>
                            </td>
                            <td style="width: 347px;">
                                <div class="table-responsive">
                                    <table class="table table-hover qc-margin-bot-none">
                                        <tr>
                                            <td class="qc-color-grey">
                                                <em class=" qc-color-grey">Mã ĐH:</em>
                                                <b>{!! $dataOrders->orderCode() !!}</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="qc-color-grey">
                                                <em >Khách hàng:</em>
                                                <b>{!! $dataOrders->customer->name() !!}</b>
                                                <b> - ĐT: {!! $dataOrders->customer->phone() !!}</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <em class="qc-color-grey">Ngày nhận:</em>
                                                <b>{!! $hFunction->convertDateDMYFromDatetime($dataOrders->receiveDate()) !!}</b>
                                                <span>-</span>
                                                <em class="qc-color-grey">Ngày giao:</em>
                                                <b>{!! $hFunction->convertDateDMYFromDatetime($dataOrders->deliveryDate()) !!}</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <em class="qc-color-grey">Phụ trách sx thi công:</em>
                                                <b>ĐT: {!! $hotlinePhone !!}</b>
                                                &nbsp;
                                                <span>({!! $hotlineName !!})</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <em class="qc-color-grey">NV kinh doanh:</em>
                                                <b>ĐT: 09.077.077.28</b>
                                                &nbsp;
                                                <span>(Mr.Hoàng)</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <em class="qc-color-grey">Đ/c thi công:</em>
                                                <b>
                                                    {!! $dataOrders->constructionAddress() !!}
                                                    - ĐT: {!! $dataOrders->constructionPhone() !!}
                                                    - LH: {!! $dataOrders->constructionContact() !!}
                                                </b>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center" colspan="2">
                                <b class="qc-font-size-16">HÓA ĐƠN ĐẶT HÀNG (ORDER)</b>
                                <p>
                                    <em>Đặt hàng:</em>
                                    <label class="qc-font-size-16">{!! $dataOrders->name() !!}</label>
                                    {{--<i class="glyphicon glyphicon-minus"></i>--}}
                                </p>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="qc-margin-bot-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="qc-container-table col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered" style="margin-bottom: 0;">
                                    <tr style="background-color: whitesmoke;">
                                        <th class="text-center" style="width: 20px;padding: 0;">
                                            <i class="qc-font-size-16 glyphicon glyphicon-shopping-cart"></i>
                                        </th>
                                        <th>Hàng Hóa/DV</th>
                                        <th>Thiết kế</th>
                                        <th>Ghi chú</th>
                                        <th class="text-center" style="width: 20px;">Dài (m)</th>
                                        <th class="text-center" style="width: 20px; padding: 0;">Rộng (m)</th>
                                        <th class="text-center" style="width: 20px; padding: 0;">Đơn vị</th>
                                        <th class="text-center" style="width: 20px; padding: 0;">SL</th>
                                        <th class="text-right">Giá/SP</th>
                                        <th class="text-right">Thành tiền</th>
                                    </tr>
                                    <?php
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
                                                    {!! $n_o+=1  !!}
                                                </td>
                                                <td>
                                                    {!! $product->productType->name()  !!}
                                                </td>
                                                <td style="padding-bottom: 10px;">
                                                    @if($hFunction->checkCount($dataProductDesign))
                                                        @if($dataProductDesign->checkApplyStatus())
                                                            <img style="width: 70px; height: auto; margin: 5px;"
                                                                 title="Đang áp dụng"
                                                                 src="{!! $dataProductDesign->pathSmallImage($dataProductDesign->image()) !!}">
                                                        @else
                                                            <img style="width: 70px; height: 70px; margin-bottom: 5px;"
                                                                 title="Không được áp dụng"
                                                                 src="{!! $dataProductDesign->pathSmallImage($dataProductDesign->image()) !!}">
                                                        @endif
                                                    @else
                                                        @if(!$hFunction->checkEmpty($designImage))
                                                            <img style="width: 70px; height: 70px; margin: 5px; "
                                                                 src="{!! $product->pathSmallDesignImage($designImage) !!}">
                                                        @else
                                                            <em class="qc-color-grey">Gửi thiết kế sau</em>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>
                                                    {!! mb_strtolower($product->description())  !!}
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
                                                <b>
                                                    Tổng tiền VNĐ:
                                                    (Chưa VAT 10%)
                                                </b>
                                            </td>
                                            <td colspan="7"></td>
                                            <td class="text-right">
                                                <b>{!! $hFunction->currencyFormat($dataOrders->totalPrice()) !!}</b>
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
                {{-- Thông tin thanh toán --}}
                <div class="qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="qc-container-table qc-container-table-border-none col-xs-8 col-sm-6 col-md-6-lg-6" style="float: right;">
                            <div class="table-responsive">
                                <table class="table table-hover qc-margin-bot-none">
                                    <tr>
                                        <td>
                                            <em class=" qc-color-grey">Tổng tiền:</em>
                                        </td>
                                        <td class="text-right">
                                            <b>{!! $hFunction->currencyFormat($dataOrders->totalPrice()) !!}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <em class="qc-color-grey">Giảm {!! $dataOrders->discount() !!}%:</em>
                                        </td>
                                        <td class="text-right">
                                            <b>{!! $hFunction->currencyFormat($dataOrders->totalMoneyDiscount()) !!}</b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <em class="qc-color-grey">VAT {!! $dataOrders->vat() !!}%:</em>
                                        </td>
                                        <td class="text-right">
                                            <b>{!! $hFunction->currencyFormat($dataOrders->totalMoneyOfVat()) !!}</b>
                                        </td>
                                    </tr>
                                    <?php
                                    $dataOrderPay = $dataOrders->infoOrderPayOfOrder();
                                    $n_o = 0;
                                    ?>
                                    @if($hFunction->checkCount($dataOrderPay))
                                        @foreach($dataOrderPay as $orderPay)
                                            <tr>
                                                <td>
                                                    <em class="qc-color-grey">TT lần {!! $n_o+=1  !!}:</em>
                                                </td>
                                                <td class="text-right">
                                                    <b class="qc-color-red">{!! $hFunction->currencyFormat($orderPay->money()) !!}</b>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    <tr>
                                        <td>
                                            <em class="qc-color-grey">Còn lại:</em>
                                        </td>
                                        <td class="text-right">
                                            <b class="qc-color-red">{!! $hFunction->currencyFormat($dataOrders->totalMoneyUnpaid()) !!}</b>
                                        </td>
                                    </tr>
                                </table>
                            </div>
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
                            <b>Lập hóa đơn</b>
                            <br/>
                            <em class="qc-color-grey">(Ký tên và ghi rõ họ tên)</em>
                            <br/>
                            <br/>
                            <br/>
                            <span>{!! $dataOrders->staffReceive->fullName() !!}</span>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 30px;">
                        <div class="qc-container-table-border-none text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered" style="margin-bottom: 0px;">
                                    @if($hFunction->checkCount($dataOrderImage))
                                        <tr>
                                            <td class="text-left" style="padding-top: 0 !important;">
                                                <em>***Thiết kế tổng thể</em>
                                            </td>
                                        </tr>
                                        @foreach($dataOrderImage as $orderImage)
                                            <tr>
                                                <td class="text-center" style="padding-top: 0 !important;">
                                                    <a class="qc-link">
                                                        <img style="width: 100%; margin-bottom: 5px;" title="Thiết kế tổng quát"
                                                             src="{!! $orderImage->pathFullImage($orderImage->image()) !!}">
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </table>
                            </div>
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
