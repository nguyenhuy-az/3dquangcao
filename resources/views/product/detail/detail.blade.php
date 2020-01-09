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
                <div class="qc-padding-top-30 qc-padding-bot-30 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <h2>Bài viết chi tiết sản phẩm</h2>
                </div>
                <div class="qc-padding-top-30 qc-padding-bot-30 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 content-spacing">

                        <div class="midd-bar">

                            <div class="recent-post-box clearfix">
                                <p class="img-left">
                                    <img width="301" height="196"
                                         src="http://dangquangad.com/wp-content/uploads/2015/09/thi-cong-mat-dung-aluminium-avatar-301x196.jpg"
                                         class="attachment-single-page-thumbnail size-single-page-thumbnail wp-post-image"
                                         alt="thi công mặt dựng aluminium"
                                         srcset="http://dangquangad.com/wp-content/uploads/2015/09/thi-cong-mat-dung-aluminium-avatar.jpg 301w, http://dangquangad.com/wp-content/uploads/2015/09/thi-cong-mat-dung-aluminium-avatar-300x196.jpg 300w"
                                         sizes="(max-width: 301px) 100vw, 301px">
                                </p>

                                <p><em><a href="http://dangquangad.com/gioi-thieu/">Công ty Quảng Cáo</a> Đăng
                                        Quang</em> chúng tôi tự hào là 1 công ty có nhiều kinh nghiệm và chuyên
                                    nghiệp trong <em><a href="http://dangquangad.com/lam-bang-hieu-quang-cao-gia-re/">thi
                                            công bảng quảng cáo</a>, biển hiệu, <a title="thi công mặt dựng aluminium"
                                                                                   href="http://dangquangad.com/thi-cong-mat-dung-alu-1/">mặt
                                            dựng aluminium</a>, mặt dựng nhôm, ốp alu</em>, sản xuất <em>chữ nổi mica,
                                        chữ nổi đồng, chữ nổi inox,</em> logo cao cấp tại<em> TP.HCM.</em></p>

                                <p>&gt;&gt; Xem: <em><span style="text-decoration: underline;"><a
                                                    href="http://dangquangad.com/bang-hieu-alu-la-gi/">Bảng hiệu Alu là
                                                gì ?&nbsp;</a></span></em></p>

                                <p>Chúng tôi có đội thợ chuyên <em><a title="thi công mặt dựng aluminium"
                                                                      href="http://dangquangad.com/thi-cong-mat-dung-alu-1/">thi
                                            công mặt dựng Aluminium</a></em>&nbsp;các công trình <em><a
                                                title="thi công mặt dựng aluminium"
                                                href="http://dangquangad.com/thi-cong-mat-dung-alu-1/">ốp Aluminium</a></em>&nbsp;composite
                                    trang trí nội ngoại thất cho các công trình như:&nbsp;Mặt tiền – Nội thất – Pano –
                                    Biển quảng cáo – Mái sảnh – Cột tròn – Hộp kĩ thuật – Trần – Hộp cửa cuốn…</p>


                                <p>&nbsp;</p>

                                <p style="text-align: center;">Hình 1 –<em><a title="thi công mặt dựng aluminium"
                                                                               href="http://dangquangad.com/thi-cong-mat-dung-alu-1/">
                                            Thi công mặt dựng Aluminium</a>, <a
                                                href="http://dangquangad.com/thi-cong-chu-noi-mica-chu-noi-led/">chữ nổi
                                            mica</a>,</em> led&nbsp;cho coffee Effoc</p>

                                <p>Với đội ngũ thợ tay nghề cao và kinh nghiệm thi công nhiều công trình thi công mặt
                                    dựng alumium, mặt dựng alumium tại <em>Quận 1, Quận 2, Quận 3, Quận 4, Quận
                                        5, Quận 6, Quận 7, Quận 8, Quận 9, Quận 10, Quận 11, Quận 12, Quận Bình
                                        Thạnh, Quận Tân Bình, Quận Tân Phú, Quận Phú Nhuận, Quận Thủ Đức,
                                        Quận Gò Vấp, Quận Nhà Bè, Quận Hóc Môn, Quận Củ Chi,Bình Tân, Bình
                                        Chánh Tp HCM – Sài Gòn</em>, chúng tôi sẽ mang lại cho các bạn những công
                                    trình đẹp, bền, chất lượng tôt với giá cả hợp lý nhất. Ngoài ra chúng tôi miễn phí
                                    tư vấn thiết kế cho quý khách hàng thi công<br>
                                    -Nhận hợp tác với các đơn vị tư vấn thiết kế <em>thi công mặt dựng alumium, ốp
                                        alumium mái đón, ốp alumium mái vòm, thi công alumium cửa cuốn,thi công
                                        bảng hiệu alumium công ty</em><br>
                                    -Nhận Khoán lại phần thi công (đội thợ tay nghề cao, kinh nghiệm, ý thức tốt và
                                    trách nhiệm)<br>
                                    -Nhận sửa chữa, cải tạo công trình cũ hỏng hóc</p>

                                <p>
                                    <a href="http://dangquangad.com/wp-content/uploads/2015/10/thi-cong-mat-dung-alumium-gia-re-tphcm-sai-gon.jpg"><img
                                                class="size-full wp-image-1444 aligncenter"
                                                src="http://dangquangad.com/wp-content/uploads/2015/10/thi-cong-mat-dung-alumium-gia-re-tphcm-sai-gon.jpg"
                                                alt="Thi công mặt dưng alumium giá rẻ quận 10" width="450"
                                                height="800"
                                                srcset="http://dangquangad.com/wp-content/uploads/2015/10/thi-cong-mat-dung-alumium-gia-re-tphcm-sai-gon.jpg 450w, http://dangquangad.com/wp-content/uploads/2015/10/thi-cong-mat-dung-alumium-gia-re-tphcm-sai-gon-169x300.jpg 169w"
                                                sizes="(max-width: 450px) 100vw, 450px"></a></p>

                                <p>&nbsp;</p>

                                <p style="text-align: center;">Hình 2- <em>Thi công mặt dựng aluminium,chữ nổi mica
                                        công ty Thanh Hằng</em></p>

                                <p style="text-align: center;"><a
                                            href="http://dangquangad.com/wp-content/uploads/2015/10/op-mat-dung-alumium-gia-re-tphcm-sai-gon.jpg"><img
                                                class="alignnone size-full wp-image-1441"
                                                src="http://dangquangad.com/wp-content/uploads/2015/10/op-mat-dung-alumium-gia-re-tphcm-sai-gon.jpg"
                                                alt="Thi công mặt dựng alumium giá rẻ tp hcm" width="600"
                                                height="458"
                                                srcset="http://dangquangad.com/wp-content/uploads/2015/10/op-mat-dung-alumium-gia-re-tphcm-sai-gon.jpg 600w, http://dangquangad.com/wp-content/uploads/2015/10/op-mat-dung-alumium-gia-re-tphcm-sai-gon-300x229.jpg 300w"
                                                sizes="(max-width: 600px) 100vw, 600px"></a></p>

                                <p style="text-align: center;">Hình 3- <em>Thi công mặt dựng aluminium</em> cho trung
                                    tâm anh ngữ Năng Khiếu Việt</p>

                                <p>-Nhờ sự tận tụy với công việc cộng với sự tin tưởng của quí khách hàng,
                                    chúng tôi luôn lắng nghe, học tập và thay đổi không ngừng nhằm mang đến những sản
                                    phẩm tốt nhất cho khách hàng.</p>

                                <p>Bề ngoài của một trụ sở văn phòng rất quan trọng nó quyết định rất lớn
                                    đến khả năng thành công hay thất bại của một doanh nghiệp Vì vậy chúng
                                    tôi sẽ giúp làm nổi bật, khẳng định quy mô đẳng cấp của các bạn.<br>
                                    Nào hay liên hệ với chúng tôi để được tư vấn và thiết kế miễn phí:</p>

                                <p><strong><span
                                                style="color: #ff0000;">BẢNG GIÁ THI CÔNG MẶT DỰNG ALUMINIMUM</span></strong>
                                </p>

                                <p><em> Ốp Mặt Dựng Nhôm Alu: Alu PVDF hoặc PE, khung sắt chịu lực (13*26, Vuông 20-30,
                                        sơn chống rỉ) gắn giáp mí hoặc phay rong bắn silicone trang trí. Phần chữ tùy
                                        thuộc yêu cầu. Thi công gắn tại các Đại lý, nhà phân phối, công ty, Văn
                                        Phòng.</em><br>
                                    <em>Giá loại PE, dày 3.0mm, độ nhôm 0.1mm : <strong>550.000
                                            VND/M2.</strong></em><br>
                                    <em>Giá Loại PVDF dày 3.0-4.0mm độ nhôm 0.20mm : <strong>800.000 VND/M2</strong></em>
                                </p>

                                <p>&nbsp;</p>

                                <p>&nbsp;</p>

                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{--footer--}}
@section('qc_master_footer')
    @include('components.footer.footer')
@endsection