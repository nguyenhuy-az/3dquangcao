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
$company = $dataRules->company;
?>
@extends('ad3d.components.container.container-10')
@section('qc_ad3d_container_content')
    <div class="qc-padding-bot-30 col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
                <h3 style="color: red;">CẬP NHẬT NỘI QUY</h3>
                <label>{!! $company->name() !!}</label>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <form class="frmEdit" name="frmEdit" role="form" method="post"
                      action="{!! route('qc.ad3d.system.rules.edit.post', $dataRules->rulesId()) !!}">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="frm_notify form-group qc-color-red qc-font-size-20"></div>
                            </div>
                            @if($company->checkParent())
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label style="color: blue;">
                                                <input type="checkbox" name="checkAllStatus" checked="checked">
                                                CẬP NHẬT TOÀN HỆ THỐNG (Áp dụng cho các chi nhánh)
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                    <label>
                                        Tiêu đề:
                                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    </label>
                                    <input type="text" name="txtTitle" class="form-control"
                                           placeholder="Tiêu đề nội quy"
                                           value="{!! $dataRules->title() !!}">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                    <label>
                                        Nội dung:
                                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <textarea id="txtRuleContent" class="form-control"
                                              name="txtRuleContent">{!! $dataRules->content() !!}</textarea>
                                    <script type="text/javascript">ckeditor('txtRuleContent')</script>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <button type="button" class="qc_save btn btn-primary">CẬP NHẬT</button>
                        <button type="button" class="qc_ad3d_container_close btn btn-default">ĐÓNG</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
