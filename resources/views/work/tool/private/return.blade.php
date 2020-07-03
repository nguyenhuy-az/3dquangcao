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
$dataCompanyStaffWork = $dataStaff->companyStaffWorkInfoActivity();
?>
@extends('work.tool.private.index')
@section('qc_work_tool_private_body')
    <div class="row qc_work_tool_private_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-8 col-lg-8">
            @if($hFunction->checkCount($dataCompanyStaffWork))
                <?php
                $workId = $dataCompanyStaffWork->workId();
                ?>
                <div class="row">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <h3>BÀN GIAO LẠI ĐỒ NGHỀ</h3>
                    </div>
                </div>
                {{-- chi tiêt --}}
                <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <form id="frmWorkToolPrivateReturn" role="form" name="frmWorkToolPrivateReturn" method="post"
                          enctype="multipart/form-data"
                          action="{!! route('qc.work.tool.private.return.post') !!}">
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <tr style="background-color: black;color: yellow;">
                                        <th class="text-center" style="width: 20px;"></th>
                                        <th>Dụng cụ</th>
                                        <th class="text-center">Số lượng nhận</th>
                                        <th class="text-center">SL đã trả</th>
                                        <th class="text-center">Số lượng trả</th>
                                    </tr>
                                    @if($hFunction->checkCount($dataCompanyStore))
                                        @foreach($dataCompanyStore as $companyStore)
                                            <?php
                                            $storeId = $companyStore->storeId();
                                            $dataTool = $companyStore->tool;
                                            $toolId = $dataTool->toolId();
                                            $toolName = $dataTool->name();
                                            $totalToolReceiveOfStaff = $dataCompanyStaffWork->totalToolReceive($toolId, $workId);
                                            $totalToolReturnOfStaff = 0;
                                            ?>
                                            {{--chi tra nhung dung cu con lai--}}
                                            @if($totalToolReceiveOfStaff > $totalToolReturnOfStaff)
                                                <tr class="@if($selectedStoreId == $storeId) info @endif">
                                                    <td class="text-center" style="padding: 0;">
                                                        <div class="form-group" style="margin: 0;">
                                                            <input type="checkbox" class="txtReturnStore form-control"
                                                                   name="txtReturnStore[]" style="margin: 0;"
                                                                   @if($selectedStoreId == $storeId) checked="checked"
                                                                   @endif value="{!! $storeId !!}">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        {!!  $toolName !!}
                                                    </td>
                                                    <td class="text-center">
                                                        {!! $totalToolReceiveOfStaff !!}
                                                    </td>
                                                    <td class="text-center">

                                                    </td>
                                                    <td class="text-center" style="padding: 0;">
                                                        <div class="form-group" style="margin: 0;">
                                                            <input class="form-control" type="number"
                                                                   name="txtReturnAmount_{!! $storeId !!}"
                                                                   value="{!! $totalToolReceiveOfStaff - $totalToolReturnOfStaff !!}">
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        <tr>
                                            <td colspan="4" style="background-color:whitesmoke;">
                                                <div class="checkbox" style="margin: 0;">
                                                    <label style="color: red; font-size: 20px;">
                                                        <input class="txtCheckAll" type="checkbox" name="txtCheckAll">
                                                        Giao
                                                        hết
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                                <a class="qc_save btn btn-sm btn-primary">
                                                    GIAO
                                                </a>
                                                <a class="btn btn-sm btn-default" onclick="qc_main.page_back();">
                                                    Về trang trước
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
            @else
                <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-6 col-lg-6">
                    <span>Không có thông tin làm việc</span>
                </div>
            @endif
        </div>
    </div>
@endsection
