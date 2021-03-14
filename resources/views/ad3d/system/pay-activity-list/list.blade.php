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
$indexHref = route('qc.ad3d.system.pay_activity_list.get');
?>
@extends('ad3d.system.pay-activity-list.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green" href="{!! $indexHref !!}">
                        <i class="glyphicon glyphicon-refresh qc-font-size-20"></i>
                    </a>
                    &nbsp;
                    <label class="qc-font-size-20" style="color: red;">LĨNH VỰC CHI</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <a class="qc-link-white-bold btn btn-primary form-control"
                   href="{!! route('qc.ad3d.system.pay_activity_list.add.post') !!}">
                    <i class="glyphicon glyphicon-plus"></i>
                    THÊM
                </a>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="qc_ad3d_list_content row"
                 data-href-view="{!! route('qc.ad3d.system.pay_activity_list.view.get') !!}"
                 data-href-edit="{!! route('qc.ad3d.system.pay_activity_list.edit.get') !!}"
                 data-href-del="{!! route('qc.ad3d.system.pay_activity_list.delete') !!}">
                <table class="table table-hover table-bordered">
                    <tr style="background-color: black; color: yellow;">
                        <th style="min-width: 180px;">Tên</th>
                        <th>Mô tả</th>
                    </tr>
                    @if($hFunction->checkCount($dataPayActivityList))
                        <?php
                        $perPage = $dataPayActivityList->perPage();
                        $currentPage = $dataPayActivityList->currentPage();
                        $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                        ?>
                        @foreach($dataPayActivityList as $payActivityList)
                            <?php
                            $payListIdId = $payActivityList->payListId();
                            $description = $payActivityList->description();
                            $n_o += 1;
                            ?>
                            <tr class="qc_ad3d_list_object @if($n_o%2) info @endif"
                                data-object="{!! $payListIdId !!}">
                                <td>
                                    <em>{!! $n_o !!}). </em>
                                    <b>{!! $payActivityList->name() !!}</b>
                                    <br/>&emsp;
                                    <a class="qc_view qc-link" href="#">
                                        <i class="qc-font-size-14 glyphicon glyphicon-eye-open"></i>
                                    </a>
                                    <span>&nbsp;|&nbsp;</span>
                                    <a class="qc_edit qc-link-green" href="#">
                                        <i class="qc-font-size-14 glyphicon glyphicon-pencil"></i>
                                    </a>
                                    <span>&nbsp;|&nbsp;</span>
                                    <a class="qc_delete qc-link-red" href="#">
                                        <i class="qc-font-size-14 glyphicon glyphicon-trash"></i>
                                    </a>
                                </td>
                                <td>
                                    <span>{!! $payActivityList->typeLabel() !!}</span>
                                    @if(!$hFunction->checkEmpty($description))
                                        <br/>
                                        <em style="color: grey;">- {!! $description !!}</em>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="text-center" colspan="2">
                                {!! $hFunction->page($dataPayActivityList) !!}
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td class="text-center qc-color-red" colspan="5">
                                Chưa có danh mục chi
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
@endsection
