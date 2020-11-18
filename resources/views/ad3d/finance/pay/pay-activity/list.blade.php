<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:32 PM
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$hrefIndex = route('qc.ad3d.finance.pay_activity.get');
$dataStaffLogin = $modelStaff->loginStaffInfo();
$companyLoginId = $dataStaffLogin->companyId(); # id cua cong nhan vien dang dang nhap
?>
@extends('ad3d.finance.pay.pay-activity.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! $hrefIndex !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20">CHI HOẠT ĐỘNG</label>
                </div>
                <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <select class="cbCompanyFilter form-control" name="cbCompanyFilter"
                            data-href-filter="{!! $hrefIndex !!}">
                        {{--@if($dataStaffLogin->checkRootManage())
                            <option value="0">Tất cả</option>
                        @endif--}}
                        @if($hFunction->checkCount($dataCompany))
                            @foreach($dataCompany as $company)
                                @if($dataStaffLogin->checkRootManage())
                                    <option value="{!! $company->companyId() !!}"
                                            @if($companyFilterId == $company->companyId()) selected="selected" @endif >{!! $company->name() !!}</option>
                                @else
                                    @if($companyFilterId == $company->companyId())
                                        <option value="{!! $company->companyId() !!}">{!! $company->name() !!}</option>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="qc_ad3d_list_content row"
                 data-href-view="{!! route('qc.ad3d.finance.pay_activity.view.get') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: black; color:yellow;">
                            <th class="text-center" style="width: 20px;">STT</th>
                            <th style="width: 150px;">NGÀY</th>
                            <th>SỐ TIỀN - LÝ DO</th>
                            <th style="width: 400px;">CHI TIẾT</th>
                            <th>NGƯỜI CHI</th>
                            <th>NGƯỜI DUYỆT</th>
                        </tr>
                        <tr>
                            <td class="text-center"></td>
                            <td style="padding: 0 !important;">
                                <select class="cbDayFilter col-sx-3 col-sm-3 col-md-3 col-lg-3"
                                        style="padding: 0;height: 34px;"
                                        data-href="{!! $hrefIndex !!}">
                                    <option value="0" @if((int)$dayFilter == 0) selected="selected" @endif >
                                        All
                                    </option>
                                    @for($d =1;$d<= 31; $d++)
                                        <option value="{!! $d !!}"
                                                @if((int)$dayFilter == $d) selected="selected" @endif >
                                            {!! $d !!}
                                        </option>
                                    @endfor
                                </select>
                                <select class="cbMonthFilter col-sx-3 col-sm-3 col-md-3 col-lg-3"
                                        style="padding: 0;height: 34px;"
                                        data-href="{!! $hrefIndex !!}">
                                    <option value="0" @if($monthFilter == 0) selected="selected" @endif>
                                        All
                                    </option>
                                    @for($m =1;$m<= 12; $m++)
                                        <option value="{!! $m !!}"
                                                @if((int)$monthFilter == $m) selected="selected" @endif>
                                            {!! $m !!}
                                        </option>
                                    @endfor
                                </select>
                                <select class="cbYearFilter col-sx-6 col-sm-6 col-md-6 col-lg-6"
                                        style="padding: 0;height: 34px;"
                                        data-href="{!! $hrefIndex !!}">
                                    @for($y =2017;$y<= 2050; $y++)
                                        <option value="{!! $y !!}"
                                                @if($yearFilter == $y) selected="selected" @endif>
                                            {!! $y !!}
                                        </option>
                                    @endfor
                                </select>
                            </td>
                            <td>
                                <b class="qc-color-red">{!! $hFunction->currencyFormat($totalMoneyPayActivity)  !!}</b>
                            </td>
                            <td></td>
                            <td style="padding: 0 !important;">
                                <select class="cbStaffFilterId form-control" data-href="{!! $hrefIndex !!}">
                                    <option value="0" @if($staffFilterId == 0) selected="selected" @endif>
                                        Tất cả
                                    </option>
                                    @if($hFunction->checkCount($dataStaff))
                                        @foreach($dataStaff as $staff)
                                            <option @if($staff->staffId() == $staffFilterId) selected="selected"
                                                    @endif  value="{!! $staff->staffId() !!}">{!! $staff->lastName() !!}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </td>
                            <td style="padding: 0 !important;">
                                <select class="cbConfirmStatusFilter form-control" name="cbConfirmStatusFilter"
                                        data-href="{!! $hrefIndex !!}">
                                    <option value="3" @if($confirmStatusFilter == 3) selected="selected" @endif>Tất cả
                                    </option>
                                    <option value="0" @if($confirmStatusFilter == 0) selected="selected" @endif>Chưa xác
                                        nhận
                                    </option>
                                    <option value="1" @if($confirmStatusFilter == 1) selected="selected" @endif>Đã xác
                                        nhận
                                    </option>
                                </select>
                            </td>
                        </tr>
                        @if($hFunction->checkCount($dataPayActivityDetail))
                            <?php $n_o = 0; ?>
                            @foreach($dataPayActivityDetail as $payActivityDetail)
                                <?php
                                $payId = $payActivityDetail->payId();
                                $money = $payActivityDetail->money();
                                $payDate = $payActivityDetail->payDate();
                                $payImage = $payActivityDetail->payImage();
                                $confirmStatus = $payActivityDetail->checkConfirm();
                                $confirmNote = $payActivityDetail->confirmNote();
                                $payCompanyId = $payActivityDetail->companyId();

                                # thong tin nhan vien
                                $dataStaffPay = $payActivityDetail->staff;
                                # anh dai dien
                                $image = $dataStaffPay->image();
                                $src = $dataStaffPay->pathAvatar($image);
                                # trang thai xac nhan
                                if ($confirmStatus) {
                                    $dataConfirmStaff = $payActivityDetail->confirmStaff;
                                } else {
                                    $dataConfirmStaff = null;
                                }
                                ?>
                                <tr class="qc_ad3d_list_object qc-ad3d-list-object @if($n_o%2) info @endif"
                                    data-object="{!! $payId !!}">
                                    <td class="text-center">
                                        {!! $n_o = $n_o+1 !!}
                                    </td>
                                    <td>
                                        <b>{!! date('d/m/Y',strtotime($payDate))  !!}</b>
                                        @if(!$confirmStatus)
                                            @if($payCompanyId == $companyLoginId)
                                                <br/>
                                                <a class="qc_confirm_get qc-font-size-14 qc-link-green-bold"
                                                   data-href="{!! route('qc.ad3d.finance.pay_activity.confirm.get', $payId) !!}">
                                                    DUYỆT
                                                </a>
                                            @endif
                                        @else
                                            @if($payActivityDetail->checkInvalid())
                                                <br/>
                                                <em style="color: blue;">- Được duyệt</em>
                                            @else
                                                <br/>
                                                <em style="color: brown;">- Không duyệt</em>
                                            @endif
                                        @endif
                                        @if(!$hFunction->checkEmpty($confirmNote))
                                            <br/>
                                            <em class="qc-color-grey">- {!! $confirmNote  !!}</em>
                                        @endif
                                    </td>
                                    <td>
                                        <b style="color: red;">{!! $hFunction->currencyFormat($money)  !!}</b>
                                        <br/>
                                        <em style="color: grey;">
                                           - {!! $payActivityDetail->payActivityList->name()  !!}
                                        </em>
                                    </td>
                                    <td>
                                        <b>
                                            {!! $payActivityDetail->payActivityList->typeLabel() !!}
                                        </b>
                                        <br/>
                                        @if(!empty($payImage))
                                            <img class="qc-link" onclick="qc_main.rotateImage(this);"
                                                 style="width: 150px;"
                                                 src="{!! $payActivityDetail->pathSmallImage($payImage) !!}">
                                            <br/>
                                        @endif
                                        @if(!empty($payActivityDetail->note()))
                                            <em style="color: grey;">{!! $payActivityDetail->note()  !!}</em>
                                        @else
                                            <em class="qc-color-grey">---</em>
                                        @endif
                                    </td>
                                    <td style="color: grey;">
                                        <div class="media">
                                            <a class="pull-left" href="#">
                                                <img class="media-object"
                                                     style="background-color: white; width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                                     src="{!! $src !!}">
                                            </a>

                                            <div class="media-body">
                                                <h5 class="media-heading">{!! $dataStaffPay->fullName() !!}</h5>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($hFunction->checkCount($dataConfirmStaff))
                                            <div class="media">
                                                <a class="pull-left" href="#">
                                                    <img class="media-object"
                                                         style="background-color: white; width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                                         src="{!! $dataConfirmStaff->pathAvatar($dataConfirmStaff->image()) !!}">
                                                </a>

                                                <div class="media-body">
                                                    <h5 class="media-heading">{!! $dataConfirmStaff->fullName() !!}</h5>
                                                </div>
                                            </div>
                                        @else
                                            <em>Chưa xác nhận</em>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center qc-padding-top-20 qc-padding-bot-20" colspan="6">
                                    {!! $hFunction->page($dataPayActivityDetail) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-center" colspan="6">
                                    <em class="qc-color-red">Không có thông chi</em>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
