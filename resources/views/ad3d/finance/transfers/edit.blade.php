<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataStaffSalaryBasic
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$dataStaffLogin = $modelStaff->loginStaffInfo();
$dataStaff = $modelStaff->getInfoActivityByLevel(0); // người quản lý
?>
@extends('ad3d.components.container.container-6')
@section('qc_ad3d_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <h3>SỬA</h3>
            </div>
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class="frmEdit" name="frmEdit" role="form" method="post"
                      action="{!! route('qc.ad3d.finance.transfers.edit.get',$dataTransfers->transfersId()) !!}">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
                         @if($mobileStatus) style="padding: 0 0;" @endif>
                        <div class="frm_notify text-center form-group form-group-sm qc-color-red"></div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group form-group-sm qc-padding-none">
                                <label>
                                    Nhân viên:
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                </label>
                                <select class="cbReceiveStaff form-control" name="cbReceiveStaff" style="height: 25px;">
                                    @if(count($dataStaff)> 0)
                                        @foreach($dataStaff as $staff)
                                            @if($staff->staffId() !== $dataStaffLogin->staffId())
                                                <option value="{!! $staff->staffId() !!}" @if($dataTransfers->receiveStaffId() == $staff->staffId()) selected="selected" @endif >
                                                    {!! $staff->fullName() !!}
                                                </option>
                                            @endif
                                        @endforeach
                                    @endif

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                             @if($mobileStatus) style="padding: 5px 0 5px 0;" @endif>
                            <label>
                                Ngày chi:
                                <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                            </label>
                            <?php
                            $transfersDate = $dataTransfers->transfersDate();
                            ?>
                            <div class="form-group form-group-sm">
                                <select name="cbDay" style="margin-top: 5px; height: 25px;">
                                    @for($i = 1;$i<= 31; $i++)
                                        <option value="{!! $i !!}"
                                                @if($i === (int)$hFunction->getDayFromDate($transfersDate)) selected="selected" @endif >
                                            {!! $i !!}
                                        </option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select name="cbMonth" style="margin-top: 5px; height: 25px;">
                                    @for($i = 1;$i<= 12; $i++)
                                        <option value="{!! $i !!}"
                                                @if($i === (int)$hFunction->getMonthFromDate($transfersDate)) selected="selected" @endif >
                                            {!! $i !!}
                                        </option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select name="cbYear" style="margin-top: 5px; height: 25px;">
                                    @for($i = 2017;$i<= 2050; $i++)
                                        <option value="{!! $i !!}"
                                                @if($i === (int)$hFunction->getYearFromDate($transfersDate)) selected="selected" @endif>
                                            {!! $i !!}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group form-group-sm qc-padding-none">
                                <label>
                                    Số tiền (VND):
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                </label>
                                <input type="number" class="form-control" name="txtMoney"
                                       value="{!! $dataTransfers->money() !!}"
                                       placeholder="Nhập số tiền">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group form-group-sm qc-padding-none">
                                <label>
                                    Lý do:
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                </label>
                                <input type="text" class="form-control" name="txtReason"
                                       value="{!! $dataTransfers->reason() !!}">
                            </div>
                        </div>
                    </div>
                    <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <button type="button" class="qc_save btn btn-sm btn-primary">Lưu</button>
                        <button type="reset" class="btn btn-sm btn-default">Hủy</button>
                        <button type="button" class="qc_ad3d_container_close btn btn-sm btn-default">Đóng</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
