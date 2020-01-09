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
                <h2>Bài viết về Tư vấn - Thiết kế</h2>
            </div>
            <div class="qc-padding-top-30 qc-padding-bot-30 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <p>
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết <br/><br/>
                    Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết Nội dung bài viết
                </p>
                <img style="max-width: 100%; border: 1px solid #D7D7D7;" src="{!! asset('public/imgtest/tvtk_1.jpg') !!}">
                <br/><br/>
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
                <img style="max-width: 100%; border: 1px solid #D7D7D7;" src="{!! asset('public/imgtest/tvtk_2.jpg') !!}">
                <br/>
                <br/>
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