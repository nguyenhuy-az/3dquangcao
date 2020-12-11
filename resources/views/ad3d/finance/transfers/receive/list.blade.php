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
$dataStaffLogin = $modelStaff->loginStaffInfo();
$hrefIndex = route('qc.ad3d.finance.transfers.receive.get');
?>
@extends('ad3d.finance.transfers.receive.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="padding-left: 0;">
            @include('ad3d.finance.transfers.menu')
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="qc_ad3d_list_content row">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr>
                            <td colspan="3" style="padding: 0;">
                                <select class="cbCompanyFilter form-control" name="cbCompanyFilter"
                                        style="height: 34px;"
                                        data-href-filter="{!! $hrefIndex !!}">
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
                            </td>
                            <td></td>
                        </tr>
                        <tr style="background-color: black; color: yellow;">
                            <th style="width: 150px;">NGÀY</th>
                            <th style="width: 200px;">
                                SỐ TIỀN
                            </th>
                            <th style="width: 170px;">NGƯỜI NHẬN</th>
                            <th>NGƯỜI CHUYỂN</th>
                        </tr>
                        <tr>
                            <td style="padding: 0;">
                                <select class="cbDayFilter col-sx-4 col-sm-4 col-md-4 col-lg-4"
                                        style="padding: 0;height: 34px;"
                                        data-href="{!! $hrefIndex !!}">
                                    <option value="0" @if((int)$dayFilter == 0) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($i =1;$i<= 31; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$dayFilter == $i) selected="selected" @endif >{!! $i !!}</option>
                                    @endfor
                                </select>
                                <select class="cbMonthFilter col-sx-4 col-sm-4 col-md-4 col-lg-4"
                                        style="padding: 0;height: 34px;"
                                        data-href="{!! $hrefIndex !!}">
                                    <option value="0" @if((int)$monthFilter == 0) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($m =1;$m<= 12; $m++)
                                        <option value="{!! $m !!}"
                                                @if((int)$monthFilter == $m) selected="selected" @endif>
                                            {!! $m !!}
                                        </option>
                                    @endfor
                                </select>
                                <select class="cbYearFilter col-sx-4 col-sm-4 col-md-4 col-lg-4"
                                        style="padding: 0;height: 34px;"
                                        data-href="{!! $hrefIndex !!}">
                                    @for($i =2017;$i<= 2050; $i++)
                                        <option value="{!! $i !!}"
                                                @if($yearFilter == $i) selected="selected" @endif>
                                            {!! $i !!}
                                        </option>
                                    @endfor
                                </select>
                            </td>
                            <td style="padding: 0;">
                                <select class="cbTransfersType form-control" name="cbTransfersType"
                                        data-href="{!! $hrefIndex !!}">
                                    <option value="0" @if($transfersType == 0) selected="selected" @endif>
                                        Tất cả
                                    </option>
                                    <option value="{!! $modelTransfers->getDefaultTransferTypeOfBusiness() !!}"
                                            @if($transfersType == $modelTransfers->getDefaultTransferTypeOfBusiness()) selected="selected" @endif>
                                        Chuyển doanh thu
                                    </option>
                                    <option value="{!! $modelTransfers->getDefaultTransferTypeOfInvestment() !!}"
                                            @if($transfersType == $modelTransfers->getDefaultTransferTypeOfInvestment()) selected="selected" @endif >
                                        Chuyển đầu tư
                                    </option>
                                    <option value="{!! $modelTransfers->getDefaultTransferTypeOfTreasurer() !!}"
                                            @if($transfersType == $modelTransfers->getDefaultTransferTypeOfTreasurer()) selected="selected" @endif >
                                        Chuyển nộp lên cty
                                    </option>
                                </select>
                            </td>
                            <td class="text-center" style="padding: 0px;">
                                <select class="cbStaffFilterId form-control" data-href="{!! $hrefIndex !!}">
                                    <option value="0" @if($staffFilterId == 0) selected="selected" @endif>
                                        Tất cả
                                    </option>
                                    @if($hFunction->checkCount($dataStaff))
                                        @foreach($dataStaff as $staff)
                                            <option @if($staff->staffId() == $staffFilterId) selected="selected"
                                                    @endif  value="{!! $staff->staffId() !!}">
                                                {!! $staff->fullName() !!}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </td>

                            <td></td>

                        </tr>
                        @if($hFunction->checkCount($dataTransfers))
                            <?php
                            $perPage = $dataTransfers->perPage();
                            $currentPage = $dataTransfers->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataTransfers as $transfers)
                                <?php
                                $transfersId = $transfers->transfersId();
                                $transfersReason = $transfers->reason();
                                # thong tin nguoi chuyen
                                $dataTransfersStaff = $transfers->transfersStaff;
                                # thong tin nguoi nha
                                $dataReceiveStaff = $transfers->receiveStaff;
                                $n_o += 1;
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2) info @endif"
                                    data-object="{!! $transfersId !!}">
                                    <td>
                                        <a class="qc_view qc-link-green">
                                            <b style="color: blue;">{!! date('d/m/Y', strtotime($transfers->transfersDate())) !!}</b>
                                        </a>
                                        @if($transfers->checkConfirmReceive())
                                            <br/>
                                            <i class="glyphicon glyphicon-ok qc-font-size-12" style="color: green;"></i>
                                            <em class="qc-color-grey">Đã xác nhận</em>
                                        @else
                                            <br/>
                                            <i class="glyphicon glyphicon-ok qc-font-size-12" style="color: red;"></i>
                                            <em style="color: brown;">Chờ xác nhận</em>
                                            @if($dataStaffLogin->staffId() == $transfers->receiveStaffId())
                                                <br/>
                                                <a class="qc_ad3d_transfer_confirm_receive_act qc-link-red qc-font-size-14"
                                                   data-href="{!! route('qc.ad3d.finance.transfers.receive.confirm.get',$transfersId) !!}">
                                                    XÁC NHẬN
                                                </a>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <b style="color:red;">
                                            {!! $hFunction->currencyFormat($transfers->money()) !!}
                                        </b>
                                        <br/>
                                        <span style="color: grey;">{!! $transfers->transferTypeLabel($transfers->transferType()) !!}</span>
                                        @if(!$hFunction->checkEmpty($transfersReason))
                                            <br/>
                                            <em style="color: grey;">- {!! $transfersReason !!}</em>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="media">
                                            <a class="pull-left" href="#">
                                                <img class="media-object"
                                                     style="background-color: white; width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                                     src="{!! $dataReceiveStaff->pathAvatar($dataReceiveStaff->image()) !!}">
                                            </a>

                                            <div class="media-body">
                                                <h5 class="media-heading">{!! $dataReceiveStaff->lastName() !!}</h5>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 3px;">
                                        <div class="media">
                                            <a class="pull-left" href="#">
                                                <img class="media-object"
                                                     style="background-color: white; width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                                     src="{!! $dataTransfersStaff->pathAvatar($dataTransfersStaff->image()) !!}">
                                            </a>

                                            <div class="media-body">
                                                <h5 class="media-heading">{!! $dataTransfersStaff->lastName() !!}</h5>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="4"
                                    style="border-left: 5px solid brown; padding-top: 0; padding-bottom: 0;">
                                    {!! $hFunction->page($dataTransfers) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="4">
                                    <em class="qc-color-red">Không tìm thấy thông tin</em>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
