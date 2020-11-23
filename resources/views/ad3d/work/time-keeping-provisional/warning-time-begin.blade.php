<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataCompany
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$timekeepingId = $dataTimekeepingProvisional->timekeepingProvisionalId();
# lay thong tin canh bao cham cong
$dataTimekeepingProvisionalWarning = $dataTimekeepingProvisional->timekeepingProvisionalWarningGetTimeBegin();
?>
@extends('ad3d.components.container.container-6')
@section('qc_ad3d_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <h3 style="color: red;">CẢNH BÁO GIỜ VÀO KHÔNG ĐÚNG</h3>
            </div>
            @if($hFunction->checkCount($dataTimekeepingProvisionalWarning))
                <?php
                $warningNote = $dataTimekeepingProvisionalWarning->note();
                $warningImage = $dataTimekeepingProvisionalWarning->image();
                ?>
                <div class="row">
                    <div class="text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <label style="color: blue">ĐÃ GỬI CẢNH BÁO</label>
                    </div>
                </div>
                @if(!$hFunction->checkEmpty($warningNote))
                    <div class="row">
                        <div class="text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <span style="color: blue;">{!! $warningNote !!}</span>
                        </div>
                    </div>
                @endif
                @if(!$hFunction->checkEmpty($warningImage))
                    <div class="row">
                        <div class=" text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <img style="width: 80%;" src="{!! $dataTimekeepingProvisionalWarning->pathFullImage($warningImage) !!}">
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="text-center form-group form-group-sm">
                            <button type="button" class="qc_ad3d_container_close btn btn-sm btn-primary">
                                ĐÓNG
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <form class="qc_frm_warming_time_begin_add form" name="qc_frm_warming_time_begin_add" role="form"
                          method="post"
                          enctype="multipart/form-data"
                          action="{!! route('qc.ad3d.work.time_keeping_provisional.warning_begin.post',$timekeepingId) !!}">
                        <div class="row">
                            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="font-weight: bold;">
                                <div class="qc_notify qc-font-size-16 form-group qc-color-red"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label>Nguyên nhân:</label>
                                <input class="form-control" type="text" name="txtWarningNote"
                                       value="Giờ vào thực tế KHÔNG ĐÚNG GIỜ BÁO">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label>Ảnh cảnh báo:</label>
                                <input type="file" class="txtWarningImage form-control" name="txtWarningImage">
                            </div>
                        </div>
                        <div class="row">
                            <div class="qc-padding-top-10 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="text-center form-group form-group-sm">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                    <button type="button" class="qc_save btn btn-sm btn-primary">GỬI CẢNH BÁO</button>
                                    <button type="reset" class="btn btn-sm btn-default">NHẬP LẠI</button>
                                    <button type="button" class="qc_ad3d_container_close btn btn-sm btn-default">
                                        ĐÓNG
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @endif

        </div>
    </div>
@endsection
