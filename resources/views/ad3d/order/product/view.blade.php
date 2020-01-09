<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
$hFunction = new Hfunction();
$dataWorkAllocation = $dataProduct->workAllocationInfoOfProduct();
?>
@extends('ad3d.components.container.container-8')
@section('qc_ad3d_container_content')
    <div class="qc-padding-top-20 qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
            <h3>CHI TIẾT SẢN PHẨM</h3>
            <em class="qc-color-red">
                @if(!$dataProduct->checkFinishStatus())
                    Đang xử lý
                @else
                    @if($dataProduct->checkCancelStatus())
                        Đã hủy
                    @else
                        Đã hoàn thành
                    @endif
                    - {!! $dataProduct->staff->fullName() !!}
                @endif
            </em>
        </div>

        {{-- chi tiết đơn hàng --}}
        <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="qc-padding-top-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <em>
                                <i class="qc-color-grey glyphicon glyphicon-arrow-right"></i>
                                Đơn hàng:
                            </em>
                            <b>{!! $dataProduct->order->name() !!}</b>
                        </div>
                        <div class="qc-padding-top-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <em>
                                <i class="qc-color-grey glyphicon glyphicon-arrow-right"></i>
                                Sản phẩm:
                            </em>
                            <b>{!! $dataProduct->productType->name() !!}</b>
                        </div>
                        <div class="qc-padding-top-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <em>
                                <i class="qc-color-grey glyphicon glyphicon-arrow-right"></i>
                                Kích thước:
                            </em>
                            <b>({!! $dataProduct->width() !!}x{!! $dataProduct->height() !!}
                                x {!! ($dataProduct->depth()==null)?0:$dataProduct->depth() !!})mm</b>
                        </div>
                        <div class="qc-padding-top-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <em>
                                <i class="qc-color-grey glyphicon glyphicon-arrow-right"></i>
                                Giá:
                            </em>
                            <b>{!! $hFunction->dotNumber($dataProduct->price()) !!}</b>
                        </div>
                        <div class="qc-padding-top-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <em>
                                <i class="qc-color-grey glyphicon glyphicon-arrow-right"></i>
                                Ngày nhận:
                            </em>
                            <b>{!! $dataProduct->order->receiveDate() !!}</b>
                        </div>
                        <div class="qc-padding-top-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <em>
                                <i class="qc-color-grey glyphicon glyphicon-arrow-right"></i>
                                Ngày giao:
                            </em>
                            <b>{!! $dataProduct->order->deliveryDate() !!}</b>
                        </div>
                    </div>
                    <div class="row">
                        <div class="qc-padding-top-5 col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <em>
                                <i class="qc-color-grey glyphicon glyphicon-arrow-right"></i>
                                Mô tả:
                            </em>
                            <b>
                                {!! $dataProduct->description() !!}
                            </b>
                        </div>
                    </div>
                </div>
            </div>

            @if(count($dataWorkAllocation) > 0)
                <div class="row" style="margin-top: 20px;">
                    <div class="qc-color-red col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 1px dotted grey;">
                        <i class="glyphicon glyphicon-user"></i>
                        <span>Danh sách thi công</span>
                    </div>
                    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        @foreach($dataWorkAllocation as $workAllocation)
                            <b> - {!! $workAllocation->receiveStaff->fullName() !!} </b>
                            <br/>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        <div class="row">
            <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <button type="button" class="qc_ad3d_container_close btn btn-primary">
                    Đóng
                </button>
            </div>
        </div>
    </div>

@endsection
