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
@extends('ad3d.system.pay-activity-list.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="margin-bottom: 10px; padding-top: 10px; padding-bottom: 10px; border-bottom: 2px dashed brown;">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green"
                       href="{!! route('qc.ad3d.system.pay_activity_list.get') !!}">
                        <i class="glyphicon glyphicon-refresh qc-font-size-20"></i>
                    </a>
                    <i class="qc-font-size-20 glyphicon glyphicon-list-alt"></i>
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
                                <a class="btn btn-primary btn-sm"
                                   href="{!! route('qc.ad3d.system.pay_activity_list.add.post') !!}">
                                    <i class="glyphicon glyphicon-plus"></i>Thêm
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="qc_ad3d_list_content row"
                 data-href-view="{!! route('qc.ad3d.system.pay_activity_list.view.get') !!}"
                 data-href-edit="{!! route('qc.ad3d.system.pay_activity_list.edit.get') !!}"
                 data-href-del="{!! route('qc.ad3d.system.pay_activity_list.delete') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: whitesmoke;">
                            <th class="text-center qc-padding-none">STT</th>
                            <th class="qc-padding-none">Tên</th>
                            <th class="qc-padding-none">Mô tả</th>
                            <th class="text-center qc-padding-none">Loại chi phí</th>
                            <th class="qc-padding-none"></th>
                        </tr>
                        @if(count($dataPayActivityList) > 0)
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
                                <tr class="qc_ad3d_list_object qc-ad3d-list-object" data-object="{!! $payListIdId !!}">
                                    <td class="text-center qc-padding-none">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td class="qc-padding-none">
                                        {!! $payActivityList->name() !!}
                                    </td>
                                    <td class="qc-link-grey qc-padding-none">
                                        @if(!empty($description))
                                            {!! $description !!}
                                        @else
                                            <em>---</em>
                                        @endif
                                    </td>
                                    <td class="text-center qc-padding-none">
                                        {!! $payActivityList->typeLabel() !!}
                                    </td>
                                    <td class="text-right qc-padding-none">
                                        <a class="qc_view qc-link-green" href="#">
                                            Chi tiết
                                        </a>
                                        <span>|</span>
                                        <a class="qc_edit qc-link-green" href="#">Sửa</a>
                                        <span>|</span>
                                        <a class="qc_delete qc-link-green" href="#">Xóa</a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center qc-color-red qc-padding-none" colspan="5">
                                    Chưa có danh mục chi
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    {!! $hFunction->page($dataPayActivityList) !!}
                </div>
            </div>
        </div>
    </div>
@endsection
