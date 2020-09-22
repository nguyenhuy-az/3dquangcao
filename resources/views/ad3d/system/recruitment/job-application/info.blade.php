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
$currentDay = (int)date('d');
$currentMonth = (int)date('m');
$currentYear = (int)date('Y');

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
<style type="text/css">
    .qc_work_recruitment_info table {
        border: none !important;
    }

    .qc_work_recruitment_info table tr td {
        padding-right: 0 !important;
        padding-left: 0 !important;
    }
</style>
@extends('ad3d.system.recruitment.job-application.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="qc_ad3d_recruitment_info qc-padding-top-10 qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-sx-6 col-sm-6 col-md-6 col-lg-6">
                    <a class="qc-link-white-bold btn btn-primary" onclick="qc_main.page_back_go();">Về trang trước</a>
                </div>
            </div>
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <label style="color: red; font-size: 3em;">HỒ SƠ ỨNG TUYỂN</label>
                </div>
            </div>
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-8 col-lg-8" style="background-color: grey; padding: 10px;">
                    @if(!$dataJobApplication->checkConfirmStatus())
                        <form class="ad3dFrmConfirmJobApplication form-horizontal" name="ad3dFrmConfirmJobApplication"
                              role="form" method="post" enctype="multipart/form-data"
                              action="{!! route('qc.ad3d.system.job-application.confirm.post',$jobApplicationId) !!}">
                            <div class="form-group form-group-sm" style="margin-bottom: 0;">
                                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                    <select class="cbAgreeStatus form-control" name="cbAgreeStatus"
                                            style="height: 34px; color: red;">
                                        <option value="1" selected="selected">
                                            ĐỒNG Ý VÀ HẸN PHỎNG VẤN
                                        </option>
                                        <option value="0">
                                            KHÔNG ĐỒNG Ý HỒ SƠ NÀY
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div id="jobApplicationInterview" class="form-group form-group-sm">
                                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                    <label style="color: yellow;">Ngày phỏng vấn:</label>
                                </div>
                                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                    <select class="col-xs-2 col-sm-2 col-md-2 col-lg-2" name="cbDay"
                                            style="height: 34px;">
                                        <option value="">Ngày</option>
                                        @for($d = 1;$d<= 31; $d++)
                                            <option value="{!! $d !!}">
                                                {!! $d !!}
                                            </option>
                                        @endfor
                                    </select>
                                    <select class="col-xs-2 col-sm-2 col-md-2 col-lg-2" name="cbMonth"
                                            style="height: 34px;">
                                        @for($m = 1;$m<= 12; $m++)
                                            <option value="{!! $m !!}"
                                                    @if($currentMonth == $m) selected="selected" @endif>
                                                {!! $m !!}
                                            </option>
                                        @endfor
                                    </select>
                                    <select class="col-xs-4 col-sm-4 col-md-4 col-lg-4" name="cbYear"
                                            style="height: 34px;">
                                        <option value="{!! $currentYear !!}" selected="selected">
                                            {!! $currentYear !!}
                                        </option>
                                        <option value="{!! $currentYear +1 !!}">
                                            {!! $currentYear+1 !!}
                                        </option>
                                    </select>
                                    <select class="col-xs-2 col-sm-2 col-md-2 col-lg-2" name="cbHours"
                                            style="height: 34px; color: red;">
                                        @for($h =8;$h<= 24; $h++)
                                            <option value="{!! $h !!}" @if($h == 8) selected="selected" @endif >
                                                {!! $h !!}
                                            </option>
                                        @endfor
                                    </select>
                                    <select class="col-xs-2 col-sm-2 col-md-2 col-lg-2" name="cbMinute"
                                            style="height: 34px; color: red;">
                                        <option value="0">00</option>
                                        <option value="30">30</option>
                                    </select>
                                </div>
                            </div>
                            @if (Session::has('confirmJobApplicationNotify'))
                                <div class="form-group form-group-sm text-center" style="color: yellow;">
                                    <span style="font-size: 2em;">
                                        {!! Session::get('confirmJobApplicationNotify') !!}
                                    </span>
                                    <?php
                                    Session::forget('confirmJobApplicationNotify');
                                    ?>
                                </div>
                            @endif
                            <div class="form-group form-group-sm" style="margin-bottom: 0;">
                                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <input type="hidden" name="txtTimekeepingProvisional"
                                           value="{!! $jobApplicationId !!}">
                                    <button type="button" class="qc_save btn btn-sm btn-primary form-control">
                                        XÁC NHẬN
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        @if(!$dataJobApplication->checkAgreeStatus())
                            <span style="color: yellow; font-size: 2em;">ĐÃ DUYỆT VÀ KHÔNG ĐƯỢC ĐỒNG Ý</span>
                        @else
                            <?php
                            # lay thong tin phong van
                            $dataJobApplicationInterview = $dataJobApplication->jobApplicationInterviewLastInfo();
                            ?>
                            @if($hFunction->checkCount($dataJobApplicationInterview))
                                @if($dataJobApplicationInterview->checkInterviewConfirm())
                                    @if($dataJobApplicationInterview->checkAgreeStatus())
                                        <span style="color: yellow; font-size: 2em;">ĐÃ PHỎNG VẤN: ĐẠT</span>
                                    @else
                                        <span style="color: yellow; font-size: 2em;">ĐÃ PHỎNG VẤN: KHÔNG ĐẠT</span>
                                    @endif
                                @else
                                    <span style="color: yellow; font-size: 2em;">ĐANG CHỜ PHỎNG VẤN</span>
                                @endif
                            @endif
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
                                                    <table class="table table-hover table-bordered"
                                                           style="margin-bottom: 0;">
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
    </div>
@endsection
