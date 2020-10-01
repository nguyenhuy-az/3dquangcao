<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/29/2017
 * Time: 10:50 AM
 */
if (isset($dataAccess)) {
    $subMenuObject = (isset($dataAccess['subObject'])) ? $dataAccess['subObject'] : null;
}
?>
@extends('ad3d.system.index')
@section('titlePage')
    Nhân viên
@endsection
@section('qc_ad3d_system_body')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <ul class="nav nav-tabs" role="tablist">
                        <li @if($subMenuObject == 'staffOn') class="active" @endif>
                            <a href="{!! route('qc.ad3d.system.staff.get','0/1') !!}"
                               @if($subMenuObject == 'staffOn') style="background-color: whitesmoke;" @endif>
                                <i class="glyphicon glyphicon-refresh qc-font-size-16"></i>
                                <label style="font-size: 16px;">ĐANG LÀM</label>
                            </a>
                        </li>
                        <li @if($subMenuObject == 'staffOff') class="active" @endif>
                            <a href="{!! route('qc.ad3d.system.staff.get','0/0') !!}"
                               @if($subMenuObject == 'staffOff') style="background-color: whitesmoke;" @endif>
                                <i class="glyphicon glyphicon-refresh qc-font-size-16"></i>
                                <label style="font-size: 16px;">ĐÃ NGHỈ</label>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="qc_ad3d_index_content col-xs-12 col-md-12 col-md-12 col-lg-12">
                @yield('qc_ad3d_index_content')
            </div>
        </div>
    </div>
@endsection

@section('qc_js_footer')
    <script src="{{ url('public/ad3d/system/staff/js/index.js')}}"></script>
@endsection

