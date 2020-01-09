<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:32 PM
 */
/*
 *$dataCompany
 */
$hFunction = new Hfunction();
$dataStaffLogin = $modelStaff->loginStaffInfo();
?>
@extends('ad3d.system.payment-type.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12"
             style="margin-bottom: 10px; padding-top: 10px; padding-bottom: 10px; border-bottom: 2px dashed brown;">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <i class="qc-font-size-20 glyphicon glyphicon-list-alt"></i>
                    <label class="qc-font-size-20">LĨNH VỰC CHI</label>
                </div>
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 2px 0 2px 0; ">
                    <form name="" action="">
                        <div class="row">
                            <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <a class="btn btn-primary btn-sm"
                                   href="{!! route('qc.ad3d.system.payment-type.add.get') !!}">
                                    <i class="glyphicon glyphicon-plus"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="qc_ad3d_list_content qc-ad3d-table-container row"
                 data-href-view="{!! route('qc.ad3d.system.payment-type.view.get') !!}"
                 data-href-edit="{!! route('qc.ad3d.system.payment-type.edit.get') !!}">


                @if(count($dataPaymentType) > 0)
                    <?php
                    $perPage = $dataPaymentType->perPage();
                    $currentPage = $dataPaymentType->currentPage();
                    $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                    ?>
                    @foreach($dataPaymentType as $paymentType)
                        <?php
                        $typeId = $paymentType->typeId();
                        ?>
                        <div class="qc_ad3d_list_object qc-ad3d-list-object row" data-object="{!! $typeId !!}">
                            <div class="text-left col-xs-12 col-sm-12 col-md-8 col-lg-8"
                                 style="padding-top:5px; padding-bottom: 5px; ">
                                <b>{!! $n_o += 1 !!}).</b> {!! $paymentType->name() !!}
                            </div>
                            <div class="text-right col-xs-12 col-sm-12 col-md-4 col-lg-4"
                                 style="padding-top:5px; padding-bottom: 5px; ">
                                <a class="qc_view qc-link-green btn btn-default btn-sm" href="#">
                                    Chi tiết
                                </a>
                                <a class="qc_edit qc-link-green btn btn-default btn-sm" href="#">Sửa</a>
                                <a class="qc_delete qc-link-green btn btn-default btn-sm" href="#">Xóa</a>
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>
            <div class="row">
                <div class="text-center col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    {!! $hFunction->page($dataPaymentType) !!}
                </div>
            </div>
        </div>
    </div>
@endsection
