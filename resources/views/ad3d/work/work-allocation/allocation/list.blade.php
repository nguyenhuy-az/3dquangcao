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
$href_workAllocation_get = route('qc.ad3d.work.work_allocation.get');
$subObject = isset($dataAccess['subObject'])?$dataAccess['subObject']:'workAllocation';
?>
@extends('ad3d.work.work-allocation.allocation.index')
@section('qc_ad3d_index_content')
    <div class="row qc-margin-bot-10">
        @include('ad3d.work.work-allocation.menu',compact('subObject'))
    </div>
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 2px 0 2px 0;">
                    <form name="" action="">
                        <div class="row">
                            <div class="text-left col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                <a class="qc-link-green-bold" href="{!! $href_workAllocation_get !!}">
                                    <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                                </a>
                            </div>
                            <div class="text-left col-xs-11 col-sm-11 col-md-5 col-lg-5">
                                <div class="input-group">
                                    <input type="text" class="textFilterName form-control" name="textFilterName"
                                           placeholder="Tìm theo tên nhân viên" style="height: 25px;"
                                           value="{!! $nameFiler !!}">
                                      <span class="input-group-btn">
                                            <button class="btFilterName btn btn-sm btn-default" type="button"
                                                    style="height: 25px;"
                                                    data-href="{!! $href_workAllocation_get !!}">Tìm
                                            </button>
                                      </span>
                                </div>
                            </div>
                            <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <select class="cbDayFilter" style="height: 25px;"
                                        data-href="{!! $href_workAllocation_get !!}">
                                    <option value="0" @if((int)$dayFilter == 0) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($i =1;$i<= 31; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$dayFilter == $i) selected="selected" @endif >{!! $i !!}</option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select class="cbMonthFilter" style="height: 25px;"
                                        data-href="{!! $href_workAllocation_get !!}">
                                    <option value="0" @if((int)$monthFilter == 0) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($i =1;$i<= 12; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$monthFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select class="cbYearFilter" style="height: 25px;"
                                        data-href="{!! $href_workAllocation_get !!}">
                                    <option value="0" @if((int)$yearFilter == 0) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($i =2017;$i<= 2050; $i++)
                                        <option value="{!! $i !!}"
                                                @if($yearFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                                &nbsp;
                                <select class="cbPaymentStatus" style="height: 25px;"
                                        data-href="{!! $href_workAllocation_get !!}">
                                    <option value="2" @if($finishStatus == 2) selected="selected" @endif>Tất cả
                                    </option>
                                    <option value="0" @if($finishStatus == 0) selected="selected" @endif>
                                        Đã hoàn thành
                                    </option>
                                    <option value="1" @if($finishStatus == 1) selected="selected" @endif>
                                        Chưa hoàn thành
                                    </option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="qc_ad3d_list_content qc-order-order-object row"
                 data-href-view="{!! route('qc.ad3d.work.work_allocation.view.get') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: whitesmoke;">
                            <th class="text-center">STT</th>
                            <th>Nhân viên</th>
                            <th>Sản phẩm</th>
                            <th>Đơn hàng</th>
                            <th class="text-center">TG nhận</th>
                            <th class="text-center">TG Giao</th>
                            <th class="text-center">Vai trò</th>
                            <th class="text-center">Thi công</th>
                            <th></th>
                        </tr>
                        @if(count($dataWorkAllocation) > 0)
                            <?php
                            $perPage = $dataWorkAllocation->perPage();
                            $currentPage = $dataWorkAllocation->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataWorkAllocation as $workAllocation)
                                <?php
                                $workAllocationId = $workAllocation->allocationId();
                                $dataWorkAllocationReport = $workAllocation->workAllocationReportInfo();
                                ?>
                                <tr class="qc_ad3d_list_object" data-object="{!! $workAllocationId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td>
                                        {!! $workAllocation->receiveStaff->fullName() !!}
                                    </td>
                                    <td>
                                        {!! $workAllocation->product->productType->name() !!}
                                    </td>
                                    <td>
                                        {!! $workAllocation->product->order->name() !!}
                                    </td>
                                    <td class="text-center">
                                        {!! date('d/m/Y', strtotime($workAllocation->allocationDate())) !!} &nbsp;
                                        {!! date('H:i', strtotime($workAllocation->allocationDate())) !!}
                                    </td>
                                    <td class="text-center">
                                        {!! date('d/m/Y', strtotime($workAllocation->receiveDeadline())) !!} &nbsp;
                                        {!! date('H:i', strtotime($workAllocation->receiveDeadline())) !!}
                                    </td>
                                    <td class="text-center">
                                        @if($workAllocation->checkRoleMain())
                                            <em class="qc-color-red">Làm chính</em>
                                        @else
                                            <em>Làm phụ</em>
                                        @endif
                                    </td>
                                    <td class="text-center qc-padding-top-none qc-padding-bot-none">
                                        @if($workAllocation->checkActivity())
                                            <em style="color: black;">Đang thi công</em>
                                            <span>|</span>
                                            <a class="qc_cancel qc-link-red"
                                               data-href="{!! route('qc.ad3d.work.work_allocation.delete', $workAllocationId) !!}">
                                                Hủy
                                            </a>
                                        @else
                                            <em style="color: grey;">Đã kết thúc</em>
                                        @endif
                                    </td>
                                    <td class="text-right qc-padding-top-none qc-padding-bot-none">
                                        <a class="qc_view qc-link-green" href="#">
                                            Chi tiết
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center qc-padding-top-5 qc-padding-bot-5" colspan="9">
                                    {!! $hFunction->page($dataWorkAllocation) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="qc-padding-top-5 qc-padding-bot-5 text-center" colspan="9">
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
