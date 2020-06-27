<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$urlReferer = $hFunction->getUrlReferer();
$mobileStatus = $mobile->isMobile();
?>
@extends('work.pay.keep-money.index')
@section('titlePage')
    Thanh toán tiền giữ
@endsection
@section('qc_work_pay_keep_money_body')
    <div class="row">
        <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="padding-bottom: 20px; border-bottom: 2px dashed brown;">
            <h3>THANH TOÁN TIỀN GIỮ</h3>
        </div>
        @if (Session::has('notifyPaykeepMoney'))
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12 qc-color-red" style="padding: 20px;">
                {!! Session::get('notifySalaryPay') !!}
                <?php
                Session::forget('notifyPaykeepMoney');
                ?>
                <br/>
                <a type="button" class="btn btn-sm btn-primary" href="{!! $urlReferer !!}">Đóng</a>
            </div>
        @else
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form id="frm_work_pay_keep_money" role="form" method="post"
                      action="{!! route('qc.work.pay.keep_money.add.post', 1) !!}">
                    <div class="row">
                        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group frm_notify text-center qc-color-red qc-padding-top-20"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <h4>Tên NV</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <tr style="background-color: black; color: yellow;">
                                        <th style="width: 30px;"></th>
                                        <th>
                                            Ngày giữ
                                        </th>
                                        <th>
                                            Ghi chú
                                        </th>
                                        <th class="text-center">
                                            Tháng lương
                                        </th>
                                    </tr>
                                    @if($hFunction->checkCount($dataKeepMoney))
                                        @foreach($dataKeepMoney as $keepMoney)
                                            <?php
                                            $keepId = $keepMoney->keepId();
                                            ?>
                                            <tr>
                                                <td class="text-center">
                                                    <input class="keepMoney" type="checkbox" name="chkKeepMoney_{!! $keepId !!}" checked="checked">
                                                </td>
                                                <td>
                                                    {!! date('m/Y', strtotime($keepMoney->keepDate())) !!}
                                                </td>
                                                <td>
                                                    {!! $keepMoney->description() !!}
                                                </td>
                                                <td class="text-center" style="color: red;">
                                                    {!! $hFunction->currencyFormat($keepMoney->money()) !!}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <button type="button" class="qc_save btn btn-sm btn-primary">Thanh toán</button>
                                <a type="button" class="btn btn-sm btn-default" href="{!! $urlReferer !!}">Đóng</a>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        @endif
    </div>
    </div>
@endsection
