<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:32 PM
 *
 * modelStaff
 * dataAccess
 * dataTimekeeping
 * dateFilter
 *
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$dataStaffLogin = $modelStaff->loginStaffInfo();
$hrefIndex = route('qc.ad3d.store.store.tool.get');

$companyFilterId = $dataCompanyFilter->companyId();
?>
@extends('ad3d.store.store.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6"
                     style="padding-left: 0;padding-right: 0;">
                    <a class="qc-link-green-bold" href="{!! $hrefIndex !!}">
                        <i class="qc-font-size-20 glyphicon glyphicon-refresh"></i>
                    </a>
                    <label class="qc-font-size-20">DANH SÁCH DỤNG CỤ</label>
                </div>
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6"
                     style="padding-left: 0;padding-right: 0;">
                    <select class="cbCompanyFilter form-control" name="cbCompanyFilter"
                            data-href="{!! $hrefIndex !!}">
                        {{--@if($dataStaffLogin->checkRootManage())
                            <option value="">Tất cả</option>
                        @endif--}}
                        @if($hFunction->checkCount($dataCompany))
                            @foreach($dataCompany as $company)
                                @if($dataStaffLogin->checkRootManage())
                                    <option value="{!! $company->companyId() !!}"
                                            @if($companyFilterId == $company->companyId()) selected="selected" @endif >{!! $company->name() !!}</option>
                                @else
                                    @if($companyFilterId == $company->companyId())
                                        <option value="{!! $company->companyId() !!}">{!! $company->name() !!}</option>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="qc_ad3d_list_content row">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style="background-color: black;color: yellow;">
                            <th class="text-center" style="width: 20px;">STT</th>
                            <th>Tên</th>
                            <th>Loại</th>
                            <th class="text-center">Số lượng</th>
                            <th class="text-center">Đã giao</th>
                            <th class="text-center">Còn lại</th>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="padding: 0;">
                                <div class="input-group">
                                    <input type="text" class="textFilterName form-control" name="textFilterName"
                                           style="height: 30px;"
                                           placeholder="Tìm theo tên" value="{!! $nameFiler !!}">
                                      <span class="input-group-btn">
                                        <button class="btFilterName btn btn-sm btn-default" type="button"
                                                style="height: 30px;"
                                                data-href="{!! route('qc.ad3d.store.store.tool.get') !!}">Tìm
                                        </button>
                                      </span>
                                </div>
                            </td>
                            <td style="padding: 0;">
                                <select class="cbType form-control" name="cbType"
                                        data-href="{!! $hrefIndex !!}">
                                    <option value="null" @if($type == 'null') selected="selected" @endif>Tất cả</option>
                                    <option value="1" @if($type == 1) selected="selected" @endif>Dùng chung</option>
                                    <option value="2" @if($type == 2) selected="selected" @endif>Dùng cấp phát</option>
                                </select>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        @if($hFunction->checkCount($dataTool))
                            <?php
                            $perPage = $dataTool->perPage();
                            $currentPage = $dataTool->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataTool as $tool)
                                <?php
                                $toolId = $tool->toolId();
                                $toolName = $tool->name();
                                $totalTool = $dataCompanyFilter->totalTool($companyFilterId, $toolId);
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2) info @endif" data-object="{!! $toolId !!}">
                                    <td class="text-center">
                                        {!! $n_o +=1 !!}
                                    </td>
                                    <td>
                                        {!! $toolName !!}
                                    </td>
                                    <td>
                                        {!! $tool->getLabelType() !!}
                                    </td>
                                    <td class="qc-color-red text-center">
                                        <b>{!! $totalTool !!}</b>
                                    </td>
                                    <td class="text-center">
                                        {!! 0 !!}
                                    </td>
                                    <td class="qc-color-red text-center">
                                        {!! 0 !!}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center" colspan="6">
                                    {!! $hFunction->page($dataTool) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-center" colspan="6">
                                    Không có đữ liệu
                                </td>
                            </tr>
                        @endif

                        {{--cu--}}
                        @if($hFunction->checkCount($dataCompanyStore))
                            <?php
                            $perPage = $dataCompanyStore->perPage();
                            $currentPage = $dataCompanyStore->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataCompanyStore as $companyStore)
                                <?php
                                $storeId = $companyStore->storeId();
                                $updateDate = $companyStore->updateDate();
                                $amount = $companyStore->amount();
                                $toolAllocationDetailTotalAmount = $companyStore->toolAllocationDetailTotalAmount();
                                $amountInventoryInStore = $amount - $toolAllocationDetailTotalAmount;

                                $dataTool = $companyStore->tool;
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2) info @endif" data-object="{!! $storeId !!}">
                                    <td class="text-center">
                                        {!! $n_o +=1 !!}
                                    </td>
                                    <td>
                                        {!! $dataTool->name() !!}
                                    </td>
                                    <td>
                                        {!! $dataTool->getLabelType() !!}
                                    </td>
                                    <td class="qc-color-red text-center">
                                        {!! $companyStore->amount() !!}
                                    </td>
                                    <td class="text-center">
                                        {!! $toolAllocationDetailTotalAmount !!}
                                    </td>
                                    <td class="qc-color-red text-center">
                                        {!! $amountInventoryInStore !!}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center" colspan="6">
                                    {!! $hFunction->page($dataCompanyStore) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-center" colspan="6">
                                    Không có đữ liệu
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
