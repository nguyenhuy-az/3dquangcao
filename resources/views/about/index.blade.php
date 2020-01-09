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
            {{--title--}}
            <div class="row">
                <div class="qc-padding-top-10 qc-padding-bot-10 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <h3>
                        Bài viết giới thiệu công ty
                    </h3>
                </div>
            </div>

            {{--content--}}
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-sm-12 col-lg-12">
                    DEC
                    3
                    Cách làm một bảng hiệu đẹp - xinh xắn - độc lạ
                    Cách làm một bảng hiệu đẹp - xinh xắn - độc lạ các bạn nào chưa có kinh nghiệm hãy tuân thủ các bước sau đây:
                    Chọn Tên+Logo > Đo kích thước > Thiết Kế Sơ Bộ Ý Tưởng > Chọn Người Tư Vấn chất liệu
                    Từ các thông tin trên Người làm Phương án sẻ cho ra báo giá phù hợp dựa trên chất liệu này. nhà thiết kế sẻ hoàn chỉnh và Thi công, khi các bạn làm việc với người chuyên nghiệp thì sẻ giảm được nhiều chi phí khi khi làm ra không ưng ý, hoặc tốn thời gian sửa chữa, hoặc làm ra không đẹp...
                    LH ngay: 09.077.077.28, 0904.428.324 , Cty 3D - Chúng tôi sẻ có phương án hoàn hảo cho quý khách trong vòng 24h
                    <p>
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
                    </p>
                    <p>
                        dung bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai noi dung bai viet noi
                        dung
                        bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai noi dung bai viet noi dung
                        bai
                        viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai noi dung bai viet noi dung bai
                        viet
                        noi .
                    </p>
                    <img style="max-width: 100%; border: 1px solid #D7D7D7;" src="{!! asset('public/imgtest/a6.jpg') !!}">
                    <br/>
                    <br/>
                    <p>
                        dung bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai noi dung bai viet noi
                        dung
                        bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai noi dung bai viet noi dung
                        bai
                        viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai noi dung bai viet noi dung bai
                        viet
                        noi .
                    </p>
                    bai
                    viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai viet noi dung bai noi dung bai
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
                    <p>
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
                    </p>
                    <img style="max-width:100%;border: 1px solid #D7D7D7;" src="{!! asset('public/imgtest/a7.jpg') !!}">
                    <p>
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
                    </p>
                    <p>
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
                    </p>
                </div>
            </div>

        </div>
    </div>
@endsection

{{--footer--}}
@section('qc_master_footer')
    @include('components.footer.footer')
@endsection