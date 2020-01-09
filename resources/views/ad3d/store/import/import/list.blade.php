<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:32 PM
 *
 * modelStaff
 * dataAccess
 * dataTimekeeping
 * dateFilter
 *
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$dataStaffLogin = $modelStaff->loginStaffInfo();
$companyLoginId = $dataStaffLogin->companyId(); # id cua cong nhan vien dang dang nhap
$hrefIndex = route('qc.ad3d.store.import.get');
?>
@extends('ad3d.store.import.import.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="margin-bottom: 10px; padding-top : 10px;padding-bottom: 10px; border-bottom: 2px dashed brown;">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! $hrefIndex !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <i class="qc-font-size-20 glyphicon glyphicon-list-alt"></i>
                    <label class="qc-font-size-20">THÔNG TIN NHẬP</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <select class="cbCompanyFilter" name="cbCompanyFilter" style="margin-top: 5px; padding: 3px 0;"
                            data-href-filter="{!! $hrefIndex !!}">
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
                            <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <select class="cbDayFilter" style="margin-top: 5px; padding: 3px 0;"
                                        data-href="{!! $hrefIndex !!}">
                                    <option value="0" @if((int)$monthFilter == 0) selected="selected" @endif >Tất cả
                                    </option>
                                    @for($i =1;$i<= 31; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$dayFilter == $i) selected="selected" @endif >{!! $i !!}</option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select class="cbMonthFilter" style="margin-top: 5px; padding: 3px 0;"
                                        data-href="{!! $hrefIndex !!}">
                                    @for($i =1;$i<= 12; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$monthFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select class="cbYearFilter" style="margin-top: 5px; padding: 3px 0;"
                                        data-href="{!! $hrefIndex !!}">
                                    @for($i =2017;$i<= 2050; $i++)
                                        <option value="{!! $i !!}"
                                                @if($yearFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="qc_ad3d_list_content qc-ad3d-table-container row"
                 data-href-view=""
                 data-href-confirm="">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: whitesmoke;">
                            <th class="text-center">STT</th>
                            <th>Ngày</th>
                            <th>Nhân viên</th>
                            <th>Chi chú</th>
                            <th class="text-center">Thanh toán</th>
                            <th></th>
                            <th class="text-right">Thành tiền</th>
                        </tr>
                        <tr>
                            <td class="text-center qc-color-red"></td>
                            <td></td>
                            <td class="text-center" style="padding: 0px; margin: 0;">
                                <select class="cbStaffFilterId form-control" data-href="{!! $hrefIndex !!}">
                                    <option value="0" @if($staffFilterId == 0) selected="selected" @endif>
                                        Tất cả
                                    </option>
                                    @if(count($dataListStaff)> 0)
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
                                    <option value="3" @if($payStatusFilter == 3) selected="selected" @endif>Tất cả
                                    </option>
                                    <option value="1" @if($payStatusFilter == 1) selected="selected" @endif>Đã thanh
                                        toán
                                    </option>
                                    <option value="0" @if($payStatusFilter == 0) selected="selected" @endif>Chưa thanh
                                        toán
                                    </option>
                                </select>
                            </td>
                            <td></td>
                            <td class="text-right qc-color-red">
                                <b class="qc-color-red">{!! $hFunction->currencyFormat($importTotalMoney) !!}</b>
                            </td>

                        </tr>
                        @if(count($dataImport) > 0)
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
                                ?>
                                <tr class="qc_ad3d_list_object @if(!$import->checkExactlyStatus()) danger  @else @if($n_o%2 == 1) info @endif @endif "
                                    data-object="{!! $importId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td>
                                        {!! date('d-m-Y', strtotime($importDate)) !!}
                                    </td>
                                    <td>
                                        {!! $import->staffImport->lastName() !!}
                                    </td>
                                    <td>
                                        <em class="qc-color-grey">{!! $import->confirmNote() !!}</em>
                                    </td>
                                    <td class="text-center">
                                        @if($import->checkExactlyStatus())
                                            @if($import->checkPay())
                                                <em>Đã thanh toán</em>
                                                @if($import->checkPayConfirmOfImport($importId))
                                                    <span>||</span>
                                                    <em>Đã xác nhận</em>
                                                @else
                                                    <span>|</span>
                                                    <em>Chưa xác nhận</em>
                                                @endif
                                            @else
                                                <em class="qc-color-grey">Chưa thanh toán</em>
                                                @if($companyImportId == $companyLoginId)
                                                    @if($import->checkConfirm())
                                                        <span>|</span>
                                                        <a class="qc-link-green qc_ad3d_import_pay_act"
                                                           data-href="{!! route('qc.ad3d.store.import.pay.get', $importId) !!}">
                                                            Thanh toán
                                                        </a>
                                                    @endif
                                                @else
                                                    <span>|</span>
                                                    <em class="qc-color-grey">
                                                        Chỉ được thanh toán của chi nhánh
                                                    </em>
                                                @endif

                                            @endif
                                        @else
                                            <em class="qc-color-red">Nhập không đúng</em>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a class="qc-link"
                                           href="{!! route('qc.ad3d.store.import.view.get', $importId) !!}">
                                            Chi tiết
                                        </a>
                                        @if(!$import->checkConfirm())
                                            @if($companyImportId == $companyLoginId)
                                                <span>|</span>
                                                <a class="qc-link-green-bold"
                                                   href="{!! route('qc.ad3d.store.import.confirm.get',$importId) !!}">Duyệt</a>
                                            @else
                                                <span>|</span>
                                                <em class="qc-color-grey">
                                                    Chỉ được duyệt của chi nhánh
                                                </em>
                                            @endif
                                        @else
                                            <span>|</span>
                                            <em class="qc-color-grey">Đã Duyệt</em>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($import->totalMoneyOfImport()) !!}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="qc-padding-top-5 qc-padding-bot-5 text-center" colspan="7">
                                    <em class="qc-color-red">Không tìm thấy thông tin phù hợp</em>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>

            </div>
            <div class="row">
                <div class="text-center qc-padding-top-10 qc-padding-bot-10 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    {!! $hFunction->page($dataImport) !!}
                </div>
            </div>
        </div>
    </div>
@endsection
