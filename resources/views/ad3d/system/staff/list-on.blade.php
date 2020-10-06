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
                            <a class="btn btn-primary qc-link-white-bold "
                               href="{!! route('qc.ad3d.system.staff.add.get') !!}">
                                <i class="qc-font-size-14 glyphicon glyphicon-plus"></i>
                                <span class="qc-font-size-14">THÊM</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="qc_ad3d_list_content row"
                 data-href-view="{!! route('qc.ad3d.system.staff.view.get') !!}"
                 data-href-reset="{!! route('qc.ad3d.system.staff.reset_pass') !!}"
                 data-href-del="{!! route('qc.ad3d.system.staff.delete') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: black;color: yellow;">
                            <th class="text-center" style="width: 20px;">STT</th>
                            <th>TÊN</th>
                            <th>
                                CÔNG TY ĐANG LÀM
                            </th>
                            <th>BỘ PHẬN</th>
                            <th>
                                BẢNG LƯƠNG
                                <br/> (cơ bản)
                            </th>
                            <th>
                                CHẤM CÔNG
                            </th>
                            <th>
                                QUYỀN ADMIN
                            </th>
                            <th class="text-center">
                                ÁP DỤNG NỘI QUY
                            </th>
                            <th class="text-center">
                                TT ĐĂNG NHẬP
                            </th>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td style="padding: 0 !important;">
                                <select class="cbCompanyFilter form-control" name="cbCompanyFilter"
                                        data-action-status="{!! $actionStatus !!}"
                                        data-href="{!! route('qc.ad3d.system.staff.get') !!}">
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
                        @if($hFunction->checkCount($dataCompanyStaffWork))
                            <?php
                            $perPage = $dataCompanyStaffWork->perPage();
                            $currentPage = $dataCompanyStaffWork->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataCompanyStaffWork as $companyStaffWork)
                                <?php
                                # thong tin nhan vien
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
                                    <td style="padding: 0;">
                                        <div class="media" style="margin: 3px;">
                                            <a class="pull-left"
                                               href="{!! route('qc.ad3d.system.staff.info.get', $staffId) !!}">
                                                <img class="media-object"
                                                     style="max-width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                                     src="{!! $src !!}">
                                            </a>

                                            <div class="media-body">
                                                <h5 class="media-heading">{!! $staff->fullName() !!}</h5>

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
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {!! $companyStaffWork->company->nameCode() !!}
                                    </td>
                                    <td>
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
                                    </td>
                                    <td>
                                        @if($companyStaffWork->checkExistsActivityStaffWorkSalary())
                                            <em>Đã có</em>
                                        @else
                                            <em class="qc-color-grey">Chư có</em>
                                        @endif
                                    </td>
                                    <td>
                                        @if($hFunction->checkCount($dataLastWork))
                                            @if($dataLastWork->checkActivity())
                                                <em>Đang mở</em>
                                            @else
                                                <em style="color: red;">Đã tắt</em>
                                                <br/>
                                                <a class="qc_ad3d_staff_open_work_act qc-link-red"
                                                   data-href="{!! route('qc.ad3d.system.staff.open_work.get', $companyStaffWork->workId()) !!}">
                                                    Mở chấm công
                                                </a>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if($staff->checkLoginAdmin())
                                            <em class="qc-color-grey">Có quyền</em>
                                        @else
                                            <em class="qc-color-grey">Không có quyền</em>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($staff->checkApplyRule())
                                            <em class="qc-color-grey">Có</em>
                                        @else
                                            <em class="qc-color-grey">Không</em>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a class="qc_reset_pass qc-link-green" href="#">Reset Mật khẩu</a>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center" colspan="10">
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
