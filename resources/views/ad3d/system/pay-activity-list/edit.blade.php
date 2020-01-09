<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataCompany
 */
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
?>
@extends('ad3d.components.container.container-6')
@section('qc_ad3d_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
                <h3>SỬA</h3>
            </div>
            <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class="frmEdit" name="frmEdit" role="form" method="post" action="{!! route('qc.ad3d.system.pay_activity_list.post.get', $dataPayActivityList->payListId()) !!}">
                    <div class="col-sx-12 col-sm-12 col-md-8 col-lg-8"
                         @if($mobileStatus) style="padding: 0 0;" @endif>
                        <div class="form-group form-group-sm">
                            <label>
                                Tên danh mục chi:
                                <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                            </label>
                            <input type="text" name="txtName" class="form-control" placeholder="Tên danh mục chi"
                                   value="{!! $dataPayActivityList->name() !!}">
                        </div>
                    </div>
                    <div class="col-sx-12 col-sm-12 col-md-4 col-lg-4"
                         @if($mobileStatus) style="padding: 0 0;" @endif>
                        <div class="form-group form-group-sm">
                            <label>Loại chi phí</label>
                            <select class="form-control" name="cbType">
                                <option value="2" @if($dataPayActivityList->type() == 2) selected="selected" @endif>Không cố định</option>
                                <option value="1" @if($dataPayActivityList->type() == 1) selected="selected" @endif>Cố định</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
                         @if($mobileStatus) style="padding: 0 0;" @endif>
                        <div class="form-group form-group-sm">
                            <label>Mô tả</label>
                            <input type="text" name="txtDescription" class="form-control" placeholder="Mô tả"
                                   value="{!! $dataPayActivityList->description() !!}">
                        </div>
                    </div>
                    <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <button type="button" class="qc_save btn btn-primary">Lưu</button>
                        <button type="button" class="qc_ad3d_container_close btn btn-default">Đóng</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
