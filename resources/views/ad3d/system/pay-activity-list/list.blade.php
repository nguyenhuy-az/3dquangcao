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
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="margin-bottom: 10px; padding-top: 10px; padding-bottom: 10px; border-bottom: 2px dashed brown;">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green" href="{!! $indexHref !!}">
                        <i class="glyphicon glyphicon-refresh qc-font-size-20"></i>
                    </a>
                    &nbsp;
                    <label class="qc-font-size-20">LĨNH VỰC CHI</label>
                </div>
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 2px 0 2px 0; ">
                    <form name="" action="">
                        <div class="row">
                            <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <a class="qc-link-bold"
                                   href="{!! route('qc.ad3d.system.pay_activity_list.add.post') !!}">
                                    <i class="glyphicon glyphicon-plus"></i>Thêm
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="qc_ad3d_list_content qc-ad3d-table-container row"
                 data-href-view="{!! route('qc.ad3d.system.pay_activity_list.view.get') !!}"
                 data-href-edit="{!! route('qc.ad3d.system.pay_activity_list.edit.get') !!}"
                 data-href-del="{!! route('qc.ad3d.system.pay_activity_list.delete') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: whitesmoke;">
                            <th class="text-center" style="width: 20px;">STT</th>
                            <th>Tên</th>
                            <th>Mô tả</th>
                            <th>Loại chi phí</th>
                            <th></th>
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
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2) info @endif" data-object="{!! $payListIdId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td>
                                        {!! $payActivityList->name() !!}
                                    </td>
                                    <td class="qc-link-grey">
                                        @if(!$hFunction->checkCount($description))
                                            {!! $description !!}
                                        @else
                                            <em>---</em>
                                        @endif
                                    </td>
                                    <td>
                                        {!! $payActivityList->typeLabel() !!}
                                    </td>
                                    <td class="text-right">
                                        <a class="qc_view qc-link" href="#">
                                            <i class="qc-font-size-14 glyphicon glyphicon-eye-open"></i>
                                        </a>
                                        <span>|</span>
                                        <a class="qc_edit qc-link-green" href="#">
                                            <i class="qc-font-size-14 glyphicon glyphicon-pencil"></i>
                                        </a>
                                        <span>|</span>
                                        <a class="qc_delete qc-link-red" href="#">
                                            <i class="qc-font-size-14 glyphicon glyphicon-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center" colspan="5">
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
    </div>
@endsection
