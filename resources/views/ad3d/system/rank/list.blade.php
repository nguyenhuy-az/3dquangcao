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
@extends('ad3d.system.rank.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="margin-bottom: 10px; padding-top: 10px; padding-bottom: 10px; border-bottom: 2px dashed brown;">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <i class="qc-font-size-20 glyphicon glyphicon-list-alt"></i>
                    <label class="qc-font-size-20">CẤP BẬC</label>
                </div>
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 2px 0 2px 0; ">
                    <form name="" action="">
                        <div class="row">
                            {{--<div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <input class="col-xs-12" type="text" value="" placeholder="Tên công ty"
                                       style="height: 30px;">
                            </div>--}}
                                <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <a class="btn btn-primary btn-sm"
                                       href="{!! route('qc.ad3d.system.rank.add.get') !!}">
                                        <i class="glyphicon glyphicon-plus"></i>
                                    </a>
                                </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="qc_ad3d_list_content row"
                 data-href-view="{!! route('qc.ad3d.system.rank.view.get') !!}"
                 data-href-edit="{!! route('qc.ad3d.system.rank.edit.get') !!}">
                @if(count($dataRank) > 0)
                    <?php
                    $perPage = $dataRank->perPage();
                    $currentPage = $dataRank->currentPage();
                    $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                    ?>
                    @foreach($dataRank as $rank)
                        <?php
                        $rankId = $rank->rankId();
                        ?>
                        <div class="qc_ad3d_list_object qc-ad3d-list-object row" data-object="{!! $rankId !!}">
                            <div class="text-left col-xs-12 col-sm-12 col-md-3 col-lg-4"
                                 style="padding-top:5px; padding-bottom: 5px; ">
                                <b>{!! $n_o += 1 !!}).</b> {!! $rank->name() !!}
                            </div>
                            <div class="text-left col-xs-12 col-sm-12 col-md-3 col-lg-4"
                                 style="padding-top:5px; padding-bottom: 5px; ">
                                {!! $rank->description() !!}
                            </div>
                            <div class="text-right col-xs-12 col-sm-12 col-md-3 col-lg-4"
                                 style="padding-top:5px; padding-bottom: 5px; ">
                                <a class="qc_view qc-link-green btn btn-default btn-sm" href="#">
                                    Chi tiết
                                </a>
                                    <a class="qc_edit qc-link-green btn btn-default btn-sm" href="#">Sửa</a>
                                    <a class="qc_delete qc-link-green btn btn-default btn-sm" href="#">Xóa</a>
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>
            <div class="row">
                <div class="text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    {!! $hFunction->page($dataRank) !!}
                </div>
            </div>
        </div>
    </div>
@endsection
