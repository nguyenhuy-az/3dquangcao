<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 5/15/2018
 * Time: 10:12 AM
 *
 * dataStaff
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$dataStaff = $modelStaff->loginStaffInfo();
$loginStaffId = $dataStaff->staffId();
$companyId = $dataStaff->companyId();
$hrefIndex = route('qc.work.tool.check_store.get');
$currentMonth = $hFunction->currentMonth();
?>
@extends('work.tool.check-store.index')
@section('qc_work_tool_check_store_body')
    <div class="row qc_work_tool_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-6 col-lg-6">
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form id="frmWorkToolCheckCompanyStore" role="form" name="frmWorkToolCheckCompanyStore" method="post"
                      enctype="multipart/form-data" action="{!! route('qc.work.tool.check_store.confirm.post') !!}">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr style="background-color: black; color: yellow;">
                                    <th class="text-center" style="width: 20px;">STT</th>
                                    <th>Đồ nghề</th>
                                    <th>
                                        Xác nhận
                                    </th>
                                </tr>
                                @if($hFunction->checkCount($dataCompanyStoreCheck))
                                    <?php $checkId = $dataCompanyStoreCheck->checkId() ?>
                                    {{--xac nhan dã kiem tra--}}
                                    @if($dataCompanyStoreCheck->checkConfirmStatus())
                                        <?php
                                        # lay thong tin bao cao kiem tra
                                        $dataCompanyStoreCheckReport = $dataCompanyStoreCheck->infoCompanyStoreCheckReport();
                                        ?>
                                        <tr>
                                            <td colspan="3">
                                                Ngày giao:
                                                <span style="color: red;">{!! date('d-m-Y', strtotime($dataCompanyStoreCheck->receiveDate())) !!}</span>
                                            </td>
                                        </tr>
                                        @if($hFunction->checkCount($dataCompanyStoreCheckReport))
                                            @foreach($dataCompanyStoreCheckReport as $companyStoreCheckReport)
                                                <?php
                                                $no = (isset($no)) ? $no + 1 : 1;
                                                $useStatus = $companyStoreCheckReport->useStatus();
                                                ?>
                                                <tr>
                                                    <td>
                                                        {!! $no !!}
                                                    </td>
                                                    <td>
                                                        {!! $companyStoreCheckReport->companyStore->name() !!}
                                                    </td>
                                                    <td style="padding: 0;">
                                                        <input type="text" class="form-control" readonly
                                                               value="{!! $companyStoreCheckReport->labelUseStatus() !!}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3">
                                                    <span style="color: red;">Không có thông tin báo cáo</span>
                                                </td>
                                            </tr>
                                        @endif
                                    @else
                                        <tr>
                                            <td colspan="3">
                                                <b style="background-color: red; color: white; padding: 5px;">KIỂM TRA
                                                    VÀ XÁC NHẬN KHÔNG ĐÚNG SẼ BỊ PHẠT
                                                    THEO NỘI QUY</b> <br/><br/>
                                                <span style="color:deeppink;">Nếu không xác nhận, cuối ngày HỆ THỐNG TỰ XÁC NHẬN ĐỦ và Sẽ bị phạt nếu hôm sau bị báo mất</span>
                                            </td>
                                        </tr>
                                        @if($hFunction->checkCount($dataCompanyStore))
                                            @foreach($dataCompanyStore as $companyStore)
                                                <?php
                                                $storeId = $companyStore->storeId();
                                                ?>
                                                <tr>
                                                    <td class="text-center" style="padding: 0;">
                                                        <div class="form-group" style="margin: 0;">
                                                            <input type="checkbox" class="form-control" disabled
                                                                   style="margin: 0;"
                                                                   checked="checked">
                                                            <input type="hidden" class="form-control"
                                                                   name="txtCompanyStore[]" style="margin: 0;"
                                                                   value="{!! $storeId !!}">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        {!! $companyStore->name() !!}
                                                    </td>
                                                    <td style="padding: 0;">
                                                        <select name="cbUseStatus_{!! $storeId !!}"
                                                                class="form-control">
                                                            <option value="1">Có - Dùng được</option>
                                                            <option value="2">Có - Không dùng được</option>
                                                            <option value="3">Không có</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="2" style="background-color: whitesmoke;"></td>
                                                <td class="text-center">
                                                    <b style="color: red; font-size: 16px;">
                                                        SAU KHI XÁC NHẬN SẼ KHÔNG ĐƯỢC THAY ĐỔI
                                                    </b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="background-color: whitesmoke;"></td>
                                                <td style="padding: 0;">
                                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                                    <input type="hidden" name="txtCompanyCheck"
                                                           value="{!! $checkId !!}">
                                                    <button class="qc_save btn btn-primary" type="button"
                                                            style="width: 100%;">
                                                        XÁC NHẬN ĐÚNG
                                                    </button>
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td colspan="3">
                                                <span style="color: red;">
                                                    Chưa có đồ nghề để kiểm tra
                                                </span>
                                                </td>
                                            </tr>
                                        @endif
                                    @endif
                                @else
                                    <tr>
                                        <td colspan="3">
                                            <span style="color: red;">Không có thông tin kiểm tra</span>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
