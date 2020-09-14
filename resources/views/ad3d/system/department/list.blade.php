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
@extends('ad3d.system.department.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green"
                       href="{!! route('qc.ad3d.system.department.get') !!}">
                        <i class="glyphicon glyphicon-refresh qc-font-size-20"></i>
                    </a>
                    <label class="qc-font-size-20">BỘ PHẬN</label>
                </div>
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 2px 0 2px 0; ">
                    <div class="row">
                        <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <a class="btn btn-primary btn-sm"
                               href="{!! route('qc.ad3d.system.department.add.get') !!}">
                                <i class="glyphicon glyphicon-plus"></i>
                                Thêm
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="qc_ad3d_list_content row"
                 data-href-status="{!! route('qc.ad3d.system.department.status.update') !!}"
                 data-href-view="{!! route('qc.ad3d.system.department.view.get') !!}"
                 data-href-edit="{!! route('qc.ad3d.system.department.edit.get') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: black; color: yellow;">
                            <th class="text-center" style="width: 20px;">STT</th>
                            <th>Mã BP</th>
                            <th>Tên</th>
                            <th class="text-center">Hoạt động</th>
                            <th></th>
                        </tr>
                        @if($hFunction->checkCount($dataDepartment))
                            <?php
                            $perPage = $dataDepartment->perPage();
                            $currentPage = $dataDepartment->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataDepartment as $department)
                                <?php
                                $departmentId = $department->departmentId();
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2) info @endif" data-object="{!! $departmentId !!}">
                                    <td class="text-center">
                                        <b>{!! $n_o += 1 !!}</b>
                                    </td>
                                    <td>
                                        {!! $department->departmentCode() !!}
                                    </td>
                                    <td>
                                        {!! $department->name() !!}
                                    </td>
                                    <td class="text-center">
                                        <a class="qc_status btn btn-default btn-sm " href="#" title="Activity status">
                                            @if($department->checkActivityStatus())
                                                <i class="glyphicon glyphicon-ok qc-link-green" title="Vô hiệu"></i>
                                            @else
                                                <i class="glyphicon glyphicon-ok" title="Kích hoạt"></i>
                                            @endif
                                        </a>
                                    </td>
                                    <td class="text-right">
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
                                <td class="text-center" colspan="5">
                                    {!! $hFunction->page($dataDepartment) !!}
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
