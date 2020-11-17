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
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-12 col-lg-12"
                     style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! $indexHref !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    &nbsp;
                    <label class="qc-font-size-20">DANGH MỤC PHẠT</label>
                </div>
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="qc_ad3d_list_content row"
                 data-href-view="{!! route('qc.ad3d.system.punish-content.view.get') !!}"
                 data-href-edit="{!! route('qc.ad3d.system.punish-content.edit.get') !!}"
                 data-href-del="{!! route('qc.ad3d.system.punish-content.delete') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr>
                            <td colspan="2" style="padding: 0;">
                                <a class="qc-link-white-bold btn btn-primary form-control"
                                   href="{!! route('qc.ad3d.system.punish_content.add.get') !!}">
                                    <i class="qc-font-size-16 glyphicon glyphicon-plus"></i>
                                    THÊM
                                </a>
                            </td>
                            <td colspan="5"></td>
                        </tr>
                        <tr style="background-color: black;color: yellow;">
                            <th class="text-center" style="width: 20px;">STT</th>
                            <th>MÃ PHẠT</th>
                            <th>TÊN</th>
                            <th style="width: 500px;">MÔ TẢ</th>
                            <th>LOẠI TIỀN PHẠT</th>
                            <th class="text-right">SỐ TIỀN</th>
                            <th class="text-center">ÁP DỤNG PHẠT</th>
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
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2) info @endif" data-object="{!! $punishId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td>
                                        {!! $punishContent->punishCode() !!}
                                    </td>
                                    <td>
                                        <label>{!! $punishContent->name() !!}</label>
                                        <br/>
                                        <a class="qc_view qc-link" href="#">
                                            <i class="qc-font-size-14 glyphicon glyphicon-eye-open"></i>
                                        </a>
                                        &nbsp;|&nbsp;
                                        <a class="qc_edit qc-link-green" href="#">
                                            <i class="qc-font-size-14 glyphicon glyphicon-pencil"></i>
                                        </a>
                                        &nbsp;|&nbsp;
                                        <a class="qc_delete qc-link-red" href="#" title="Xóa">
                                            <i class="qc-font-size-14 glyphicon glyphicon-trash"></i>
                                        </a>
                                    </td>
                                    <td>
                                        {!! $punishContent->note() !!}
                                    </td>
                                    <td>
                                        {!! $punishContent->punishType->name() !!}
                                    </td>
                                    <td class="text-right">
                                        <span style="color: blue;">
                                            {!! $hFunction->currencyFormat($punishContent->money()) !!}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if ($punishContent->checkApplyStatus())
                                            <em style="color: brown;">Đang áp dụng</em>
                                            <span style="color: red;"> | </span>
                                            <a class="qc_update_apply_status qc-link-green-bold"
                                               data-href="{!! route('qc.ad3d.system.punish_content.apply_status.update',"$punishId/0") !!}">
                                                TẮT
                                            </a>
                                        @else
                                            <em style="color: grey;">Đang tắt</em>
                                            <span style="color: red;"> | </span>
                                            <a class="qc_update_apply_status qc-link-green-bold"
                                               data-href="{!! route('qc.ad3d.system.punish_content.apply_status.update',"$punishId/1") !!}">
                                                MỞ
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center" colspan="7">
                                    {!! $hFunction->page($dataPunishContent) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-center" colspan="6">
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
