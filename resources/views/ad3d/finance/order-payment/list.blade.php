<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:32 PM
 *
 * dataOrder
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$dataStaffLogin = $modelStaff->loginStaffInfo();
$hrefIndex =  route('qc.ad3d.finance.order-payment.get');
?>
@extends('ad3d.finance.order-payment.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="margin-bottom: 10px; padding-top : 10px;padding-bottom: 10px; border-bottom: 2px dashed brown;">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! $hrefIndex !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20">THU ĐƠN HÀNG</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <select class="cbCompanyFilter" name="cbCompanyFilter" style="margin-top: 5px; height: 25px;" data-href-filter="{!! $hrefIndex !!}">
                        {{--@if($dataStaffLogin->checkRootManage())
                            <option value="0">Tất cả</option>
                        @endif--}}
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
                <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 2px 0 2px 0;">
                    <form name="" action="">
                        <div class="row">
                            <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6">

                            </div>
                            <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <select class="cbDayFilter" style="margin-top: 5px; height: 25px;"
                                        data-href="{!! $hrefIndex !!}">
                                    <option value="0" @if((int)$dayFilter == 0) selected="selected" @endif >Tất cả
                                    </option>
                                    @for($i =1;$i<= 31; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$dayFilter == $i) selected="selected" @endif >{!! $i !!}</option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select class="cbMonthFilter" style="margin-top: 5px; height: 25px;"
                                        data-href="{!! $hrefIndex !!}">
                                    @for($i =1;$i<= 12; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$monthFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select class="cbYearFilter" style="margin-top: 5px; height: 25px;"
                                        data-href="{!! $hrefIndex !!}">
                                    @for($i =2017;$i<= 2050; $i++)
                                        <option value="{!! $i !!}"
                                                @if($yearFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select> &nbsp;
                                <a class="btn btn-sm btn-primary"
                                   href="{!! route('qc.ad3d.finance.order-payment.add.get') !!}">
                                    +
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="qc_ad3d_list_content qc-order-order-object row"
                 data-href-view="{!! route('qc.ad3d.finance.order-payment.view.get') !!}"
                 data-href-del="{!! route('qc.ad3d.finance.order-payment.delete') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: whitesmoke;">
                            <th class="text-center"></th>
                            <th>Mã ĐH</th>
                            <th>Tên ĐH</th>
                            <th class="text-center">Ngày thu</th>
                            <th>Thủ quỹ</th>
                            <th class="text-right"></th>
                            <th class="text-center">Giao tiền</th>
                            <th class="text-right">Thành tiền</th>
                        </tr>
                        <tr>
                            <td class="text-center"></td>
                            <td></td>
                            <td class="text-center" style="padding: 0px;">
                                <div class="input-group">
                                    <input type="text" class="textOrderFilterName form-control"
                                           name="textOrderFilterName"
                                           placeholder="Tìm theo tên" value="{!! $orderFilterName !!}">
                                      <span class="input-group-btn">
                                            <button class="btOrderFilterName btn btn-default" type="button"
                                                    data-href="{!! $hrefIndex !!}">
                                                <i class="glyphicon glyphicon-search"></i>
                                            </button>
                                      </span>
                                </div>
                            </td>
                            <td class="text-center"></td>
                            <td class="text-center" style="padding: 0px;">
                                <select class="cbStaffFilterId form-control" data-href="{!! $hrefIndex !!}">
                                    <option value="0" @if($staffFilterId == 0) selected="selected" @endif>
                                        Tất cả
                                    </option>
                                    @if(count($dataStaff)> 0)
                                        @foreach($dataStaff as $staff)
                                            <option @if($staff->staffId() == $staffFilterId) selected="selected"
                                                    @endif  value="{!! $staff->staffId() !!}">{!! $staff->lastName() !!}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </td>
                            <td class="text-right"></td>
                            <td style="padding: 0px;">
                                <select class="cbTransferStatus form-control" data-href="{!! $hrefIndex !!}">
                                    <option value="2" @if($transferStatus == 2) selected="selected" @endif>Tất cả
                                    </option>
                                    <option value="1" @if($transferStatus == 1) selected="selected" @endif>
                                        Đã bàn giao
                                    </option>
                                    <option value="0" @if($transferStatus == 0) selected="selected" @endif>
                                        Chưa bàn giao
                                    </option>
                                </select>
                            </td>
                            <td class="text-right qc-color-red" >
                                <b>{!! $hFunction->dotNumber($totalOrderPay) !!}</b>
                            </td>

                        </tr>
                        @if(count($dataOrderPay) > 0)
                            <?php
                            $perPage = $dataOrderPay->perPage();
                            $currentPage = $dataOrderPay->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataOrderPay as $orderPay)
                                <?php
                                $payId = $orderPay->payid();
                                ?>
                                <tr class="qc_ad3d_list_object  @if($n_o%2 == 1) info @endif"
                                    data-object="{!! $payId !!}">
                                    <td class="text-center">
                                        <b>{!! $n_o += 1 !!}</b>
                                    </td>
                                    <td class="qc-color-grey">
                                        {!! $orderPay->order->orderCode() !!}
                                    </td>
                                    <td>
                                        {!! $orderPay->order->name() !!}
                                    </td>
                                    <td class="text-center">
                                        {!! date('d/m/Y', strtotime($orderPay->datePay())) !!}
                                    </td>
                                    <td>
                                        {!! $orderPay->staff->fullName() !!}
                                    </td>
                                    <td class="text-right">
                                        <a class="qc_view qc-link-green" href="#">
                                            Chi tiết
                                        </a>
                                        @if($orderPay->checkStaffInput($dataStaffLogin->staffId()))
                                            <span>|</span>
                                            <a class="qc_delete qc-link-green" href="#">Hủy</a>
                                        @endif
                                    </td>
                                    <td class="text-center qc-color-grey">
                                        @if($orderPay->checkExistTransfersDetail())
                                            <i class="glyphicon glyphicon-ok-circle qc-color-green" title="Đã giao"></i>
                                        @else
                                            <i class="glyphicon glyphicon-ok-circle qc-color-red" title="Chưa giao"></i>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <b>{!! $hFunction->currencyFormat($orderPay->money()) !!}</b>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center qc-padding-top-20 qc-padding-bot-20" colspan="8">
                                    {!! $hFunction->page($dataOrderPay) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-right qc-color-red" colspan="8">
                                    <em class="qc-color-red">Không tìm thấy thông tin phù hợp</em>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection()
