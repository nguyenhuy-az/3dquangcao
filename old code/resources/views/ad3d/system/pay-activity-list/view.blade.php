<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
/*
 *dataCompany
 */
?>
@extends('ad3d.components.container.container-8')
@section('qc_ad3d_container_content')
    <div class="qc-padding-top-20 qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <h4>THÔNG TIN CHI TIẾT</h4>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <tr style="background-color: whitesmoke;">
                        <th class="text-center qc-padding-none">Tên</th>
                        <th class="qc-padding-none">Mô tả</th>
                        <th class="text-center qc-padding-none">Loại hình chi</th>
                    </tr>
                    <tr>
                        <td class="text-center qc-padding-none">
                            {!! $dataPayActivityList->name() !!}
                        </td>
                        <td class="qc-padding-none">
                            {!! $dataPayActivityList->description() !!}
                        </td>
                        <td class="text-center qc-padding-none">
                            {!! $dataPayActivityList->typeLabel() !!}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class=" text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <button type="button" class="qc_ad3d_container_close btn btn-primary">
                Đóng
            </button>
        </div>
    </div>
@endsection
