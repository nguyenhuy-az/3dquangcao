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
$hrefIndex = route('qc.ad3d.system.department_work.get');
if ($hFunction->checkCount($dataDepartmentSelected)) {
    $departmentSelectedId = $dataDepartmentSelected->departmentId(0);
} else {
    $departmentSelectedId = null;
}
?>
@extends('ad3d.system.department-work.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <label class="qc-font-size-20">CÔNG VIỆC CỦA BỘ PHẬN</label>
                </div>
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="qc_ad3d_list_content row">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <form class="frmAdd" name="frmAdd" role="form" method="post"
                              action="{!! route('qc.ad3d.system.department_work.add.post') !!}">
                            <tr>
                                <td colspan="4" style="padding: 0;">
                                    <select class="cbDepartment form-control" name="cbDepartment" style="color: blue;"
                                            data-href="{!! $hrefIndex !!}">
                                        @if($hFunction->checkCount($dataDepartmentAll))
                                            @foreach($dataDepartmentAll as $department)
                                                <option @if($departmentSelectedId == $department->departmentId()) selected="selected"
                                                        @endif value="{!! $department->departmentId() !!}">
                                                    {!! $department->name() !!}
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="0">
                                                Không có bộ phận được chọn
                                            </option>
                                        @endif
                                    </select>
                                </td>
                            </tr>
                            @if (Session::has('notifyAdd'))
                                <tr>
                                    <td></td>
                                    <td colspan="3" style="color: red; font-size: 1.5em;">
                                        {!! Session::get('notifyAdd') !!}
                                        <?php
                                        Session::forget('notifyAdd');
                                        ?>
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td></td>
                                <td style="padding: 0;">
                                    <input type="text" name="txtName" class="form-control"
                                           placeholder="Nhập tên công việc" value="">
                                </td>
                                <td style="padding: 0;">
                                    <input type="text" name="txtDescription" class="form-control"
                                           placeholder="Mô tả công việc" value="">
                                </td>
                                <td style="padding: 0; width: 150px;">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <button type="submit" class="btn btn-primary qc-link-white-bold"
                                            style="width: 100%;">
                                        THÊM
                                    </button>
                                </td>
                            </tr>
                        </form>
                        <tr style="background-color: black; color: yellow;">
                            <th class="text-center" style="width: 20px;">STT</th>
                            <th class="text-center">Công việc</th>
                            <th>Mô tả</th>
                            <th></th>
                        </tr>
                        @if($hFunction->checkCount($dataDepartmentWork))
                            <?php
                            $n_o = 0;
                            ?>
                            @foreach($dataDepartmentWork as $departmentWork)
                                <?php
                                $workId = $departmentWork->workId();
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2) info @endif" data-object="{!! $workId !!}">
                                    <td class="text-center">
                                        <b>{!! $n_o += 1 !!}</b>
                                    </td>
                                    <td>
                                        {!! $departmentWork->name() !!}
                                    </td>
                                    <td>
                                        {!! $departmentWork->description() !!}
                                    </td>
                                    <td class="text-right">
                                        <a class="qc_edit qc-link-green"
                                           data-href="{!! route('qc.ad3d.system.department_work.edit.get',$workId) !!}"
                                           title="Sửa"><i class="qc-font-size-14 glyphicon glyphicon-pencil"></i>
                                        </a>
                                        &nbsp;|&nbsp;
                                        <a class="qc_delete qc-link-red" title="Xóa"
                                           data-href="{!! route('qc.ad3d.system.department_work.delete', $workId) !!}">
                                            <i class="qc-font-size-14 glyphicon glyphicon-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4">
                                    <span style="color: blue;">Chưa có danh mục công việc</span>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
