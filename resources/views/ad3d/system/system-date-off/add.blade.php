<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
?>
@extends('ad3d.system.system-date-off.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
            <h3 style="color: red;">THÊM NGÀY NGHỈ</h3>
        </div>
        <div class="qc-padding-top-20 col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <form class="frmAdd" name="frmAdd" role="form" method="post"
                  action="{!! route('qc.ad3d.system.system_date_off.add.post') !!}">
                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                @if (Session::has('notifyAdd'))
                                    <div class="form-group text-center qc-color-red">
                                        {!! Session::get('notifyAdd') !!}
                                        <?php
                                        Session::forget('notifyAdd');
                                        ?>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 10px;">
                                <label>Công ty:</label>
                                <select class="form-control" name="cbCompany">
                                    <option value="{!! $dataCompany->companyId() !!}">
                                        {!! $dataCompany->name() !!}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div id="qc_date_off_container" class="col-xs-12 col-sm-12 col-md-12 col-lg-12"
                                 @if($mobileStatus) style="padding: 0 0;" @endif>
                                @include('ad3d.system.system-date-off.add-date')
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <a class="qc_date_off_add_act qc-link-green-bold qc-font-size-14"
                                   data-href="{!! route('qc.ad3d.system.system_date_off.add.date.get') !!}">
                                    + THÊM NGÀY
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group form-group-sm">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <button type="submit" class="btn btn-sm btn-primary">THÊM</button>
                            <a href="{!! route('qc.ad3d.system.system_date_off.get') !!}">
                                <button type="button" class="btn btn-sm btn-default">ĐÓNG</button>
                            </a>
                        </div>

                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
