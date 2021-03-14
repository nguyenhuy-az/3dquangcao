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
$staffLoginId = $dataStaffLogin->staffId();
$dataCompanyLogin = $modelStaff->companyLogin();
$loginCompanyStaffWork = $modelStaff->loginCompanyStaffWork();
# chi duoc them - xoa - sua khi thuoc NV cua cty
$actionStatus = $dataCompanySelected->checkStaffWorkingOfCompany($staffLoginId);
$indexHref = route('qc.ad3d.system.staff.get');
$dataStaffLogin->checkRootManage();
?>
@section('titlePage')
    Nhân viên đang làm
@endsection
@extends('ad3d.system.staff.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 2px 0 2px 0; ">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <a href="{!! route('qc.ad3d.system.staff.change-pass.get') !!}">
                                <em>Đổi mật khẩu</em>
                            </a><span>&nbsp; | &nbsp;</span>
                            <a href="{!! route('qc.ad3d.system.staff.change-account.get') !!}">
                                <em>Đổi Tài khoản</em>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="padding: 0;">
                    <select class="cbCompanyFilter form-control" name="cbCompanyFilter"
                            data-action-status="{!! $actionStatus !!}"
                            data-href="{!! route('qc.ad3d.system.staff.get') !!}">
                        @if($hFunction->checkCount($dataListCompany))
                            @foreach($dataListCompany as $company)
                                <?php $companyId = $company->companyId(); ?>
                                @if($dataCompanyLogin->checkParent())
                                    <option value="{!! $companyId !!}"
                                            @if($companyFilterId == $companyId) selected="selected" @endif >
                                        {!! $company->name() !!}
                                    </option>
                                @else
                                    @if($companyFilterId == $companyId)
                                        <option value="{!! $companyId !!}">
                                            {!! $company->name() !!}
                                        </option>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="padding: 0;">
                    @if($actionStatus)
                        <a class="form-control btn btn-primary qc-link-white-bold "
                           href="{!! route('qc.ad3d.system.staff.add.get') !!}">
                            <i class="qc-font-size-14 glyphicon glyphicon-plus"></i>
                            <span class="qc-font-size-14">THÊM</span>
                        </a>
                    @else
                        <span style="background-color: red; color: yellow; padding: 3px;">
                            Đang truy cập từ cty mẹ
                        </span>
                    @endif
                </div>
            </div>
            <div class="qc_ad3d_list_content row"
                 data-href-reset="{!! route('qc.ad3d.system.staff.reset_pass') !!}"
                 data-href-del="{!! route('qc.ad3d.system.staff.delete') !!}">
                <table class="table table-hover table-bordered">
                    <tr style="background-color: black;color: yellow;">
                        <th class="text-center" style="width: 20px;">STT</th>
                        <th>TÊN</th>
                    </tr>
                    @if($hFunction->checkCount($dataCompanyStaffWork))
                        <?php
                        $perPage = $dataCompanyStaffWork->perPage();
                        $currentPage = $dataCompanyStaffWork->currentPage();
                        $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                        ?>
                        @foreach($dataCompanyStaffWork as $companyStaffWork)
                            <?php
                            # thong tin nhan vien
                            $companyStaffWorkId = $companyStaffWork->workId();
                            $staff = $companyStaffWork->staff;
                            $staffId = $staff->staffId();
                            $image = $staff->image();
                            $src = $staff->pathAvatar($image);
                            $staffId = $staff->staffId();
                            # thong tin bang cham cong
                            $dataLastWork = $companyStaffWork->workLastInfo();
                            ?>
                            <tr class="qc_ad3d_list_object @if($n_o%2) info @endif"
                                data-object="{!! $staffId !!}">
                                <td class="text-center">
                                    {!! $n_o += 1 !!}
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                            <div class="media">
                                                <a class="pull-left"
                                                   href="{!! route('qc.ad3d.system.staff.info.get', $staffId) !!}">
                                                    <img class="media-object img-circle"
                                                         style="    background-color: white; width: 40px;height: 40px; border: 1px solid #d7d7d7;"
                                                         src="{!! $src !!}">
                                                </a>

                                                <div class="media-body">
                                                    <h6 class="media-heading">{!! $staff->fullName() !!}</h6>
                                                    @if($staff->checkRootStatus())
                                                        @if($dataStaffLogin->checkRootStatus())
                                                            <a class="qc_edit qc-font-size-12 qc-link-green"
                                                               title="Quản lý thông tin"
                                                               href="{!! route('qc.ad3d.system.staff.info.get', $staffId) !!}">
                                                                Chi tiết
                                                            </a>
                                                        @endif
                                                    @else
                                                        <a class="qc_edit qc-font-size-12 qc-link-green"
                                                           title="Quản lý thông tin"
                                                           href="{!! route('qc.ad3d.system.staff.info.get', $staffId) !!}">
                                                            Chi tiết
                                                        </a>
                                                        {{--<span>|</span>
                                                        <a class="qc_delete qc-link-red" href="#"
                                                           title="Xóa khỏi danh sách NV">
                                                            <i class="qc-font-size-14 glyphicon glyphicon-trash"></i>
                                                        </a>--}}
                                                    @endif
                                                    <span> | </span>
                                                    <a class="qc-font-size-12 qc-link-red" title="Thống kê"
                                                       href="{!! route('qc.ad3d.system.staff.statistical.get', $companyStaffWorkId) !!}">
                                                        Thống kê
                                                    </a>
                                                    <span> | </span>
                                                    <a class="qc_reset_pass qc-link-green" href="#">Reset Mật khẩu</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                            <?php
                                            $dataDepartment = $companyStaffWork->infoDepartmentActivityOfStaff();
                                            ?>
                                            @if($hFunction->checkCount($dataDepartment))
                                                @foreach($dataDepartment as $department)
                                                    {!! $department->name().',' !!}
                                                @endforeach
                                            @else
                                                <em class="qc-color-grey">Chưa phân bổ CV</em>
                                            @endif
                                            <br/>
                                            @if($staff->checkLoginAdmin())
                                                <em class="qc-color-grey">Có quyền Admin</em>
                                            @else
                                                <em class="qc-color-grey">Không có quyền Admin</em>
                                            @endif
                                            <span>&nbsp;|&nbsp;</span>
                                            @if($staff->checkApplyRule())
                                                <em class="qc-color-grey">Áp dụng nội quy</em>
                                            @else
                                                <em class="qc-color-grey">Không áp dụng nội quy</em>
                                            @endif
                                            @if(!$companyStaffWork->checkExistsActivityStaffWorkSalary())
                                                <span>&nbsp;|&nbsp;</span>
                                                <em class="qc-color-grey">Chư có bảng lương</em>
                                            @endif
                                            @if($hFunction->checkCount($dataLastWork))
                                                @if(!$dataLastWork->checkActivity())
                                                    <span>&nbsp;|&nbsp;</span>
                                                    <a class="qc_ad3d_staff_open_work_act qc-link-red"
                                                       data-href="{!! route('qc.ad3d.system.staff.open_work.get', $companyStaffWork->workId()) !!}">
                                                        Mở chấm công
                                                    </a>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="text-center" colspan="2">
                                {!! $hFunction->page($dataCompanyStaffWork) !!}
                            </td>
                        </tr>
                    @endif
                </table>
            </div>

        </div>
    </div>
@endsection
