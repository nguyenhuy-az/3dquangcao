<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 5/15/2018
 * Time: 10:12 AM
 *
 * dataStaff
 */
$hFunction = new Hfunction();
?>
@extends('work.index')
@section('qc_work_body')
    <div class="row">
        <div class="qc-padding-bot-20 col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <a class="qc-link-white-bold btn btn-primary" onclick="qc_main.page_back_go();">Về trang trước</a>
            </div>
            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    @if($hFunction->checkCount($dataRule))
                        <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <h3>{!! $dataRule->title() !!}</h3>
                        </div>
                        <div class="qc-padding-top-5 qc-padding-bot-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            {!! $dataRule->content() !!}
                        </div>
                    @else
                        <div class="qc_ad3d_list_object qc-ad3d-list-object row">
                            <div class="qc-padding-top-5 qc-padding-bot-5 text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <em class="qc-color-red">CHƯA CÓ NỘI QUY</em>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
