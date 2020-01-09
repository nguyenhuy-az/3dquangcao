<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 */
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$hFunction = new Hfunction();
$dataStaffLogin = $modelStaff->loginStaffInfo();
$dataPunishType = $modelPunishType->getInfo();
?>
@extends('ad3d.system.punish-content.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed brown;">
            <h3>NỘI DUNG PHẠT</h3>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <form id="frmAdd" class="frmAdd" role="form" method="post"
                  action="{!! route('qc.ad3d.system.punish-content.add.post') !!}">
                <div class="row">
                    <div class="qc-padding-top-20 col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        @if (Session::has('notifyAdd'))
                            <div class="form-group text-center qc-color-red">
                                {!! Session::get('notifyAdd') !!}
                                <?php
                                Session::forget('notifyAdd');
                                ?>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group qc-padding-none">
                            <label>
                                Lĩnh vực phạt:
                                <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                            </label>
                            <select class="cbPunishType form-control" name="cbPunishType">
                                <option value="">Chọn lĩnh vực phạt</option>
                                @if(count($dataPunishType)> 0)
                                    @foreach($dataPunishType as $punishType)
                                        <option value="{!! $punishType->typeId() !!}">
                                            {!! $punishType->name() !!}
                                        </option>
                                    @endforeach
                                @endif

                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group qc-padding-none">
                            <label>
                                Mã phạt <em>(Không thay đổi)</em>
                                <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                            </label>
                            <em class="qc-color-grey">Tên viết tắc</em>
                            <input type="text" class="form-control" name="txtPunishName" value="">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group qc-padding-none">
                            <label>
                                Lý do phạt:
                                <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                            </label>
                            <input type="text" class="form-control" name="txtName" value="">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group qc-padding-none">
                            <label>
                                Tiền phạt:
                                <i class="qc-color-red glyphicon glyphicon-star-empty"></i>
                            </label>
                            <input type="number" class="form-control" name="txtMoney"
                                   placeholder="Nhập số tiền">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group qc-padding-none">
                            <label>
                                Mô tả:
                            </label>
                            <input type="text" class="form-control" name="txtNote" value="">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="qc-padding-top-20 qc-padding-bot-20  qc-border-none col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <button type="button" class="qc_save btn btn-primary btn-sm">Lưu</button>
                        <button type="reset" class="btn btn-default btn-sm">Hủy</button>
                        <a href="{!! route('qc.ad3d.system.punish-content.get') !!}">
                            <button type="button" class="btn btn-default btn-sm">Đóng</button>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
