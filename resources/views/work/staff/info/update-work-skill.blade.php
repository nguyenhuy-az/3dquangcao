<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$hFunction = new Hfunction();
$departmentWorkId = $dataDepartmentWork->workId();
$getDefaultNotLevel = $modelWorkSkill->getDefaultNotLevel();
$getDefaultMediumLevel = $modelWorkSkill->getDefaultMediumLevel();
$getDefaultGoodLevel = $modelWorkSkill->getDefaultGoodLevel();
if ($hFunction->checkCount($dataWorkSkill)) {
    $currentLevel = $dataWorkSkill->level();
} else {
    $currentLevel = $getDefaultMediumLevel; # mac dinh la biet
}
?>
@extends('components.container.container-6')
@section('qc_container_content')
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <h3 style="color: red;">CẬP NHẬT KỸ NĂNG</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <form class="frmWorkSkillUpdate" role="form" method="post"
                      action="{!! route('qc.work.staff.skill.update.post',"$companyStaffWorkId/$departmentWorkId") !!}">
                    <div class="form-group form-group-sm">
                        <div class="qc_notify_content form-group text-center qc-color-red"></div>
                    </div>
                    <div class="form-group form-group-sm">
                        <div class="form-group form-group-sm qc-padding-none">
                            <input type="text" class="form-control" readonly name="txtWorkSkill"
                                   value="{!! $dataDepartmentWork->name() !!}">
                        </div>
                    </div>
                    <div class="form-group form-group-sm">
                        <div class="radio">
                            <label>
                                <input type="radio" name="chkSkillLevel" value="{!! $getDefaultNotLevel !!}"
                                       @if($currentLevel == $getDefaultNotLevel) checked="checked" @endif>
                                {!! $hFunction->mp_strtoupper( $modelWorkSkill->levelLabel($getDefaultNotLevel)) !!}
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="chkSkillLevel" value="{!! $getDefaultMediumLevel !!}"
                                       @if($currentLevel == $getDefaultMediumLevel) checked="checked" @endif>
                                {!! $hFunction->mp_strtoupper($modelWorkSkill->levelLabel($getDefaultMediumLevel)) !!}
                            </label>

                        </div>
                        <div class="radio">
                            <label >
                                <input type="radio" name="chkSkillLevel" value="{!! $getDefaultGoodLevel !!}"
                                       @if($currentLevel == $getDefaultGoodLevel) checked="checked" @endif>
                                {!! $hFunction->mp_strtoupper($modelWorkSkill->levelLabel($getDefaultGoodLevel)) !!}
                            </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="text-center qc-padding-bot-20  qc-border-none col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <button type="button" class="qc_save btn btn-sm btn-primary">CẬP NHẬT</button>
                            <button type="button" class="qc_container_close btn btn-sm btn-default">ĐÓNG</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
