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
$dataCompanyLogin = $modelStaff->companyLogin();
?>
@extends('ad3d.system.rules.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="qc_ad3d_list_content row">
                @if($hFunction->checkCount($dataRules))
                    <?php
                    $rulesId = $dataRules->rulesId();
                    ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <a class="qc_edit qc-link-green btn btn-default btn-sm"
                               data-href="{!! route('qc.ad3d.system.rules.edit.get',$rulesId) !!}">
                                SỬA
                            </a>
                            <a class="qc_delete qc-link-green btn btn-default btn-sm"
                               data-href="{!! route('qc.ad3d.system.rules.del',$rulesId) !!}">
                                XÓA
                            </a>
                        </div>
                        <div class="panel-body">
                            <h3 style="color: red;">NỘI QUY CTY - {!! $dataRules->company->name() !!}</h3>
                            <h4>{!! $dataRules->title() !!}</h4>

                            <p>
                                {!! $dataRules->content() !!}
                            </p>
                        </div>
                    </div>
                @else
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <p>Chi nhánh chưa có nội quy</p>
                        <a class="qc-link-green-bold"
                           href="{!! route('qc.ad3d.system.rules.add.get') !!}">
                            <i class="glyphicon glyphicon-plus"></i>
                            THÊM NỘI QUY MƠI
                        </a>
                        @if(!$dataCompanyLogin->checkParent())
                            <br/><br/>
                            <a class="qc-link-bold"
                               href="{!! route('qc.ad3d.system.rules.add.get', $dataCompanyLogin->parentId()) !!}">
                                <i class="glyphicon glyphicon-plus"></i>
                                SAO CHÉP TỪ CÔNG TY MẸ
                            </a>
                        @endif
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
