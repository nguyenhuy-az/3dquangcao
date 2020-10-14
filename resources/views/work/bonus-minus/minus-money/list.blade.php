<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 5/15/2018
 * Time: 10:12 AM
 *
 * dataStaff
 */
$hFunction = new Hfunction();
$hrefIndex = route('qc.work.minus_money.get');
$checkTime = date('m-Y');
?>
@extends('work.bonus-minus.minus-money.index')
@section('qc_work_minus_money_body')
    <div class="row qc_work_minius_money_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            {{-- chi tiêt --}}
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: black;color: yellow;">
                                <th class="text-center" style="width: 20px;">STT</th>
                                <th>Ngày</th>
                                <th>Nguyên nhân</th>
                                <th>Ghi chú</th>
                                <th>Phản hồi</th>
                                <th class="text-center">Áp dụng</th>
                                <th class="text-right">Số tiền</th>
                            </tr>
                            <tr>
                                <td class="text-center"></td>
                                <td style="padding: 0;">
                                    <select class="qc_work_minus_money_month col-sx-6 col-sm-6 col-md-6 col-lg-6" style="height: 34px; padding: 0;"
                                            data-href="{!! $hrefIndex !!}">
                                        {{--<option value="100" @if((int)$monthFilter == 100) selected="selected" @endif >
                                            Tất cả tháng
                                        </option>--}}
                                        @for($m =1;$m<= 12; $m++)
                                            <option value="{!! $m !!}"
                                                    @if((int)$monthFilter == $m) selected="selected" @endif>
                                                Tháng {!! $m !!}
                                            </option>
                                        @endfor
                                    </select>
                                    <select class="qc_work_minus_money_year col-sx-6 col-sm-6 col-md-6 col-lg-6" style="height: 34px; padding: 0;"
                                            data-href="{!! $hrefIndex !!}">
                                        {{--<option value="100" @if((int)$yearFilter == 100) selected="selected" @endif >
                                            Tất cả năm
                                        </option>--}}
                                        @for($y =2017;$y<= 2050; $y++)
                                            <option value="{!! $y !!}"
                                                    @if($yearFilter == $y) selected="selected" @endif>
                                                {!! $y !!}
                                            </option>
                                        @endfor
                                    </select>
                                </td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-right">
                                </td>
                            </tr>
                            @if($hFunction->checkCount($dataMinusMoney))
                                <?php
                                $n_o = 0;
                                $totalMoney = 0;
                                ?>
                                @foreach($dataMinusMoney as $minusMoney)
                                    <?php
                                    $minusId = $minusMoney->minusId();
                                    $dateMinus = $minusMoney->dateMinus();
                                    $orderAllocationId = $minusMoney->orderAllocationId();
                                    $orderConstructionId = $minusMoney->orderConstructionId();
                                    $reason = $minusMoney->reason();
                                    $cancelStatus = $minusMoney->checkCancelStatus();
                                    if ($cancelStatus) {
                                        $money = 0;
                                    } else {
                                        $money = $minusMoney->money();
                                    }
                                    $totalMoney = $totalMoney + $money;
                                    # trang thai co hieu luc the / sua /xoa cua phan hoi
                                    $actionStatus = ($checkTime == date('m-Y', strtotime($dateMinus))) ? true : false;
                                    # thong tin phan
                                    $dataMinusMoneyFeedback = $minusMoney->infoMinusMoneyFeedback();
                                    ?>
                                    <tr @if($n_o%2) class="info" @endif>
                                        <td class="text-center">
                                            {!! $n_o = (isset($n_o)) ? $n_o + 1 : 1 !!}
                                        </td>
                                        <td>
                                            {!! date('d-m-Y', strtotime($minusMoney->dateMinus())) !!}
                                        </td>
                                        <td>
                                            {!! $minusMoney->punishContent->name() !!}
                                        </td>
                                        <td>
                                            <b>{!! $minusMoney->reason() !!}</b>
                                            @if(!$hFunction->checkEmpty($orderAllocationId))
                                                <br/>
                                                <em>Đơn hàng:</em>
                                                <a class="qc-link-red"
                                                   href="{!! route('qc.work.work_allocation.order_allocation.product.get',$orderAllocationId) !!}">
                                                    {!! $minusMoney->orderAllocation->orders->name() !!}
                                                </a>
                                            @endif
                                            @if(!$hFunction->checkEmpty($orderConstructionId))
                                                <br/>
                                                <em>Đơn hàng:</em>
                                                <a class="qc-link-red"
                                                   href="{!! route('qc.work.work_allocation.order.construction.get',$orderConstructionId) !!}">
                                                    {!! $minusMoney->orderConstruction->name() !!}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            {{--co phan hoi--}}
                                            @if($hFunction->checkCount($dataMinusMoneyFeedback))
                                                <?php
                                                $feedbackId = $dataMinusMoneyFeedback->feedbackId();
                                                $feedbackContent = $dataMinusMoneyFeedback->content();
                                                $feedbackImage = $dataMinusMoneyFeedback->image();
                                                ?>
                                                <span>{!! $feedbackContent !!}</span>
                                                @if(!$hFunction->checkEmpty($feedbackImage))
                                                    <br/>
                                                    <a class="qc_view_image qc-link"
                                                       data-href="{!! route('qc.work.minus_money.feedback.view_image.get',$feedbackId) !!}">
                                                        <img style="height: 70px;" alt="..."
                                                             src="{!! $dataMinusMoneyFeedback->pathSmallImage($feedbackImage) !!}">
                                                    </a>
                                                @endif
                                                @if($actionStatus && !$dataMinusMoneyFeedback->checkConfirm())
                                                    <br/><br/>
                                                    <a class="qc_minus_money_feedback_cancel qc-link-green-bold"
                                                       title="Xóa phản hồi"
                                                       data-href="{!! route('qc.work.minus_money.feedback.cancel',$feedbackId) !!}">
                                                        <i class="qc-font-size-16 glyphicon glyphicon-trash"
                                                           style="color: red;"></i>
                                                    </a>
                                                @else
                                                    @if($dataMinusMoneyFeedback->checkConfirm())
                                                        <br/>
                                                        <b style="color: grey;">Đã xác nhận:</b>
                                                        @if($dataMinusMoneyFeedback->checkConfirmAccept())
                                                            <b style="color: grey;">Đồng ý</b>
                                                        @else
                                                                <b style="color: grey;">Không đồng ý</b>
                                                        @endif
                                                    @endif
                                                @endif
                                            @else
                                                @if($actionStatus)
                                                    <a class="qc_minus_money_feedback qc-link-green-bold"
                                                       data-href="{!! route('qc.work.minus_money.feedback.get',$minusId) !!}">
                                                        GỬI PHẢN HỒI
                                                    </a>
                                                @else
                                                    <em>Hêt hạn</em>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($cancelStatus)
                                                <em style="color: grey;">Đã hủy</em>
                                            @else
                                                @if($minusMoney->checkEnableApply())
                                                    <em>Có hiệu lực</em>
                                                @else
                                                    <span>Tạm thời</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <span style="color: blue;">
                                                {!! $hFunction->currencyFormat($money) !!}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="text-center" colspan="6" style="background-color: black;"></td>
                                    <td class="text-right">
                                        <b style="color: red;">{!! $hFunction->currencyFormat($totalMoney) !!}</b>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td class="text-center qc-padding-none" colspan="5">
                                        Không có thông tin phạt
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <a class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                        Về trang trước
                    </a>
                    <a class="btn btn-sm btn-default" href="{!! route('qc.work.home') !!}">
                        Về trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
