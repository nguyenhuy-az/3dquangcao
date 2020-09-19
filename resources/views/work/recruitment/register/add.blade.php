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
$companyId = $dataCompany->companyId();
$hrefIndex = route('qc.work.recruitment.register.add.get', "$companyId/$phoneNumber");
if ($hFunction->checkCount($dataDepartmentSelected)) {
    $departmentSelectedId = $dataDepartmentSelected->departmentId();
} else {
    $departmentSelectedId = null;
}
?>
@extends('master')
@section('titlePage')
    Đăng ký tuyển dụng
@endsection
@section('qc_master_body')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <a class="qc-link-white-bold btn btn-primary" onclick="qc_main.page_back_go();">Về trang trước</a>
        </div>
        <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <h3 style="color:red;">TẠO HỒ SƠ ỨNG TUYỂN</h3>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="padding-bottom: 0; margin: 0;">
            <em>Mọi thắc mắc về Hồ sơ liên hệ</em>
            <span style="color: red; font-size: 1.5em;">0939.88.99.07</span>
            <em>Mr.Huy</em>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <form class="frmWorRecruitmentRegisterAdd" name="frmWorRecruitmentRegisterAdd" role="form" method="post"
                  enctype="multipart/form-data"
                  action="{!! route('qc.work.recruitment.register.add.post',$companyId) !!}">
                <div class="row">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        @if (Session::has('notifyRecruitmentAdd'))
                            <div class="form-group form-group-sm text-center qc-color-red">
                                {!! Session::get('notifyRecruitmentAdd') !!}
                                <?php
                                Session::forget('notifyRecruitmentAdd');
                                ?>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-dm-12 col-lg-12"
                             style="padding-top: 5px; border-bottom: 2px solid black; margin-bottom: 5px;">
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
                                            <select class="cbDepartment form-control" name="cbDepartment"
                                                    style="color: red;" data-href="{!! $hrefIndex !!}">
                                                @if($hFunction->checkCount($dataDepartment))
                                                    <option value="0">
                                                        CHỌN BỘ PHẬN ỨNG TUYỂN
                                                    </option>
                                                    @foreach($dataDepartment as $department)
                                                        <option @if($departmentSelectedId == $department->departmentId()) selected="selected"
                                                                @endif value="{!! $department->departmentId() !!}">
                                                            {!! $department->name() !!}
                                                        </option>
                                                    @endforeach
                                                @else
                                                    <option value="0">
                                                        KHÔNG CÓ THÔNG TIN TUYỂN DỤNG
                                                    </option>
                                                @endif
                                            </select>
                                        </th>
                                    </tr>
                                    @if($hFunction->checkCount($dataDepartmentWork))
                                        <tr>
                                            <th>
                                                TAY NGHỀ / KỸ NĂNG
                                            </th>
                                            <th class="text-center" style="width: 70px;">
                                                KHÔNG BIẾT
                                            </th>
                                            <th class="text-center" style="width: 70px;">
                                                BIẾT
                                            </th>
                                            <th class="text-center" style="width: 70px;">
                                                GIỎI
                                            </th>
                                        </tr>
                                        @foreach($dataDepartmentWork as $departmentWork)
                                            <?php
                                            $workId = $departmentWork->workId();
                                            ?>
                                            <tr>
                                                <td>
                                                    <i class="glyphicon glyphicon-arrow-right"></i>
                                                    {!! $departmentWork->name() !!}
                                                    <input type="hidden" name="txtDepartmentWork[]"
                                                           value="{!! $workId !!}">
                                                </td>
                                                <td class="text-center">
                                                    <input class="qcDepartmentWork" type="radio" value="1"
                                                           name="chkSkill_{!! $workId !!}">
                                                </td>
                                                <td class="text-center">
                                                    <input class="qcDepartmentWork" type="radio" value="2"
                                                           name="chkSkill_{!! $workId !!}">
                                                </td>
                                                <td class="text-center">
                                                    <input class="qcDepartmentWork" type="radio" value="3"
                                                           name="chkSkill_{!! $workId !!}">
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @if($hFunction->checkCount($dataDepartmentSelected))
                    <div class="qc-margin-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-dm-12 col-lg-12"
                                 style="border-bottom: 2px solid black;margin-bottom: 10px;">
                                <i class="glyphicon glyphicon-record" style="font-size: 1.5em;"></i>
                                <label style="font-size: 1.5em; color: blue;">MỨC LƯƠNG ĐỀ XUẤT</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group form-group-sm" style="margin: 0;">
                                    <label class="qc-color-red">Tổng lương đề xuất</label>
                                    <input type="text" class="form-control" name="txtTotalSalary"
                                           onkeyup="qc_main.showFormatCurrency(this);"
                                           placeholder="Tổng lương đề xuất ban đầu" value="">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-dm-12 col-lg-12"
                                 style="margin-bottom: 10px; border-bottom: 2px solid black;">
                                <i class="glyphicon glyphicon-record" style="font-size: 1.5em;"></i>
                                <label style="font-size: 1.5em;color: blue;">THÔNG TIN CÁ NHÂN</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="form-group form-group-sm">
                                    <label>Họ <i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                    <input type="text" class="form-control" name="txtFirstName" placeholder="Nhập họ"
                                           value="">
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="form-group form-group-sm">
                                    <label>Tên <i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                    <input type="text" class="form-control" name="txtLastName" placeholder="Nhập Tên"
                                           value="">
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="form-group form-group-sm">
                                    <label>Chứng minh thư </label>
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    <input type="text" class="form-control" name="txtIdentityCard"
                                           placeholder="Số chứng minh nhân dân" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="form-group form-group-sm">
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
                                <div class="form-group form-group-sm">
                                    <label>Ngày sinh</label>
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    <input id="txtBirthday" type="text" class="form-control" name="txtBirthday"
                                           placeholder="Ngày sinh" value="">
                                    <script type="text/javascript">
                                        qc_main.setDatepicker('#txtBirthday');
                                    </script>

                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="form-group form-group-sm">
                                    <label>Điện thoại<i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                                    <input type="text" class="form-control" name="txtPhone"
                                           onkeyup="qc_main.showNumberInput(this);" placeholder="Số điện thoại"
                                           value="{!! $phoneNumber !!}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sx-12 col-sm-12 col-md-8 col-lg-8">
                                <div class="form-group form-group-sm">
                                    <label>Địa chỉ</label>
                                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    <input type="text" class="form-control" name="txtAddress"
                                           placeholder="Thông tin địa chỉ" value="">
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="form-group form-group-sm">
                                    <label>Email</label>
                                    <input type="text" class="form-control" name="txtEmail" placeholder="Địa chỉ email"
                                           value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="form-group form-group-sm">
                                    <label>Ảnh cá nhân</label>
                                    <input type="file" name="txtImage" title="Ảnh cá nhân" value="">
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="form-group form-group-sm">
                                    <label>Ảnh CMND mặt trước</label>
                                    <input type="file" name="txtIdentityCardFront" title="Ảnh CMND mặt trước" value="">
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="form-group form-group-sm">
                                    <label>Ảnh CMND mặt sau</label>
                                    <input type="file" name="txtIdentityCardBack" title="Ảnh CMND mặt sau" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group form-group-sm">
                                    <label>Mô tả công việc từng làm (Nếu có):</label>
                                    <input type="text" class="form-control" name="txtIntroduce"
                                           placeholder="Nhập nội dung tự giới thiệu" value="">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="form-group form-group-sm" style="padding: 10px;">
                                <span style="padding: 5px;font-size: 1.5em; background-color: red; color: yellow;">HỒ SƠ SAU KHI GỬI SẼ KHÔNG ĐƯỢC SỬA ĐỔI</span>
                                <br/><br/>
                                <span style="color: blue;">Hồ sơ sẽ được duyệt trong vòng 3 ngày</span>
                            </div>
                        </div>
                    </div>
                    <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12 ">
                                <div class="form-group form-group-sm" style="margin: 0;">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <button type="button" class="qc_save btn btn-sm btn-primary">NỘP HỒ SƠ</button>
                                    <button type="reset" class="qc_reset btn btn-sm btn-default">Nhập lại</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>
@endsection
@section('qc_js_footer')
    <script src="{{ url('public/work/recruitment/register/js/index.js')}}"></script>
@endsection