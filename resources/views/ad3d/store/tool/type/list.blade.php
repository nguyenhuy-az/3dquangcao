<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:32 PM
 */
?>
@extends('ad3d.store.tool.type.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 10px; padding-top: 10px; padding-bottom: 10px; border-bottom: 2px dashed brown;">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <i class="qc-font-size-20 glyphicon glyphicon-list-alt"></i>
                    <label class="qc-font-size-20">LOẠI CÔNG CỤ</label>
                </div>
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 2px 0 2px 0; ">
                    <form name="" action="">
                        <div class="row">
                            <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <input class="col-xs-12" type="text" value="" placeholder="Tìm theo tên" style="height: 30px;">
                            </div>
                            <div class="text-right col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <a class="btn btn-primary btn-sm"
                                   href="{!! route('qc.ad3d.tool.type.add.get') !!}">
                                    <i class="glyphicon glyphicon-plus"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="qc_ad3d_list_content row"
                 data-href-view="{!! route('qc.ad3d.tool.type.view.get') !!}"
                 data-href-edit="{!! route('qc.ad3d.tool.type.edit.get') !!}">
                @for($i=1; $i <= 10; $i++)
                    <div class="qc_ad3d_list_object qc-ad3d-list-object row" data-object="{!! $i !!}">
                        <div class="text-left col-xs-12 col-sm-12 col-md-8 col-lg-8"
                             style="padding-top:5px; padding-bottom: 5px; ">
                          <b>{!! $i !!}).</b>  Tên Công ty  {!! $i !!}
                        </div>
                        <div class="text-right col-xs-12 col-sm-12 col-md-4 col-lg-4"
                             style="padding-top:5px; padding-bottom: 5px; ">
                            <a class="qc_view qc-link-green btn btn-default btn-sm" href="#">
                                Chi tiết
                            </a>
                            <a class="qc_edit qc-link-green btn btn-default btn-sm" href="#">Sửa</a>
                            <a class="qc_delete qc-link-green btn btn-default btn-sm" href="#">Xóa</a>
                        </div>
                    </div>
                @endfor
            </div>
            <div class="row">
                <div class="text-center qc-padding-top-20 qc-padding-bot-20 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    @for($i =1;$i<= 5; $i++)
                        <a class="qc-link qc-padding-5 qc-margin-5" href="#">
                            {!! $i !!}
                        </a>
                    @endfor
                    <span>...</span>
                </div>
            </div>
        </div>
    </div>
@endsection
