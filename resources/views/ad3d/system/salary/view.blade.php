<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataStaff
 * dataStaffSalaryBasic
 *
 */
$hFunction = new Hfunction();
?>
@extends('ad3d.components.container.container-6')
@section('qc_ad3d_container_content')
    <div class="qc-padding-top-20 qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <img class="qc-img-100" src="{!! asset('public\imgtest\people.png') !!}">

            <h3>{!! $dataStaff->fullName() !!}</h3>
        </div>
        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <i class="glyphicon glyphicon-arrow-right"></i>
            <em>Mã Nhân viên: </em>
            {!! $dataStaff->nameCode() !!}
        </div>
        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <table class="table table-hover">
                <tr>
                    <th>
                        Ngày
                    </th>
                    <th>
                        Lương (VND)
                    </th>
                </tr>
                @if(count($dataStaffSalaryBasic) > 0)
                    @foreach($dataStaffSalaryBasic as $salary)
                        <tr @if($salary->action() == 0) class="qc-color-grey" @endif>
                            <th>
                                {!! $salary->createdAt() !!}
                            </th>
                            <th>
                                {!! $hFunction->dotNumber($salary->salary()) !!}
                            </th>
                        </tr>
                    @endforeach
                @endif
            </table>
        </div>
        <div class=" text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <button type="button" class="qc_ad3d_container_close btn btn-sm btn-primary">
                Đóng
            </button>
        </div>
    </div>
@endsection
