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
$hrefIndex = route('qc.work.store.tool_package_allocation.get');
$currentMonth = $hFunction->currentMonth();
?>
@extends('work.store.tool-package.index')
@section('qc_work_store_tool_package_body')
    <div class="row qc_work_store_tool_package_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-8 col-lg-8">
            {{-- chi tiêt --}}
            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr>
                                <td class="text-center" colspan="4" style="border: none;">
                                    <span style="background-color: red; color: yellow;">
                                        Hệ thống sẽ giao ngẫu nhiện  CHO NHỮNG NGƯỜI THI CÔNG CHƯA CÓ.
                                        <br/>
                                        Nếu không có túi đồ nghề hệ thống sẽ tự tạo túi đồ nghề</span>
                                </td>
                                <td style="padding: 0; border: none;">
                                    <a class="qc_auto_allocation_get btn btn-primary" style="width: 100%;"
                                       data-href="{!! route('qc.work.store.tool_package.auto_allocation.add') !!}">GIAO
                                        TỰ ĐỘNG</a>
                                </td>
                            </tr>
                            <tr style="background-color: black;color: yellow;">
                                <th class="text-center" style="width: 20px;">STT</th>
                                <th>Túi đồ nghề</th>
                                <th>
                                    Hình ảnh
                                </th>
                                <th class="text-center">
                                    Dụng cụ đã trang bị
                                </th>
                                <th class="text-center">
                                    Đã phát
                                </th>
                            </tr>
                            {{--<tr>
                                <td></td>
                                <td style="padding: 0;">
                                    <select class="cbToolFilter form-control" name="cbToolFilter"
                                            data-href="{!! $hrefIndex !!}">
                                        <option value="0" @if($typeFilter == 0) selected="selected" @endif>Tất cả
                                        </option>
                                        @if($hFunction->checkCount($dataTool))
                                            @foreach($dataTool as $tool)
                                                <option value="{!! $tool->toolId() !!}"
                                                        @if($toolFilter == $tool->toolId()) selected="selected" @endif>
                                                    {!! $tool->name()  !!}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>--}}
                            @if($hFunction->checkCount($dataToolPackage))
                                <?php $n_o = 0; ?>
                                @foreach($dataToolPackage as $toolPackage)
                                    <?php
                                    $package = $toolPackage->packageId();
                                    $packageName = $toolPackage->name();
                                    $n_o = $n_o + 1;
                                    # thong tin dang ban giao
                                    $dataToolPackageAllocation = $toolPackage->toolPackageAllocationIsActive();
                                    # do nghe dang trang bo trong tui
                                    $companyStore = $toolPackage->companyStoreGetInfoIsActive();
                                    ?>
                                    <tr class="@if($n_o%2) info @endif">
                                        <td class="text-center">
                                            {!! $n_o !!}
                                        </td>
                                        <td>
                                            {!!  $packageName !!}
                                        </td>
                                        <td>

                                        </td>
                                        <td class="text-center">
                                            {!! count($companyStore) !!}
                                        </td>
                                        <td class="text-center">
                                            @if($hFunction->checkCount($dataToolPackageAllocation))
                                                {!! $dataToolPackageAllocation->companyStaffWork->staff->fullName() !!}
                                            @else
                                                <span>X</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td style="color: red;" colspan="5">
                                        Hê thống chưa có túi nghề
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <a class="btn btn-sm btn-primary" href="{!! route('qc.work.home') !!}">Đóng</a>
                </div>
            </div>
        </div>
    </div>
@endsection
