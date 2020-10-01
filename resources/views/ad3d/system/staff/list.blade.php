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
    Nhân viên đang làm
@endsection
@extends('ad3d.system.staff.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 2px 0 2px 0; ">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <a href="{!! route('qc.ad3d.system.staff.change-pass.get') !!}">
                                <em>Đổi mật khẩu</em>
                            </a><span>&nbsp; | &nbsp;</span>
                            <a href="{!! route('qc.ad3d.system.staff.change-account.get') !!}">
                                <em>Đổi Tài khoản</em>
                            </a>
                        </div>
                        <div class="text-right col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <a class="btn btn-primary qc-link-white-bold " href="{!! route('qc.ad3d.system.staff.add.get') !!}">
                                <i class="qc-font-size-14 glyphicon glyphicon-plus"></i>
                                <span class="qc-font-size-14">Thêm</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="qc_ad3d_list_content qc-ad3d-table-container row"
                 data-href-view="{!! route('qc.ad3d.system.staff.view.get') !!}"
                 data-href-reset="{!! route('qc.ad3d.system.staff.reset_pass') !!}"
                 data-href-del="{!! route('qc.ad3d.system.staff.delete') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: black;color: yellow;">
                            <th class="text-center" style="width: 20px;">STT</th>
                            <th class="text-center">Ảnh</th>
                            <th>Tên</th>
                            <th>
                                Công ty
                                <br/> (Đang làm)
                            </th>
                            <th>Bộ phân</th>
                            <th>
                                Bảng lương
                                <br/> (cơ bản)
                            </th>
                            <th>
                                Chấm Công
                            </th>
                            <th class="text-center">
                                Quyền Admin
                            </th>
                            <th class="text-center">
                                Áp dụng nội quy <br/>
                                (Báo giờ vào/nghỉ)
                            </th>
                            <th class="text-center">
                                TT Đăng nhập
                            </th>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="padding: 0 !important;">
                                <select class="cbCompanyFilter form-control" name="cbCompanyFilter"
                                        data-href="{!! route('qc.ad3d.system.staff.get') !!}">
                                    {{--@if($dataStaffLogin->checkRootManage())
                                        <option value="null" @if($companyFilterId == null) selected="selected" @endif>
                                            Tất cả
                                        </option>
                                    @endif--}}
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
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        @if($hFunction->checkCount($dataStaff))
                            <?php
                            $perPage = $dataStaff->perPage();
                            $currentPage = $dataStaff->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataStaff as $staff)
                                <?php
                                $staffId = $staff->staffId();
                                # dang lam cho 1 cty
                                $dataCompanyStaffWork = $staff->companyStaffWorkInfoActivity($staffId);
                                # anh dai dien
                                $image = $staff->image();
                                # trạng thái làm
                                $checkWorkStatus = $staff->checkWorkStatus();
                                ?>
                                @if($hFunction->checkCount($dataCompanyStaffWork))
                                    <tr class="qc_ad3d_list_object @if($n_o%2) info @endif"
                                        data-object="{!! $staffId !!}">
                                        <td class="text-center">
                                            {!! $n_o += 1 !!}
                                        </td>
                                        <td class="text-canter" style="padding: 0;">
                                            <a class="qc-link-green"
                                               href="{!! route('qc.ad3d.system.staff.info.get', $staffId) !!}">
                                                <img style="max-width: 50px;height: 50px;"
                                                     src="{!! $staff->pathAvatar($image) !!}">
                                            </a>
                                        </td>
                                        <td>
                                            {!! $staff->firstName().' '.$staff->lastName() !!}
                                            @if($hFunction->checkCount($dataCompanyStaffWork))
                                                <br/>
                                                @if($staff->checkRootStatus())
                                                    @if($dataStaffLogin->checkRootStatus())
                                                        <a class="qc_edit qc-link" title="Quản lý thông tin"
                                                           href="{!! route('qc.ad3d.system.staff.info.get', $staffId) !!}">
                                                            <i class="qc-font-size-14 glyphicon glyphicon-pencil"></i>
                                                        </a>
                                                    @endif
                                                @else
                                                    <a class="qc_edit qc-link" title="Quản lý thông tin"
                                                       href="{!! route('qc.ad3d.system.staff.info.get', $staffId) !!}">
                                                        <i class="qc-font-size-14 glyphicon glyphicon-pencil"></i>
                                                    </a>
                                                    {{--<span>|</span>
                                                    <a class="qc_delete qc-link-red" href="#"
                                                       title="Xóa khỏi danh sách NV">
                                                        <i class="qc-font-size-14 glyphicon glyphicon-trash"></i>
                                                    </a>--}}
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if($checkWorkStatus)
                                                @if($hFunction->checkCount($dataCompanyStaffWork))
                                                    {!! $dataCompanyStaffWork->company->nameCode() !!}
                                                @else
                                                    <em class="qc-color-grey">Chưa phân bổ CV</em>
                                                @endif
                                            @else
                                                <em style=" color: red;">Đã nghỉ</em>
                                            @endif
                                        </td>
                                        <td>
                                            @if($checkWorkStatus)
                                                @if($hFunction->checkCount($dataCompanyStaffWork))
                                                    <?php
                                                    $listIdDepartment = $staff->departmentActivityOfStaff();
                                                    ?>
                                                    @if($hFunction->checkCount($listIdDepartment))
                                                        @foreach($listIdDepartment as $department)
                                                            {!! $modelDepartment->name($department)[0].',' !!}
                                                        @endforeach
                                                    @else
                                                        <em class="qc-color-grey">Chưa phân bổ CV</em>
                                                    @endif
                                                @else
                                                    <em class="qc-color-grey">Chưa phân bổ CV</em>
                                                @endif
                                            @else
                                                <em style=" color: red;">Đã nghỉ</em>
                                            @endif
                                        </td>
                                        <td>
                                            @if($checkWorkStatus)
                                                @if($hFunction->checkCount($dataCompanyStaffWork))
                                                    @if($dataCompanyStaffWork->checkExistsActivityStaffWorkSalary())
                                                        <em>Đã có</em>
                                                    @else
                                                        <em class="qc-color-grey">Chư có</em>
                                                    @endif
                                                @else
                                                    <em class="qc-color-grey">Chưa có </em>
                                                @endif
                                            @else
                                                <em style=" color: red;">Đã nghỉ</em>
                                            @endif
                                        </td>
                                        <td>
                                            @if($checkWorkStatus)
                                                @if($hFunction->checkCount($dataCompanyStaffWork))
                                                    @if($dataCompanyStaffWork->checkExistsActivityWork())
                                                        <em>Đang mở</em>
                                                    @else
                                                        <em class="qc-color-grey">Đã tắt</em>
                                                        <br/>
                                                        <a class="qc_ad3d_staff_open_work_act qc-link-red"
                                                           data-href="{!! route('qc.ad3d.system.staff.open_work.get', $dataCompanyStaffWork->workId()) !!}">
                                                            Mở chấm công
                                                        </a>
                                                    @endif
                                                @else
                                                    <em class="qc-color-grey">Chưa phân bổ CV</em>
                                                @endif
                                            @else
                                                <em style=" color: red;">Đã nghỉ</em>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($checkWorkStatus)
                                                @if($staff->checkLoginAdmin())
                                                    <em class="qc-color-grey">Có quyền</em>
                                                @else
                                                    <em class="qc-color-grey">Không có quyền</em>
                                                @endif
                                            @else
                                                <em style=" color: red;">Đã nghỉ</em>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($checkWorkStatus)
                                                @if($staff->checkApplyRule())
                                                    <em class="qc-color-grey">Có</em>
                                                @else
                                                    <em class="qc-color-grey">Không</em>
                                                @endif
                                            @else
                                                <em style=" color: red;">Đã nghỉ</em>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($checkWorkStatus)
                                                <a class="qc_reset_pass qc-link-green" href="#">Reset Mật khẩu</a>
                                            @else
                                                <em style=" color: red;">Đã nghỉ</em>
                                            @endif
                                        </td>
                                    </tr>
                                @else
                                    <tr class="qc_ad3d_list_object @if($n_o%2) info @endif"
                                        data-object="{!! $staffId !!}">
                                        <td class="text-center">
                                            {!! $n_o += 1 !!}
                                        </td>
                                        <td class="text-canter" style="padding: 0;">
                                            <a class="qc-link-green"
                                               href="{!! route('qc.ad3d.system.staff.info.get', $staffId) !!}">
                                                <img style="max-width: 50px;height: 50px;"
                                                     src="{!! $src !!}">
                                            </a>
                                        </td>
                                        <td>
                                            {!! $staff->firstName().' '.$staff->lastName() !!}
                                            @if($checkWorkStatus)
                                                <br/>
                                                <a class="qc_delete qc-link-red" href="#"
                                                   title="Xóa khỏi danh sách NV">
                                                    <i class="qc-font-size-14 glyphicon glyphicon-trash"></i>
                                                </a>
                                            @endif
                                        </td>
                                        <td colspan="8">
                                            <span style="color: red;">Đã nghỉ</span>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            <tr>
                                <td class="text-center" colspan="11">
                                    {!! $hFunction->page($dataStaff) !!}
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
