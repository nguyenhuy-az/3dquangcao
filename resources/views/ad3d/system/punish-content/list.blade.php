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
$indexHref = route('qc.ad3d.system.punish-content.get');
$dataPunishType = $modelPunishType->getInfo();
?>
@extends('ad3d.system.punish-content.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-12 col-lg-12"
                     style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! $indexHref !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    &nbsp;
                    <label class="qc-font-size-20" style="color: red;">DANGH MỤC PHẠT</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <a class="qc-link-white-bold btn btn-primary form-control"
                   href="{!! route('qc.ad3d.system.punish_content.add.get') !!}">
                    <i class="qc-font-size-16 glyphicon glyphicon-plus"></i>
                    THÊM
                </a>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="qc_ad3d_list_content row"
                 data-href-view="{!! route('qc.ad3d.system.punish-content.view.get') !!}"
                 data-href-edit="{!! route('qc.ad3d.system.punish-content.edit.get') !!}"
                 data-href-del="{!! route('qc.ad3d.system.punish-content.delete') !!}">
                <table class="table table-hover table-bordered">
                    <tr style="background-color: black;color: yellow;">
                        <th>TÊN</th>
                    </tr>
                    @if($hFunction->checkCount($dataPunishContent))
                        <?php
                        $perPage = $dataPunishContent->perPage();
                        $currentPage = $dataPunishContent->currentPage();
                        $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                        ?>
                        @foreach($dataPunishContent as $punishContent)
                            <?php
                            $punishId = $punishContent->punishId();
                            $n_o += 1;
                            ?>
                            <tr class="qc_ad3d_list_object @if($n_o%2) info @endif" data-object="{!! $punishId !!}">
                                <td>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                            <em>{!! $n_o !!}).</em>
                                            <label>{!! $punishContent->name() !!}</label>
                                            <br/>&emsp;
                                            <span style="color: red;">
                                            {!! $hFunction->currencyFormat($punishContent->money()) !!}
                                        </span>
                                            @if ($punishContent->checkApplyStatus())
                                                <span>&nbsp;|&nbsp;</span>
                                                <em style="color: grey;">Đang áp dụng</em>
                                                <span>&nbsp;|&nbsp;</span>
                                                <a class="qc_update_apply_status qc-link-green-bold"
                                                   data-href="{!! route('qc.ad3d.system.punish_content.apply_status.update',"$punishId/0") !!}">
                                                    TẮT
                                                </a>
                                            @else
                                                <span>&nbsp;|&nbsp;</span>
                                                <em style="color: grey;">Đang tắt</em>
                                                <span>&nbsp;|&nbsp;</span>
                                                <a class="qc_update_apply_status qc-link-green-bold"
                                                   data-href="{!! route('qc.ad3d.system.punish_content.apply_status.update',"$punishId/1") !!}">
                                                    MỞ
                                                </a>
                                            @endif
                                            <br/>&emsp;
                                            <span style="color: blue;">
                                            {!! $punishContent->punishCode() !!}
                                        </span>
                                            <span>&nbsp;|&nbsp;</span>
                                            <a class="qc_view qc-link" href="#">
                                                <i class="qc-font-size-14 glyphicon glyphicon-eye-open"></i>
                                            </a>
                                            <span>&nbsp;|&nbsp;</span>
                                            <a class="qc_edit qc-link-green" href="#">
                                                <i class="qc-font-size-14 glyphicon glyphicon-pencil"></i>
                                            </a>
                                            <span>&nbsp;|&nbsp;</span>
                                            <a class="qc_delete qc-link-red" href="#" title="Xóa">
                                                <i class="qc-font-size-14 glyphicon glyphicon-trash"></i>
                                            </a>
                                        </div>
                                        <div class="hidden-xs col-sm-6 col-md-6 col-lg-none">
                                            <b>{!! $punishContent->punishType->name() !!}</b>
                                            <br/>
                                            <em style="color: grey;">
                                                {!! $punishContent->note() !!}
                                            </em>
                                        </div>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                        <tr>
                            <td class="text-center">
                                {!! $hFunction->page($dataPunishContent) !!}
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td class="text-center">
                                <em class="qc-color-red">Không tìm thấy thông tin</em>
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
@endsection
