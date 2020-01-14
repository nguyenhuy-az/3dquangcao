<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:32 PM
 *
 * dataOrder
 */
$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$dataStaffLogin = $modelStaff->loginStaffInfo();
$indexHref = route('qc.ad3d.work.work_allocation_report.get');
$subObject = isset($dataAccess['subObject']) ? $dataAccess['subObject'] : 'workAllocationReport';
?>
@extends('ad3d.work.work-allocation.report.index')
@section('qc_ad3d_index_content')
    <div class="row qc-margin-bot-10">
        @include('ad3d.work.work-allocation.menu',compact('subObject'))
    </div>
    <div id="qc_work_work_allocation_report" class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 2px 0 2px 0;">
                    <form name="" action="">
                        <div class="row">
                            <div class="text-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <select class="cbDayFilter" style="height: 25px;" data-href="{!! $indexHref !!}">
                                    <option value="100" @if((int)$dayFilter == 100) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($i =1;$i<= 31; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$dayFilter == $i) selected="selected" @endif >{!! $i !!}</option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select class="cbMonthFilter" style="height: 25px;" data-href="{!! $indexHref !!}">
                                    <option value="100" @if((int)$monthFilter == 100) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($i =1;$i<= 12; $i++)
                                        <option value="{!! $i !!}"
                                                @if((int)$monthFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                    @endfor
                                </select>
                                <span>/</span>
                                <select class="cbYearFilter" style="height: 25px;" data-href="{!! $indexHref !!}">
                                    <option value="100" @if((int)$yearFilter == 100) selected="selected" @endif >
                                        Tất cả
                                    </option>
                                    @for($i =2017;$i<= 2050; $i++)
                                        <option value="{!! $i !!}" @if($yearFilter == $i) selected="selected" @endif>
                                            {!! $i !!}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="qc_ad3d_list_content qc-order-order-object row">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr style=" background-color: whitesmoke;">
                            <th class="text-center" style="width: 20px;">STT</th>
                            <th>Nhân viên</th>
                            <th>Công việc</th>
                            <th>Sản phẩm</th>
                            <th class="text-center">Thời gian</th>
                            <th class="text-center">Hình ảnh</th>
                            <th class="text-center">Nội dung</th>
                        </tr>
                        <tr>
                            <td class="text-center">STT</td>
                            <td style="padding: 0;">
                                <div class="input-group">
                                    <input type="text" class="textFilterName form-control" name="textFilterName"
                                           placeholder="Tìm theo tên nhân viên" value="{!! $nameFiler !!}">
                                      <span class="input-group-btn">
                                            <button class="btFilterName btn btn-default" type="button"
                                                    data-href="{!! $indexHref !!}">
                                                <i class="glyphicon glyphicon-search"></i>
                                            </button>
                                      </span>
                                </div>
                            </td>
                            <td></td>
                            <td></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                        </tr>
                        @if($hFunction->checkCount($dataWorkAllocationReport))
                            <?php
                            $perPage = $dataWorkAllocationReport->perPage();
                            $currentPage = $dataWorkAllocationReport->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataWorkAllocationReport as $workAllocationReport)
                                <?php
                                $reportId = $workAllocationReport->reportId();
                                $reportDate = $workAllocationReport->reportDate();
                                $content = $workAllocationReport->content();
                                $workAllocation = $workAllocationReport->workAllocation;
                                $workAllocationNote = $workAllocation->noted();
                                $dataWorkAllocationReportImage = $workAllocationReport->workAllocationReportImageInfo();
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2) info @endif"  data-object="{!! $reportId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td>
                                        {!! $workAllocation->receiveStaff->fullName() !!}
                                    </td>
                                    <td>
                                        @if($hFunction->checkEmpty($workAllocationNote))
                                            <em>Làm theo chỉ dẫn</em>
                                        @else
                                            {!! $workAllocation->noted() !!}
                                        @endif
                                    </td>
                                    <td>
                                        {!! $workAllocation->product->productType->name() !!} <br/>
                                        <em class="qc-color-grey">{!! $workAllocation->product->order->name() !!}</em>
                                    </td>
                                    <td class="text-center">
                                        {!! $hFunction->convertDateDMYFromDatetime($reportDate) !!} &nbsp;
                                        {!! $hFunction->getTimeFromDate($reportDate) !!}
                                    </td>
                                    <td class="text-center">
                                        @if($hFunction->checkCount($dataWorkAllocationReportImage))
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                @foreach($dataWorkAllocationReportImage as $workAllocationReportImage)
                                                    <div style="position: relative; float: left; margin: 5px; width: 70px; height: 70px; border: 1px solid #d7d7d7;">
                                                        <a class="qc_view_image qc-link"
                                                           data-href="{!! route('qc.ad3d.work.work_allocation_report.image.view',$workAllocationReportImage->imageId()) !!}">
                                                            <img style="max-width: 100%; max-height: 100%;"
                                                                 src="{!! $workAllocationReportImage->pathSmallImage($workAllocationReportImage->name()) !!}">
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center qc-color-grey">
                                        {!! $content !!}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center qc-padding-top-5 qc-padding-bot-5" colspan="9">
                                    {!! $hFunction->page($dataWorkAllocationReport) !!}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="qc-padding-top-5 qc-padding-bot-5 text-center" colspan="9">
                                    <em class="qc-color-red">Không có báo cáo</em>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection()
