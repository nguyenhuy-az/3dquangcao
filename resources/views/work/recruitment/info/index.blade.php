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

$jobApplicationId = $dataJobApplication->jobApplicationId();
$nameCode = $dataJobApplication->nameCode();
$firstName = $dataJobApplication->firstName();
$lastName = $dataJobApplication->lastName();
$identityCard = $dataJobApplication->identityCard();
$birthday = $dataJobApplication->birthday();
$gender = $dataJobApplication->gender();
$image = $dataJobApplication->image();
$identityFront = $dataJobApplication->identityFront();
$identityBack = $dataJobApplication->identityBack();
$phone = $dataJobApplication->phone();
$address = $dataJobApplication->address();
$email = $dataJobApplication->email();
$salary = $dataJobApplication->salaryOffer();
$dateAdd = $dataJobApplication->createdAt();
# thong tin lam viec o bo phan
$dataJobApplicationWork = $dataJobApplication->jobApplicationWorkGetInfo();

?>
@extends('master')
@section('titlePage')
    Đăng nhập
@endsection
@section('qc_js_header')
    {{--<script src="{{ url('public/work/js/work.js')}}"></script>--}}
@endsection
<style type="text/css">
    .qc_work_recruitment_info table {
        border: none !important;
    }

    .qc_work_recruitment_info table tr td {
        padding-right: 0 !important;
        padding-left: 0 !important;
    }
</style>
@section('qc_master_body')
    <div class="qc_work_recruitment_info qc-padding-top-10 qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="col-sx-6 col-sm-6 col-md-6 col-lg-6">
                <a class="qc-link-white-bold btn btn-primary" onclick="qc_main.page_back_go();">Về trang trước</a>
            </div>
            <div class="text-right col-sx-6 col-sm-6 col-md-6 col-lg-6">
                <span style="color: blue;">HotLine:</span>
                <em style="color: red; font-size: 2em;">0939.88.99.07</em>
                <span style="color: blue;">- Mr.Huy</span>
            </div>
        </div>
        <div class="row">
            <div class=" text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <label style="color: red; font-size: 3em;">HỒ SƠ ỨNG TUYỂN</label>
            </div>
        </div>
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="background-color: red; padding: 10px;">
                @if($dataJobApplication->checkConfirmStatus())
                    <h4 style="color: yellow;">ĐANG CHỜ DUYỆT</h4>
                @else
                    @if(!$dataJobApplication->checkConfirmStatus())
                        <em style="color: yellow;font-size: 1.5em;">Cảm ơn Anh/Chị đã nộp hồ sơ ứng tuyển.</em> <br>
                        <span style="color: yellow; font-size: 1.5em;">HIỆN TẠI SỐ LƯỢNG ỨNG TUYỂN ĐÃ ĐỦ</span><br>
                        <em style="color: white; font-size: 1.5em;">Bộ phận nhân sự sẽ liên hệ với Anh/Chị khi có nhu cầu tuyển dụng mới.</em>
                    @else

                    @endif
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0;">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        {{--THONG TIN CO BAN--}}
                        <tr>
                            <td style="border: none;">
                                <i class="glyphicon glyphicon-record" style="font-size: 1.5em;"></i>
                                <label style="font-size: 1.5em; color: blue;">THÔNG TIN CƠ BẢN</label>
                            </td>
                        </tr>
                        <tr>
                            <td id="staffInfoBasicContainer">
                                <div class="row">
                                    <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                                        <div class="row">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-bordered" style="margin-bottom: 0;">
                                                    <tr>
                                                        <td class="text-center" style="border: none;" colspan="2">
                                                            @if(!empty($image))
                                                                <div style="position: relative; margin: 5px 10px 5px 10px; width: 100%; height: 100%;">
                                                                    <a class="qc-link" data-href="#">
                                                                        <img style="max-width: 100%;height: 190px;"
                                                                             src="{!! $dataJobApplication->pathFullImage($image) !!}">
                                                                    </a>
                                                                </div>
                                                            @else
                                                                <span>Ảnh</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center" style="border: none;">
                                                            @if(!empty($identityFront))
                                                                <a class="qc-link" data-href="#">
                                                                    <img style="width: 100px;height: 90px;"
                                                                         src="{!! $dataJobApplication->pathFullImage($identityFront) !!}">
                                                                </a>
                                                            @else
                                                                <span>Mặt trước CMND</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center" style="border: none;">
                                                            @if(!empty($identityBack))
                                                                <a class="qc-link" data-href="#">
                                                                    <img style="width: 150px; height: 90px;"
                                                                         src="{!! $dataJobApplication->pathFullImage($identityBack) !!}">
                                                                </a>
                                                            @else
                                                                <span>Mặt sau CMND</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sx-12 col-sm-12 col-md-8 col-lg-8">
                                        <div class="table-responsive">
                                            <table class="table table-hover" style="margin: 0;">
                                                <tr>
                                                    <td style="border: none;">
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        <em>Mã hồ sơ: </em>&nbsp;&nbsp;
                                                        <b>{!! $nameCode !!}</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border: none;">
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        <em>Họ và tên: </em>
                                                        <b> Tên ứng tuyển</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border: none;">
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        <em>CMND: </em> &nbsp;&nbsp;
                                                        <b>{!! $identityCard !!}</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border: none;">
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        <em>Ngày sinh: </em>&nbsp;&nbsp;
                                                        <b>{!! date('d/m/Y', strtotime($birthday)) !!}</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border: none;">
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        <em>Điện thoại: </em>
                                                        <b>{!! $phone !!}</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border: none;">
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        <em>Địa chỉ: </em>&nbsp;&nbsp;
                                                        <b>Thông tin địa chỉ</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border: none;">
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        <em>Email: </em>&nbsp;&nbsp;
                                                        <b>{!! $address !!}</b>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border: none;">
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        <em>Ngày nộp: </em>&nbsp;&nbsp;
                                                        <b>{!! date('d/m/Y', strtotime($dateAdd)) !!}</b>
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
                            <td style="border: none;">
                                <i class="glyphicon glyphicon-record" style="font-size: 1.5em;"></i>
                                <label style="font-size: 1.5em;color: blue;">VỊ TRÍ ỨNG TUYỂN</label>
                            </td>
                        </tr>
                        <tr>
                            <td id="staffInfoWorkContainer">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered" style="margin-bottom: 0;">
                                        <tr>
                                            <td style="border: none;">
                                                <em>Công ty:</em> &nbsp;
                                                <b>{!! $dataJobApplication->company->name() !!}</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <em>Hình thức làm: </em> &nbsp;&nbsp;
                                                <b>Toàn thời gian</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                bộ phận ứng tuyển:
                                                <span style="font-size: 1.5em;">{!! $dataJobApplication->department->name() !!}</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover table-condensed" style="margin-bottom: 0;">
                                        <tr style="color: red;">
                                            <th>
                                                Tay nghề / Kỹ năng
                                            </th>
                                            <th class="text-center" style="width: 70px;">
                                                Kỹ năng
                                            </th>
                                        </tr>
                                        @if($hFunction->checkCount($dataJobApplicationWork))
                                            @foreach($dataJobApplicationWork as $jobApplicationWork)
                                                <?php
                                                $skillStatus = $jobApplicationWork->skillStatus();
                                                ?>
                                                <tr>
                                                    <td>
                                                        <i class="glyphicon glyphicon-arrow-right"></i>
                                                        {!! $jobApplicationWork->departmentWork->name() !!}
                                                    </td>
                                                    <td class="text-center">
                                                        <span>{!! $jobApplicationWork->skillStatusLabel($skillStatus) !!}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
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
                                <span>Tổng mức lương đề xuất:</span>
                                <b style="color: red;">
                                    {!! $hFunction->currencyFormat($salary) !!}
                                </b>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
