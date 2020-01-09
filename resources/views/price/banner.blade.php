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


            <div class="qc-padding-top-30 qc-padding-bot-30 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <h2>Bài viết báo giá bảng hiệu</h2>
            </div>
            <div class="qc-padding-top-30 qc-padding-bot-30 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <p>
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết
                </p>

                <p>
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết
                </p>

                <p>
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết
                </p>

                <p>
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết
                </p>

                <p>
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết
                </p>

                <p>
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết
                </p>

                <p>
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết
                </p>

                <p>
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết
                </p>
            </div>
        </div>
@endsection

{{--footer--}}
@section('qc_master_footer')
    @include('components.footer.footer')
@endsection