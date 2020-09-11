<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataCompany
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
?>
@extends('master')
@section('titlePage')
    Đăng nhập
@endsection
@section('qc_js_header')
    {{--<script src="{{ url('public/work/js/work.js')}}"></script>--}}
@endsection
@section('qc_master_body')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <a class="qc-link-white-bold btn btn-primary" onclick="qc_main.page_back_go();">Về trang trước</a>
        </div>
        <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <h3 style="color:red;">HỒ SƠ ỨNG TUYỂN</h3>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <form class="frmWorRecruitmentRegisterAd" name="frmWorRecruitmentRegister" role="form" method="post"
                  enctype="multipart/form-data"
                  action="{!! route('qc.work.recruitment.register.add.post') !!}">
                <div class="row">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        @if (Session::has('notifyAdd'))
                            <div class="form-group form-group-sm text-center qc-color-red">
                                {!! Session::get('notifyAdd') !!}
                                <?php
                                Session::forget('notifyAdd');
                                ?>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-dm-12 col-lg-12"
                             style="padding-top: 5px; border-bottom: 2px solid black;background-color: whitesmoke;">
                            <i class="glyphicon glyphicon-record" style="font-size: 1.5em;"></i>
                            <label style="font-size: 1.5em;color: blue;">THÔNG TIN LÀM VIỆC</label>
                        </div>
                    </div>
                    <div class="row">
                        {{--them bo phan--}}
                        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-hover table-condensed">
                                    <tr>
                                        <th colspan="4" style="padding: 0;">
                                            <select class="form-control" style="color: red;">
                                                <option value="0">Chọn bộ phận ứng tuyển</option>
                                                <option value="1">Bộ phận 1</option>
                                                <option value="2">Bộ phận 2</option>
                                            </select>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>
                                            Tay nghề / Kỹ năng
                                        </th>
                                        <th class="text-center" style="width: 70px;">
                                            Không biết
                                        </th>
                                        <th class="text-center" style="width: 70px;">
                                            Biết
                                        </th>
                                        <th class="text-center" style="width: 70px;">
                                            Giỏi
                                        </th>
                                    </tr>
                                    @for($i=1;$i<10; $i++)
                                        <tr>
                                            <td>
                                                <i class="glyphicon glyphicon-arrow-right"></i>
                                                Tên kỹ năng {!! $i !!}
                                            </td>
                                            <td class="text-center">
                                                <input class="departmentManageRank" type="radio"
                                                       name="chkSkill_{!! $i !!}">
                                            </td>
                                            <td class="text-center">
                                                <input class="departmentStaffRank" type="radio"
                                                       name="chkSkill_{!! $i !!}">
                                            </td>
                                            <td class="text-center">
                                                <input class="departmentStaffRank" type="radio"
                                                       name="chkSkill_{!! $i !!}">
                                            </td>
                                        </tr>
                                    @endfor
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="qc-margin-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-dm-12 col-lg-12"
                             style="padding-top: 5px; border-bottom: 2px solid black;background-color: whitesmoke;">
                            <i class="glyphicon glyphicon-record" style="font-size: 1.5em;"></i>
                            <label style="font-size: 1.5em; color: blue;">MỨC LƯƠNG ĐỀ XUẤT</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label class="qc-color-red">Tổng lương đề xuất</label>
                                <input type="text" class="form-control" name="txtTotalSalary"
                                       placeholder="Tổng lương đề xuất ban đầu" value="0">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-dm-12 col-lg-12"
                             style="padding-top: 5px; border-bottom: 2px solid black;background-color: whitesmoke;">
                            <i class="glyphicon glyphicon-record" style="font-size: 1.5em;"></i>
                            <label style="font-size: 1.5em;color: blue;">THÔNG TIN CÁ NHÂN</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Họ <i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input type="text" class="form-control" name="txtFirstName" placeholder="Nhập họ"
                                       value="">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="form-group form-group-sm qc-padding-none" style="margin: 0;">
                                <label>Tên <i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input type="text" class="form-control" name="txtLastName" placeholder="Nhập Tên"
                                       value="">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Chứng minh thư <i
                                            class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input type="text" class="form-control" name="txtIdentityCard"
                                       placeholder="Số chứng minh nhân dân" value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Giới tính</label>
                                <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                <select class="form-control" name="cbGender">
                                    <option value="">Chọn giới tính</option>
                                    <option value="1">Nam</option>
                                    <option value="0">Nữ</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Ngày sinh</label>
                                <input id="txtBirthday" type="text" class="form-control" name="txtBirthday"
                                       placeholder="Ngày sinh" value="">
                                <script type="text/javascript">
                                    qc_main.setDatepicker('#txtBirthday');
                                </script>

                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Điện thoại<i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                <input type="text" class="form-control" name="txtPhone"
                                       onkeyup="qc_main.showNumberInput(this);" placeholder="Số điện thoại" value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sx-12 col-sm-12 col-md-8 col-lg-8">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Địa chỉ</label>
                                <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                <input type="text" class="form-control" name="txtAddress"
                                       placeholder="Thông tin địa chỉ" value="">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Email</label>
                                <input type="text" class="form-control" name="txtEmail" placeholder="Địa chỉ email"
                                       value="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Ảnh cá nhân</label>
                                <input type="file" name="txtImage" title="Ảnh cá nhân" value="">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Ảnh CMND mặt trước</label>
                                <input type="file" name="txtIdentityCardFront" title="Ảnh CMND mặt trước" value="">
                            </div>
                        </div>
                        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <label>Ảnh CMND mặt sau</label>
                                <input type="file" name="txtIdentityCardBack" title="Ảnh CMND mặt sau" value="">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group form-group-sm" style="padding: 10px;">
                            <span style="padding: 5px;font-size: 1.5em; background-color: red; color: yellow;">HỒ SƠ SAU KHI GỬI SẼ KHÔNG ĐƯỢC SỬA ĐỔI</span>
                        </div>
                    </div>
                </div>
                <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="text-center qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12 ">
                            <div class="form-group form-group-sm" style="margin: 0;">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                <button type="submit" class="qc_save btn btn-sm btn-primary">GỬI</button>
                                <button type="reset" class="qc_reset btn btn-sm btn-default">Nhập lại</button>
                                <button type="button" class="btn btn-sm btn-default" onclick="qc_main.page_back();">
                                    Đóng
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
