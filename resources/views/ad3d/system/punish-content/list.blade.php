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

$dataPunishType = $modelPunishType->getInfo();
?>
@extends('ad3d.system.punish-content.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="margin-bottom: 10px; padding-top : 10px;padding-bottom: 10px; border-bottom: 2px dashed black;">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! route('qc.ad3d.system.punish-content.get') !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    &nbsp;
                    <i class="qc-font-size-20 glyphicon glyphicon-list-alt"></i>
                    <label class="qc-font-size-20">NỘI DUNG PHẠT</label>
                </div>
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 2px 0 2px 0; ">
                    <form name="" action="">
                        <div class="row">
                            <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <select class="cbPunishTypeFilter" name="cbPunishTypeFilter"
                                        style="margin-top: 5px; height: 25px;"
                                        data-href="{!! route('qc.ad3d.system.punish-content.get') !!}">
                                    <option value="0">Tất cả</option>
                                    @if(count($dataPunishType)> 0)
                                        @foreach($dataPunishType as $punishType)
                                            <option value="{!! $punishType->typeId() !!}"
                                                    @if($punishTypeId == $punishType->typeId()) selected="selected" @endif >{!! $punishType->name() !!}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <a class="btn btn-sm btn-primary" style="height: 25px;"
                                   href="{!! route('qc.ad3d.system.punish-content.add.get') !!}">
                                    + Thêm
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="qc_ad3d_list_content qc-ad3d-table-container row"
                 data-href-view="{!! route('qc.ad3d.system.punish-content.view.get') !!}"
                 data-href-edit="{!! route('qc.ad3d.system.punish-content.edit.get') !!}"
                 data-href-del="{!! route('qc.ad3d.system.punish-content.delete') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: whitesmoke;">
                            <th class="text-center">STT</th>
                            <th>Mã</th>
                            <th>Tên</th>
                            <th>Mô tả</th>
                            <th class="text-center">Số tiền</th>
                            <th></th>
                        </tr>
                        @if(count($dataPunishContent) > 0)
                            <?php
                            $perPage = $dataPunishContent->perPage();
                            $currentPage = $dataPunishContent->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataPunishContent as $punishContent)
                                <?php
                                $punishId = $punishContent->punishId();
                                ?>
                                <tr class="qc_ad3d_list_object" data-object="{!! $punishId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td>
                                        {!! $punishContent->punishCode() !!}
                                    </td>
                                    <td>
                                        {!! $punishContent->name() !!}
                                    </td>
                                    <td>
                                        {!! $punishContent->note() !!}
                                    </td>
                                    <td class="text-center">
                                        {!! $hFunction->currencyFormat($punishContent->money()) !!}
                                    </td>
                                    <td class="text-right">
                                        <a class="qc_view qc-link-green" href="#">
                                            Chi tiết
                                        </a>
                                        &nbsp;|&nbsp;
                                        <a class="qc_edit qc-link-green" href="#">Sửa</a>
                                        &nbsp;|&nbsp;
                                        <a class="qc_delete qc-link-green" href="#">Xóa</a>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center" colspan="6">
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