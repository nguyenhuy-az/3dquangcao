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
# thong tin phong van
$dataJobApplication = $dataJobApplicationInterview->jobApplication;
$interviewId = $dataJobApplicationInterview->interviewId();
$interviewDate = $dataJobApplicationInterview->interviewDate();
# thong tin ho so
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
@extends('ad3d.system.recruitment.job-application-interview.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="qc_ad3d_recruitment_interview_info qc-padding-top-10 qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-sx-6 col-sm-6 col-md-6 col-lg-6">
                    <a class="qc-link-white-bold btn btn-primary" onclick="qc_main.page_back_go();">Về trang trước</a>
                </div>
            </div>
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <label style="color: red; font-size: 3em;">HỒ SƠ PHỎNG VẤN</label>
                    <br/>
                    Ngày hẹn PV: <span
                            style="font-size: 2em; color: blue;">{!! date('d/m/Y H:j', strtotime($interviewDate)) !!}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-8 col-lg-8" style="background-color: grey; padding: 10px;">
                    @if(!$dataJobApplicationInterview->checkInterviewConfirm())
                        <form class="ad3dFrmConfirmJobApplicationInterview form-horizontal"
                              name="ad3dFrmConfirmJobApplicationInterview"
                              role="form" method="post" enctype="multipart/form-data"
                              action="{!! route('qc.ad3d.system.job-application-interview.confirm.post',$interviewId) !!}">
                            <div class="form-group" style="margin-bottom: 0;">
                                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                    <select class="cbAgreeStatus form-control" name="cbAgreeStatus" style="color: red;">
                                        <option value="1" selected="selected">
                                            ĐỒNG Ý TUYỂN DỤNG
                                        </option>
                                        <option value="0">
                                            KHÔNG ĐỒNG Ý TUYỂN
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div id="jobApplicationInterviewContent" class="row">
                                <div class="col-sx-12 col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                            <label style="color: yellow;">Mức lương thỏa thuận (THỬ VIỆC):</label>
                                            <input type="text" class="txtSalary form-control" name="txtSalary"
                                                   placeholder="Mức lương thỏa thuận"
                                                   onkeyup="qc_main.showFormatCurrency(this);"
                                                   value="{!! $hFunction->currencyFormat($salary) !!}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sx-12 col-sm-6 col-md-6 col-lg-6">
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                            <label style="color: yellow;">Câp bậc thử việc:</label>
                                            <select class="cbDepartmentRank form-control" name="cbDepartmentRank"
                                                    style="color: red;">
                                                <option value="{!! $modelRank->staffRankId() !!}" selected="selected">
                                                    CẤP NHÂN VIÊN
                                                </option>
                                                <option value="{!! $modelRank->manageRankId() !!}">
                                                    CẤP QUẢN LÝ
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group form-group-sm">
                                        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                            <label style="color: yellow;">Ngày bắt đầu làm việc:</label>
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
                                </div>
                            </div>
                            @if (Session::has('confirmJobApplicationInterviewNotify'))
                                <div class="form-group form-group-sm text-center" style="color: yellow;">
                                    <span style="font-size: 2em;">
                                        {!! Session::get('confirmJobApplicationInterviewNotify') !!}
                                    </span>
                                    <?php
                                    Session::forget('confirmJobApplicationInterviewNotify');
                                    ?>
                                </div>
                            @endif
                            <div class="form-group form-group-sm" style="margin-bottom: 0;">
                                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <button type="button" class="qc_save btn btn-sm btn-primary form-control">
                                        XÁC NHẬN
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        @if(!$dataJobApplicationInterview->checkAgreeStatus())
                            <span style="color: yellow; font-size: 2em;">ĐÃ PHỎNG VẤN : KHÔNG ĐẠT</span>
                        @else
                            <span style="color: yellow; font-size: 2em;">ĐÃ PHỎNG VẤN: ĐẠT</span>
                        @endif
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0;">
                    <div class="table-responsive">
                        <table class="table table-bordered" style="border: none;">
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
                                            <div class="table-responsive">
                                                <table class="table table-bordered"
                                                       style="margin-bottom: 0; border: none;">
                                                    <tr>
                                                        <td class="text-center" style="border: none;" colspan="2">
                                                            @if(!empty($image))
                                                                <img style="max-width: 100%;height: 190px;" src="{!! $dataJobApplication->pathFullImage($image) !!}">
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
                                        <table class="table table-hover table-bordered" style="border: none; margin-bottom: 0;">
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
                                            </tr>
                                            @if($hFunction->checkCount($dataJobApplicationWork))
                                                @foreach($dataJobApplicationWork as $jobApplicationWork)
                                                    <?php
                                                    $skillStatus = $jobApplicationWork->skillStatus();
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <i class="glyphicon glyphicon-arrow-right"></i>
                                                            <b>
                                                                {!! $jobApplicationWork->departmentWork->name() !!}
                                                            </b>
                                                            <br/> &emsp;
                                                            <em style="color: grey;">
                                                                {!! $jobApplicationWork->skillStatusLabel($skillStatus) !!}
                                                            </em>
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
                                    <label style="font-size: 1.5em;color: blue;">MƯỚC LƯƠNG ĐỀ XUẤT</label>
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
