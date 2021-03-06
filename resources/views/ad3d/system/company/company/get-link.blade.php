<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataCompany
 */
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
?>
@extends('ad3d.components.container.container-10')
@section('qc_ad3d_container_content')
    <div class="qc-padding-bot-30 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
                <h3 style="color: red;">LINK TUYỂN DỤNG</h3>
            </div>
            <div class="qc-padding-top-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form class="frmAd3dGetLink" name="frmAd3dGetLink" role="form" method="post" action="#">
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group form-group-sm">
                            <label style="background-color: red;color: yellow;">
                                Link gửi cho người ứng tuyển
                            </label>
                            <input id="txtRecruitmentLink" type="text" name="txtLink" class="form-control" readonly
                                   value="{!! route('qc.work.recruitment.login.get',$dataCompany->companyId()) !!}">
                        </div>
                    </div>
                    <div class="text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        {{--<input type="hidden" name="_token" value="{!! csrf_token() !!}">--}}
                        <button type="button" class="qc_copy btn btn-sm btn-primary">
                            COPY LINK
                        </button>
                        <button type="button" class="qc_ad3d_container_close btn btn-sm btn-default">Đóng</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
