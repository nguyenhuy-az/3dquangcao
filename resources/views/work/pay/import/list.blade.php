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
$hrefIndex = route('qc.work.pay.import.get');
$currentMonth = $hFunction->currentMonth();
?>
@extends('work.pay.import.index')
@section('qc_work_pay_import_body')
    <div class="row qc_work_pay_import_wrap">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="margin-bottom: 10px; padding-top : 10px;">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <a class="qc-link-green-bold" href="{!! $hrefIndex !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20">THÔNG TIN NHẬP</label>
                </div>
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <tr style="background-color: black; color: yellow;">
                        <th class="text-center" style="width:20px;">STT</th>
                        <th>Hóa đơn</th>
                        <th class="text-center" style="width: 180px;">Ngày</th>
                        <th> Dụng cụ / Vật tư</th>
                        <th>Nhân viên</th>
                        <th>Chi chú duyệt</th>
                        <th>Thanh toán</th>
                        <th class="text-center">Duyệt</th>
                        <th class="text-right">Thành tiền</th>
                    </tr>
                    <tr>
                        <td class="text-center qc-color-red"></td>
                        <td></td>
                        <td style="padding: 0;">
                            <select class="cbMonthFilter" style="height: 30px;" data-href="{!! $hrefIndex !!}">
                                <option value="100" @if($monthFilter == 100) selected="selected" @endif>Tất cả</option>
                                @for($m =1;$m<= 12; $m++)
                                    <option value="{!! $m !!}"
                                            @if((int)$monthFilter == $m) selected="selected" @endif>
                                        {!! $m !!}
                                    </option>
                                @endfor
                            </select>
                            <select class="cbYearFilter" style="height: 30px;" data-href="{!! $hrefIndex !!}">
                                <option value="100" @if($yearFilter == 100) selected="selected" @endif>Tất cả</option>
                                @for($y =2017;$y<= 2050; $y++)
                                    <option value="{!! $y !!}" @if($yearFilter == $y) selected="selected" @endif>
                                        {!! $y !!}
                                    </option>
                                @endfor
                            </select>
                        </td>
                        <td></td>
                        <td class="text-center" style="padding: 0px; margin: 0;">
                            <select class="cbStaffFilterId form-control" data-href="{!! $hrefIndex !!}">
                                <option value="0" @if($staffFilterId == 0) selected="selected" @endif>
                                    Tất cả
                                </option>
                                @if($hFunction->checkCount($dataListStaff))
                                    @foreach($dataListStaff as $staff)
                                        <option @if($staff->staffId() == $staffFilterId) selected="selected"
                                                @endif  value="{!! $staff->staffId() !!}">{!! $staff->lastName() !!}</option>
                                    @endforeach
                                @endif
                            </select>
                        </td>
                        <td></td>
                        <td class="text-center" style="padding: 0px; margin: 0;">
                            <select class="cbPayStatusFilter form-control" data-href="{!! $hrefIndex !!}">
                                <option value="4" @if($payStatusFilter == 4) selected="selected" @endif>
                                    Tất cả
                                </option>
                                <option value="0" @if($payStatusFilter == 0) selected="selected" @endif>
                                    Chưa thanh toán
                                </option>
                                <option value="1" @if($payStatusFilter == 1) selected="selected" @endif>
                                    Đã thanh toán
                                </option>
                                <option value="2" @if($payStatusFilter == 2) selected="selected" @endif>
                                    Thanh toán - Chưa xác nhận
                                </option>
                                <option value="3" @if($payStatusFilter == 3) selected="selected" @endif>
                                    Thanh toán - Đã xác nhận
                                </option>
                            </select>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    @if($hFunction->checkCount($dataImport))
                        <?php
                        $perPage = $dataImport->perPage();
                        $currentPage = $dataImport->currentPage();
                        $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                        ?>
                        @foreach($dataImport as $import)
                            <?php
                            $importId = $import->importId();
                            $importDate = $import->importDate();
                            $companyImportId = $import->companyId();
                            $confirmStatus = $import->checkConfirm();
                            $dataImportPay = $import->importPayInfo();
                            # thong tin chi tiet nhap
                            $dataImportDetail = $import->infoDetailOfImport();
                            #anh hoa don
                            $dataImportImage = $import->importImageInfoOfImport();
                            ?>
                            <tr class="@if(!$import->checkExactlyStatus()) danger  @else @if($n_o%2 == 1) info @endif @endif "
                                data-object="{!! $importId !!}">
                                <td class="text-center">
                                    {!! $n_o += 1 !!}
                                </td>
                                <td style="padding: 0;">
                                    @foreach($dataImportImage as $importImage)
                                        <img class="qc-link" alt="..." style="width: 150px;"
                                             src="{!! $importImage->pathFullImage($importImage->name()) !!}">
                                    @endforeach
                                </td>
                                <td>
                                    <a class="qc-link-green"
                                       href="{!! route('qc.work.pay.import.get.view.get',$importId) !!}">
                                        {!! date('d-m-Y', strtotime($importDate)) !!}
                                        <br/>
                                        <i class="glyphicon glyphicon-eye-open"></i>
                                    </a>
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
                                <td>
                                    {!! $import->staffImport->fullName() !!}
                                </td>
                                <td>
                                    <em class="qc-color-grey">{!! $import->confirmNote() !!}</em>
                                </td>
                                <td class="text-center">
                                    @if($import->checkExactlyStatus())
                                        @if($import->checkPay())
                                            <em>Đã thanh toán</em>
                                            @if($import->checkPayConfirmOfImport($importId))
                                                <br/>
                                                <i class="glyphicon glyphicon-ok" style="color: green;"
                                                   title="Đã xác nhận"></i>
                                            @else
                                                <br/>
                                                <i class="glyphicon glyphicon-ok" style="color: red;"
                                                   title="Chưa xác nhận"></i>
                                            @endif
                                        @else
                                            <em class="qc-color-grey">Chưa thanh toán</em>
                                            @if($import->checkConfirm())
                                                <br/>
                                                <a class="qc-link-green qc_import_pay_get"
                                                   data-href="{!! route('qc.work.pay.import.pay.get', $importId) !!}">
                                                    Thanh toán
                                                </a>
                                            @endif

                                        @endif
                                    @else
                                        <em class="qc-color-red">Nhập không đúng</em>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if(!$confirmStatus)
                                        <a class="qc_confirm_get qc-link-green-bold"
                                           data-href="{!! route('qc.work.pay.import.confirm.get',$importId) !!}">
                                            Duyệt
                                        </a>
                                    @else
                                        <em class="qc-color-grey">Đã Duyệt</em>
                                        <br/>
                                        <i class="glyphicon glyphicon-ok" style="color: green;"></i>
                                    @endif
                                </td>
                                <td class="text-right" style="color: red;">
                                    <b>{!! $hFunction->currencyFormat($import->totalMoneyOfImport()) !!}</b>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="qc-padding-top-5 qc-padding-bot-5 text-center" colspan="9">
                                <em class="qc-color-red">Không tìm thấy thông tin phù hợp</em>
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td class="qc-padding-top-5 qc-padding-bot-5 text-center" colspan="9">
                            <div class="row">
                                <div class="text-center qc-padding-top-10 qc-padding-bot-10 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    {!! $hFunction->page($dataImport) !!}
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection
