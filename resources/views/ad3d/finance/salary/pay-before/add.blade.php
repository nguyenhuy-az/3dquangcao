<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$hFunction = new Hfunction();
$dataStaffLogin = $modelStaff->loginStaffInfo();

if (count($dataWorkSelect) > 0) {
    $workSelectId = $dataWorkSelect->workId();
} else {
    $workSelectId = null;
}
?>
@extends('ad3d.finance.salary.pay-before.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
            <h3>ỨNG LƯƠNG</h3>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <form id="frmAdd" class="frmAdd" role="form" method="post"
                  action="{!! route('qc.ad3d.finance.salary.pay-before.add.post') !!}">
                <div class="row">
                    <div class="qc-padding-top-20 col-xs-12 col-sm-12 col-md-6 col-lg-6">
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
                {{-- thông tin khách hàng --}}
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6"
                         @if($mobileStatus) style="padding: 0 0;" @endif>
                        <div class="form-group form-group-sm qc-padding-none">
                            <label>Công ty (<em>tên cty của nhân viên đăng nhập</em>): <i
                                        class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                            <select class="cbCompany form-control" name="cbCompany"
                                    data-href="{!! route('qc.ad3d.finance.salary.pay-before.add.get') !!}">
                                @if(count($dataCompany)> 0)
                                    @foreach($dataCompany as $company)
                                        @if($dataStaffLogin->checkRootManage())
                                            <option value="{!! $company->companyId() !!}"
                                                    @if($companyLoginId == $company->companyId()) selected="selected" @endif >{!! $company->name() !!}</option>
                                        @else
                                            @if($companyLoginId == $company->companyId())
                                                <option value="{!! $company->companyId() !!}">{!! $company->name() !!}</option>
                                            @endif
                                        @endif
                                    @endforeach
                                @endif

                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6"
                         @if($mobileStatus) style="padding: 0 0;" @endif>
                        <div class="form-group form-group-sm qc-padding-none">
                            <label>Nhân viên: <i
                                        class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                            <select name="cbWork" class="cbWork form-control"
                                    data-href="{!! route('qc.ad3d.finance.salary.pay-before.add.get') !!}">
                                <option value="">Chọn nhân viên</option>
                                @if(count($dataWork) > 0)
                                    @foreach($dataWork as $work)
                                        <option value="{!! $work->workId() !!}" @if($work->workId() == $workSelectId) selected="selected" @endif>
                                            @if(!empty($work->companyStaffWorkId()))
                                            {!! $work->companyStaffWork->staff->fullName() !!}
                                            - {!! $work->companyStaffWork->staff->identityCard() !!}
                                            @else
                                                {!! $work->staff->fullName() !!}
                                                - {!! $work->staff->identityCard() !!}
                                            @endif
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
                @if(!empty($workSelectId))
                    <?php
                    $fromDate = $dataWorkSelect->fromDate();
                    //$salaryBasic = $dataWorkSelect->staff->salaryBasicOfStaff();
                    //$priceHours = floor(($salaryBasic / 26) / 8);

                    $workId = $dataWorkSelect->workId();
                    $totalMoneyMinus = $dataWorkSelect->totalMoneyMinus();
                    $totalMoneyBeforePay = $dataWorkSelect->totalMoneyBeforePay();

                    $limitBeforePay = $dataWorkSelect->limitBeforePay($workId);
                    $limitBeforePay = ($limitBeforePay > 0) ? $limitBeforePay : 0;
                    $companyStaffWorkId = $dataWorkSelect->companyStaffWorkId();

                    if (!empty($companyStaffWorkId)) {
                        $dataStaffWorkSalary = $modelCompanyStaffWork->staffWorkSalaryActivity($companyStaffWorkId);
                        if (count($dataStaffWorkSalary) > 0) { # da co ban luong co ban cua he thong
                            $salaryOneHour = $dataStaffWorkSalary->salaryOnHour();
                            $totalCurrentSalary = $dataWorkSelect->totalSalaryBasicOfWorkInMonth($workId);//$dataWork->totalCurrentSalary();
                        }
                    } else {
                        $dataStaffWorkSalary = $modelCompanyStaffWork->staffWorkSalaryActivityOfStaff($staffId);
                        if (count($dataStaffWorkSalary) > 0) {
                            $salaryBasic = $dataStaffWorkSalary->salary();
                            $salaryOneHour = $dataStaffWorkSalary->salaryOnHour();
                            $totalCurrentSalary = $dataWorkSelect->totalSalaryBasicOfWorkInMonth($workId);//$dataWork->totalCurrentSalary();
                        } else {
                            # truong hop phien ban cu chua cap nhat
                            $salaryBasic = $dataWorkSelect->staff->salaryBasicOfStaff($staffId);
                            if (count($salaryBasic) > 0) { # da co ban luong co ban cua he thong
                                $salaryOneHour = floor($salaryBasic / 208);
                                $totalCurrentSalary = $dataWorkSelect->totalSalaryBasicOfWorkInMonth();//$dataWork->totalCurrentSalary();
                            }
                        }
                    }
                    ?>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group form-group-sm qc-padding-none">
                                <label>Lương:</label>
                                <input type="text" class="form-control" readonly
                                       value="{!! $hFunction->currencyFormat($totalCurrentSalary) !!}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-2 col-lg-2"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group form-group-sm qc-padding-none">
                                <label>Tổng ứng và phạt:</label>
                                <input type="text" class="form-control" readonly
                                       value="{!! $hFunction->currencyFormat($totalMoneyBeforePay + $totalMoneyMinus) !!}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-2 col-lg-2"
                             @if($mobileStatus) style="padding: 0 0;" @endif>
                            <div class="form-group form-group-sm qc-padding-none">
                                <label>Giới hạn ứng:</label>
                                <input type="text" class="form-control" readonly
                                       value="{!! $hFunction->currencyFormat($limitBeforePay) !!}">
                            </div>
                        </div>
                    </div>
                    @if($limitBeforePay >= 100000)
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6"
                                 @if($mobileStatus) style="padding: 5px 0 5px 0;" @endif>
                                <label>Ngày: <i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6"
                                 @if($mobileStatus) style="padding: 5px 0 5px 0;" @endif>
                                <div class="form-group form-group-sm">
                                    <select name="cbDay" style="margin-top: 5px; height: 30px;">
                                        <option value="">Ngày</option>
                                        @for($i = $hFunction->getDayFromDate($fromDate);$i<= 31; $i++)
                                            <option value="{!! $i !!}">{!! $i !!}</option>
                                        @endfor
                                    </select>
                                    <span>/</span>
                                    <select name="cbMonth" style="margin-top: 5px; height: 30px;">
                                        {{--<option name="cbMonthIn" value="">Tháng</option>--}}
                                        <option value="{!! (int)$hFunction->getMonthFromDate($fromDate) !!}">{!! (int)$hFunction->getMonthFromDate($fromDate) !!}</option>
                                    </select>
                                    <span>/</span>
                                    <select name="cbYear" style="margin-top: 5px; height: 30px;">
                                        {{--<option value="">Năm</option>--}}
                                        <option value="{!! $hFunction->getYearFromDate($fromDate) !!}">{!! $hFunction->getYearFromDate($fromDate) !!}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6"
                                 @if($mobileStatus) style="padding: 0 0;" @endif>
                                <div class="form-group form-group-sm qc-padding-none">
                                    <label>Số tiền (VND):</label>
                                    <input type="number" class="form-control" name="txtMoney"
                                           placeholder="Nhập số tiền">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6"
                                 @if($mobileStatus) style="padding: 0 0;" @endif>
                                <div class="form-group form-group-sm qc-padding-none">
                                    <label>Ghi chú: </label>
                                    <input type="text" class="form-control" name="txtDescription" value="">
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6"
                                 @if($mobileStatus) style="padding: 0 0;" @endif>
                                <div class="form-group form-group-sm text-center qc-padding-none">
                                    <em class="qc-color-red">Không còn đủ tiền để ứng</em>
                                </div>
                            </div>
                        </div>
                    @endif

                @endif

                <div class="row">
                    <div class="qc-padding-top-20 qc-padding-bot-20  qc-border-none col-sx-12 col-sm-12 col-md-12 col-lg-12"
                         @if($mobileStatus) style="padding: 0 0;" @endif>
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        @if(!empty($workSelectId) && $limitBeforePay > 100)
                            <button type="button" class="qc_save btn btn-primary">Lưu</button>
                            <button type="reset" class="btn btn-default">Hủy</button>
                            <a href="{!! route('qc.ad3d.finance.salary.pay-before.get') !!}">
                                <button type="button" class="btn btn-sm btn-default">Đóng</button>
                            </a>
                        @else
                            <a href="{!! route('qc.ad3d.finance.salary.pay-before.get') !!}">
                                <button type="button" class="btn btn-primary">Đóng</button>
                            </a>
                        @endif

                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
