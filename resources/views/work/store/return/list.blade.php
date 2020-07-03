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
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$dataStaff = $modelStaff->loginStaffInfo();
$loginStaffId = $dataStaff->staffId();
$companyId = $dataStaff->companyId();
$hrefIndex = route('qc.work.store.return.get');
$currentMonth = $hFunction->currentMonth();

?>
@extends('work.store.return.index')
@section('qc_work_store_return_body')
    <div class="row qc_work_store_return_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-6 col-lg-6">
            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: black;color: yellow;">
                                <th class="text-center" style="width: 20px;">STT</th>
                                <th>Người trả</th>
                                <th>
                                    Ngày
                                </th>
                                <th class="text-center">
                                    Số lượng báo trả
                                </th>
                                <th class="text-center">
                                    Xác nhận
                                </th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td style="padding: 0">
                                    <select class="cbConfirmStatusFilter form-control" name="cbConfirmStatusFilter"
                                            data-href="{!! $hrefIndex !!}">
                                        <option value="100" @if($cbConfirmStatusFilter == 0) selected="selected" @endif>
                                            Tất cả
                                        </option>
                                        <option value="0" @if($cbConfirmStatusFilter == 0) selected="selected" @endif>
                                            Chưa duyệt
                                        </option>
                                        <option value="1" @if($cbConfirmStatusFilter == 1) selected="selected" @endif>Đã
                                            duyệt
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            @if($hFunction->checkCount($dataToolReturn))
                                <?php $n_o = 0; ?>
                                @foreach($dataToolReturn as $toolReturn)
                                    <?php
                                    $returnId = $toolReturn->returnId();
                                    $returnDate = $toolReturn->returnDate();
                                    # tong so luong tra
                                    $totalAmount = $toolReturn->totalAmountStoreReturn();
                                    # thong tin lam viec
                                    $dataCompanyStaffWork = $toolReturn->companyStaffWork;
                                    # thong tin nha vien tra
                                    $dataStaffReturn = $dataCompanyStaffWork->staff;
                                    $n_o = $n_o + 1;
                                    ?>
                                    <tr class="@if($n_o%2) info @endif">
                                        <td class="text-center">
                                            {!! $n_o !!}
                                        </td>
                                        <td>
                                            {!!  $dataStaffReturn->fullName() !!}
                                        </td>
                                        <td>
                                            {!! date('d/m/Y', strtotime($returnDate)) !!}
                                        </td>
                                        <td class="text-center">
                                            <b style="color: blue;">{!! $totalAmount !!}</b>
                                        </td>
                                        <td class="text-center">
                                            @if($toolReturn->checkConfirm())
                                                <em>Đã duyệt</em>
                                            @else
                                                <a class="qc_confirm_get qc-link-green-bold"
                                                   data-href="{!! route('qc.work.store.return.confirm.get', $returnId) !!}">
                                                    Duyệt
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="6">
                                        Không có thông tin trả
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
