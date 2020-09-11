<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataStaff
 */

$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
/*
$staffId = $dataStaff->staffId();
$firstName = $dataStaff->firstName();
$lastName = $dataStaff->lastName();
$identityCard = $dataStaff->identityCard();
$birthday = $dataStaff->birthday();
$gender = $dataStaff->gender();
$image = $dataStaff->image();
$identityCardFront = $dataStaff->identityCardFront();
$identityCardBack = $dataStaff->identityCardBack();
$phone = $dataStaff->phone();
$address = $dataStaff->address();
$email = $dataStaff->email();
$bankAccount = $dataStaff->bankAccount();
$bankName = $dataStaff->bankName();
$dateAdd = $dataStaff->createdAt();
*/

?>
@extends('master')
@section('titlePage')
    Đăng nhập
@endsection
@section('qc_js_header')
    {{--<script src="{{ url('public/work/js/work.js')}}"></script>--}}
@endsection
@section('qc_master_body')
    <div class=" qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <a class="qc-link-white-bold btn btn-primary" onclick="qc_main.page_back_go();">Về trang trước</a>
            </div>
        </div>
        <div class="row">
            <div class=" text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <h3 style="color: red;">HỒ SƠ ỨNG TUYỂN</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <h4 style="color: green;">CHỜ DUYỆT / ĐÃ DUYỆT</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        {{--THONG TIN CO BAN--}}
                        <tr>
                            <td >
                                <i class="glyphicon glyphicon-record" style="font-size: 1.5em;"></i>
                                <label style="font-size: 1.5em; color: blue;">THÔNG TIN CƠ BẢN</label>
                                {{--<a class="qc_staffInfoBasicContainerEdit qc-link-red pull-right "
                                   data-href="{!! route('qc.ad3d.system.staff.info_basic.edit.get',$staffId) !!}"
                                   title="Sửa thông tin">
                                    <i class="glyphicon glyphicon-pencil" style="font-size: 1.5em;"></i>
                                </a>--}}
                            </td>
                        </tr>
                        <tr>
                            <td id="staffInfoBasicContainer">
                                <div class="row">
                                    <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                                        <div class="row">
                                            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12"
                                                 style="height: 200px;">
                                                <span>Ảnh chân dung</span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="text-center col-sx-12 col-sm-12 col-md-6 col-lg-6"
                                                 style="max-height: 100px;">
                                                <span>Mặt trước CMND</span>
                                            </div>
                                            <div class="text-center col-sx-12 col-sm-12 col-md-6 col-lg-6"
                                                 style="max-height: 100px;">
                                                <span>Mặt sau CMND</span>
                                            </div>
                                        </div>
                                        <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                            <a class=" qc-link-green"
                                               data-href="#">
                                                Cập nhật hình ảnh
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-sx-12 col-sm-12 col-md-8 col-lg-8">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-condensed" style="margin: 0;">
                                                <tr>
                                                    <td style="border-top: none;">
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        <em>Họ và tên: </em>
                                                        <b> Tên ứng tuyển</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        <em>Mã hồ sơ: </em>&nbsp;&nbsp;
                                                        <b>09123231</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        <em>CMND: </em> &nbsp;&nbsp;
                                                        <b>1234567890123</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        <em>Ngày sinh: </em>&nbsp;&nbsp;
                                                        <em>Ngày/tháng/năm</em>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        <em>Điện thoại: </em>
                                                        <b>0987654321</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        <em>Địa chỉ: </em>&nbsp;&nbsp;
                                                        <b>Thông tin địa chỉ</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        <em>Email: </em>&nbsp;&nbsp;
                                                        <b>Địa chỉ email</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        <em>Ngày nộp: </em>&nbsp;&nbsp;
                                                        <b>Ngày/tháng/năm</b>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        {{--THONG TIN LAM VIEC--}}
                        <tr>
                            <td style="padding-bottom: 0;">
                                <i class="glyphicon glyphicon-record" style="font-size: 1.5em;"></i>
                                <label style="font-size: 1.5em;color: blue;">VỊ TRÍ ỨNG TUYỂN</label>
                            </td>
                        </tr>
                        <tr>
                            <td id="staffInfoWorkContainer">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered" style="margin-bottom: 0;">
                                        <tr>
                                            <td>
                                                <em>Công ty:</em> &nbsp;
                                                <b>Tên công ty</b>
                                            </td>
                                            <td>
                                                <em>Hình thức làm: </em> &nbsp;&nbsp;
                                                <b>Toàn thời gian</b>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover table-condensed">
                                        <tr>
                                            <th colspan="2">
                                                Vị trí ứng tuyển: <span style="font-size: 1.5em;">BỘ PHẬN THI CÔNG</span>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                Tay nghề / Kỹ năng
                                            </th>
                                            <th class="text-center" style="width: 70px;">
                                                Kỹ năng
                                            </th>
                                        </tr>
                                        @for($i=1;$i<10; $i++)
                                            <tr>
                                                <td>
                                                    <i class="glyphicon glyphicon-arrow-right"></i>
                                                    Tên kỹ năng {!! $i !!}
                                                </td>
                                                <td class="text-center">
                                                    Không biết /Biết/Giỏi
                                                </td>
                                            </tr>
                                        @endfor
                                    </table>
                                </div>
                            </td>
                        </tr>
                        {{--THONG TIN LUONG--}}
                        <tr>
                            <td style="padding-bottom: 0;">
                                <i class="glyphicon glyphicon-record" style="font-size: 1.5em;"></i>
                                <label style="font-size: 1.5em;color: blue;">MƯỚC LƯƠNG</label>
                            </td>
                        </tr>
                        <tr>
                            <td id="staffInfoSalaryContainer">
                                Tổng mức lương đề xuất: 8.000.000
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
