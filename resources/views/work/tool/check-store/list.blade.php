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
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-10 col-lg-10">
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form id="frmWorkToolCheckCompanyStore" role="form" name="frmWorkToolCheckCompanyStore" method="post"
                      enctype="multipart/form-data" action="{!! route('qc.work.tool.check_store.confirm.post') !!}">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr>
                                    <td style="padding: 0" colspan="5">
                                        <select class="cbCompanyStoreCheckFilter form-control"
                                                name="cbCompanyStoreCheckFilter" style="color: red;"
                                                data-href="{!! $hrefIndex !!}">
                                            @if($hFunction->checkCount($dataCompanyStoreCheck))
                                                @foreach($dataCompanyStoreCheck as $companyStoreCheck)
                                                    <?php
                                                    $checkId = $companyStoreCheck->checkId();
                                                    ?>
                                                    <option value="{!! $checkId !!}"
                                                            @if($checkIdFilter == $checkId) selected="selected" @endif>
                                                        {!! date('d-m-Y', strtotime($companyStoreCheck->receiveDate())) !!}
                                                    </option>
                                                @endforeach
                                            @else
                                                <option value="0">
                                                    Chưa được giao kiểm tra
                                                </option>
                                            @endif
                                        </select>
                                    </td>
                                </tr>
                                <tr style="background-color: black; color: yellow;">
                                    <th class="text-center" style="width: 20px;">STT</th>
                                    <th>Đồ nghề</th>
                                    <th>Ảnh bàn giao</th>
                                    <th>Ảnh báo cáo</th>
                                    <th>
                                        Xác nhận
                                    </th>
                                </tr>
                                @if($hFunction->checkCount($dataCompanyStoreCheckSelected))
                                    <?php
                                    $checkId = $dataCompanyStoreCheckSelected->checkId()
                                    ?>
                                    {{--xac nhan dã kiem tra--}}
                                    @if($dataCompanyStoreCheckSelected->checkConfirmStatus())
                                        <?php
                                        # lay thong tin bao cao kiem tra
                                        $dataCompanyStoreCheckReport = $dataCompanyStoreCheckSelected->infoCompanyStoreCheckReport();
                                        ?>
                                        @if($hFunction->checkCount($dataCompanyStoreCheckReport))
                                            @foreach($dataCompanyStoreCheckReport as $companyStoreCheckReport)
                                                <?php
                                                $no = (isset($no)) ? $no + 1 : 1;
                                                $reportId = $companyStoreCheckReport->reportId();
                                                $storeId = $companyStoreCheckReport->storeId();
                                                $useStatus = $companyStoreCheckReport->useStatus();
                                                $reportImage = $companyStoreCheckReport->reportImage();
                                                $companyStore = $companyStoreCheckReport->companyStore;
                                                $dataCompanyStoreCheckReportHasImage = $companyStoreCheckReport->lastInfoHasImageOfPreviousReportAndCompanyStore($reportId, $storeId);
                                                ?>
                                                <tr @if($no%2) class="info" @endif>
                                                    <td>
                                                        {!! $no !!}
                                                    </td>
                                                    <td>
                                                        {!! $companyStoreCheckReport->companyStore->name() !!}
                                                    </td>
                                                    <td>
                                                        @if($hFunction->checkCount($dataCompanyStoreCheckReportHasImage))
                                                            {{--lay anh sau cung cua bao cao truoc --}}
                                                            <div style="position: relative; float: left; width: 70px; max-height: 70px; background-color: grey;">
                                                                <a class="qc_view_image_get qc-link"
                                                                   data-href="{!! route('qc.work.tool.check_store.report_image.get',$dataCompanyStoreCheckReportHasImage->reportId()) !!}">
                                                                    <img style="max-width: 100%; max-height: 100%;"
                                                                         src="{!! $dataCompanyStoreCheckReportHasImage->pathFullImage($dataCompanyStoreCheckReportHasImage->reportImage()) !!}">
                                                                </a>
                                                            </div>
                                                        @else
                                                            {{--khong co hinh anh mơi - lay anh nhap kho --}}
                                                            <?php
                                                            $dataImport = $companyStore->import;
                                                            $dataImportImage = $dataImport->importImageInfoOfImport();
                                                            ?>
                                                            @if($hFunction->checkCount($dataImportImage))
                                                                @foreach($dataImportImage as $importImage)
                                                                    <div style="position: relative; float: left; width: 70px; max-height: 70px; background-color: grey;">
                                                                        <a class="qc_view_image_get qc-link"
                                                                           data-href="{!! route('qc.work.tool.check_store.import_image.get',$importImage->imageId()) !!}">
                                                                            <img style="max-width: 100%; max-height: 100%;"
                                                                                 src="{!! $importImage->pathFullImage($importImage->name()) !!}">
                                                                        </a>
                                                                    </div>
                                                                @endforeach
                                                            @endif

                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(!$hFunction->checkEmpty($reportImage))
                                                            <div style="position: relative; float: left; width: 70px; max-height: 70px; background-color: grey;">
                                                                <a class="qc_view_image_get qc-link"
                                                                   data-href="{!! route('qc.work.tool.check_store.report_image.get',$reportId) !!}">
                                                                    <img style="max-width: 100%; max-height: 100%;"
                                                                         src="{!! $companyStoreCheckReport->pathFullImage($reportImage) !!}">
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span>{!! $companyStoreCheckReport->labelUseStatus() !!}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="5">
                                                    <span style="color: red;">Không có thông tin báo cáo</span>
                                                </td>
                                            </tr>
                                        @endif
                                    @else
                                        <tr>
                                            <td colspan="5">
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
                                                $dataCompanyStoreCheckReportLastHasImage = $companyStore->companyStoreCheckReportLastInfoHasImage();
                                                ?>
                                                <tr>
                                                    <td class="text-center" style="padding: 0;">
                                                        <div class="form-group" style="margin: 0;">
                                                            <input type="checkbox" class="form-control" disabled
                                                                   style="margin: 0;" checked="checked">
                                                            <input type="hidden" class="form-control"
                                                                   name="txtCompanyStore[]" style="margin: 0;"
                                                                   value="{!! $storeId !!}">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        {!! $companyStore->name() !!}
                                                    </td>
                                                    <td>
                                                        @if($hFunction->checkCount($dataCompanyStoreCheckReportLastHasImage))
                                                            {{--lay anh sau cung cua bao cao--}}
                                                            <div style="position: relative; float: left; width: 70px; max-height: 70px; background-color: grey;">
                                                                <a class="qc_view_image_get qc-link"
                                                                   data-href="{!! route('qc.work.tool.check_store.report_image.get',$dataCompanyStoreCheckReportLastHasImage->reportId()) !!}">
                                                                    <img style="max-width: 100%; max-height: 100%;"
                                                                         src="{!! $dataCompanyStoreCheckReportLastHasImage->pathFullImage($dataCompanyStoreCheckReportLastHasImage->reportImage()) !!}">
                                                                </a>
                                                            </div>
                                                        @else
                                                            {{--khong co hinh anh mơi - lay anh nhap kho --}}
                                                            <?php
                                                            $dataImport = $companyStore->import;
                                                            $dataImportImage = $dataImport->importImageInfoOfImport();
                                                            ?>
                                                            @if($hFunction->checkCount($dataImportImage))
                                                                @foreach($dataImportImage as $importImage)
                                                                    <div style="position: relative; float: left; width: 70px; max-height: 70px; background-color: grey;">
                                                                        <a class="qc_view_image_get qc-link"
                                                                           data-href="{!! route('qc.work.tool.check_store.import_image.get',$importImage->imageId()) !!}">
                                                                            <img style="max-width: 100%; max-height: 100%;"
                                                                                 src="{!! $importImage->pathFullImage($importImage->name()) !!}">
                                                                        </a>
                                                                    </div>
                                                                @endforeach
                                                            @endif

                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="form-group" style="margin: 0;">
                                                            <input id="txtReportImage_{!! $storeId !!}" type="file"
                                                                   name="txtReportImage_{!! $storeId !!}">
                                                        </div>
                                                    </td>
                                                    <td style="padding: 0;">
                                                        <select name="cbUseStatus_{!! $storeId !!}"
                                                                class="form-control">
                                                            <option value="1">Có</option>
                                                            <option value="2">Bị hư</option>
                                                            <option value="3">Mất</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="3" style="background-color: whitesmoke;"></td>
                                                <td class="text-center" colspan="2">
                                                    <b style="color: red; font-size: 16px;">
                                                        SAU KHI XÁC NHẬN SẼ KHÔNG ĐƯỢC THAY ĐỔI
                                                    </b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" style="background-color: whitesmoke;"></td>
                                                <td style="padding: 0;">
                                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                                    <input type="hidden" name="txtCompanyCheck"
                                                           value="{!! $checkId !!}">
                                                    <button class="qc_save btn btn-primary" type="button"
                                                            style="width: 100%;">
                                                        XÁC NHẬN ĐÚNG
                                                    </button>
                                                </td>
                                                <td style="padding: 0;">
                                                    <button class="btn btn-default" type="reset" style="width: 100%;">
                                                        NHẬP LẠI
                                                    </button>
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td colspan="5">
                                                    <span style="color: red;">
                                                        Chưa có đồ nghề để kiểm tra
                                                    </span>
                                                </td>
                                            </tr>
                                        @endif
                                    @endif
                                @else
                                    <tr>
                                        <td colspan="5">
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
