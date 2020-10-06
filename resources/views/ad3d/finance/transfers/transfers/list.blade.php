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
$hrefIndex = route('qc.ad3d.finance.transfers.transfers.get');
?>
@extends('ad3d.finance.transfers.transfers.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    @include('ad3d.finance.transfers.menu')
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <select class="cbCompanyFilter form-control" name="cbCompanyFilter" style="height: 34px;"
                            data-href-filter="{!! $hrefIndex !!}">
                        @if($hFunction->checkCount($dataCompany))
                            @foreach($dataCompany as $company)
                                @if($dataStaffLogin->checkRootManage())
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
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="qc_ad3d_list_content row"
                 data-href-view="{!! route('qc.ad3d.finance.transfers.transfers.view.get') !!}"
                 data-href-edit="{!! route('qc.ad3d.finance.transfers.transfers.edit.get') !!}"
                 data-href-del="{!! route('qc.ad3d.finance.transfers.transfers.delete') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: black; color: yellow;">
                            <th style="width:20px;">STT</th>
                            <th style="width: 150px;">Ngày</th>
                            <th>Người chuyển</th>
                            <th>Người nhận</th>
                            <th>Hình thức chuyển</th>
                            <th>Ghi chú</th>
                            <th class="text-center">Xác nhận</th>
                            <th class="text-right">Số tiền</th>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="padding: 0;">
                                <select class="cbDayFilter col-sx-3 col-sm-3 col-md-3 col-lg-3"
                                        style="padding: 0;height: 34px;"
                                        data-href="{!! $hrefIndex !!}">
                                    <option value="0" @if((int)$dayFilter == 0) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($i =1;$i<= 31; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$dayFilter == $i) selected="selected" @endif >{!! $i !!}</option>
                                    @endfor
                                </select>
                                <select class="cbMonthFilter col-sx-3 col-sm-3 col-md-3 col-lg-3"
                                        style="padding: 0;height: 34px;"
                                        data-href="{!! $hrefIndex !!}">
                                    <option value="0" @if((int)$monthFilter == 0) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($m =1;$m<= 12; $m++)
                                        <option value="{!! $m !!}"
                                                @if((int)$monthFilter == $m) selected="selected" @endif>
                                            {!! $m !!}
                                        </option>
                                    @endfor
                                </select>
                                <select class="cbYearFilter col-sx-6 col-sm-6 col-md-6 col-lg-6"
                                        style="padding: 0;height: 34px;"
                                        data-href="{!! $hrefIndex !!}">
                                    @for($i =2017;$i<= 2050; $i++)
                                        <option value="{!! $i !!}"
                                                @if($yearFilter == $i) selected="selected" @endif>
                                            {!! $i !!}
                                        </option>
                                    @endfor
                                </select>
                            </td>

                            <td class="text-center" style="padding: 0px;">
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
                                <select class="cbTransfersStatus form-control" name="cbTransfersStatus"
                                        data-href="{!! $hrefIndex !!}">
                                    <option value="0" @if($transfersType == 0) selected="selected" @endif>
                                        Tất cả
                                    </option>
                                    <option value="transfers" @if($transfersType == 1) selected="selected" @endif>
                                        Chuyên doanh thu
                                    </option>
                                    <option value="receive" @if($transfersType == 2) selected="selected" @endif >
                                        Chuyển đầu tư
                                    </option>
                                </select>
                            </td>
                            <td></td>
                            <td></td>
                            <td class="text-right" style="color: red;">
                                {!! $hFunction->currencyFormat($totalMoneyTransfers) !!}
                            </td>
                        </tr>
                        @if($hFunction->checkCount($dataTransfers))
                            <?php
                            $perPage = $dataTransfers->perPage();
                            $currentPage = $dataTransfers->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataTransfers as $transfers)
                                <?php
                                $transfersId = $transfers->transfersId();
                                # thong tin nguoi chuyen
                                $dataTransfersStaff = $transfers->transfersStaff;
                                # thong tin nguoi nha
                                $dataReceiveStaff = $transfers->receiveStaff;
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2) info @endif"
                                    data-object="{!! $transfersId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td>
                                        <a class="qc_view qc-link-green">
                                            {!! date('d/m/Y', strtotime($transfers->transfersDate())) !!}
                                            {{-- &nbsp;
                                             <i class="glyphicon glyphicon-eye-open"></i>--}}
                                        </a>
                                    </td>
                                    <td style="padding: 3px;">
                                        <div class="media" style="">
                                            <a class="pull-left" href="#">
                                                <img class="media-object"
                                                     style="max-width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                                     src="{!! $dataTransfersStaff->pathAvatar($dataTransfersStaff->image()) !!}">
                                            </a>

                                            <div class="media-body">
                                                <h5 class="media-heading">{!! $dataTransfersStaff->fullName() !!}</h5>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="media" style="">
                                            <a class="pull-left" href="#">
                                                <img class="media-object"
                                                     style="max-width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                                     src="{!! $dataReceiveStaff->pathAvatar($dataReceiveStaff->image()) !!}">
                                            </a>

                                            <div class="media-body">
                                                <h5 class="media-heading">{!! $dataReceiveStaff->fullName() !!}</h5>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {!! $transfers->transferTypeLabel($transfers->transferType()) !!}
                                    </td>
                                    <td>
                                        <em>{!! $transfers->reason() !!}</em>
                                    </td>
                                    <td class="text-center">
                                        @if($transfers->checkConfirmReceive())
                                            <em class="qc-color-grey">Đã xác nhận</em>
                                        @else
                                            <em style="color: red;">Chờ xác nhận</em>
                                        @endif
                                    </td>
                                    <td class="text-right" style="color: blue;">
                                        {!! $hFunction->currencyFormat($transfers->money()) !!}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center" colspan="8">
                                    {!! $hFunction->page($dataTransfers) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-center" colspan="8">
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
