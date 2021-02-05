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
$dataWork = $dataSalary->work;
$dataCompanyStaffWork = $dataWork->companyStaffWork;
# thong tin nhan vien
$dataStaffWork = $dataCompanyStaffWork->staff;
# bang luong co ban
$dataStaffWorkSalary = $dataSalary->staffWorkSalary;
$benefitMoney = $dataSalary->benefitMoney();

$workId = $dataWork->workId();
$companyStaffWorkId = $dataCompanyStaffWork->workId();// $dataWork->companyStaffWorkId();
$companyId = $dataCompanyStaffWork->companyId();
$staffId = $dataWork->staffId();
# tien thuong
$totalMoneyBonus = $dataWork->totalMoneyBonusApplied();
#tien phat
$totalMoneyMinus = $dataWork->totalMoneyMinusApplied();
# tien ung
$totalMoneyBeforePay = $dataWork->totalMoneyBeforePay();
$sumMainMinute = $dataWork->sumMainMinute();
$sumPlusMinute = $dataWork->sumPlusMinute();
$sumPlusMinute_1_5 = $sumPlusMinute * 1.5;
$sumMinusMinute = $dataWork->sumMinusMinute();
$totalMinute = $sumMainMinute + $sumPlusMinute - $sumMinusMinute;
$infoSalaryBasic = true;
$totalSalaryBasic = 0;
$oldVersion = false;
if ($hFunction->checkCount($dataStaffWorkSalary)) { # da co ban luong co ban cua he thong
    $totalSalaryBasic = $dataStaffWorkSalary->totalSalary();

    $salaryBasic = $dataStaffWorkSalary->salary();

    $responsibility = $dataStaffWorkSalary->responsibility();# phu cap trach nhiem /26 ngay
    $usePhone = $dataStaffWorkSalary->usePhone();# phu cap su dung dien thoai
    $fuel = $dataStaffWorkSalary->fuel();# phu cap xang di lai

    $overtimeHour = $dataStaffWorkSalary->overtimeHour();# phu cap tang ca
    $sumPlusMinute = $dataWork->sumPlusMinute($workId);

    $totalMoneyOvertimeHour = ($sumPlusMinute / 60) * $overtimeHour; # tien phu cap an uong tang ca
    $totalMoneyInsurance = $dataStaffWorkSalary->totalMoneyInsurance();# phu cap bao hiem %

    $salaryOneHour = $dataStaffWorkSalary->salaryOnHour();#luong theo gio
    $totalCurrentSalary = $dataSalary->totalSalaryBasic();
} else {
    $infoSalaryBasic = false;
    $oldVersion = true;
}
// 2 = tong tien mua vat tu da duyet chua thanh toan
$totalMoneyImportOfStaff = $modelStaff->importTotalMoneyHasConfirmNotPay($companyId,$staffId, date('Y-m', strtotime($dataWork->fromDate())));
#thong tin thanh toan
$dataSalaryPayInfo = $dataSalary->infoSalaryPay();
$totalPaid = $dataSalary->totalPaid();
#cham cong
$dataTimekeeping = $dataWork->infoTimekeeping();
?>
@extends('work.index')
@section('qc_work_body')
    <div class="row">
        <div class="qc-padding-bot-20 col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <a class=" qc-link-red" onclick="qc_main.page_back();">
                    <i class="qc-font-size-14 glyphicon glyphicon-backward"></i>
                    <span class="qc-font-size-16" style="color: blue;">Trởlại</span>
                </a>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <h3 style="color: red;">CHI TIẾT BẢNG LƯƠNG</h3>
            </div>
            {{--phien ban cu - huy--}}
            @if($oldVersion)
                <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    Dữ liệu cũ chưa cập nhật
                </div>
            @else
                <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="media">
                                        <a class="pull-left" href="#">
                                            <img class="media-object"
                                                 style="background-color: white; width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 20px;"
                                                 src="{!! $dataStaffWork->pathAvatar($dataStaffWork->image()) !!}">
                                        </a>

                                        <div class="media-body">
                                            <label class="media-heading">{!! $dataStaffWork->lastName() !!}</label>
                                            <br/>
                                            <em>Từ: </em>
                                            <span class="qc-color-red qc-font-bold">
                                                {!! $hFunction->convertDateDMYFromDatetime($dataWork->fromDate()) !!}
                                            </span>
                                            <em>Đến: </em>
                                            <span class="qc-color-red qc-font-bold">
                                               {!! $hFunction->convertDateDMYFromDatetime($dataWork->toDate()) !!}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Bảng lương cơ bản   --}}
                            <div class="row">
                                <div class="qc-padding-top-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="table-responsive">
                                        <table class="table table-hover qc-margin-bot-none">
                                            <tr class="qc-link-green" data-toggle="modal"
                                                data-target="#qc_work_system_info_salary_basic_show">
                                                <th>
                                                    <i class="qc-font-size-14 glyphicon glyphicon-play"></i>
                                                    <span class="qc-font-size-14">Bảng lương cơ bản</span>
                                                </th>
                                                <th class="text-right">
                                                    <i class="qc-font-size-16 glyphicon glyphicon-eye-open"></i>
                                                </th>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="modal fade" id="qc_work_system_info_salary_basic_show" tabindex="-1"
                                         role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header"
                                                     style="background-color: black; color: yellow;">
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        <span aria-hidden="true">&times;</span><span
                                                                class="sr-only">Close</span>
                                                    </button>
                                                    <h4 class="modal-title" id="myModalLabel">
                                                        BẢNG LƯƠNG CƠ BẢN
                                                    </h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover">
                                                            <tr>
                                                                <td>
                                                                    <em class=" qc-color-grey">Tổng lương:</em>
                                                                </td>
                                                                <td class="text-right">
                                                                    <b>{!! $hFunction->currencyFormat($totalSalaryBasic) !!}</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <em class="qc-color-grey">Lương cơ bản:</em>
                                                                </td>
                                                                <td class="text-right">
                                                                    <b>{!! $hFunction->currencyFormat($salaryBasic) !!}</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <em class="qc-color-grey">Bảo hiểm:</em>
                                                                </td>
                                                                <td class="text-right">
                                                                    <b>{!! $hFunction->currencyFormat($totalMoneyInsurance) !!}</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <em class="qc-color-grey">Ngày phép:</em>
                                                                </td>
                                                                <td class="text-right">
                                                                    <b>{!! $hFunction->currencyFormat($salaryOneHour*8) !!}</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <em class="qc-color-grey">PC Trách nhiệm:</em>
                                                                </td>
                                                                <td class="text-right">
                                                                    {!! $hFunction->currencyFormat($responsibility) !!}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <em class="qc-color-grey">Tăng ca/h:</em>
                                                                </td>
                                                                <td class="text-right">
                                                                    <b>{!! $hFunction->currencyFormat($overtimeHour) !!}</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <em class="qc-color-grey">Điện thoại/26Ng:</em>
                                                                </td>
                                                                <td class="text-right">
                                                                    <b>{!! $hFunction->currencyFormat($usePhone) !!}</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <em class="qc-color-grey">Xăng/26Ng:</em>
                                                                </td>
                                                                <td class="text-right">
                                                                    <b>{!! $hFunction->currencyFormat($fuel) !!}</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <em class="qc-color-grey">Lương/h:</em>
                                                                </td>
                                                                <td class="text-right">
                                                                    <b>{!! $hFunction->currencyFormat($salaryOneHour) !!}</b>
                                                                </td>
                                                            </tr>

                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-primary"
                                                            data-dismiss="modal">Đóng
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Thong tin lam viec   --}}
                            <div class="row">
                                <div class="qc-padding-top-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="table-responsive">
                                        <table class="table table-hover qc-margin-bot-none">
                                            <tr class="qc-link-green" data-toggle="modal"
                                                data-target="#qc_work_system_info_salary_work_statistic_show">
                                                <th>
                                                    <i class="qc-font-size-14 glyphicon glyphicon-play"></i>
                                                    <span class="qc-font-size-14">Thống kê làm việc</span>
                                                </th>
                                                <th class="text-right">
                                                    <i class="qc-font-size-16 glyphicon glyphicon-eye-open"></i>
                                                </th>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="modal fade" id="qc_work_system_info_salary_work_statistic_show"
                                         tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background-color: black;">
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        <span aria-hidden="true">&times;</span>
                                                        <span class="sr-only" style="color: yellow;">Close</span>
                                                    </button>
                                                    <h4 class="modal-title" id="myModalLabel" style="color: yellow;">
                                                        THỐNG KÊ LÀM VIỆC
                                                    </h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-hover qc-margin-bot-none">
                                                            <tr>
                                                                <td>
                                                                    <em class=" qc-color-grey">Giờ làm chính:</em>
                                                                </td>
                                                                <td class="text-right">
                                                                    {!! floor(($sumMainMinute-$sumMainMinute%60)/60) !!}
                                                                    <b>h</b> {!! $sumMainMinute%60 !!}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <em class="qc-color-grey">Tăng ca đã nhân 1.5:</em>
                                                                </td>
                                                                <td class="text-right">
                                                                    {!! floor(($sumPlusMinute_1_5-$sumPlusMinute_1_5%60)/60) !!}
                                                                    <b>h</b> {!! $sumPlusMinute_1_5%60 !!}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <em class="qc-color-grey">Tổng giờ làm:</em>
                                                                </td>
                                                                <td class="text-right">
                                                                    {!! floor(($sumMainMinute + $sumPlusMinute_1_5)/60) !!}
                                                                    <span>h</span> {!! ($sumMainMinute + $sumPlusMinute_1_5)%60 !!}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <em class="qc-color-grey">Nghỉ có phép:</em>
                                                                </td>
                                                                <td class="text-right">
                                                                    <b>{!! $dataWork->sumOffWorkTrue() !!}</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <em class="qc-color-grey">Nghỉ không phép:</em>
                                                                </td>
                                                                <td class="text-right">
                                                                    <b>{!! $dataWork->sumOffWorkFalse() !!}</b>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-primary"
                                                            data-dismiss="modal">Đóng
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="row">
                                {{-- Chi tiết lương   --}}
                                <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <table class="table table-hover qc-margin-bot-none">
                                        <tr style="background-color: whitesmoke;">
                                            <th colspan="2">
                                                <i class="qc-font-size-14 glyphicon glyphicon-usd"></i>
                                                <b class="qc-color-red">CHI TIÊT LƯƠNG LÃNH</b>
                                            </th>
                                        </tr>
                                        @if($infoSalaryBasic)
                                            <tr style="color: brown;">
                                                <td>
                                                    <em>Tổng lương cơ bản:</em>
                                                </td>
                                                <td class="text-right">
                                                    <b>{!! $hFunction->currencyFormat($totalCurrentSalary) !!}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Mua vật tư (Đã duyệt chưa TT):
                                                </td>
                                                <td class="text-right">
                                                    <b>
                                                        + {!! $hFunction->currencyFormat($totalMoneyImportOfStaff) !!}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Cộng thêm:
                                                </td>
                                                <td class="text-right">
                                                    <b>+ {!! $hFunction->currencyFormat($benefitMoney) !!}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Thưởng:
                                                </td>
                                                <td class="text-right">
                                                    <b>{!! $hFunction->currencyFormat($totalMoneyBonus) !!}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Phạt:
                                                </td>
                                                <td class="text-right">
                                                    <b>- {!! $hFunction->currencyFormat($totalMoneyMinus) !!}</b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    Ứng:
                                                </td>
                                                <td class="text-right">
                                                    <b>- {!! $hFunction->currencyFormat($totalMoneyBeforePay) !!}</b>
                                                </td>
                                            </tr>
                                            <tr style="border-top: 3px solid brown; color: brown;">
                                                <td>
                                                    <em>Lương còn lại:</em>
                                                </td>
                                                <td class="text-right">
                                                    <b>{!! $hFunction->currencyFormat($totalCurrentSalary + $totalMoneyImportOfStaff + $benefitMoney + $totalMoneyBonus -  $totalMoneyBeforePay - $totalMoneyMinus) !!}</b>
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <th class="text-center qc-color-red" colspan="5">
                                                    Chưa có bảng lương cơ bản
                                                </th>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Chi tiết thanh toan lương   --}}
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <tr>
                                        <th colspan="5">
                                            <i class="qc-font-size-14 glyphicon glyphicon-credit-card"></i>
                                            <label class="qc-color-red">CHI TIẾT THANH TOÁN LƯƠNG</label>
                                        </th>
                                    </tr>
                                    <tr style="background-color: black;color: yellow;">
                                        <th class="text-center" style="width: 20px;">STT</th>
                                        <th>Ngày</th>
                                        <th>Thủ quỹ</th>
                                        <th class="text-center">Xác nhận</th>
                                        <th class="text-right">Số tiền</th>
                                    </tr>
                                    @if($hFunction->checkCount($dataSalaryPayInfo))
                                        @foreach($dataSalaryPayInfo as $salaryPay)
                                            <tr>
                                                <td class="text-center">
                                                    {!! $n_o_pay = (isset($n_o_pay)) ? $n_o_pay + 1 : 1 !!}
                                                </td>
                                                <td>
                                                    {!!  $hFunction->convertDateDMYFromDatetime($salaryPay->datePay()) !!}
                                                </td>
                                                <td>
                                                    {!! $salaryPay->staff->fullName()  !!}
                                                </td>
                                                <td class="text-center">
                                                    @if($salaryPay->checkConfirmed())
                                                        <em class="qc-color-grey">Đã xác nhận</em>
                                                    @else
                                                        <em class="qc-color-grey">Chưa xác nhận</em>
                                                    @endif
                                                </td>
                                                <td class="text-right qc-color-red">
                                                    {!! $hFunction->currencyFormat($salaryPay->money()) !!}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5">
                                                Không có thông tin thanh toán
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Chi tiet cham cong   --}}
                    @if($hFunction->checkCount($dataTimekeeping))
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <table class="table table-hover table-bordered">
                                    <tr class="qc-color-red">
                                        <th colspan="4">
                                            CHI TIẾT CHẤM CÔNG
                                        </th>
                                    </tr>
                                    <tr style="background-color: whitesmoke;">
                                        <th style="width: 120px;">GIỜ VÀO - RA</th>
                                        <th class="text-center">GIỜ CHÍNH</th>
                                        <th class="text-center">TĂNG CA</th>
                                        <th>Ghi chú</th>
                                    </tr>
                                    <?php $n_o = 0; ?>
                                    @foreach($dataTimekeeping as $timekeeping)
                                        <?php
                                        $timekeepingId = $timekeeping->timekeepingId();
                                        ?>
                                        <tr>
                                            <td>
                                                @if(!$timekeeping->checkOff())
                                                    <span style="color: blue;">{!! date('d-m-Y',strtotime($timekeeping->timeBegin())) !!}</span>
                                                    <b>
                                                        {!! date('H:i',strtotime($timekeeping->timeBegin())) !!}
                                                    </b>
                                                    @if(!$hFunction->checkEmpty($timekeeping->timeEnd()))
                                                        <br/>
                                                        <span style="color: brown;">{!! date('d-m-Y',strtotime($timekeeping->timeEnd())) !!}</span>
                                                        <b> {!! date('H:i',strtotime($timekeeping->timeEnd())) !!}</b>
                                                        <br/>
                                                        @if($timekeeping->checkAfternoonStatus())
                                                            <em style="color: grey;">Có làm trưa</em>
                                                        @endif
                                                    @else
                                                        <br/>
                                                        <em style="color: grey;">Không có giờ ra</em>
                                                    @endif
                                                @else
                                                    @if($timekeeping->checkPermissionStatus())
                                                        <b>{!! date('d-m-Y', strtotime($timekeeping->dateOff())) !!}</b>
                                                        <br/>
                                                        <em style="color: grey;">Nghỉ có phép</em>
                                                    @else
                                                        <b style="color: red;">{!! date('d-m-Y', strtotime($timekeeping->dateOff())) !!}</b>
                                                        <br/>
                                                        <em style="color: grey;">Nghỉ không phép</em>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if(!$timekeeping->checkOff())
                                                    <b style="color: blue;">{!! ($timekeeping->mainMinute() - $timekeeping->mainMinute()%60 )/60 !!}</b>
                                                    <span>h</span>
                                                    <b>{!! $timekeeping->mainMinute()%60 !!}</b>
                                                @else
                                                    <span>0</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if(!$timekeeping->checkOff())
                                                    <b style="color: brown;">{!! ($timekeeping->plusMinute()-$timekeeping->plusMinute()%60)/60 !!}</b>
                                                    <span>h</span>
                                                    <b>{!! $timekeeping->plusMinute()%60 !!}</b>
                                                @else
                                                    <span>0</span>
                                                @endif
                                            </td>
                                            <td>
                                                <em class="qc-color-grey"> {!! $timekeeping->note() !!}</em>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <span style="color: blue;">Không có thông tin chấm công</span>
                        </div>
                    @endif

                    <div class="row">
                        <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <button type="button" class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                                ĐÓNG
                            </button>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection
