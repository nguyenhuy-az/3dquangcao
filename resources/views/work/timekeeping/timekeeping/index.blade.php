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
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$loginStaffId = $dataStaff->staffId();
$dataWork = $dataStaff->workInfoActivityOfStaff();
if ($hFunction->checkCount($dataWork)) {
    $workId = $dataWork->workId();
// chấm công
    $dataTimekeepingProvisional = $dataWork->infoTimekeepingProvisional($workId, 'DESC');

// xin nghỉ
    $dataLicenseOffWork = $dataStaff->timekeepingOffWorkOfStaff($loginStaffId, date('Y-m'));

// xin trễ
    $dataLicenseLateWork = $dataStaff->timekeepingLateWorkOfStaff($loginStaffId, date('Y-m'));
}

?>
@extends('work.index')
@section('qc_work_body')
    <div class="row">
        @if($hFunction->checkCount($dataWork))
            <div class="qc_timekeeping_wrap qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12"
                 data-work="{!! $workId !!}">
                @include('work.timekeeping.menu')

                @yield('qc_work_timekeeping_body')
            </div>
        @else
            <div class="text-center qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <h3 style="color: red">Đã tắt chấm công</h3>
            </div>
        @endif
    </div>
@endsection
@section('qc_js_footer')
    <script src="{{ url('public/work/timekeeping/timekeeping/js/index.js')}}"></script>
@endsection
