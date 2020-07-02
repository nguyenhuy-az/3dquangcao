<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 5/15/2018
 * Time: 9:43 AM
 */
$loginCode = (isset($loginCode)) ? $loginCode : null;
if (isset($dataAccess)) {
    $viewLoginObject = $dataAccess['object'];
    $subObjectLabel = (isset($dataAccess['subObjectLabel'])) ? $dataAccess['subObjectLabel'] : null;
} else {
    $viewLoginObject = 'panel';
    $subObject = null;
}
$href = null;
if ($viewLoginObject == 'work') {
    $label = 'Làm Việc';
    $href = route('qc.work.work.get');
} elseif ($viewLoginObject == 'orders') {
    $label = 'Đơn hàng';
    $href = route('qc.work.orders.get');
} elseif ($viewLoginObject == 'moneyReceive') {
    $label = 'Thu - chi';
    $href = route('qc.work.money.receive.get');
} elseif ($viewLoginObject == 'moneyPay') {
    $label = 'Thu - chi';
    $href = route('qc.work.money.pay.import.get');
} elseif ($viewLoginObject == 'moneyTransfer') {
    $label = 'Thu - chi';
    $href = route('qc.work.money.transfer.transfer.get');
} elseif ($viewLoginObject == 'moneyTransferReceive') {
    $label = 'Thu - chi';
    $href = route('qc.work.money.transfer.receive.get');
} elseif ($viewLoginObject == 'moneyHistory') {
    $label = 'Thu - chi';
    $href = route('qc.work.money.history.receive.get');
} elseif ($viewLoginObject == 'moneyStatistical') {
    $label = 'Thu - chi';
    $href = route('qc.work.money.statistical.get');
} elseif ($viewLoginObject == 'workAllocation') {
    $label = 'Phân việc';
    $href = route('qc.work.work_allocation.get');
}elseif ($viewLoginObject == 'workAllocationActivity') {
    $label = 'Việc đang làm';
    $href = route('qc.work.work_allocation.activity.get');
} elseif ($viewLoginObject == 'workAllocationFinish') {
    $label = 'Việc đã làm';
    $href = route('qc.work.work_allocation.finish.get');
} elseif ($viewLoginObject == 'workAllocationConstruction') {
    $label = 'Đơn hàng được giao';
    $href = route('qc.work.work_allocation.construction.get');
} elseif ($viewLoginObject == 'workAllocationManage') {
    $label = 'Quản lý đơn hàng';
    $href = route('qc.work.work_allocation.manage.get');
}elseif ($viewLoginObject == 'timekeeping') {
    $label = 'Chấm công';
    $href = route('qc.work.timekeeping.get');
} elseif ($viewLoginObject == 'salary') {
    $label = 'Bảng lương';
    $href = route('qc.work.salary.salary.get');
}elseif ($viewLoginObject == 'keepMoney') {
    $label = 'Giữ lương';
    $href = route('qc.work.salary.salary.get');
} elseif ($viewLoginObject == 'minusMoney') {
    $label = 'Phạt';
    $href = route('qc.work.minus_money.get');
} elseif ($viewLoginObject == 'beforePay') {
    $label = 'Ứng lương';
    $href = route('qc.work.salary.before_pay.get');
} elseif ($viewLoginObject == 'payImport') { # thong tin chi
    $label = 'Chi';
    $href = route('qc.work.pay.import.get');
} elseif ($viewLoginObject == 'payActivity') { # thong tin chi
    $label = 'Chi';
    $href = route('qc.work.pay.pay_activity.get');
} elseif ($viewLoginObject == 'paySalary') { # thong tin chi
    $label = 'Chi';
    $href = route('qc.work.pay.pay_salary.get');
}elseif ($viewLoginObject == 'payKeepMoney') { # thanh toan tien giu
    $label = 'Thanh toán tiền giữ';
    $href = route('qc.work.pay.keep_money.get');
} elseif ($viewLoginObject == 'toolPrivate') {
    $label = 'Đồ nghề';
    $href = route('qc.work.tool.private.get');
}elseif ($viewLoginObject == 'storeTool') {
    $label = 'Kho';
    $href = route('qc.work.store.tool.get');
} elseif ($viewLoginObject == 'productTypePrice') {
    $label = 'Bảng giá';
    $href = route('qc.work.product_type_price.get');
} elseif ($viewLoginObject == 'rules') {
    $label = 'Nội quy';
    $href = route('qc.work.rules');
} elseif ($viewLoginObject == 'changeAccount') {
    $label = 'Quản lý tài khoản';
} else {
    $label = 'Trang chủ';
}

?>
@extends('master')
@section('qc_css_header')
    <link href="{{ url('public/work/css/work.css')}}" rel="stylesheet">
@endsection
@section('titlePage')
    {!! $label !!}
@endsection
@section('qc_master_header')
    @if($viewLoginObject != 'panel')
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <ol class="breadcrumb" style="background: none; padding-bottom: 0;">
                    <li>
                        <a href="{!! route('qc.work.home') !!}">Trang chủ</a>
                    </li>
                    <li>
                        <a href="{!! $href !!}">{!! $label !!}</a>
                    </li>
                    @if(!empty($subObjectLabel))
                        <li class="active">
                            {!! $subObjectLabel !!}
                        </li>
                    @endif
                </ol>
            </div>
        </div>
    @endif
@endsection
@section('qc_master_body')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="min-height: 1500px;">
            @yield('qc_work_body')
        </div>
    </div>
@endsection
@section('qc_js_footer')
    <script src="{{ url('public/work/js/work.js')}}"></script>
@endsection
