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
?>
@extends('work.import.index')
@section('qc_work_import_body')
    <div class="row qc_work_import_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: black;color: yellow;">
                                <th style="width: 20px;"></th>
                                <th>Hóa đơn</th>
                                <th style="width: 190px;">Ngày chi</th>
                                <th></th>
                                <th class="text-center">Thanh toán</th>
                                <th class="text-center">Duyệt</th>
                                <th>Chi chú duyệt</th>
                                <th class="text-right">Số tiền</th>
                                <th class="text-right">Đã thanh toán</th>
                                <th class="text-right">Chưa thanh toán</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td style="padding: 0 !important;">
                                    <select class="qc_work_import_day_filter" style="height: 30px;"
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
                                    <select class="qc_work_import_month_filter" style="height: 30px;"
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
                                    <select class="qc_work_import_year_filter" style="height: 30px;"
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
                                <th></th>
                                <td class="text-center" style="padding: 0;">
                                    <select class="qc_work_import_pay_status form-control"
                                            data-href="{!! $hrefIndex !!}"
                                            style="border: 0;">
                                        <option value="3" @if($loginPayStatus == 3) selected="selected" @endif>
                                            Tất cả
                                        </option>
                                        <option value="0" @if($loginPayStatus == 0) selected="selected" @endif>
                                            Chưa thanh toán
                                        </option>
                                        <option value="1" @if($loginPayStatus == 1) selected="selected" @endif>
                                            Đã thanh toán
                                        </option>
                                    </select>
                                </td>
                                <td class="text-center"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                            </tr>
                            @if($hFunction->checkCount($dataImport))
                                <?php
                                $n_o = 0;
                                $sumMoney = 0;
                                $sumPaid = 0;
                                $sumUnPaid = 0
                                ?>
                                @foreach($dataImport as $import)
                                    <?php
                                    $importId = $import->importId();
                                    $importDate = $import->importDate();
                                    $totalMoneyOfImport = $import->totalMoneyOfImport();
                                    $sumMoney = $sumMoney + $totalMoneyOfImport;
                                    if ($import->checkPay()) { # da thanh toan
                                        $moneyPaid = $totalMoneyOfImport;
                                        $sumPaid = $sumPaid + $moneyPaid;
                                        $moneyUnPaid = 0;
                                    } else {
                                        $moneyPaid = 0;
                                        $moneyUnPaid = $totalMoneyOfImport;
                                        $sumUnPaid = $sumUnPaid + $moneyUnPaid;
                                    }
                                    # thong tin chi tiet nhap
                                    $dataImportDetail = $import->infoDetailOfImport();
                                    #anh hoa don
                                    $dataImportImage = $import->importImageInfoOfImport();
                                    ?>
                                    <tr class="@if(!$import->checkExactlyStatus()) danger  @else @if($n_o%2 == 1) info @endif @endif">
                                        <td class="text-center">
                                            {!! $n_o = $n_o + 1 !!}
                                        </td>
                                        <td>
                                            @foreach($dataImportImage as $importImage)
                                                <img class="qc-link" alt="..." style="width: 150px;"
                                                     src="{!! $importImage->pathFullImage($importImage->name()) !!}">
                                            @endforeach
                                        </td>
                                        <td>
                                            <a class="qc-link-green"
                                               href="{!! route('qc.work.import.view.get',$importId) !!}">
                                                {!! date('d-m-Y', strtotime($importDate)) !!}
                                                <br/>
                                                <i class="glyphicon glyphicon-eye-open"></i>
                                            </a>
                                            @if(!$import->checkConfirm())
                                                <span>&nbsp | &nbsp</span>
                                                <a class="qc_work_import_delete qc-link-red-bold"
                                                   data-href="{!! route('qc.work.import.delete.get',$importId) !!}">
                                                    Hủy
                                                </a>
                                            @endif
                                        </td>
                                        <td>
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
                                            @else
                                                <em style="color: grey;">Không có dữ liệu</em>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($import->checkExactlyStatus())
                                                @if($import->checkPay())
                                                    @if($import->checkPayConfirmOfImport($importId))
                                                        <em class="qc-color-grey">Đã Nhận tiền</em>
                                                    @else
                                                        <a class="qc_work_import_confirm_pay_act qc-link"
                                                           style="background-color: red; padding: 3px; color: yellow !important;"
                                                           data-href="{!! route('qc.work.import.confirm_pay.get',$importId) !!}">
                                                            Xác nhận thanh toán
                                                        </a>
                                                    @endif
                                                @else
                                                    <em>Chưa thanh toán</em>
                                                @endif
                                            @else
                                                <em class="qc-color-red">Không được chấp nhận</em>
                                            @endif

                                        </td>
                                        <td class="text-center" style="color: grey;">
                                            @if($import->checkConfirm())
                                                <em>Đã duyệt</em>
                                            @else
                                                <em>Chưa duyệt</em>
                                            @endif
                                        </td>
                                        <td>
                                            <em class="qc-color-grey">{!! $import->confirmNote() !!}</em>
                                        </td>
                                        <td class="text-right">
                                            <span style="color: blue;">
                                                {!! $hFunction->currencyFormat($totalMoneyOfImport) !!}
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            {!! $hFunction->currencyFormat($moneyPaid)  !!}
                                        </td>
                                        <td class="text-right">
                                            {!! $hFunction->currencyFormat($moneyUnPaid)  !!}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr style="border-top: 2px solid brown;">
                                    <td class="text-right qc-color-red"
                                        style="background-color: whitesmoke;" colspan="7"></td>
                                    <td class="text-right qc-color-red">
                                        {!! $hFunction->currencyFormat($sumMoney)  !!}
                                    </td>
                                    <td class="text-right qc-color-red">
                                        {!! $hFunction->currencyFormat($sumPaid)  !!}
                                    </td>
                                    <td class="text-right qc-color-red">
                                        {!! $hFunction->currencyFormat($sumUnPaid)  !!}
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td class="text-right" colspan="10">
                                        <em class="qc-color-red">Không có thông tin mua</em>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <a class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                        Về trang trước
                    </a>
                    <a class="btn btn-sm btn-default" href="{!! route('qc.work.home') !!}">
                        Về trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
