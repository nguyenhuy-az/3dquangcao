<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataStaff
 */

$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$staffId = $dataStaff->staffId();
$firstName = $dataStaff->firstName();
$lastName = $dataStaff->lastName();
$identityCard = $dataStaff->identityCard();
$birthday = $dataStaff->birthday();
$gender = $dataStaff->gender();
$image = $dataStaff->image();
$identityCardFront = $dataStaff->identityCardFront();
$identityCardBack = $dataStaff->identityCardBack();
$phone = $dataStaff->phone();
$address = $dataStaff->address();
$email = $dataStaff->email();
$bankAccount = $dataStaff->bankAccount();
$bankName = $dataStaff->bankName();
$dateAdd = $dataStaff->createdAt();
# lay thong tin lam viec tai cty
$dataCompanyStaffWork = $dataStaff->companyStaffWorkInfoActivity();
$companyStaffWorkStatus = ($hFunction->checkCount($dataCompanyStaffWork)) ? true : false; # co dang lam cho 1 cty hay ko
if ($companyStaffWorkStatus) {
    $manageRankId = $modelRank->manageRankId();
    $staffRankId = $modelRank->staffRankId();
    # ap dung chuyen doi tu phien ban cua phan tích moi nv chi lam tai 1 cty
    # thiet ke moi
    $companyId = $dataCompanyStaffWork->companyId();
    $companyName = $dataCompanyStaffWork->company->name();
    $level = $dataCompanyStaffWork->level();
    $beginDate = $dataCompanyStaffWork->beginDate();
    $dataStaffWorkDepartment = $dataCompanyStaffWork->staffWorkDepartmentInfoActivity();
    $dataStaffWorkSalary = $dataCompanyStaffWork->staffWorkSalaryActivity();
} else {
    # thiet ke cu
    $companyId = null;
    $companyName = null;
    $level = null;
    $beginDate = $hFunction->carbonNow();
    $dataStaffWorkDepartment = null;
    $dataStaffWorkSalary = null;
    $departmentStaff = null;
}
$beginDate = date('Y-m-d', strtotime($beginDate));
# hinh thuc lam viec
$dataStaffWorkMethod = $dataStaff->infoActivityStaffWorkMethod();
if ($hFunction->checkCount($dataStaffWorkMethod)) {
    $workMethod = $dataStaffWorkMethod->method();
    $applyRule = $dataStaffWorkMethod->applyRule();
    $workMethodLabel = $dataStaffWorkMethod->methodLabel($workMethod);
    $applyRuleLabel = $dataStaffWorkMethod->applyRuleLabel($applyRule);
} else {
    $workMethod = 1; # mac dinh
    $applyRule = 1; # mac dinh
    $workMethodLabel = 'Chính thức';
    $applyRuleLabel = 'Áp dụng';
}
?>
<form id="frmStaffInfoWorkEdit" class="frmStaffInfoWorkEdit" name="frmStaffInfoWorkEdit" role="form" method="post"
      action="{!! route('qc.ad3d.system.staff_info.work.edit.post', $staffId) !!}">
    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="frm_work_notify form-group form-group-sm qc-color-red"></div>
    </div>

    {{--cty --}}
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-8 col-lg-8">
            <div class="form-group form-group-sm" style="margin: 0;">
                <label>
                    Công ty
                    <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                </label>
                <select class="form-control" name="cbCompany">
                    <option value="{!! $companyId !!}">{!! $companyName !!}</option>
                </select>
            </div>
        </div>
        @if($dataStaff->level() > 0)
            <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                <div class="form-group form-group-sm" style="margin: 0;">
                    <label>
                        Cấp bậc truy cập
                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i></label>
                    <em style="color: brown;">Cấp < 4 sẽ truy cập vào trang quản lý</em>
                    <select class="form-control" name="cbLevel">
                        <option value="1"
                                @if($dataStaff->level() == 1) selected="selected" @endif>
                            1
                        </option>
                        <option value="2"
                                @if($dataStaff->level() == 2) selected="selected" @endif>
                            2
                        </option>
                        <option value="3"
                                @if($dataStaff->level() == 3) selected="selected" @endif>
                            3
                        </option>
                        <option value="4"
                                @if($dataStaff->level() == 4) selected="selected" @endif>
                            4
                        </option>
                        <option value="5"
                                @if($dataStaff->level() == 5) selected="selected" @endif>
                            5
                        </option>
                    </select>
                </div>
            </div>
        @else
            <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
                <div class="form-group form-group-sm" style="margin: 0;">
                    <input type="hidden" name="cbLevel"
                           value="{!! $dataStaff->level() !!}">
                </div>
            </div>
        @endif
        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
            <div class="form-group form-group-sm" style="margin: 0;">
                <label>Ngày vào</label>
                <input id="txtDateWork" type="text" class="form-control"
                       name="txtDateWork"
                       value="{!! $beginDate !!}">
                <script type="text/javascript">
                    qc_main.setDatepicker('#txtDateWork');
                </script>
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
            <div class="form-group form-group-sm" style="margin: 0;">
                <label>
                    Hình thức làm<i
                            class="qc-color-red glyphicon glyphicon-star-empty"></i>
                </label>
                <select class="form-control" name="cbWorkMethod">
                    <option value="1" @if($workMethod == 1) selected="selected" @endif>
                        Chính thức
                    </option>
                    <option value="2" @if($workMethod == 2) selected="selected" @endif>
                        Không chính thức
                    </option>
                </select>
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4">
            <div class="form-group form-group-sm" style="margin: 0;">
                <label>
                    Áp dụng nội quy<i
                            class="qc-color-red glyphicon glyphicon-star-empty"></i>
                </label>
                <select class="form-control" name="cbApplyRule">
                    <option value="1" @if($applyRule == 1) selected="selected" @endif>
                        Áp dụng
                    </option>
                    <option value="2" @if($applyRule == 2) selected="selected" @endif>
                        Không áp dụng (báo giờ làm)
                    </option>
                </select>
            </div>
        </div>
    </div>
    {{--them bo phan--}}
    <div class="table-responsive">
        <table class="table table-hover table-condensed">
            <tr>
                <th colspan="3">
                    <label style="background-color: red; color: yellow; padding: 3px;">LƯU Ý</label>
                    Trong <span style="color: red;">1 bộ phận</span> 1 NV chỉ đảm nhiệm <span style="color: red;">1 vị trí</span>.
                    Cấp <span style="color: red;">quản lý</span> làm được <span style="color: red;">Tất cả CV</span> của cấp  <span style="color: red;">Nhân viên</span>
                </th>
            </tr>
            <tr>
                <th>
                    BỘ PHẬN
                </th>
                <th class="text-center">
                    CẤP QUẢN LÝ
                </th>
                <th class="text-center">
                    CẤP NHÂN VIÊN
                </th>
            </tr>
            @if($hFunction->checkCount($dataDepartment))
                @foreach($dataDepartment as $department)
                    <?php
                    $departmentId = $department->departmentId();
                    ?>
                    @if($companyStaffWorkStatus)
                        <tr>
                            <td>
                                <i class="glyphicon glyphicon-arrow-right"></i>
                                {!! $department->name() !!}
                            </td>
                            <td class="text-center">
                                <input class="departmentManageRank" type="checkbox" name="chkDepartmentManageRank_{!! $departmentId !!}"
                                       @if($dataCompanyStaffWork->checkExistActivityWorkDepartmentAndRank($departmentId, $manageRankId)) checked="checked" @endif>
                            </td>
                            <td class="text-center">
                                <input class="departmentStaffRank" type="checkbox" name="chkDepartmentStaffRank_{!! $departmentId !!}"
                                       @if($dataCompanyStaffWork->checkExistActivityWorkDepartmentAndRank($departmentId, $staffRankId)) checked="checked" @endif>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td>
                                <i class="glyphicon glyphicon-arrow-right"></i>
                                {!! $department->name() !!}
                            </td>
                            <td class="text-center">
                                <input class="departmentManageRank" type="checkbox" name="chkDepartmentManageRank_{!! $departmentId !!}">
                            </td>
                            <td class="text-center">
                                <input class="departmentStaffRank" type="checkbox" name="chkDepartmentStaffRank_{!! $departmentId !!}">
                            </td>
                        </tr>
                    @endif
                @endforeach
            @endif
        </table>
    </div>

    <div class="row qc-padding-top-10 qc-padding-bot-10">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12 ">
            <div class="form-group form-group-sm" style="margin: 0;">
                <label class="radio-inline" style="color: red;">
                    <input type="radio" checked="checked" name="salaryStatus" value="1">
                    Theo bảng lương cũ khi thay đổi công ty
                </label>
            </div>
        </div>
        <div class="text-right  col-sx-12 col-sm-12 col-md-12 col-lg-12 ">
            <div class="form-group form-group-sm" style="margin: 0;">
                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                <button type="button" class="qc_save btn btn-sm btn-primary">
                    LƯU THAY ĐỔI
                </button>
                <button type="reset" class="btn btn-sm btn-default">Nhập lại</button>
                <button type="button" class="btn btn-sm btn-default"
                        onclick="qc_main.window_reload();">
                    Đóng
                </button>
            </div>
        </div>
    </div>
</form>
