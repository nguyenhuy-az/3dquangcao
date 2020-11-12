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
$indexHref = route('qc.ad3d.work.work_allocation.get');
?>
@extends('ad3d.work.work-allocation.allocation.index')
@section('qc_ad3d_index_content')
    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="qc_ad3d_list_content qc-order-order-object row"
             data-href-view="{!! route('qc.ad3d.work.work_allocation.view.get') !!}">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <tr style=" background-color: black; color: yellow;">
                        <th class="text-center" style="width: 20px;">STT</th>
                        <th style="width: 150px;">TG nhận - TG giao</th>
                        <th>Nhân viên</th>
                        <th>Sản phẩm</th>
                        <th class="text-center">Thi công</th>
                        <th class="text-center">Phạt</th>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td style="padding: 0;">
                            <select class="cbDayFilter col-sx-3 col-sm-3 col-md-3 col-lg-3"
                                    style="height: 34px; padding: 0;"
                                    data-href="{!! $indexHref !!}">
                                <option value="100" @if((int)$dayFilter == 100) selected="selected" @endif >
                                    Tất cả
                                </option>
                                @for($i =1;$i<= 31; $i++)
                                    <option value="{!! $i !!}"
                                            @if((int)$dayFilter == $i) selected="selected" @endif >{!! $i !!}</option>
                                @endfor
                            </select>
                            <select class="cbMonthFilter col-sx-3 col-sm-3 col-md-3 col-lg-3"
                                    style="height: 34px; padding: 0;"
                                    data-href="{!! $indexHref !!}">
                                <option value="100" @if((int)$monthFilter == 100) selected="selected" @endif >
                                    Tất cả
                                </option>
                                @for($i =1;$i<= 12; $i++)
                                    <option value="{!! $i !!}"
                                            @if((int)$monthFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                @endfor
                            </select>
                            <select class="cbYearFilter col-sx-6 col-sm-6 col-md-6 col-lg-6"
                                    style="height: 34px; padding: 0;"
                                    data-href="{!! $indexHref !!}">
                                <option value="100" @if((int)$yearFilter == 100) selected="selected" @endif >
                                    Tất cả
                                </option>
                                @for($i =2017;$i<= 2050; $i++)
                                    <option value="{!! $i !!}"
                                            @if($yearFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                @endfor
                            </select>
                        </td>
                        <td style="padding: 0;">
                            <div class="input-group">
                                <input type="text" class="textFilterName form-control" name="textFilterName"
                                       placeholder="Tìm theo tên nhân viên" value="{!! $nameFiler !!}">
                                      <span class="input-group-btn">
                                            <button class="btFilterName btn btn-default" type="button"
                                                    data-href="{!! $indexHref !!}">
                                                <i class="glyphicon glyphicon-search"></i>
                                            </button>
                                      </span>
                            </div>
                        </td>
                        <td></td>
                        <td style="padding: 0;">
                            <select class="text-center cbPaymentStatus form-control" data-href="{!! $indexHref !!}">
                                <option value="2" @if($finishStatus == 2) selected="selected" @endif>
                                    Tất cả
                                </option>
                                <option value="0" @if($finishStatus == 0) selected="selected" @endif>
                                    Đã hoàn thành
                                </option>
                                <option value="1" @if($finishStatus == 1) selected="selected" @endif>
                                    Chưa hoàn thành
                                </option>
                            </select>
                        </td>
                        <td></td>
                    </tr>
                    @if($hFunction->checkCount($dataWorkAllocation))
                        <?php
                        $perPage = $dataWorkAllocation->perPage();
                        $currentPage = $dataWorkAllocation->currentPage();
                        $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                        ?>
                        @foreach($dataWorkAllocation as $workAllocation)
                            <?php
                            $workAllocationId = $workAllocation->allocationId();
                            $allocationDate = $workAllocation->allocationDate();
                            $deadline = $workAllocation->receiveDeadline();
                            # bao cao thi cong
                            $dataWorkAllocationReport = $workAllocation->workAllocationReportInfo();
                            # thong tin phat
                            $dataMinus = $workAllocation->minusMoneyGetInfo();
                            # thong tin nguoi nhan
                            $dataReceiveStaff = $workAllocation->receiveStaff;
                            ?>
                            <tr class="qc_ad3d_list_object @if($n_o%2) info @endif"
                                data-object="{!! $workAllocationId !!}">
                                <td class="text-center">
                                    {!! $n_o += 1 !!}
                                </td>
                                <td>
                                    <span style="color: red;">{!! $hFunction->convertDateDMYFromDatetime($allocationDate) !!}</span>
                                    <span class="qc-font-bold">{!! date('H:i', strtotime($allocationDate))!!}</span>
                                    <br/>
                                    <span style="color: blue;">{!! $hFunction->convertDateDMYFromDatetime($deadline) !!}</span>
                                    <span class="qc-font-bold">{!! date('H:i', strtotime($deadline)) !!}</span>
                                </td>
                                <td style="padding: 3px;">
                                    <div class="media">
                                        <a class="pull-left" href="#">
                                            <img class="media-object"
                                                 style="background-color: white;width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                                 src="{!! $dataReceiveStaff->pathAvatar($dataReceiveStaff->image()) !!}">
                                        </a>
                                        <div class="media-body">
                                            <h5 class="media-heading">{!! $dataReceiveStaff->fullName() !!}</h5>
                                            <a class="qc_view qc-link-green" href="#">
                                                <i class="glyphicon glyphicon-eye-open qc-font-size-14"></i>
                                            </a>
                                            @if($workAllocation->checkRoleMain())
                                                <em class="qc-color-red"> - Làm chính</em>
                                            @else
                                                <em style="color: grey;"> - Làm phụ</em>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <label style="color: brown;">
                                        {!! $workAllocation->product->productType->name() !!}
                                    </label>
                                    <br/>
                                    <em style="color: grey;">ĐH:</em>
                                    <span>{!! $workAllocation->product->order->name() !!}</span>
                                </td>
                                <td class="text-center qc-padding-top-none qc-padding-bot-none">
                                    @if($workAllocation->checkActivity())
                                        <em style="color: brown;">Đang thi công</em>
                                        {{--<span>|</span>
                                        <a class="qc_cancel qc-link-red"
                                           data-href="{!! route('qc.ad3d.work.work_allocation.delete', $workAllocationId) !!}">
                                            Hủy
                                        </a>--}}
                                    @else
                                        <em style="color: grey;">Đã kết thúc</em>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a class="qc_minus_money_get qc-link-bold"
                                       data-href="{!! route('qc.ad3d.work.work_allocation.minus_money.add.post',$workAllocationId) !!}">
                                        Bồi thường vật tư
                                    </a>
                                    @if($hFunction->checkCount($dataMinus))
                                        @foreach($dataMinus as $minus)
                                            <br/>
                                            <em style="text-decoration: underline;">Đã Phạt:</em>
                                            <span style="color: red;">{!! $hFunction->currencyFormat($minus->money()) !!}</span>
                                        @endforeach
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="text-center qc-padding-top-5 qc-padding-bot-5" colspan="6">
                                {!! $hFunction->page($dataWorkAllocation) !!}
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td class="qc-padding-top-5 qc-padding-bot-5 text-center" colspan="6">
                                <em class="qc-color-red">Không tìm thấy thông tin phù hợp</em>
                            </td>
                        </tr>
                    @endif
                </table>
            </div>

        </div>
    </div>
@endsection()
