<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:32 PM
 */

$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$dataStaffLogin = $modelStaff->loginStaffInfo();
?>
@extends('ad3d.store.tool.tool.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 5px; padding-top: 5px; padding-bottom: 5px; border-bottom: 2px dashed brown;">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! route('qc.ad3d.store.supplies.supplies.get') !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20">CÁC LOẠI DỤNG CỤ HỆ THỐNG</label>
                </div>
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        @if($dataStaffLogin->checkRootStatus())
                            <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <a class="btn btn-primary btn-sm"
                                   href="{!! route('qc.ad3d.store.tool.tool.add.get') !!}">
                                    <i class="glyphicon glyphicon-plus"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="qc_ad3d_list_content row"
                 data-href-view="{!! route('qc.ad3d.store.tool.tool.view.get') !!}"
                 data-href-edit="{!! route('qc.ad3d.store.tool.tool.edit.get') !!}"
                 data-href-del="{!! route('qc.ad3d.store.tool.tool.del.get') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: whitesmoke;">
                            <th class="text-center">STT</th>
                            <th class="qc-padding-none">Tên</th>
                            <th class="text-center">Đơn vị tính</th>
                            <th class="text-right"></th>
                        </tr>
                        @if(count($dataTool) > 0)
                            <?php
                            $perPage = $dataTool->perPage();
                            $currentPage = $dataTool->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataTool as $tool)
                                <tr class="qc_ad3d_list_object qc-ad3d-list-object" data-object="{!! $tool->toolId() !!}" >
                                    <td class="text-center">
                                        {!! $n_o +=1 !!}
                                    </td>
                                    <td>
                                        {!! $tool->name() !!}
                                    </td>
                                    <td class="text-center">
                                        {!! $tool->unit() !!}
                                    </td>
                                    <td class="text-right">
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
                            <tr>
                                <td class="text-center" colspan="4">
                                    {!! $hFunction->page($dataTool) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-center" colspan="4">
                                    Không có đữ liệu
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection
