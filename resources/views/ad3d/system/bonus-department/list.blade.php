<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:32 PM
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$dataStaffLogin = $modelStaff->loginStaffInfo();
$indexHref = route('qc.ad3d.system.bonus_department.get');
$manageRankId = $modelRank->manageRankId();
$staffRankId = $modelRank->staffRankId();
?>
@extends('ad3d.system.bonus-department.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="margin-bottom: 10px; padding-top : 10px;padding-bottom: 10px;">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-12 col-lg-12"
                     style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! $indexHref !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    &nbsp;
                    <label class="qc-font-size-20">THƯỞNG/PHẠT THEO BỘ PHẬN</label>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-left: 0;padding-right: 0;">
                    <span style="color: deeppink;">Thưởng và Phạt theo % trên tổng giá trị đơn hàng</span>
                </div>
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="qc_ad3d_list_content row">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: black;color: yellow;">
                            <th class="text-center" style="width: 20px;">STT</th>
                            <th>BỘ PHẬN</th>
                            <th class="text-center">
                                CẤP QUẢN LÝ
                                <br/>
                                %
                            </th>
                            <th class="text-center">
                                CẤP NHÂN VIÊN <br/>
                                %
                            </th>
                            <th class="text-center">
                                TỔNG THƯỞNG /PHẠT <br/>
                                %
                            </th>
                        </tr>
                        @if($hFunction->checkCount($dataDepartment))
                            <?php
                            $perPage = $dataDepartment->perPage();
                            $currentPage = $dataDepartment->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            $totalBonus = 0;
                            ?>
                            @foreach($dataDepartment as $department)
                                <?php
                                $departmentId = $department->departmentId();
                                # thong tin thuong cua cap quan ly
                                $bonusManageRank = $department->bonusInfoActivityManageRank();
                                if ($hFunction->checkCount($bonusManageRank)) {
                                    $valueBonusManageRank = $bonusManageRank->percent();
                                } else {
                                    $valueBonusManageRank = 0;
                                }
                                # thong tin thuong cua cap nhan vien
                                $bonusStaffRank = $department->bonusInfoActivityStaffRank();
                                if ($hFunction->checkCount($bonusStaffRank)) {
                                    $valueBonusStaffRank = $bonusStaffRank->percent();
                                } else {
                                    $valueBonusStaffRank = 0;
                                }

                                $totalBonus = $totalBonus + $valueBonusManageRank + $valueBonusStaffRank;
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2) info @endif"
                                    data-object="{!! $departmentId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td>
                                        {!! $department->name() !!}
                                    </td>
                                    <td class="text-center">
                                        <b style="color: blue;">{!! $valueBonusManageRank !!}</b>
                                        <br>
                                        <a class="qc_add qc-link-green"
                                           data-href="{!! route('qc.ad3d.system.bonus_department.add.get',"$departmentId/$manageRankId") !!}"
                                           title="Cập nhật thưởng">
                                            <i class="qc-font-size-14 glyphicon glyphicon-pencil"></i>
                                        </a>
                                        &nbsp;|&nbsp;
                                        <a class="qc_delete qc-link-red" href="#" title="Xóa">
                                            <i class="qc-font-size-14 glyphicon glyphicon-trash"></i>
                                        </a>
                                        <br/>
                                        @if ($hFunction->checkCount($bonusManageRank))
                                            <?php
                                            $manageBonusId = $bonusManageRank->bonusId();
                                            ?>
                                            @if ($bonusManageRank->checkApplyBonus())
                                                <em style="color: brown;">Đang mở thưởng |</em>
                                                <a class="qc_update_apply_bonus qc-link-green-bold" data-href="{!! route('qc.ad3d.system.bonus_department.apply_bonus.update',"$manageBonusId/0") !!}">
                                                    TẮT
                                                </a>
                                            @else
                                                <em style="color: grey;">Đang tắt thưởng |</em>
                                                <a class="qc_update_apply_bonus qc-link-green-bold" data-href="{!! route('qc.ad3d.system.bonus_department.apply_bonus.update',"$manageBonusId/1") !!}">
                                                    MỞ
                                                </a>
                                            @endif
                                            <br/>
                                            @if ($bonusManageRank->checkApplyMinus())
                                                <em style="color: grey;">Đang mở phạt |</em>
                                                <a class="qc_update_apply_minus qc-link-green-bold" data-href="{!! route('qc.ad3d.system.bonus_department.apply_minus.update',"$manageBonusId/0") !!}">
                                                    TẮT
                                                </a>
                                            @else
                                                <em style="color: grey;">Đang tắt phạt |</em>
                                                <a class="qc_update_apply_minus qc-link-green-bold" data-href="{!! route('qc.ad3d.system.bonus_department.apply_minus.update',"$manageBonusId/1") !!}">
                                                    MỞ
                                                </a>
                                            @endif
                                        @else
                                            <em>Chưa áp dụng thưởng - phạt</em>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <b style="color: blue;">{!! $valueBonusStaffRank !!}</b>
                                        <br>
                                        <a class="qc_add qc-link-green"
                                           data-href="{!! route('qc.ad3d.system.bonus_department.add.get',"$departmentId/$staffRankId") !!}"
                                           title="Cập nhật thưởng">
                                            <i class="qc-font-size-14 glyphicon glyphicon-pencil"></i>
                                        </a>
                                        &nbsp;|&nbsp;
                                        <a class="qc_delete qc-link-red" href="#" title="Xóa">
                                            <i class="qc-font-size-14 glyphicon glyphicon-trash"></i>
                                        </a>
                                        <br/>
                                        @if ($hFunction->checkCount($bonusStaffRank))
                                            <?php
                                                $staffBonusId = $bonusStaffRank->bonusId();
                                            ?>
                                            @if ($bonusStaffRank->checkApplyBonus())
                                                <em style="color: brown;">Đang mở thưởng |</em>
                                                <a class="qc_update_apply_bonus qc-link-green-bold" data-href="{!! route('qc.ad3d.system.bonus_department.apply_bonus.update',"$staffBonusId/0") !!}">
                                                    TẮT
                                                </a>
                                            @else
                                                <em style="color: grey;">Đang tắt thưởng |</em>
                                                <a class="qc_update_apply_bonus qc-link-green-bold" data-href="{!! route('qc.ad3d.system.bonus_department.apply_bonus.update',"$staffBonusId/1") !!}">
                                                    MỞ
                                                </a>
                                            @endif
                                            <br/>
                                            @if ($bonusStaffRank->checkApplyMinus())
                                                <em style="color: grey;">Đang mở phạt |</em>
                                                <a class="qc_update_apply_minus qc-link-green-bold" data-href="{!! route('qc.ad3d.system.bonus_department.apply_minus.update',"$staffBonusId/0") !!}">
                                                    TẮT
                                                </a>
                                            @else
                                                <em style="color: grey;">Đang tắt phạt |</em>
                                                <a class="qc_update_apply_minus qc-link-green-bold" data-href="{!! route('qc.ad3d.system.bonus_department.apply_minus.update',"$staffBonusId/1") !!}">
                                                    MỞ
                                                </a>
                                            @endif
                                        @else
                                            <em>Chưa áp dụng thưởng - phạt</em>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {!! $valueBonusManageRank + $valueBonusStaffRank !!}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-right" colspan="4" style="color: red">
                                    <h4>Tổng thưởng trên 1 đơn hàng</h4>
                                </td>
                                <td class="text-center">
                                    <b style="color: red;">
                                        <h4>{!! $totalBonus !!}</h4>
                                    </b>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center" colspan="5">
                                    {!! $hFunction->page($dataDepartment) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-center" colspan="5">
                                    <em class="qc-color-red">Không tìm thấy thông tin</em>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
