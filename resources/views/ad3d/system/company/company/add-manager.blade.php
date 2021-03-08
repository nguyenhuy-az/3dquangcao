<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$companyId = $dataCompany->companyId();

?>
@extends('ad3d.system.company.company.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <h3 style="color:red;">CẬP NHẬT NGƯỜI QUẢN LÝ</h3>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <span style="background-color: red; color: yellow; padding: 5px;">
                Mỗi chi nhánh chỉ chỉ có 1 người lãnh đạo cao nhất
            </span>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <form class="frmAdd" name="frmAdd" role="form" method="post"
                  action="{!! route('qc.ad3d.system.company.add.post') !!}">
                @if (Session::has('notifyAdd'))
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group qc-font-size-16" style="background-color: red; color: yellow;">
                                {!! Session::get('notifyAdd') !!}
                                <?php
                                Session::forget('notifyAdd');
                                ?>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-top: 10px;">
                        <div class="form-group">
                            <a class="qc-font-size-16" href="{!! route('qc.ad3d.system.company.update_manager.get',"$companyId/selectNew") !!}">
                                @if($selectObject == 'selectNew')
                                    <i class="qc-font-size-16 glyphicon glyphicon-check"  style="color: blue;"></i>
                                @else
                                    <i class="qc-font-size-16 glyphicon glyphicon-unchecked"  style="color: grey;" ></i>
                                @endif
                                Thêm mới
                            </a>
                            <br/>
                            <a class="qc-font-size-16" href="{!! route('qc.ad3d.system.company.update_manager.get',"$companyId/selectSystem") !!}">
                                @if($selectObject == 'selectSystem')
                                    <i class="qc-font-size-16 glyphicon glyphicon-check"  style="color: blue;"></i>
                                @else
                                    <i class="qc-font-size-16 glyphicon glyphicon-unchecked"  style="color: grey;" ></i>
                                @endif
                                Chọn nhân viên của công ty
                            </a>
                        </div>
                    </div>
                </div>
                @if($selectObject == 'selectSystem')
                    <div id="qc_container_select_system" class="row">
                        {{--thông tin người quản lý--}}
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="border-left: 3px solid grey;">
                            <div class="form-group form-group-sm">
                                @if($hFunction->checkCount($dataCompanyStaffWork))
                                    @foreach($dataCompanyStaffWork as $companyStaffWork)
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="radSelectStaff"
                                                       onclick="qc_main.show('#qc_container_select_system');qc_main.hide('#qc_container_add_new');"
                                                       value="select_system">
                                                Từ Danh sách nhân viên
                                            </label>
                                        </div>
                                    @endforeach
                                @else
                                    <h4 style="color: blue;">
                                        Không có nhân viên
                                    </h4>
                                @endif
                            </div>
                        </div>
                    </div>

                @else
                    <div id="qc_container_add_new" class="row">
                        {{--thông tin người quản lý--}}
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="border-left: 3px solid grey;">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                    <div class="form-group form-group-sm">
                                        <h4>
                                            THÔNG TIN NGƯỜI MỚI
                                        </h4>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-group form-group-sm">
                                        <label>Họ :</label>
                                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                        <input type="text" class="form-control" name="txtFirstName"
                                               placeholder="Nhập họ"
                                               value="">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-group form-group-sm qc-padding-none">
                                        <label>Tên :</label>
                                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                        <input type="text" class="form-control" name="txtLastName"
                                               placeholder="Nhập Tên"
                                               value="">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-group form-group-sm">
                                        <label>Chứng minh thư :</label>
                                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                        <input type="text" class="form-control" name="txtIdentityCard"
                                               placeholder="Số chứng minh nhân dân" value="">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-group form-group-sm">
                                        <label>Giới tính :</label>
                                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                        <select class="form-control" name="cbGender">
                                            <option value="">Chọn giới tính</option>
                                            <option value="{!! $modelStaff->getDefaultGenderMale() !!}">Nam
                                            </option>
                                            <option value="{!! $modelStaff->getDefaultGenderFeMale() !!}">Nữ
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group form-group-sm" style="margin: 0;">
                                        <label>Điện thoại :</label>
                                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                        <input type="text" class="form-control" name="txtStaffPhone"
                                               onkeyup="qc_main.showNumberInput(this);"
                                               placeholder="Số điện thoại"
                                               value="">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group form-group-sm">
                                        <label>Địa chỉ :</label>
                                        <input type="text" class="form-control" name="txtStaffAddress"
                                               placeholder="Thông tin địa chỉ" value="">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group form-group-sm">
                                        <label>Email :</label>
                                        <input type="text" class="form-control" name="txtStaffEmail"
                                               placeholder="Địa chỉ email"
                                               value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif


                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group form-group-sm">
                            <span style="color: red;">
                                NGƯỜI ĐƯỢC CHỌN SẼ ĐƯỢC THÊM VÀO BỘ PHẬN QUẢN LÝ CẤP CAO NHẤT
                            </span>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group form-group-sm">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <button type="button" class="qc_save btn btn-sm btn-primary">THÊM</button>
                            <button type="reset" class="btn btn-sm btn-default">NHẬP LẠI</button>
                            <a href="{!! route('qc.ad3d.system.company.get') !!}">
                                <button type="button" class="btn btn-sm btn-default">ĐÓNG</button>
                            </a>
                        </div>

                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
