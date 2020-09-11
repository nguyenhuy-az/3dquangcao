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

?>
@extends('work.store.tool-package-allocation.index')
@section('qc_work_store_allocation_body')
    <div class="row qc_work_store_allocation_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: black;color: yellow;">
                                <th class="text-center" style="width: 20px;">STT</th>
                                <th>Nhân viên</th>
                                <th>Túi đồ nghề</th>
                                <th class="text-center">Đã trang bị</th>
                                <th></th>
                            </tr>
                            @if($hFunction->checkCount($dataToolPackageAllocation))
                                <?php $n_o = 0; ?>
                                @foreach($dataToolPackageAllocation as $toolPackageAllocation)
                                    <?php
                                    $allocationId = $toolPackageAllocation->allocationId();
                                    $n_o = $n_o + 1;
                                    $dataStaffAllocation = $toolPackageAllocation->companyStaffWork->staff;
                                    # anh dai dien
                                    $image = $dataStaffAllocation->image();
                                    if ($hFunction->checkEmpty($image)) {
                                        $src = $dataStaffAllocation->pathDefaultImage();
                                    } else {
                                        $src = $dataStaffAllocation->pathFullImage($image);
                                    }
                                    ?>
                                    <tr class="@if($n_o%2) info @endif">
                                        <td class="text-center">
                                            {!! $n_o !!}
                                        </td>
                                        <td style="padding: 0;">
                                            <img style="width: 40px; height: 40px; border: 1px solid #d7d7d7;"
                                                 src="{!! $src !!}">
                                            {!!  $dataStaffAllocation->fullName() !!}
                                        </td>
                                        <td>
                                            {!! $toolPackageAllocation->toolPackage->name() !!}
                                        </td>
                                        <td class="text-center">
                                            {!! count($toolPackageAllocation->toolPackageAllocationDetailInfoIsActive()) !!}
                                        </td>
                                        <td class="text-center">
                                            <a class="qc-link-green"
                                               href="{!! route('qc.work.store.tool_package_allocation.check.get',$allocationId) !!}">
                                                Kiểm tra
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="5">
                                        Không có thông tin bàn giao
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
