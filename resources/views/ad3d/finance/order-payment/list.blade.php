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
$hrefIndex = route('qc.ad3d.finance.order-payment.get');
?>
@extends('ad3d.finance.order-payment.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! $hrefIndex !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20">THU ĐƠN HÀNG</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <select class="cbCompanyFilter form-control" name="cbCompanyFilter"
                            data-href-filter="{!! $hrefIndex !!}">
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
            <div class="qc_ad3d_list_content qc-order-order-object row"
                 data-href-view="{!! route('qc.ad3d.finance.order-payment.view.get') !!}"
                 data-href-del="{!! route('qc.ad3d.finance.order-payment.delete') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: black;color: yellow;">
                            <th class="text-center"></th>
                            <th style="width: 150px;">NGÀY THU</th>
                            <th>ĐƠN HÀNG</th>
                            <th>THỦ QUỸ</th>
                            <th class="text-center">GIAO TIỀN</th>
                            <th class="text-right">SỐ TIỀN</th>
                        </tr>
                        <tr>
                            <td class="text-center"></td>
                            <td style="padding: 0;">
                                <select class="cbDayFilter col-sx-3 col-sm-3 col-md-3 col-lg-3"
                                        style="padding: 0;height: 34px;"
                                        data-href="{!! $hrefIndex !!}">
                                    <option value="0" @if((int)$dayFilter == 0) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($d =1;$d<= 31; $d++)
                                        <option value="{!! $d !!}"
                                                @if((int)$dayFilter == $d) selected="selected" @endif >
                                            {!! $d !!}
                                        </option>
                                    @endfor
                                </select>
                                <select class="cbMonthFilter col-sx-3 col-sm-3 col-md-3 col-lg-3"
                                        style="padding: 0;height: 34px;" data-href="{!! $hrefIndex !!}">
                                    @for($m =1;$m<= 12; $m++)
                                        <option value="{!! $m !!}"
                                                @if((int)$monthFilter == $m) selected="selected" @endif>
                                            {!! $m !!}
                                        </option>
                                    @endfor
                                </select>
                                <select class="cbYearFilter col-sx-6 col-sm-6 col-md-6 col-lg-6"
                                        style="padding: 0;height: 34px;" data-href="{!! $hrefIndex !!}">
                                    @for($y =2017;$y<= 2050; $y++)
                                        <option value="{!! $y !!}"
                                                @if($yearFilter == $y) selected="selected" @endif>
                                            {!! $y !!}
                                        </option>
                                    @endfor
                                </select>
                            </td>
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
                            <td class="text-right qc-color-red">
                                <b>{!! $hFunction->dotNumber($totalOrderPay) !!}</b>
                            </td>
                        </tr>
                        @if($hFunction->checkCount($dataOrderPay))
                            <?php
                            $perPage = $dataOrderPay->perPage();
                            $currentPage = $dataOrderPay->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataOrderPay as $orderPay)
                                <?php
                                $payId = $orderPay->payid();
                                # nhan vien thu
                                $dataStaffReceive = $orderPay->staff;
                                # anh dai dien
                                $image = $dataStaffReceive->image();
                                if ($hFunction->checkEmpty($image)) {
                                    $src = $dataStaffReceive->pathDefaultImage();
                                } else {
                                    $src = $dataStaffReceive->pathFullImage($image);
                                }
                                ?>
                                <tr class="qc_ad3d_list_object  @if($n_o%2 == 1) info @endif"
                                    data-object="{!! $payId !!}">
                                    <td class="text-center">
                                        <b>{!! $n_o += 1 !!}</b>
                                    </td>
                                    <td>
                                        <a class="qc_view qc-link-green" href="#">
                                            {!! date('d/m/Y', strtotime($orderPay->datePay())) !!} &nbsp;
                                            <i class="glyphicon glyphicon-eye-open"></i>
                                        </a>
                                    </td>
                                    <td>
                                        {!! $orderPay->order->name() !!}
                                        <br/>
                                        <em style="color: grey;">Mã: {!! $orderPay->order->orderCode() !!}</em>
                                    </td>
                                    <td>
                                        <div class="media">
                                            <a class="pull-left" href="#">
                                                <img class="media-object"
                                                     style="max-width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                                     src="{!! $src !!}">
                                            </a>

                                            <div class="media-body">
                                                <h5 class="media-heading">{!! $dataStaffReceive->fullName() !!}</h5>
                                            </div>
                                        </div>
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
                                <td class="text-center qc-padding-top-20 qc-padding-bot-20" colspan="7">
                                    {!! $hFunction->page($dataOrderPay) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-right qc-color-red" colspan="7">
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
