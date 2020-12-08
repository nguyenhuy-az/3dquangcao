<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
/*
 *dataCompany
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
?>
@extends('components.container.container-6')
@section('qc_container_content')
    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <h3 style="color: red;">XÁC NHẬN ĐÃ NHẬN TIỀN </h3>
            </div>
        </div>
        <div class="row">
            <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form id="frmWorkSalaryPayConfirm" class="frmWorkSalaryPayConfirm" role="form" method="post"
                      action="{!! route('qc.work.salary.salary.confirm.post', $salaryId) !!}">
                    <div class="row">
                        <div class="frm_notify qc-color-red text-center col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <tr style="background-color: black; color: yellow;">
                                        <th style="width: 130px;">Số tiền</th>
                                        <th>Thủ quỹ</th>
                                    </tr>
                                    @if($hFunction->checkCount($dataSalaryPay))
                                        @foreach($dataSalaryPay as $salaryPay)
                                            <?php
                                            $money = $salaryPay->money();
                                            $dataStaffPay = $salaryPay->staff;
                                            $totalMoney = (isset($totalMoney)) ? $totalMoney + $money : $money;
                                            ?>
                                            <tr>
                                                <td>
                                                    <b style="color: red;">{!! $hFunction->currencyFormat($money) !!} </b>
                                                    <br/>
                                                    <em style="color: blue;">{!!  date('d-m-Y',strtotime($salaryPay->datePay())) !!}</em>
                                                </td>
                                                <td>
                                                    <div class="media">
                                                        <a class="pull-left" href="#">
                                                            <img class="media-object"
                                                                 style="background-color: white; width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                                                 src="{!! $dataStaffPay->pathAvatar($dataStaffPay->image()) !!}">
                                                        </a>

                                                        <div class="media-body">
                                                            <h5 class="media-heading">{!! $dataStaffPay->lastName() !!}</h5>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td>
                                                <b style="color: red; font-size: 1.5em;">
                                                    {!! $hFunction->currencyFormat($totalMoney) !!}
                                                </b>
                                            </td>
                                            <td>
                                                <em><= Tổng nhận</em>
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="2">
                                                Không có thông tin thanh toán
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="text-center qc-padding-top-20 qc-padding-bot-20  qc-border-none col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <button type="button" class="qc_save btn btn-sm btn-primary">XÁC NHẬN</button>
                            <button type="button" class="qc_container_close btn btn-sm btn-default">ĐÓNG</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
