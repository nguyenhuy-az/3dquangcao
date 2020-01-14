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
$indexHref = route('qc.ad3d.order.allocation.get');
?>
@extends('ad3d.order.allocation.index')
@section('qc_ad3d_order_allocation')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="margin-bottom: 10px; padding-top : 10px;padding-bottom: 10px; border-bottom: 2px dashed brown;">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! $indexHref !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20">ĐƠN HÀNG BÀN GIAO</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <select class="cbCompanyFilter" name="cbCompanyFilter" style="margin-top: 5px; height: 20px;"
                            data-href-filter="{!! $indexHref !!}">
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
                <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 2px 0 2px 0;">
                    <form name="" action="">
                        <div class="row">
                            {{--<div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="input-group">
                                    <input type="text" class="textFilterName form-control" name="textFilterName"
                                           placeholder="Tìm theo tên" style="height: 25px;" value="{!! $nameFiler !!}">
                                      <span class="input-group-btn">
                                            <button class="btFilterName btn btn-sm btn-default" type="button"
                                                    style="height: 25px;"
                                                    data-href="{!! route('qc.ad3d.order.allocation.get') !!}">Tìm
                                            </button>
                                      </span>
                                </div>
                            </div>--}}
                            <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <select class="cbDayFilter" style="height: 20px;"
                                        data-href="{!! $indexHref !!}">
                                    <option value="100" @if((int)$dayFilter == 100) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($i =1;$i<= 31; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$dayFilter == $i) selected="selected" @endif >{!! $i !!}</option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select class="cbMonthFilter" style="height: 20px;"
                                        data-href="{!! $indexHref !!}">
                                    <option value="100" @if((int)$monthFilter == 100) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($i =1;$i<= 12; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$monthFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select class="cbYearFilter" style="height: 20px;"
                                        data-href="{!! $indexHref !!}">
                                    <option value="100" @if((int)$yearFilter == 100) selected="selected" @endif >
                                        Tất cả
                                    </option>
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
            <div class="qc_ad3d_list_content qc-order-order-object row"
                 data-href-confirm-finish="{!! route('qc.ad3d.order.allocation.confirm.get') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: whitesmoke;">
                            <th class="text-center" style="width: 20px;">STT</th>
                            <th class="text-center">Mã ĐH</th>
                            <th>Đơn hàng</th>
                            <th>Khách hàng</th>
                            <th>NV Nhận</th>
                            <th class="text-center">TG Nhận</th>
                            <th class="text-center">TG Giao</th>
                            <th></th>
                        </tr>
                        <tr>
                            <td class="text-center" style="width: 20px;"></td>
                            <td class="text-center"></td>
                            <td></td>
                            <td class="text-center"></td>
                            <td></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td style="padding: 0;">
                                <select class="cbFinishStatus form-control"
                                        data-href="{!! $indexHref !!}">
                                    <option value="2" @if($finishStatus == 2) selected="selected" @endif>
                                        Tất cả
                                    </option>
                                    <option value="1" @if($finishStatus == 1) selected="selected" @endif>
                                        Đã kết thúc
                                    </option>
                                    <option value="0" @if($finishStatus == 0) selected="selected" @endif>
                                        Đang thi công
                                    </option>
                                </select>
                            </td>
                        </tr>
                        @if($hFunction->checkCount($dataOrderAllocation))
                            <?php
                            $perPage = $dataOrderAllocation->perPage();
                            $currentPage = $dataOrderAllocation->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataOrderAllocation as $orderAllocation)
                                <?php
                                $allocationId = $orderAllocation->allocationId();
                                $dataOrders = $orderAllocation->orders;
                                $orderId = $dataOrders->orderId();
                                $dataCustomer = $dataOrders->customer;
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2) info @endif"
                                    data-object="{!! $allocationId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td class="text-center">
                                        <span class="qc-color-grey">{!! $dataOrders->orderCode() !!}</span>
                                    </td>
                                    <td>
                                        <span>{!! $dataOrders->name() !!}</span>
                                    </td>
                                    <td>
                                        <span class="qc-color-grey">{!! $dataOrders->customer->name() !!}</span>
                                    </td>
                                    <td class="qc-color-grey">
                                        {!! $orderAllocation->receiveStaff->fullname() !!}
                                    </td>
                                    <td class="text-center">
                                        {!! $hFunction->convertDateDMYFromDatetime($orderAllocation->allocationDate()) !!}
                                    </td>
                                    <td class="text-center">
                                        {!! $hFunction->convertDateDMYFromDatetime($orderAllocation->receiveDeadline()) !!}
                                    </td>
                                    <td class="text-right">
                                        @if($orderAllocation->checkActivity())
                                            @if($orderAllocation->checkFinish())
                                                @if($orderAllocation->checkConfirm())
                                                    @if($orderAllocation->checkConfirmFinish())
                                                        <em>Xong</em>
                                                    @else
                                                        <em>Không hoàn thành</em>
                                                    @endif
                                                @else
                                                    <a class="qc_confirm_finish qc-link-green">
                                                        Xác nhận hoàn thành
                                                    </a>
                                                @endif
                                            @else
                                                <a class="qc_cancel qc-link-green"
                                                   data-href="{!! route('qc.ad3d.order.allocation.cancel.get',$allocationId) !!}">
                                                    Hủy
                                                </a>
                                            @endif
                                        @else
                                            @if($orderAllocation->checkFinish())
                                                <em>Xong</em> |
                                                @if($orderAllocation->checkConfirmFinish())
                                                    <em class="qc-color-grey">Hoàn thành</em>
                                                @else
                                                    <em class="qc-color-grey">Không hoàn thành</em>
                                                @endif
                                            @else
                                                <em style=" color: red;">
                                                    Đã hủy
                                                </em>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center" colspan="8">
                                    {!! $hFunction->page($dataOrderAllocation) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="qc-padding-top-5 qc-padding-bot-5 text-center" colspan="8">
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
