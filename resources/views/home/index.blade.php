<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 4/8/2017
 * Time: 12:02 PM
 */
?>
@extends('.master')

@section('titlePage')
    quang cao
@endsection

@section('shortcutPage')
    <link rel="shortcut icon" href="{!! asset('public/imgtest/travel_1.jpg') !!}"/>
@endsection

{{--description Page--}}
@section('descriptionPage')
    mo ta quang cao
@endsection
{{--css--}}
@section('qc_css_header')
    <link href="{{ url('public/home/css/home.css')}}" rel="stylesheet">
@endsection

{{--js--}}
@section('qc_js_header')
    <script src="{{ url('public/home/js/home.js')}}"></script>
@endsection

{{--header--}}
@section('qc_master_header')
    @include('components.header.header')
@endsection

{{--body--}}
@section('qc_master_body')
    <div class="row">
        <div class="qc-home-body col-xs-12 col-sm-12 col-md-12 col-lg-12">
            {{--new article--}}
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    @for($i = 1; $i < 5; $i++)
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <table class="qc-home-article table ">
                                <tr>
                                    <td >
                                        <img style="width:100%; height: 300px;border: 1px solid #D7D7D7;"
                                             src="{!! asset('public/imgtest/a2.PNG') !!}">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href="{!! route('qc.product.detail') !!}">
                                            Tên bài viết Tên bài viết Tên bài viết Tên bài viết Tên bàiviết
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <table class="qc-home-article table ">
                                <tr>
                                    <td>
                                        <img style="width:100%; height: 300px; border: 1px solid #D7D7D7;"
                                             src="{!! asset('public/imgtest/a5.jpg') !!}">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <a href="{!! route('qc.product.detail') !!}">
                                            Tên bài viết Tên bài viết Tên bài viết Tên bài viết Tên bàiviết
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    @endfor
                </div>
            </div>
            <div class="qc-padding-30 text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <a href="#">Xem thêm</a>
            </div>
        </div>
    </div>
@endsection

{{--footer--}}
@section('qc_master_footer')
    @include('components.footer.footer')
@endsection
