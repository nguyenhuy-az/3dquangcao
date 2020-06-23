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
?>
@extends('ad3d.finance.transfers.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="margin-bottom: 10px; padding-top : 10px;padding-bottom: 10px; border-bottom: 2px dashed black;">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! route('qc.ad3d.finance.transfers.get') !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20">GIAO TIỀN / NHẬN TIỀN</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">

                    <select class="cbCompanyFilter" name="cbCompanyFilter" style="margin-top: 5px; height: 25px;"
                            data-href-filter="{!! route('qc.ad3d.finance.transfers.get') !!}">
                        @if($dataStaffLogin->checkRootManage())
                            <option value="0">Tất cả</option>
                        @endif
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
            <div class="row">
                <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 2px 0 2px 0; ">
                    <div class="row">
                        <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            {{-- <a class="btn btn-sm btn-primary" style="height: 25px;"
                                href="{!! route('qc.ad3d.finance.transfers.add.get') !!}">
                                 <i class="glyphicon glyphicon-plus"></i>
                             </a>--}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="qc_ad3d_list_content row"
                 data-href-view="{!! route('qc.ad3d.finance.transfers.view.get') !!}"
                 data-href-edit="{!! route('qc.ad3d.finance.transfers.edit.get') !!}"
                 data-href-del="{!! route('qc.ad3d.finance.transfers.delete') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: black; color: yellow;">
                            <th style="width:20px;">STT</th>
                            <th>Ngày</th>
                            <th>Hình thức</th>
                            <th>Người chuyển</th>
                            <th>Người nhận</th>
                            <th>Ghi chú</th>
                            <th class="text-center">Xác nhận</th>
                            <th></th>
                            <th class="text-right">Số tiền</th>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="padding: 0;">
                                <select class="cbDayFilter" style="height: 30px;"
                                        data-href="{!! route('qc.ad3d.finance.transfers.get') !!}">
                                    <option value="0" @if((int)$monthFilter == 0) selected="selected" @endif >Tất cả
                                    </option>
                                    @for($i =1;$i<= 31; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$dayFilter == $i) selected="selected" @endif >{!! $i !!}</option>
                                    @endfor
                                </select>
                                <select class="cbMonthFilter" style="height: 30px;"
                                        data-href="{!! route('qc.ad3d.finance.transfers.get') !!}">
                                    @for($i =1;$i<= 12; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$monthFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                                <select class="cbYearFilter" style="height: 30px;"
                                        data-href="{!! route('qc.ad3d.finance.transfers.get') !!}">
                                    @for($i =2017;$i<= 2050; $i++)
                                        <option value="{!! $i !!}"
                                                @if($yearFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                            </td>
                            <td style="padding: 0;">
                                <select class="cbTransfersStatus" name="cbTransfersStatus"
                                        style="height: 30px;"
                                        data-href="{!! route('qc.ad3d.finance.transfers.get') !!}">
                                    <option value="transfers"
                                            @if($transfersStatus == 'transfers') selected="selected" @endif>Chuyển tiền
                                    </option>
                                    <option value="receive"
                                            @if($transfersStatus == 'receive') selected="selected" @endif >Nhận tiền
                                    </option>
                                </select>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
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
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2) info @endif"
                                    data-object="{!! $transfersId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td>
                                        {!! date('d/m/Y', strtotime($transfers->transfersDate())) !!}
                                    </td>
                                    <td>
                                        @if($transfersStatus == 'transfers')
                                            <span>Chuyển tiền</span>
                                        @else
                                            <span>Nhận tiền</span>
                                        @endif
                                    </td>
                                    <td>
                                        {!! $transfers->transfersStaff->lastName() !!}
                                    </td>
                                    <td>
                                        {!! $transfers->receiveStaff->lastName() !!}
                                    </td>
                                    <td>
                                        <em>{!! $transfers->reason() !!}</em>
                                    </td>
                                    <td class="text-center">
                                        @if($transfers->checkConfirmReceive())
                                            <em class="qc-color-grey">Đã Nhận tiền</em>
                                        @else
                                            @if($dataStaffLogin->staffId() == $transfers->receiveStaffId())
                                                <a class="qc_ad3d_transfer_confirm_receive_act qc-link-green"
                                                   data-href="{!! route('qc.ad3d.finance.transfers.confirm.get',$transfersId) !!}">
                                                    Xác nhận
                                                </a>
                                            @else
                                                <em class="qc-color-grey">Chờ xác nhận</em>
                                            @endif

                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <a class="qc_view qc-link-green">
                                            Chi tiết
                                        </a>
                                        @if($transfers->checkStaffInput($dataStaffLogin->staffId()))
                                            {{--<span>|</span>
                                            <a class="qc_edit qc-link-green">
                                                Sửa
                                            </a>--}}
                                            <span>|</span>
                                            <a class="qc_delete qc-link-green">
                                                Xóa
                                            </a>
                                        @endif
                                    </td>
                                    <td class="text-right qc-color-red">
                                        {!! $hFunction->currencyFormat($transfers->money()) !!}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center" colspan="9">
                                    {!! $hFunction->page($dataTransfers) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-center" colspan="9">
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
