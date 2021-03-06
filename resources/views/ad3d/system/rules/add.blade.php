<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
if ($hFunction->checkEmpty($dataRulesSelected)) {
    $title = $hFunction->getDefaultNull();
    $content = $hFunction->getDefaultNull();
} else {
    $title = $dataRulesSelected->title();
    $content = $dataRulesSelected->content();
}
?>
@extends('ad3d.system.rules.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
            <h3 style="color: red;">THÊM NỘI QUY</h3>
        </div>
        <div class="qc-padding-top-20 col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <form class="frmAdd" name="frmAdd" role="form" method="post"
                  action="{!! route('qc.ad3d.system.rules.add.post') !!}">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                @if (Session::has('notifyAdd'))
                                    <div class="form-group text-center qc-color-red">
                                        {!! Session::get('notifyAdd') !!}
                                        <?php
                                        Session::forget('notifyAdd');
                                        ?>
                                    </div>
                                @endif
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="form-group">
                                    <label>
                                        Tiêu đề:
                                        <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                                    </label>
                                    <input type="text" name="txtTitle" class="form-control"
                                           placeholder="Tiêu đề nội quy"
                                           value="{!! $title !!}">
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
                                              name="txtRuleContent">{!! $content !!}</textarea>
                                    <script type="text/javascript">ckeditor('txtRuleContent')</script>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <button type="button" class="qc_save btn btn-primary">THÊM</button>
                            <button type="button" class="qc_save btn btn-default">NHẬP LẠI</button>
                            <a href="{!! route('qc.ad3d.system.rules.get') !!}">
                                <button type="button" class="btn btn-default">ĐÓNG</button>
                            </a>
                        </div>

                    </div>
                </div>

            </form>
        </div>
    </div>
@endsection
