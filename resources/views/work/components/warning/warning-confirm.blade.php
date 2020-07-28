<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataCompany
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$dataStaff = $modelStaff->loginStaffInfo();
$mobileStatus = $mobile->isMobile();
$currentYear = date('Y');
?>
@extends('components.container.container-6')
@section('qc_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
                <h3 style="color: red;">CẢNH BÁO</h3>
                <h4 style="color: blue;">CHỈ ĐƯỢC CHẤM CÔNG KHI CÁC THÔNG TIN ĐÃ ĐƯỢC XÁC NHẬN</h4>
            </div>
            <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="table-responsive">
                    <table class="table table-hover table-condensed">
                        {{--chua xac nha phan cong kiem tra do nghe cty--}}
                        @if($dataStaff->existUnConfirmInRoundCompanyStoreCheck())
                            <tr>
                                <td>
                                    <i class="glyphicon glyphicon-arrow-right"></i>
                                    <a class="qc-link-red-bold" href="{!! route('qc.work.tool.check_store.get') !!}"
                                       title="Click để xem thông tin">
                                        CHƯA XÁC NHẬN ĐÃ KIỂM TRA ĐỒ NGHỀ CTY -
                                        <i class="glyphicon glyphicon-eye-open" style="color: green;"></i>
                                        <span style="color: green;">(Click đến xác nhận)</span>
                                    </a>
                                </td>
                            </tr>
                        @endif
                        {{--ton tai chua xac nhan thanh toan luong--}}
                        @if($hFunction->checkCount($dataSalaryPay))
                            <tr>
                                <td>
                                    <i class="glyphicon glyphicon-arrow-right"></i>
                                    <a class="qc-link-red-bold" href="{!! route('qc.work.salary.salary.get') !!}"
                                       title="Click để xem thông tin">
                                        CHƯA XÁC NHẬN THANH TOÁN TIỀN LƯƠNG -
                                        <i class="glyphicon glyphicon-eye-open" style="color: green;"></i>
                                        <span style="color: green;">(Click xem chi tiết)</span>
                                    </a>
                                </td>
                            </tr>
                        @endif
                        {{--ton tai chua xac nhan thanh toan mua vat tu--}}
                        @if($hFunction->checkCount($dataImportPay))
                            <tr>
                                <td>
                                    <i class="glyphicon glyphicon-arrow-right"></i>
                                    <a class="qc-link-red-bold" title="Click để xem thông tin"
                                       href="{!! route('qc.work.import.get',"100/100/$currentYear/1") !!}">
                                        CHƯA XÁC NHẬN ĐÃ THANH TOÁN TIỀN VẬT TƯ. -
                                        <i class="glyphicon glyphicon-eye-open" style="color: green;"></i>
                                        <span style="color: green;">(Click xem chi tiết)</span>
                                    </a>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
