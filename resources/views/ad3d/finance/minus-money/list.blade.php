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
@extends('ad3d.finance.minus-money.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="margin-bottom: 10px; padding-top : 10px;padding-bottom: 10px; border-bottom: 2px dashed black;">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! route('qc.ad3d.finance.minus-money.get') !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <i class="qc-font-size-20 glyphicon glyphicon-list-alt"></i>
                    <label class="qc-font-size-20">PHẠT</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <select class="cbCompanyFilter" name="cbCompanyFilter" style="margin-top: 5px; height: 25px;"
                            data-href-filter="{!! route('qc.ad3d.finance.minus-money.get') !!}">
                        @if($dataStaffLogin->checkRootManage())
                            <option value="0">Tất cả</option>
                        @endif
                        @if(count($dataCompany)> 0)
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
                    <form name="" action="">
                        <div class="row">
                            <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="input-group">
                                    <input type="text" class="textFilterName form-control" name="textFilterName"
                                           placeholder="Tìm theo tên" value="" style="height: 25px;">
                                      <span class="input-group-btn">
                                            <button class="btFilterName btn btn-default" type="button"
                                                    style="height: 25px;"
                                                    data-href="{!! route('bonus-minus') !!}">
                                                Tìm
                                            </button>
                                      </span>
                                </div>
                            </div>
                            <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <select class="cbDayFilter" style="margin-top: 5px; height: 25px;"
                                        data-href="{!! route('qc.ad3d.finance.minus-money.get') !!}">
                                    <option value="0" @if((int)$monthFilter == 0) selected="selected" @endif >Tất cả
                                    </option>
                                    @for($i =1;$i<= 31; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$dayFilter == $i) selected="selected" @endif >{!! $i !!}</option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select class="cbMonthFilter" style="margin-top: 5px; height: 25px;"
                                        data-href="{!! route('bonus-minus') !!}">
                                    @for($i =1;$i<= 12; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$monthFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select class="cbYearFilter" style="margin-top: 5px; height: 25px;"
                                        data-href="{!! route('qc.ad3d.finance.minus-money.get') !!}">
                                    @for($i =2017;$i<= 2050; $i++)
                                        <option value="{!! $i !!}"
                                                @if($yearFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                                &nbsp;
                                <select class="cbPunishContentFilter" style="margin-top: 5px; height: 25px;"
                                        data-href="{!! route('qc.ad3d.finance.minus-money.get') !!}">
                                    <option value="0" @if($punishContentFilterId == 0) selected="selected" @endif>
                                        Tất cả
                                    </option>
                                    @if(count($dataPunishContent) > 0)
                                        @foreach($dataPunishContent as $punishContent)
                                            <option value="{!! $punishContent->punishId() !!}"
                                                    @if($punishContent->punishId() == $punishContentFilterId) selected="selected" @endif>
                                                {!! $punishContent->name() !!}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <a class="btn btn-sm btn-primary" style="height: 25px;"
                                   href="{!! route('qc.ad3d.finance.minus-money.add.get') !!}">
                                    +
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="text-right qc-color-red col-xs-12 col-sm-12 col-md-12 col-lg-12"
                     style="padding: 2px 0 2px 0; ">
                    <em class="qc-text-under">Tổng Tiền:</em>
                    <span class="qc-font-bold"> {!! $hFunction->dotNumber($totalMinusMoney)  !!}</span>
                </div>
            </div>
            <div class="qc_ad3d_list_content row"
                 data-href-view="{!! route('qc.ad3d.finance.minus-money.view.get') !!}"
                 data-href-edit="{!! route('qc.ad3d.finance.minus-money.edit.get') !!}"
                 data-href-del="{!! route('bonus-minus') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: whitesmoke;">
                            <th class="text-center">STT</th>
                            <th>Tên</th>
                            <th class="text-center">Ngày</th>
                            <th class="text-center">lý do</th>
                            <th class="text-right">Ghi chú</th>
                            <th class="text-center"></th>
                            <th class="text-right">Thành tiền</th>

                        </tr>
                        @if(count($dataMinusMoney) > 0)
                            <?php
                            $perPage = $dataMinusMoney->perPage();
                            $currentPage = $dataMinusMoney->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataMinusMoney as $minusMoney)
                                <?php
                                $minusId = $minusMoney->minusId();
                                $dataWork = $minusMoney->work;
                                ?>
                                <tr class="qc_ad3d_list_object" data-object="{!! $minusId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td>
                                        @if(!empty($dataWork->companyStaffWorkId()))
                                            {!! $dataWork->companyStaffWork->staff->fullName() !!}
                                        @else
                                            {!! $dataWork->staff->fullName() !!}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {!! date('d/m/Y', strtotime($minusMoney->dateMinus())) !!}
                                    </td>
                                    <td class="text-center">
                                        {!! $minusMoney->punishContent->name() !!}
                                    </td>
                                    <td class="text-right">
                                        {!! $minusMoney->reason() !!}
                                    </td>
                                    <td class="text-right">
                                        <a class="qc_view qc-link-green">Chi tiết</a>
                                        @if($minusMoney->checkStaffInput($dataStaffLogin->staffId()))
                                            {{--<button type="button" class="qc_edit qc-link-green btn btn-default btn-sm">
                                                Sửa
                                            </button>--}}
                                            <span>|</span>
                                            <a class="qc_delete qc-link-green">Xóa</a>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($minusMoney->money()) !!}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center" colspan="7">
                                    {!! $hFunction->page($dataMinusMoney) !!}
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
