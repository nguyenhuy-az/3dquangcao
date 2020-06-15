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
                    <label class="qc-font-size-20">THÔNG TIN NHẬP</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <select class="cbCompanyFilter" name="cbCompanyFilter" style="margin-top: 5px; padding: 3px 0;"
                            data-href-filter="{!! $hrefIndex !!}">
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
            <div class="qc_ad3d_list_content qc-ad3d-table-container row"
                 data-href-view=""
                 data-href-confirm="">
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
                                <select class="cbDayFilter" style="height: 30px;" data-href="{!! $hrefIndex !!}">
                                    <option value="0" @if((int)$monthFilter == 0) selected="selected" @endif >Tất cả
                                    </option>
                                    @for($d =1;$d<= 31; $d++)
                                        <option value="{!! $d !!}"
                                                @if((int)$dayFilter == $d) selected="selected" @endif >
                                            {!! $d !!}
                                        </option>
                                    @endfor
                                </select>
                                <select class="cbMonthFilter" style="height: 30px;" data-href="{!! $hrefIndex !!}">
                                    <option value="0" @if($monthFilter == 0) selected="selected" @endif>Tất cả</option>
                                    @for($m =1;$m<= 12; $m++)
                                        <option value="{!! $m !!}"
                                                @if((int)$monthFilter == $m) selected="selected" @endif>
                                            {!! $m !!}
                                        </option>
                                    @endfor
                                </select>
                                <select class="cbYearFilter" style="height: 30px;" data-href="{!! $hrefIndex !!}">
                                    <option value="0" @if($yearFilter == 0) selected="selected" @endif>Tất cả</option>
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
                            {{--<td></td>--}}
                            <td class="text-right qc-color-red">
                                <b class="qc-color-red">{!! $hFunction->currencyFormat($importTotalMoney) !!}</b>
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
                                $importDate = $import->importDate();
                                $companyImportId = $import->companyId();
                                $dataImportPay = $import->importPayInfo();
                                # thong tin chi tiet nhap
                                $dataImportDetail = $import->infoDetailOfImport();
                                #anh hoa don
                                $dataImportImage = $import->importImageInfoOfImport();
                                ?>
                                <tr class="qc_ad3d_list_object @if(!$import->checkExactlyStatus()) danger  @else @if($n_o%2 == 1) info @endif @endif "
                                    data-object="{!! $importId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td style="padding: 0;">
                                        @foreach($dataImportImage as $importImage)
                                            <img class="qc-link" alt="..."
                                                 title="Click xoay hình" style="width: 150px;"
                                                 src="{!! $importImage->pathFullImage($importImage->name()) !!}">
                                        @endforeach
                                    </td>
                                    <td>
                                        <a class="qc-link-green" href="{!! route('qc.ad3d.store.import.view.get', $importId) !!}">
                                            {!! date('d-m-Y', strtotime($importDate)) !!}
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
                                    <td>
                                        @if($import->checkExactlyStatus())
                                            @if($import->checkPay())
                                                <em>Đã thanh toán</em>
                                                @if($import->checkPayConfirmOfImport($importId))
                                                    <span>|</span>
                                                    <i class="glyphicon glyphicon-ok" style="color: green;" title="Đã xác nhận"></i>
                                                @else
                                                    <span>|</span>
                                                    <i class="glyphicon glyphicon-ok" style="color: red;" title="Chưa xác nhận"></i>
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
                                        @if(!$hFunction->checkCount($dataImportPay))
                                            @if($companyImportId == $companyLoginId)
                                                <a class="qc_confirm_get qc-link-green-bold"
                                                   data-href="{!! route('qc.ad3d.store.import.confirm.get',$importId) !!}">Duyệt</a>
                                            @else
                                                <em class="qc-color-grey">
                                                    Chỉ được duyệt của chi nhánh
                                                </em>
                                            @endif
                                        @else
                                            <em class="qc-color-grey">Đã Duyệt</em>
                                            <i class="glyphicon glyphicon-ok" style="color: green;"></i>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        {!! $hFunction->currencyFormat($import->totalMoneyOfImport()) !!}
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
    </div>
@endsection
