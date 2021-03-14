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
$currentYear = $hFunction->currentYear();
$dataStaffLogin = $modelStaff->loginStaffInfo();
$companyLoginId = $dataStaffLogin->companyId();
if ($hFunction->checkCount($dataCompanySelected)) {
    $companySelectedId = $dataCompanySelected->companyId();
    #ngay nghi co dinh
    $dataSystemDateOffObligatory = $dataCompanySelected->systemDateOffObligatoryOfCompanyAndDate($companySelectedId, $yearSelected);
    #ngay nghi khong co dinh
    $dataSystemDateOffOptional = $dataCompanySelected->systemDateOffOptionalOfCompanyAndDate($companySelectedId, $yearSelected);
} else {
    $companySelectedId = $hFunction->setNull();
    $dataProductTypePrice = $hFunction->setNull();
    $dataSystemDateOffObligatory = $hFunction->setNull();
    $dataSystemDateOffOptional = $hFunction->setNull();
}
?>
@extends('ad3d.system.system-date-off.index')
@section('titlePage')
    Thêm ngày nghỉ của hệ thống
@endsection
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <a class="qc-link-white-bold btn btn-primary" onclick="qc_main.page_back();">
                <i class="qc-font-size-16 glyphicon glyphicon-backward"></i>
                <span class="qc-font-size-16">Trởlại</span>
            </a>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
            <h3 style="color: red;">SAO CHÉP LỊCH NGHỈ</h3>
        </div>
        <div class="qc-padding-top-20 col-xs-12 col-sm-12 col-md-12 col-lg-10">
            <form class="frmAd3dSystemDateOffCopy" name="frmAd3dSystemDateOffCopy" role="form" method="post"
                  action="{!! route('qc.ad3d.system.system_date_off.copy.post') !!}">
                @if (Session::has('notifyAdd'))
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm text-center">
                                <span class="qc-color-red qc-font-size-16">{!! Session::get('notifyAdd') !!}</span>
                                <?php
                                Session::forget('notifyAdd');
                                ?>
                                <br/><br/>
                                <a class="btn btn-primary"
                                   href="{!! route('qc.ad3d.system.system_date_off.get') !!}">
                                    <span class="qc-font-size-16">ĐÓNG</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- danh sach san pham --}}
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                            <div class="form-group form-group-sm">
                                <label>
                                    Ngày nghỉ trong năm
                                </label>
                                <select class="cbYearCopy form-control" name="cbYearCopy"
                                        data-href="{!! route('qc.ad3d.system.system_date_off.copy.get') !!}">
                                    <option value="{!! $currentYear !!}"
                                            @if($yearSelected == $currentYear) selected="selected" @endif>
                                        {!! $currentYear !!}
                                    </option>
                                    <option value="{!! $currentYear + 1 !!}"
                                            @if($yearSelected == ($currentYear +1)) selected="selected" @endif>
                                        {!! $currentYear + 1 !!}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group form-group-sm">
                                <label>
                                    Chọn công ty sao chép
                                </label>
                                <select class="cbCompanyCopy form-control" name="cbCompanyCopy"
                                        data-href="{!! route('qc.ad3d.system.system_date_off.copy.get') !!}">
                                    <option value="0">Chọn công ty</option>
                                    @if($hFunction->checkCount($dataCompany))
                                        @foreach($dataCompany as $company)
                                            @if($dataStaffLogin->companyId() != $company->companyId())
                                                <option @if($companySelectedId == $company->companyId()) selected="selected"
                                                        @endif value="{!! $company->companyId() !!}">
                                                    {!! $company->name() !!}
                                                </option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                    </div>
                    @if($hFunction->checkCount($dataSystemDateOffObligatory) || $hFunction->checkCount($dataSystemDateOffOptional))
                        @if($hFunction->checkCount($dataSystemDateOffObligatory) || $hFunction->checkCount($dataSystemDateOffOptional))
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <span class="qc-color-red qc-font-size-16">
                                        CHỈ SAO CHÉP NHỮNG NGÀY NGHỈ CHƯA CÓ TRONG HỆ THỐNG
                                    </span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <button type="button" class="qc_save btn btn-sm btn-primary">
                                        SAO CHÉP
                                    </button>
                                </div>
                            </div>
                        @endif
                        <div class="qc-ad3d-table-container row" style="margin-top: 10px;">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <i class="glyphicon glyphicon-list-alt qc-font-size-16"></i>
                                <label class="qc-font-size-16">Ngày nghỉ cố định</label>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <table class="table table-hover table-bordered">
                                    <tr style="background-color: whitesmoke;">
                                        <th class="text-center" style="width: 20px;">STT</th>
                                        <th>Ngày</th>
                                        <th>Ghi chú</th>
                                    </tr>
                                    @if($hFunction->checkCount($dataSystemDateOffObligatory))
                                        <?php
                                        $n_o = 0;
                                        ?>
                                        @foreach($dataSystemDateOffObligatory as $systemDateOffObligatory)
                                            <?php
                                            $dateOff = $systemDateOffObligatory->dateOff();
                                            ?>
                                            <tr class="@if($systemDateOffObligatory->checkExistsDateOfCompany($companyLoginId,$dateOff)) danger @endif">
                                                <td class="text-center">
                                                    {!! $n_o += 1 !!}
                                                </td>
                                                <td>
                                                    {!! $hFunction->convertDateDMYFromDatetime($dateOff) !!}
                                                </td>
                                                <td>
                                                    {!! $systemDateOffObligatory->description() !!}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td class="qc-color-red" colspan="3">
                                                <b>KHÔNG CÓ NGÀY NGHỈ</b>
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <i class="glyphicon glyphicon-list-alt qc-font-size-16"></i>
                                <label class="qc-font-size-16">Ngày nghỉ không cố định</label>
                            </div>
                            <div class="qc-padding-bot-20 qc-border-none col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered">
                                        <tr style="background-color: whitesmoke;">
                                            <th class="text-center" style="width: 20px;">STT</th>
                                            <th>Ngày</th>
                                            <th>Ghi chú</th>
                                        </tr>
                                        @if($hFunction->checkCount($dataSystemDateOffOptional))
                                            <?php
                                            $n_o = 0;
                                            ?>
                                            @foreach($dataSystemDateOffOptional as $systemDateOffOptional)
                                                <?php
                                                $dateOff = $systemDateOffOptional->dateOff();
                                                ?>
                                                <tr class="@if($systemDateOffOptional->checkExistsDateOfCompany($companyLoginId,$dateOff)) danger @endif">
                                                    <td class="text-center">
                                                        {!! $n_o += 1 !!}
                                                    </td>
                                                    <td>
                                                        {!! $hFunction->convertDateDMYFromDatetime($dateOff) !!}
                                                    </td>
                                                    <td>
                                                        {!! $systemDateOffOptional->description() !!}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td class="qc-color-red" colspan="3">
                                                    <b>KHÔNG CÓ NGÀY NGHỈ</b>
                                                </td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </form>
        </div>
    </div>
@endsection
