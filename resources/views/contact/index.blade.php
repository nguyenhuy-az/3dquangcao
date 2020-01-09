<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 4/8/2017
 * Time: 12:02 PM
 */
$loginStatus = false;
?>
@extends('.master')

@section('titlePage')
    chi tiết bài viết
@endsection

@section('shortcutPage')
    <link rel="shortcut icon" href="{!! asset('public/imgtest/travel_1.jpg') !!}"/>
@endsection

{{--description Page--}}
@section('descriptionPage')
    Du lịch, phược..
@endsection
{{--css--}}
@section('qc_css_header')
    <link href="{{ url('public/about/css/about.css')}}" rel="stylesheet">
@endsection

{{--js--}}
@section('qc_js_header')
    <script src="{{ url('public/about/js/about.js')}}"></script>
@endsection

{{--header--}}
@section('qc_master_header')
    @include('components.header.header')
@endsection

{{--body--}}
@section('qc_master_body')
    <div class="row">
        <div class="qc-article-body col-xs-12 col-sm-12 col-md-12 col-lg-12">


            {{--content--}}
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    đia chỉ liên hệ
                </div>
                <div class="col-xs-12 col-sm-12 col-sm-12 col-lg-12">
                    <img style="width: 100%; height: 400px;border: 1px solid #D7D7D7;"
                         src="{!! asset('public/imgtest/bando.JPG') !!}">
                </div>
            </div>

            {{--title--}}
            <div class="row">
                <div class="qc-padding-top-30 qc-padding-bot-30 col-xs-12 col-sm-12 col-md-8 col-lg-8">
                    <form role="form">
                        <div class="form-group" style="color: green;">
                            <h3>Liên hệ</h3>
                        </div>
                        <div class="form-group">
                            <label>Tên</label>
                            <input type="text" class="form-control" placeholder="Nhập tên">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" placeholder="Nhập email">
                        </div>
                        <div class="form-group">
                            <label>Nội dung</label>
                            <textarea class="form-control" rows="10"></textarea>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-default">Gửi</button>
                        </div>

                    </form>
                </div>
                <div class="qc-padding-top-30 qc-padding-bot-30 col-xs-12 col-sm-12 col-md-6 col-lg-6">

                </div>
            </div>
        </div>
    </div>
@endsection

{{--footer--}}
@section('qc_master_footer')
    @include('components.footer.footer')
@endsection