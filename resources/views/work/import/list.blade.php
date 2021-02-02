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
$dataStaff = $modelStaff->loginStaffInfo();
$loginStaffId = $dataStaff->staffId();
$hrefIndex = route('qc.work.import.get');
$currentMonth = $hFunction->currentMonth();
# lay trang thai thanh toan mac dinh
$getDefaultNotPay = $modelImport->getDefaultNotPay(); // chua thanh toan
$getDefaultHasPay = $modelImport->getDefaultHasPay(); // da thanh toan
$getDefaultAllPay = $modelImport->getDefaultAllPay();// tat ca trang thai thanh toan
?>
@extends('work.import.index')
@section('qc_work_import_body')
    <div class="row qc_work_import_wrap">
        <div class="qc-padding-bot-20 col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: black;color: yellow;">
                                <th style="width: 250px;">HÓA ĐƠN</th>
                                <th>SỐ TIỀN</th>
                            </tr>
                            <tr>
                                <td style="padding: 0 !important;">
                                    <select class="qc_work_import_day_filter col-xs-4 col-sm-4 col-md-4 col-lg-4"
                                            style="padding: 0; height: 34px;"
                                            data-href="{!! $hrefIndex !!}">
                                        <option value="100" @if($dayFilter == null) selected="selected" @endif>
                                            Tất cả
                                        </option>
                                        @for($d = 1; $d <=31; $d++)
                                            <option value="{!! $d !!}" @if($dayFilter == $d) selected="selected" @endif>
                                                Ngày {!! $d !!}
                                            </option>
                                        @endfor
                                    </select>
                                    <select class="qc_work_import_month_filter col-xs-4 col-sm-4 col-md-4 col-lg-4"
                                            style="padding: 0; height: 34px;"
                                            data-href="{!! $hrefIndex !!}">
                                        <option value="100" @if((int)$monthFilter == 100) selected="selected" @endif >
                                            Tất cả
                                        </option>
                                        @for($m = 1; $m <=12; $m++)
                                            <option value="{!! $m !!}"
                                                    @if($monthFilter == $m) selected="selected" @endif>
                                                {!! $m !!}
                                            </option>
                                        @endfor
                                    </select>
                                    <select class="qc_work_import_year_filter col-xs-4 col-sm-4 col-md-4 col-lg-4"
                                            style="padding: 0; height: 34px;"
                                            data-href="{!! $hrefIndex !!}">
                                        <option value="100" @if((int)$yearFilter == 100) selected="selected" @endif >
                                            Tất cả
                                        </option>
                                        @for($y = 2017; $y <=2050; $y++)
                                            <option value="{!! $y !!}"
                                                    @if($yearFilter == $y) selected="selected" @endif>
                                                {!! $y !!}
                                            </option>
                                        @endfor
                                    </select>
                                </td>
                                <td class="text-center" style="padding: 0;">
                                    <select class="qc_work_import_pay_status form-control"
                                            data-href="{!! $hrefIndex !!}">
                                        <option value="{!! $getDefaultAllPay !!}" @if($loginPayStatus == $getDefaultAllPay) selected="selected" @endif>
                                            Tất cả
                                        </option>
                                        <option value="{!! $getDefaultNotPay !!}"
                                                @if($loginPayStatus == $getDefaultNotPay) selected="selected" @endif>
                                            Chưa thanh toán
                                        </option>
                                        <option value="{!! $getDefaultHasPay !!}"
                                                @if($loginPayStatus == $getDefaultHasPay) selected="selected" @endif>
                                            Đã thanh toán
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            @if($hFunction->checkCount($dataImport))
                                <?php
                                $n_o = 0;
                                $sumMoney = 0;
                                ?>
                                @foreach($dataImport as $import)
                                    <?php
                                    $importId = $import->importId();
                                    $image = $import->image();
                                    $confirmNote = $import->confirmNote();
                                    $importDate = $import->importDate();
                                    # tong tien hoa don
                                    $totalMoneyOfImport = $import->totalMoneyOfImport();
                                    $sumMoney = $sumMoney + $totalMoneyOfImport;
                                    # trang thai duyet
                                    $checkHasConfirmStatus = $import->checkHasConfirm();
                                    # kiem tra nhap chinh xac khong
                                    $checkHasExactlyStatus = $import->checkHasExactlyStatus();
                                    # kiem tra da thanh toan chua
                                    $checkHasPayStatus = $import->checkHasPay();
                                    /*
                                    if ($checkHasPayStatus) { # da thanh toan
                                        $moneyPaid = $totalMoneyOfImport;
                                        $sumPaid = $sumPaid + $moneyPaid;
                                        $moneyUnPaid = 0;
                                    } else {
                                        $moneyPaid = 0;
                                        $moneyUnPaid = $totalMoneyOfImport;
                                        $sumUnPaid = $sumUnPaid + $moneyUnPaid;
                                    }
                                    */

                                    # thong tin chi tiet nhap
                                    $dataImportDetail = $import->importDetailGetInfo();
                                    ?>
                                    <tr class="@if(!$checkHasExactlyStatus) danger  @else @if($n_o%2 == 1) info @endif @endif">
                                        <td style="padding-left: 0;">
                                            <div class="media" style="width: 250px;">
                                                <div class="pull-left" href="#">
                                                    <div class="text-center" style="width: 120px;">
                                                        @if(!$hFunction->checkEmpty($image))
                                                            <img class="media-object qc-link" alt="..."
                                                                 style="width: 120px; border: 1px solid black;"
                                                                 src="{!! $import->pathFullImage($image) !!}">
                                                        @else
                                                            <b style="color: red;">KHÔNG CÓ ẢNH HĐ</b>
                                                        @endif
                                                        @if(!$checkHasConfirmStatus)
                                                            <br/>
                                                            <a class="qc_work_import_update_image_get qc-link-red-bold"
                                                               data-href="{!! route('qc.work.import.image.update.get',$importId) !!}">
                                                                CẬP NHẬT ẢNH ĐH
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <b style="color: blue;">{!! date('d-m-Y', strtotime($importDate)) !!}</b>
                                                    @if(!$checkHasConfirmStatus)
                                                        <span>&nbsp | &nbsp</span>
                                                        <a class="qc_work_import_delete qc-link-red-bold"
                                                           data-href="{!! route('qc.work.import.delete.get',$importId) !!}">
                                                            HỦY
                                                        </a>
                                                    @endif
                                                    <br/>
                                                    @if(!$checkHasConfirmStatus)
                                                        <b style="color: grey;">CHƯA DUYỆT</b>
                                                    @else
                                                        <i class="glyphicon glyphicon-ok" style="color: green;"></i>
                                                        <em class="qc-color-grey">Đã Duyệt</em>
                                                        @if(!$hFunction->checkEmpty($confirmNote))
                                                            <br/>
                                                            <span style="color: grey;">- {!! $confirmNote !!}</span>
                                                        @endif
                                                    @endif
                                                    <br/>
                                                    <em style="color: grey;">- Vật tư:</em>
                                                    @if($hFunction->checkCount($dataImportDetail))
                                                        @foreach($dataImportDetail as $importDetail)
                                                            <?php
                                                            $toolId = $importDetail->toolId();
                                                            $suppliesId = $importDetail->suppliesId();
                                                            $newName = $importDetail->newName();
                                                            ?>
                                                            @if(!$hFunction->checkEmpty($toolId))
                                                                <span>{!! $importDetail->tool->name() !!} ,</span>
                                                            @endif
                                                            @if(!$hFunction->checkEmpty($suppliesId))
                                                                <span>{!! $importDetail->supplies->name() !!} ,</span>
                                                            @endif
                                                            <span>{!! $newName !!},</span>
                                                        @endforeach
                                                    @endif
                                                    <br/>
                                                    <a class="qc-link-green"
                                                       href="{!! route('qc.work.import.view.get',$importId) !!}">
                                                        <i class="glyphicon glyphicon-minus"></i>
                                                        Xem chi tiết
                                                    </a>

                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <b style="color: red;">
                                                {!! $hFunction->currencyFormat($totalMoneyOfImport) !!}
                                            </b>
                                            <br/>
                                            @if($checkHasExactlyStatus)
                                                @if($checkHasPayStatus)
                                                    @if($import->importPayCheckHasConfirm($importId))
                                                        <i class="glyphicon glyphicon-ok" style="color: green;"></i>
                                                        <em class="qc-color-grey">Đã Nhận tiền</em>
                                                    @else
                                                        <a class="qc_work_import_confirm_pay_act qc-link"
                                                           style="background-color: red; padding: 3px; color: yellow !important;"
                                                           data-href="{!! route('qc.work.import.confirm_pay.get',$importId) !!}">
                                                            XÁC NHẬN THANH TOÁN
                                                        </a>
                                                    @endif
                                                @else
                                                    <i class="glyphicon glyphicon-ok" style="color: red;"></i>
                                                    <em>Chưa thanh toán</em>
                                                @endif
                                            @else
                                                <em class="qc-color-red">Không được chấp nhận</em>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr style="border-top: 2px solid brown;">
                                    <td class="text-right qc-color-red" style="background-color: whitesmoke;"></td>
                                    <td>
                                        <b class="qc-color-red">{!! $hFunction->currencyFormat($sumMoney)  !!}</b>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="2">
                                        <em class="qc-color-red">Không có thông tin mua</em>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
