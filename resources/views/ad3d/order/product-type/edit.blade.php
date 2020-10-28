<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataCompany
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
//$dataConstructionWorkSelected = $dataProductType->constructionWorkInfo();
$constructionWorkListId = $dataProductType->constructionWorkListId()->toArray();
?>
@extends('ad3d.components.container.container-6')
@section('qc_ad3d_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
                <h3 style="color: red;">SỬA THÔNG TIN</h3>
            </div>
            <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class="frmEdit" name="frmEdit" role="form" method="post" action="{!! route('qc.ad3d.order.product_type.edit.post', $dataProductType->typeId()) !!}">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="frm_notify form-group qc-color-red"></div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                    <label>
                                        Tên:
                                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    </label>
                                    <input type="text" name="txtName" class="form-control" placeholder="Nhập tên loại sản phẩm"
                                           value="{!! $dataProductType->name() !!}">
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                    <label>
                                        Đơn vị tính:
                                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    </label>
                                    <input type="text" name="txtUnit" class="form-control"
                                           placeholder="Nhập đơn vị tính" value="{!! $dataProductType->unit() !!}">
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                    <label>
                                        Thời gian bảo hành (Tháng):
                                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    </label>
                                    <input type="number" name="txtWarrantyTime" class="form-control"
                                           placeholder="Thời gian bảo hành" value="{!! $dataProductType->warrantyTime() !!}">
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                    <label>
                                        Mô tả sản phẩm:
                                    </label>
                                    <textarea name="txtDescription" class="form-control" placeholder="Mô tả sản phẩm">{!! $dataProductType->description() !!}</textarea>
                                </div>
                            </div>
                            <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group form-group-sm">
                                    <label>
                                        Danh mục thi công liên quan:
                                    </label>
                                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                        @if($hFunction->checkCount($dataDepartmentWork))
                                            @foreach($dataDepartmentWork as $departmentWork)
                                                <?php
                                                $workId = $departmentWork->workId();
                                                ?>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="cbDepartmentWork[]" @if($hFunction->checkInArray($workId,$constructionWorkListId)) checked="checked" @endif
                                                           value="{!! $workId !!}">
                                                    {!! $departmentWork->name() !!}
                                                </label>
                                            @endforeach
                                        @else
                                            <em class="qc-color-grey">Không có danh mục thi công</em>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="margin-top: 10px;">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <button type="button" class="qc_save btn btn-primary">Lưu</button>
                        <button type="button" class="qc_ad3d_container_close btn btn-default">Đóng</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
