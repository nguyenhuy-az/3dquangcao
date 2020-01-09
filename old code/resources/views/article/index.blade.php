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
    <link href="{{ url('public/article/css/article.css')}}" rel="stylesheet">
@endsection

{{--js--}}
@section('qc_js_header')
    <script src="{{ url('public/article/js/article.js')}}"></script>
@endsection

{{--header--}}
@section('qc_master_header')
    @include('components.header.header')
@endsection

{{--body--}}
@section('xv_master_body')
    <div class="row">
        <div class="xv-article-body col-xs-12 col-sm-12 col-md-12 col-lg-12">
            {{--title--}}
            <div class="row">
                <div class="xv-padding-top-10 xv-padding-bot-10 col-xs-12 col-sm-12 col-sm-12 col-lg-12">
                    <p>
                        <b>Tiêu đề của bài viết Tiêu đề của bài viết Tiêu đề của bài viết Tiêu đề của bài viết Tiêu đề của
                            bài viết
                            Tiêu đề của bài viết Tiêu đề của bài viết Tiêu đề của bài viết</b>
                    </p>
                </div>
            </div>

            {{--content--}}
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-sm-12 col-lg-12">


                    noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai
                    noi
                    dung bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai noi
                    dung
                    bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai noi dung
                    bai
                    viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai noi dung bai
                    viet
                    noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bainoi dung bai viet
                    noi
                    dung bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai noi dung bai viet noi
                    dung
                    bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai noi dung bai viet noi dung
                    bai
                    viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai noi dung bai viet noi dung bai
                    viet
                    noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai

                    noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai
                    noi
                    dung bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai noi
                    dung
                    bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai noi dung
                    bai
                    viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai noi dung bai
                    viet
                    noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bainoi dung bai viet
                    noi
                    dung bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai noi dung bai viet noi
                    dung
                    bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai noi dung bai viet noi dung
                    bai
                    viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai noi dung bai viet noi dung bai
                    viet
                    noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai

                    noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai
                    noi
                    dung bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai noi
                    dung
                    bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai noi dung
                    bai
                    viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai noi dung bai
                    viet
                    noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bainoi dung bai viet
                    noi
                    dung bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai noi dung bai viet noi
                    dung
                    bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai noi dung bai viet noi dung
                    bai
                    viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai noi dung bai viet noi dung bai
                    viet
                    noi .
                </div>
            </div>

            {{--source--}}
            <div class="row">
                <div class="xv-padding-top-10 xv-padding-bot-10 text-left col-xs-12 col-sm-12 col-sm-12 col-lg-12">
                    <em>Nguồn: noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi
                        d</em>
                </div>
            </div>

            {{--share--}}
            <div class="row">
                <div class="xv-padding-top-10 xv-padding-bot-10 text-right col-xs-12 col-sm-12 col-sm-12 col-lg-12">
                    <img style="width: 30px; height: 30px;" src="{!! asset('public/imgtest/fb1.png') !!}">
                    <img style="width: 30px; height: 30px;" src="{!! asset('public/imgtest/g.png') !!}">
                </div>
            </div>

            {{--comment--}}
            @include('article.comment.comment')
        </div>
    </div>
@endsection

{{--footer--}}
@section('xv_master_footer')
    @include('components.footer.footer')
@endsection