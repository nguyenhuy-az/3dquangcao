<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:32 PM
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$dataStaffLogin = $modelStaff->loginStaffInfo();
$hrefIndex = route('qc.ad3d.finance.keep_money.get');
$dataCompanyLogin = $modelStaff->companyLogin();
# dang nhap vao cty dang lam - cua minh
$actionStatus = true;
if ($companyFilterId != $dataCompanyLogin->companyId()) $actionStatus = false;
?>
@extends('ad3d.finance.keep-money.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! $hrefIndex !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20">GIỮ TIỀN NHÂN VIÊN</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <select class="cbCompanyFilter form-control" name="cbCompanyFilter"
                            data-href-filter="{!! $hrefIndex !!}">
                        @if($hFunction->checkCount($dataCompany))
                            @foreach($dataCompany as $company)
                                @if($dataCompanyLogin->checkParent())
                                    <option value="{!! $company->companyId() !!}"
                                            @if($companyFilterId == $company->companyId()) selected="selected" @endif >{!! $company->name() !!}</option>
                                @else
                                    @if($companyFilterId == $company->companyId())
                                        <option value="{!! $company->companyId() !!}">{!! $company->name() !!}</option>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="qc_ad3d_list_content row">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: black; color: yellow;">
                            <th style="width:20px;">STT</th>
                            <th style="width: 150px;">Ngày giữ</th>
                            <th>Nhân viên</th>
                            <th>Ghi chú</th>
                            <th>Tháng lương giữ</th>
                            <th class="text-center">Thanh toán</th>
                            <th class="text-right">Số tiền</th>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="padding: 0;">
                                <select class="cbDayFilter col-xs-3 col-sm-3 col-md-3 col-lg-3"
                                        style="padding: 0;height: 34px;"
                                        data-href="{!! $hrefIndex !!}">
                                    <option value="100" @if((int)$monthFilter == 100) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($d =1;$d<= 31; $d++)
                                        <option value="{!! $d !!}"
                                                @if((int)$dayFilter == $d) selected="selected" @endif >
                                            {!! $d !!}
                                        </option>
                                    @endfor
                                </select>
                                <select class="cbMonthFilter col-xs-3 col-sm-3 col-md-3 col-lg-3"
                                        style="padding: 0;height: 34px;" data-href="{!! $hrefIndex !!}">
                                    <option value="100" @if((int)$monthFilter == 100) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($m =1;$m<= 12; $m++)
                                        <option value="{!! $m !!}"
                                                @if((int)$monthFilter == $m) selected="selected" @endif>
                                            {!! $m !!}
                                        </option>
                                    @endfor
                                </select>
                                <select class="cbYearFilter col-xs-6 col-sm-6 col-md-6 col-lg-6"
                                        style="padding: 0;height: 34px;" data-href="{!! $hrefIndex !!}">
                                    @for($y =2017;$y<= 2050; $y++)
                                        <option value="{!! $y !!}"
                                                @if($yearFilter == $y) selected="selected" @endif>
                                            {!! $y !!}
                                        </option>
                                    @endfor
                                </select>
                            </td>
                            <td style="padding: 0;">
                                <select class="cbStaffFilterId form-control" data-href="{!! $hrefIndex !!}">
                                    <option value="0" @if($staffFilterId == 0) selected="selected" @endif>
                                        Tất cả
                                    </option>
                                    @if($hFunction->checkCount($dataStaff))
                                        @foreach($dataStaff as $staff)
                                            <option @if($staff->staffId() == $staffFilterId) selected="selected"
                                                    @endif  value="{!! $staff->staffId() !!}">
                                                {!! $staff->lastName() !!}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </td>
                            <td></td>
                            <td style="padding: 0;">
                                <select class="cbPayStatus form-control" name="cbPayStatus"
                                        data-href="{!! $hrefIndex !!}">
                                    <option value="0" @if($payStatus == 0) selected="selected" @endif>
                                        Tất cả
                                    </option>
                                    <option value="1" @if($payStatus == 1) selected="selected" @endif>
                                        Chưa Thanh toán
                                    </option>
                                    <option value="2" @if($payStatus == 2) selected="selected" @endif >
                                        Đã Thanh toán
                                    </option>
                                </select>
                            </td>
                            <td></td>
                        </tr>
                        @if($hFunction->checkCount($dataKeepMoney))
                            <?php
                            $perPage = $dataKeepMoney->perPage();
                            $currentPage = $dataKeepMoney->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataKeepMoney as $keepMoney)
                                <?php
                                $keepMoneyId = $keepMoney->keepId();
                                $dataWork = $keepMoney->salary->work;
                                # thong tin nhan vien
                                if (!empty($dataWork->companyStaffWorkId())) {
                                    $dataKeepStaffMinus = $dataWork->companyStaffWork->staff;
                                } else {
                                    $dataKeepStaffMinus = $dataWork->staff; // phien ban cu
                                }
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2) info @endif"
                                    data-object="{!! $keepMoneyId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td>
                                        {!! date('d/m/Y', strtotime($keepMoney->keepDate())) !!}
                                    </td>
                                    <td style="padding: 3px;">
                                        <div class="media">
                                            <a class="pull-left" href="#">
                                                <img class="media-object"
                                                     style="max-width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                                     src="{!! $dataKeepStaffMinus->pathAvatar($dataKeepStaffMinus->image()) !!}">
                                            </a>

                                            <div class="media-body">
                                                <h5 class="media-heading">{!! $dataKeepStaffMinus->fullName() !!}</h5>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {!! $keepMoney->description() !!}
                                    </td>
                                    <td>
                                        {!! date('m/Y', strtotime($dataWork->fromDate())) !!}
                                    </td>
                                    <td class="text-center">
                                        @if($keepMoney->checkPaid($keepMoneyId))
                                            <span>Đã thanh toán</span>
                                        @else
                                            <span>Chưa thanh toán</span>
                                        @endif
                                    </td>
                                    <td class="text-right qc-color-red">
                                        {!! $hFunction->currencyFormat($keepMoney->money()) !!}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center" colspan="7">
                                    {!! $hFunction->page($dataKeepMoney) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-center" colspan="7">
                                    <em class="qc-color-red">Không tìm thấy thông tin</em>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
