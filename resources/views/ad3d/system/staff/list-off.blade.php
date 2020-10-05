<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:32 PM
 *
 * dataCompany
 *
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$dataStaffLogin = $modelStaff->loginStaffInfo();
$indexHref = route('qc.ad3d.system.staff.get');
?>
@section('titlePage')
    NHÂN VIÊN ĐÃ NGHỈ
@endsection
@extends('ad3d.system.staff.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="qc_ad3d_list_content row">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: black;color: yellow;">
                            <th class="text-center" style="width: 20px;">STT</th>
                            <th>TÊN</th>
                            <th>
                                CÔNG TY ĐÃ LÀM
                            </th>
                            <th>
                                THÁNG NGHỈ
                                <br/>
                                <em>(Hết tháng)</em>
                            </th>
                        </tr>
                        @if($hFunction->checkCount($dataCompanyStaffWork))
                            <?php
                            $perPage = $dataCompanyStaffWork->perPage();
                            $currentPage = $dataCompanyStaffWork->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataCompanyStaffWork as $companyStaffWork)
                                <?php
                                $companyStaffWorkId = $companyStaffWork->workId();
                                # thong tin nhan vien
                                $staff = $companyStaffWork->staff;
                                $staffId = $staff->staffId();
                                $image = $staff->image();
                                $src = $staff->pathAvatar($image);
                                # trạng thái làm
                                $checkWorkStatus = $staff->checkWorkStatus();
                                # thong tin bang cham cong
                                $dataLastWork = $companyStaffWork->workLastInfo();
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2) info @endif"
                                    data-object="{!! $staffId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td class="text-canter" style="padding: 0;">
                                        <a class="qc-link-green"
                                           href="{!! route('qc.ad3d.system.staff.info.get', $staffId) !!}">
                                            <img style="max-width: 50px;height: 50px; border: 1px solid #d7d7d7;"
                                                 src="{!! $src !!}">
                                            {!! $staff->fullName() !!}
                                        </a>
                                    </td>
                                    <td>
                                        {!! $companyStaffWork->company->nameCode() !!}
                                    </td>
                                    <td>
                                        @if($hFunction->checkCount($dataLastWork))
                                            <span>
                                                {!! date('d/m/Y', strtotime($dataLastWork->toDate())) !!}
                                            </span>
                                        @else
                                            <em style="color: red;">Không có thông tin làm</em>
                                        @endif
                                        <br/>
                                        <a class="qc_ad3d_staff_restore_work_act qc-link-red"
                                           data-href="{!! route('qc.ad3d.system.staff.restore_work.get', $companyStaffWorkId) !!}">
                                            LÀM LẠI
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center" colspan="4">
                                    {!! $hFunction->page($dataCompanyStaffWork) !!}
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
