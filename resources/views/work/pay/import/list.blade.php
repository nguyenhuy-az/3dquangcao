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
                    <label class="qc-font-size-20">HÓA ĐƠN MUA</label>
                </div>
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <tr style="background-color: black; color: yellow;">
                        <th style="width: 300px;">HÓA ĐƠN</th>
                        <th style="width: 200px;">
                            SỐ TIỀN
                            <br/>
                            <b style="color: white;">{!! $hFunction->currencyFormat($importTotalMoney) !!}</b>
                        </th>
                        <th>NGƯỜI MUA</th>
                    </tr>
                    <tr>
                        <td style="padding: 0;">
                            <select class="cbMonthFilter col-sx-4 col-sm-4 col-md-4 col-lg-4"
                                    style="height: 34px; padding: 0;" data-href="{!! $hrefIndex !!}">
                                <option value="100" @if($monthFilter == 100) selected="selected" @endif>Tất cả</option>
                                @for($m =1;$m<= 12; $m++)
                                    <option value="{!! $m !!}"
                                            @if((int)$monthFilter == $m) selected="selected" @endif>
                                        {!! $m !!}
                                    </option>
                                @endfor
                            </select>
                            <select class="cbYearFilter col-sx-8 col-sm-8 col-md-8 col-lg-8"
                                    style="height: 34px; padding: 0;" data-href="{!! $hrefIndex !!}">
                                <option value="100" @if($yearFilter == 100) selected="selected" @endif>Tất cả</option>
                                @for($y =2017;$y<= 2050; $y++)
                                    <option value="{!! $y !!}" @if($yearFilter == $y) selected="selected" @endif>
                                        {!! $y !!}
                                    </option>
                                @endfor
                            </select>
                        </td>
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
                        <td class="text-center" style="padding: 0px; margin: 0;">
                            <select class="cbStaffFilterId form-control" data-href="{!! $hrefIndex !!}">
                                <option value="0" @if($staffFilterId == 0) selected="selected" @endif>
                                    Tất cả
                                </option>
                                @if($hFunction->checkCount($dataListStaff))
                                    @foreach($dataListStaff as $staff)
                                        <option @if($staff->staffId() == $staffFilterId) selected="selected"
                                                @endif  value="{!! $staff->staffId() !!}">{!! $staff->fullName() !!}</option>
                                    @endforeach
                                @endif
                            </select>
                        </td>
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
                            $image = $import->image();
                            $importDate = $import->importDate();
                            $companyImportId = $import->companyId();
                            $confirmStatus = $import->checkConfirm();
                            $confirmNote = $import->confirmNote();
                            $dataImportPay = $import->importPayInfo();
                            # thong tin chi tiet nhap
                            $dataImportDetail = $import->infoDetailOfImport();
                            #anh hoa don
                            $dataImportImage = $import->importImageInfoOfImport();
                            # nguoi nhap
                            $dataImportStaff = $import->staffImport;
                            ?>
                            <tr class="@if(!$import->checkExactlyStatus()) danger  @else @if($n_o%2 == 1) info @endif @endif "
                                data-object="{!! $importId !!}">
                                <td style="padding: 0;">
                                    <div class="media">
                                        <div class="pull-left" href="#">
                                            <div class="text-center" style="width: 150px;">
                                                @if(!$hFunction->checkEmpty($image))
                                                    <a href="{!! route('qc.work.pay.import.get.view.get',$importId) !!}">
                                                        <img class="media-object qc-link" alt="..."
                                                             style="width: 150px;"
                                                             src="{!! $import->pathFullImage($image) !!}">
                                                    </a>

                                                @else
                                                    <span style=" background-color: red; color: yellow;">Không có Ảnh HĐ</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <b style="color: blue;">{!! date('d-m-Y', strtotime($importDate)) !!}</b>
                                            <br/>
                                            @if(!$confirmStatus)
                                                <a class="qc_confirm_get qc-font-size-14 qc-link-red-bold"
                                                   data-href="{!! route('qc.work.pay.import.confirm.get',$importId) !!}">
                                                    DUYỆT
                                                </a>
                                            @else
                                                <i class="glyphicon glyphicon-ok" style="color: green;"></i>
                                                <em class="qc-color-grey">Đã Duyệt</em>
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
                                               href="{!! route('qc.work.pay.import.get.view.get',$importId) !!}">
                                                <i class="glyphicon glyphicon-minus"></i>
                                                Xem chi tiết
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <b style="color: red;">{!! $hFunction->currencyFormat($import->totalMoneyOfImport()) !!}</b>
                                    <br/>
                                    @if($import->checkExactlyStatus())
                                        @if($import->checkPay())
                                            @if($import->checkPayConfirmOfImport($importId))
                                                <i class="glyphicon glyphicon-ok" style="color: green;" title="Đã xác nhận"></i>
                                                <em>Thanh toán đã xác nhận</em>
                                            @else
                                                <i class="glyphicon glyphicon-ok" style="color: blue;" title="Chưa xác nhận"></i>
                                                <em>Thanh toán chưa xác nhận</em>
                                            @endif
                                        @else
                                            <em class="qc-color-grey">Chưa thanh toán</em>
                                            @if($import->checkConfirm())
                                                <br/>
                                                <a class="qc-link-green qc_import_pay_get"
                                                   data-href="{!! route('qc.work.pay.import.pay.get', $importId) !!}">
                                                    THANH TOÁN
                                                </a>
                                            @endif
                                        @endif
                                    @else
                                        <em class="qc-color-red">Nhập không đúng</em>
                                    @endif
                                    @if(!$hFunction->checkEmpty($confirmNote))
                                        <br/>
                                        <em style="color: grey;">- {!! $confirmNote !!}</em>
                                    @endif
                                </td>
                                <td>
                                    <div class="media">
                                        <a class="pull-left" href="#">
                                            <img class="media-object"
                                                 style="max-width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                                 src="{!! $dataImportStaff->pathAvatar($dataImportStaff->image()) !!}">
                                        </a>

                                        <div class="media-body">
                                            <h5 class="media-heading">{!! $dataImportStaff->fullName() !!}</h5>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="qc-padding-top-5 qc-padding-bot-5 text-center" colspan="4">
                                <em class="qc-color-red">Không tìm thấy thông tin phù hợp</em>
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td class="qc-padding-top-5 qc-padding-bot-5 text-center" colspan="4">
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
