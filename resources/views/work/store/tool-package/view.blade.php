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
$currentMonth = $hFunction->currentMonth();
# thong tin ban giaotui do nghe
$packageId = $dataToolPackage->packageId();

?>
@extends('work.store.tool-package.index')
@section('qc_work_store_tool_package_body')
    <div class="row qc_work_store_tool_package_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <a class="btn btn-sm btn-primary" onclick="qc_main.page_back();">
                        Về trang trước
                    </a>
                </div>
            </div>
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="table-responsive">
                        <tr>
                            <th colspan="4">
                                <span style="font-size: 1.5em;color: red;">Túi đồ nghề {!! $dataToolPackage->name() !!} </span>
                            </th>
                        </tr>
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: black;color: yellow;">
                                <th class="text-center" style="width: 20px;">STT</th>
                                <th>Loại Dụng cụ</th>
                                <th class="text-center">
                                    Số lượng
                                </th>
                            </tr>
                            @if($hFunction->checkCount($dataTool))
                                @foreach($dataTool as $tool)
                                    <?php
                                    $n_o = (isset($n_o)) ? $n_o + 1 : 1;
                                    $toolId = $tool->toolId();
                                    $dataCompanyStore = $dataToolPackage->companyStoreGetInfoIsActiveOfTool($packageId, $toolId);
                                    $amountStore = count($dataCompanyStore);
                                    ?>
                                    <tr class="@if($n_o%2) info @endif">
                                        <td class="text-center">
                                            {!! $n_o !!}
                                        </td>
                                        <td>
                                            {!! $tool->name() !!}
                                        </td>
                                        <td class="text-center">
                                            @if($amountStore > 0)
                                                {!! $amountStore !!}
                                            @else
                                                <span style="color: red">Chưa có</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if($amountStore > 0)
                                        <tr class="@if($n_o%2) info @endif">
                                            <td class="text-center" style="background-color: brown;">

                                            </td>
                                            <td>
                                                {!! $tool->name() !!}
                                            </td>
                                            <td class="text-center">

                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="4">
                                        Hệ thống chưa có danh sách loại đồ nghề
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
