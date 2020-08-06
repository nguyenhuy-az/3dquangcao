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
if ($hFunction->checkCount($selectCompanyStaffWork)) {
    $selectStatus = true;
    $selectedCompanyStaffWorkId = $selectCompanyStaffWork->workId();
} else {
    $selectStatus = false;
    $selectedCompanyStaffWorkId = 0;
}
# chi giao khi co do nghe hop le
$saveStatus = false;
?>
@extends('work.store.tool.index')
@section('qc_work_store_tool_body')
    <div class="row qc_work_store_tool_add_list">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-8 col-lg-8">
            <div class="row">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <h4 style="color: red;">BÀN GIAO ĐỒ NGHỀ</h4>
                </div>
            </div>
            {{-- chi tiêt --}}
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <form id="frmWorkToolAllocationAddList" role="form" name="frmWorkToolAllocationAddList" method="post"
                      enctype="multipart/form-data"
                      action="{!! route('qc.work.store.tool.allocation_list.add.post') !!}">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr>
                                    <td colspan="4" style="padding:0;">
                                        <select class="cbCompanyStaffWork form-control" style="color: blue;" name="cbCompanyStaffWork"
                                                data-href="{!! route('qc.work.store.tool.allocation_list.add.get') !!}">
                                            @if($hFunction->checkCount($dataCompanyStaffWork))
                                                <option value="0"
                                                        @if($selectedCompanyStaffWorkId == 0) selected="selected" @endif>
                                                    Chọn người nhận
                                                </option>
                                                @foreach($dataCompanyStaffWork as $companyStaffWork)
                                                    <?php $companyStaffWorkId = $companyStaffWork->workId() ?>
                                                    <option value="{!! $companyStaffWorkId !!}"
                                                            @if($selectedCompanyStaffWorkId == $companyStaffWorkId) selected="selected" @endif>
                                                        {!! $companyStaffWork->staff->fullName() !!}
                                                    </option>
                                                @endforeach
                                            @else
                                                <option>Không có nhân viên</option>
                                            @endif
                                        </select>
                                    </td>
                                </tr>
                                @if($selectStatus)
                                    <tr style="background-color: black;color: yellow;">
                                        <th class="text-center" style="width: 20px;"></th>
                                        <th>Loại Dụng cụ</th>
                                        <th>Dụng cụ</th>
                                        <th class="text-center">Giao</th>
                                    </tr>
                                    @if($hFunction->checkCount($dataPrivateTool))
                                        <?php
                                        # bo do nghe  ban giao
                                        $dataToolAllocation = $selectCompanyStaffWork->toolAllocationActivityOfWork();
                                        ?>
                                        {{--đã tồn tại--}}
                                        @if($hFunction->checkCount($dataToolAllocation))
                                            <?php
                                            $toolAllocationId = $dataToolAllocation->allocationId();
                                            ?>
                                            @foreach($dataPrivateTool as $dataTool)
                                                <?php
                                                $n_o = (isset($n_o)) ? $n_o + 1 : 1;
                                                $toolId = $dataTool->toolId();
                                                $dataToolAllocationDetail = $dataToolAllocation->infoActivityOfToolAllocationAndTool($toolAllocationId, $toolId);
                                                # trang thai duoc giao
                                                $existAllocationStatus = false;
                                                if ($hFunction->checkCount($dataToolAllocationDetail)) {
                                                    $existAllocationStatus = true;
                                                } else {
                                                    $dataCompanyStoreToAllocation = $dataTool->getOneInfoCompanyStoreToAllocationOfCompany($toolId, $companyId);
                                                }
                                                ?>
                                                <tr class="@if($n_o%2) info @endif">
                                                    <td class="text-center" style="width:20px;">
                                                        {!! $n_o !!}
                                                    </td>
                                                    <td>
                                                        {!!  $dataTool->name() !!}
                                                    </td>
                                                    <td>
                                                        {{--loai cong cu da duoc giao--}}
                                                        @if($existAllocationStatus)
                                                            @foreach($dataToolAllocationDetail as $toolAllocationDetail)
                                                                <?php
                                                                $detailImage = $toolAllocationDetail->image();
                                                                $dataCompanyStore = $toolAllocationDetail->companyStore;
                                                                ?>
                                                                <div class="media">
                                                                    @if (!$hFunction->checkEmpty($detailImage))
                                                                        <a class="pull-left">
                                                                            <img class="media-object"
                                                                                 style="width: 70px; height: auto;"
                                                                                 src="{!! $toolAllocationDetail->pathSmallImage($detailImage) !!}">
                                                                        </a>
                                                                    @endif
                                                                    <div class="media-body" style="padding-left: 10px;">
                                                                        <h5 class="media-heading">{!! $dataCompanyStore->name() !!}</h5>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @else
                                                            {{--thong tin cong cu se giao--}}
                                                            @if($hFunction->checkCount($dataCompanyStoreToAllocation))
                                                                <?php
                                                                $storeId = $dataCompanyStoreToAllocation->storeId();
                                                                # lay thong tin giao sau cung cua do nghe
                                                                $dataLastToolAllocationDetail = $dataCompanyStoreToAllocation->toolAllocationDetailLastInfo();
                                                                ?>
                                                                <div class="media">
                                                                    @if($hFunction->checkCount($dataLastToolAllocationDetail))
                                                                        <?php
                                                                        $detailImage = $dataLastToolAllocationDetail->image();
                                                                        $dataCompanyStore = $dataLastToolAllocationDetail->companyStore;
                                                                        ?>
                                                                        @if (!$hFunction->checkEmpty($detailImage))
                                                                            <a class="pull-left">
                                                                                <img class="media-object"
                                                                                     style="width: 70px; height: auto;"
                                                                                     src="{!! $dataLastToolAllocationDetail->pathSmallImage($detailImage) !!}">
                                                                            </a>
                                                                        @endif
                                                                    @else
                                                                        <?php
                                                                        # thong tin nhap kho
                                                                        $dataImport = $dataCompanyStoreToAllocation->import;
                                                                        $dataImportImage = $dataImport->getOneImportImage();
                                                                        ?>
                                                                        @if($hFunction->checkCount($dataImportImage))
                                                                            @if($hFunction->checkCount($dataImportImage))
                                                                                <a class="pull-left">
                                                                                    <img class="media-object"
                                                                                         style="width: 70px; height: auto;"
                                                                                         src="{!! $dataImportImage->pathSmallImage($dataImportImage->name()) !!}">
                                                                                </a>
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                    <div class="media-body" style="padding-left: 10px;">
                                                                        <h5 class="media-heading">{!! $dataCompanyStoreToAllocation->name() !!}</h5>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <b style="color: red;"
                                                                   title="Đã giao hết hoặc không có">
                                                                    X
                                                                </b>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if($existAllocationStatus)
                                                            <i class="glyphicon glyphicon-ok" style="color: green;"></i>
                                                            <em>Đã giao</em>
                                                        @else
                                                            {{--thong tin cong cu se giao--}}
                                                            @if($hFunction->checkCount($dataCompanyStoreToAllocation))
                                                                <?php $saveStatus = true; ?>
                                                                <input type="hidden" name="txtCompanyStore[]"
                                                                       value="{!! $dataCompanyStoreToAllocation->storeId()  !!}">
                                                                <em style="color: blue;">Chưa giao</em>
                                                            @else
                                                                <b style="color: red;"
                                                                   title="Đã giao hết hoặc không có">X</b>
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            @foreach($dataPrivateTool as $dataTool)
                                                <?php
                                                $n_o = (isset($n_o)) ? $n_o + 1 : 1;
                                                $toolId = $dataTool->toolId();
                                                # lay 1 loai dung cu trong kho de giao
                                                $dataCompanyStoreToAllocation = $dataTool->getOneInfoCompanyStoreToAllocationOfCompany($toolId, $companyId);
                                                ?>
                                                <tr class="@if($n_o%2) info @endif">
                                                    <td class="text-center" style="width:20px;">
                                                        {!! $n_o !!}
                                                    </td>
                                                    <td>
                                                        {!!  $dataTool->name() !!}
                                                    </td>
                                                    <td>
                                                        {{--thong tin cong cu se giao--}}
                                                        @if($hFunction->checkCount($dataCompanyStoreToAllocation))
                                                            <?php
                                                            $storeId = $dataCompanyStoreToAllocation->storeId();
                                                            # lay thong tin giao sau cung cua do nghe
                                                            $dataLastToolAllocationDetail = $dataCompanyStoreToAllocation->toolAllocationDetailLastInfo();
                                                            ?>
                                                            <div class="media">
                                                                @if($hFunction->checkCount($dataLastToolAllocationDetail))
                                                                    <?php
                                                                    $detailImage = $dataLastToolAllocationDetail->image();
                                                                    $dataCompanyStore = $dataLastToolAllocationDetail->companyStore;
                                                                    ?>
                                                                    @if (!$hFunction->checkEmpty($detailImage))
                                                                        <a class="pull-left">
                                                                            <img class="media-object"
                                                                                 style="width: 70px; height: auto;"
                                                                                 src="{!! $dataLastToolAllocationDetail->pathSmallImage($detailImage) !!}">
                                                                        </a>
                                                                    @endif
                                                                @else
                                                                    <?php
                                                                    # thong tin nhap kho
                                                                    $dataImport = $dataCompanyStoreToAllocation->import;
                                                                    $dataImportImage = $dataImport->getOneImportImage();
                                                                    ?>
                                                                    @if($hFunction->checkCount($dataImportImage))
                                                                        @if($hFunction->checkCount($dataImportImage))
                                                                            <a class="pull-left">
                                                                                <img class="media-object"
                                                                                     style="width: 70px; height: auto;"
                                                                                     src="{!! $dataImportImage->pathSmallImage($dataImportImage->name()) !!}">
                                                                            </a>
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                                <div class="media-body" style="padding-left: 10px;">
                                                                    <h5 class="media-heading">{!! $dataCompanyStoreToAllocation->name() !!}</h5>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <b style="color: red;"
                                                               title="Đã giao hết hoặc không có">X</b>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        {{--thong tin cong cu se giao--}}
                                                        @if($hFunction->checkCount($dataCompanyStoreToAllocation))
                                                            <?php $saveStatus = true; ?>
                                                            <input type="hidden" name="txtCompanyStore[]"
                                                                   value="{!! $dataCompanyStoreToAllocation->storeId()  !!}">
                                                            <em style="color: blue;">Chưa giao</em>
                                                        @else
                                                            <b style="color: red;"
                                                               title="Đã giao hết hoặc không có">X</b>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endif
                                @endif
                                @if($selectStatus)
                                    <tr>
                                        <td colspan="2"></td>
                                        <td style="padding: 0;">
                                            <a class="form-control btn btn-sm btn-default"
                                               onclick="qc_main.page_back();">
                                                Về trang trước
                                            </a>
                                        </td>
                                        <td class="text-center" style="padding: 0;">
                                            @if($saveStatus)
                                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                                                <a class="form-control qc_save btn btn-sm btn-primary">
                                                    GIAO
                                                </a>
                                            @else
                                                <span style="color: red;">KHÔNG CÓ ĐỒ NGHỀ ĐỂ GIAO</span>
                                            @endif
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td style="padding: 0;" colspan="4">
                                            <a class="form-control btn btn-sm btn-primary"
                                               onclick="qc_main.page_back();">
                                                Về trang trước
                                            </a>
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
