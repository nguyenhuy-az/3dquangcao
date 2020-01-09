<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataProductType
 */
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
?>
@extends('ad3d.work.work-allocation.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dotted brown;">
            <h4>PHÂN VIỆC</h4>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <form id="frmAd3dAdd" role="form" method="post" enctype="multipart/form-data"
                  action="{!! route('qc.ad3d.work.work-allocation.add.post') !!}">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        @if (Session::has('notifyAdd'))
                            <div class="form-group form-group-sm text-center qc-color-red">
                                {!! Session::get('notifyAdd') !!}
                                <?php
                                Session::forget('notifyAdd');
                                ?>
                            </div>
                        @endif
                    </div>
                </div>
                {{-- thông tin khách hàng --}}
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 5px 0 5px 0; ">
                            <select class="cbReceiveStaff" name="cbReceiveStaff">
                                <option value="">Chọn nhân viên</option>
                                @if(count($dataReceiveStaff) > 0)
                                    @foreach($dataReceiveStaff as $receiveStaff)
                                        <option value="{!! $receiveStaff->staffId() !!}">{!! $receiveStaff->fullName() !!}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>

                {{-- nội dung phân việc --}}
                <div class=" col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div id="qc_work_add_product_wrap" class="row">
                        @include('ad3d.work.work-allocation.add-product', compact('dataProduct'))
                    </div>
                </div>
                <div class="row">
                    <div class="qc-padding-top-20 qc-padding-bot-20  qc-border-none text-left col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <a class="qc_order_product_add qc-link-green"
                           data-href="{!! route('qc.ad3d.work.work-allocation.product.get') !!}">
                            <i class="glyphicon glyphicon-plus"></i>
                            Thêm việc
                        </a>
                    </div>
                </div>


                <div class="row">
                    <div class="qc-padding-top-20 qc-padding-bot-20  qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <button type="button" class="qc_save btn btn-primary btn-sm"> Đặt hàng</button>
                        <a class="btn btn-default btn-sm" type="button" href="{!! route('qc.ad3d.work.work-allocation.get') !!}">
                            Đóng
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
