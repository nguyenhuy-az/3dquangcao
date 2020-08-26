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
$dataToolReturnDetail = $dataToolReturn->toolReturnDetailInfo();
?>
@extends('components.container.container-8')
@section('qc_container_content')
    <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <h3 style="color: red;">CHI TIẾT TRẢ ĐỒ NGHỀ</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: black;color: yellow;">
                            <th class="text-center" style="width: 20px;"></th>
                            <th>Dụng cụ</th>
                            <th class="text-center">Số lượng giao</th>
                            <th class="text-center">Số lượng xác nhận</th>
                        </tr>
                        @if($hFunction->checkCount($dataToolReturnDetail))
                            @foreach($dataToolReturnDetail as $toolReturnDetail)
                                <?php
                                $detailId = $toolReturnDetail->detailId();
                                $storeId = $toolReturnDetail->storeId();
                                $amount = $toolReturnDetail->amount();
                                # thong tin dung cu
                                $dataTool = $toolReturnDetail->companyStore->tool;
                                # thong tin tra
                                $dataToolReturn = $toolReturnDetail->toolReturn;
                                $confirmAmount = $dataToolReturn->amountReturnConfirmOfStore($dataToolReturn->returnId(), $storeId);
                                ?>
                                {{--chi tra nhung dung cu con lai--}}
                                <tr>
                                    <td class="text-center" style="padding: 0 !important;">
                                        <div class="form-group" style="margin: 0 !important; ">
                                            <input type="checkbox" class="txtReturnDetail form-control"
                                                   checked="checked" disabled style="margin: 0;"
                                                   name="txtReturnDetail[]" value="{!! $detailId !!}">
                                        </div>
                                    </td>
                                    <td>
                                        {!!  $dataTool->name() !!}
                                    </td>
                                    <td class="text-center">
                                        {!! $amount !!}
                                    </td>
                                    <td class="text-center" style="padding: 0;">
                                        {!! $confirmAmount !!}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center" colspan="4">
                                    <a class="qc_container_close btn btn-sm btn-primary">
                                        Đóng
                                    </a>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
