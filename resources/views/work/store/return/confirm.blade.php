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
        <h3 style="color: red;">XÁC NHẬN BÀN GIAO LẠI ĐỒ NGHỀ</h3>
        <form id="frmWorkStoreReturn" role="form" name="frmWorkStoreReturn" method="post"
              enctype="multipart/form-data"
              action="{!! route('qc.work.store.return.confirm.post', $dataToolReturn->returnId()) !!}">
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
                            $storeId =  $toolReturnDetail->storeId();
                            $amount = $toolReturnDetail->amount();
                            $dataTool = $toolReturnDetail->companyStore->tool;
                            ?>
                            {{--chi tra nhung dung cu con lai--}}
                            <tr>
                                <td class="text-center" style="padding: 0 !important;">
                                    <div class="form-group" style="margin: 0 !important; ">
                                        <input type="checkbox" class="txtReturnDetail form-control" checked="checked" disabled style="margin: 0;"
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
                                    <div class="form-group" style="margin: 0;">
                                        <input class="form-control" type="number"
                                               name="txtReturnDetailAmount_{!! $detailId !!}"
                                               value="{!! $amount !!}">
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3"></td>
                            <td>
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <a class="qc_save btn btn-sm btn-primary">
                                    Xác nhận
                                </a>
                                <a class="qc_container_close btn btn-sm btn-default">
                                    Đóng
                                </a>
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        </form>
    </div>
@endsection
