<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:32 PM
 */
/*
 *$dataCompany
 */
$hFunction = new Hfunction();
$dataStaffLogin = $modelStaff->loginStaffInfo();
?>
@extends('ad3d.system.punish-type.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <i class="qc-font-size-20 glyphicon glyphicon-list-alt"></i>
                    <label class="qc-font-size-20" style="color: red;">LĨNH PHẠT</label>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0;">
                    <a class="form-control btn btn-primary qc-font-size-16 qc-link-white-bold" title="Thêm mới"
                       href="{!! route('qc.ad3d.system.punish-type.add.get') !!}">
                        <i class="qc-font-size-16 glyphicon glyphicon-plus"></i>
                        THÊM
                    </a>
                </div>
            </div>
            <div class="qc_ad3d_list_content qc-ad3d-table-container row"
                 data-href-view="{!! route('qc.ad3d.system.punish-type.view.get') !!}"
                 data-href-edit="{!! route('qc.ad3d.system.punish-type.edit.get') !!}"
                 data-href-del="{!! route('qc.ad3d.system.punish-type.delete') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: black; color: yellow;">
                            <th>Tên</th>
                        </tr>
                        @if($hFunction->checkCount($dataPunishType))
                            <?php
                            $perPage = $dataPunishType->perPage();
                            $currentPage = $dataPunishType->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataPunishType as $paymentType)
                                <?php
                                $typeId = $paymentType->typeId();
                                $n_o += 1
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2) info @endif" data-object="{!! $typeId !!}">
                                    <td>
                                        <em>{!! $n_o !!}). </em>
                                        <b>{!! $paymentType->name() !!}</b>
                                        <br/>&emsp;
                                        <a class="qc_view qc-link" href="#" title="Xem chi tiết">
                                            <i class="qc-font-size-14 glyphicon glyphicon-eye-open"></i>
                                        </a>
                                        &nbsp;|&nbsp;
                                        <a class="qc_edit qc-link-green" href="#" title="Sửa">
                                            <i class="qc-font-size-14 glyphicon glyphicon-pencil"></i>
                                        </a>
                                        &nbsp;|&nbsp;
                                        <a class="qc_delete qc-link-red" href="#" title="Xóa">
                                            <i class="qc-font-size-14 glyphicon glyphicon-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center">
                                    {!! $hFunction->page($dataPunishType) !!}
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
