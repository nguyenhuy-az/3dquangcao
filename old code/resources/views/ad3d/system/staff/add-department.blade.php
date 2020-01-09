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
$editStatus = false;
if (isset($staffWorkDepartment)) {
    $oldDepartmentId = $staffWorkDepartment->departmentId();
    $oldRankId = $staffWorkDepartment->rankId();
    $oldPermission = $staffWorkDepartment->permission();
    $editStatus = true;
} else {
    $oldDepartmentId = null;
    $oldRankId = null;
}
?>
<div class="qc_ad3d_staff_add_department col-xs-12 col-sm-12 col-dm-12 col-lg-12"
     style="padding: 0; border-left: 3px solid brown;">
    <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4"
         @if($mobileStatus) style="padding:0;" @endif>
        <div class="form-group form-group-sm" style="margin: 0;">
            <label>
                Bộ phận:
                <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
            </label>
            <select class="form-control cbDepartment" name="cbDepartment[]">
                <option value="">Chọn bộ phận</option>
                @if(count($dataDepartment) > 0)
                    @foreach($dataDepartment as $department)
                        <option @if($editStatus && $department->departmentId() == $oldDepartmentId) selected="selected"
                                @endif  value="{!! $department->departmentId() !!}">{!! $department->name() !!}</option>
                    @endforeach
                @endif

            </select>
        </div>
    </div>
    <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4"
         @if($mobileStatus) style="padding: 0 0;" @endif>
        <div class="form-group form-group-sm" style="margin: 0;">
            <label>
                Cấp bậc
                <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
            </label>
            <select class="form-control cbRank" name="cbRank[]">
                <option value="" selected="selected">
                    Chọn Cấp bậc làm việc
                </option>
                @if(count($dataRank) > 0)
                    @foreach($dataRank as $rank)
                        <option @if($editStatus && $rank->rankId() == $oldRankId) selected="selected"
                                @endif value="{!! $rank->rankId() !!}">{!! $rank->name() !!}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="col-sx-12 col-sm-12 col-md-2 col-lg-2"
         @if($mobileStatus) style="padding: 0 0;" @endif>
        <div class="form-group form-group-sm" style="margin: 0;">
            <label>
                Quyền
                <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
            </label>
            <select class="form-control cbPermission" name="cbPermission[]">
                <option @if($editStatus && $oldPermission == 'f') selected="selected" @endif value="f">
                    F - Tất cả
                </option>
                <option @if($editStatus && $oldPermission == 'n') selected="selected" @endif value="n">
                    N - Không có quyền
                </option>
                <option @if($editStatus && $oldPermission == 'r') selected="selected" @endif value="r">
                    R - Xem
                </option>
                <option @if($editStatus && $oldPermission == 'w') selected="selected" @endif value="w">
                    W - Ghi
                </option>
            </select>
        </div>
    </div>
    <div class="text-right col-xs-12 col-sm-12 col-md-2 col-lg-2">
        <a class="qc_delete qc-link-red" data-href="">
            Xóa
        </a>
    </div>
</div>
