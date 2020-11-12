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
    <div id="qc_work_work_allocation_report" class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <div class="qc_ad3d_list_content qc-order-order-object row">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <tr style=" background-color: black; color: yellow;">
                        <th class="text-center" style="width: 20px;">STT</th>
                        <th style="width: 170px;">Thời gian</th>
                        <th>Nhân viên</th>
                        <th>Sản phẩm</th>
                        <th>Công việc</th>
                        <th>Ảnh báo cáo</th>
                    </tr>
                    <tr>
                        <td class="text-center"></td>
                        <td style="padding: 0;">
                            <select class="cbDayFilter col-sx-3 col-sm-3 col-md-3 col-lg-3"
                                    style="height: 34px; padding: 0;" data-href="{!! $indexHref !!}">
                                <option value="100" @if((int)$dayFilter == 100) selected="selected" @endif >
                                    Tất cả
                                </option>
                                @for($i =1;$i<= 31; $i++)
                                    <option value="{!! $i !!}"
                                            @if((int)$dayFilter == $i) selected="selected" @endif >{!! $i !!}</option>
                                @endfor
                            </select>
                            <select class="cbMonthFilter col-sx-3 col-sm-3 col-md-3 col-lg-3"
                                    style="height: 34px; padding: 0;" data-href="{!! $indexHref !!}">
                                <option value="100" @if((int)$monthFilter == 100) selected="selected" @endif >
                                    Tất cả
                                </option>
                                @for($i =1;$i<= 12; $i++)
                                    <option value="{!! $i !!}"
                                            @if((int)$monthFilter == $i) selected="selected" @endif>{!! $i !!}</option>
                                @endfor
                            </select>
                            <select class="cbYearFilter col-sx-6 col-sm-6 col-md-6 col-lg-6"
                                    style="height: 34px; padding: 0;" data-href="{!! $indexHref !!}">
                                <option value="100" @if((int)$yearFilter == 100) selected="selected" @endif >
                                    Tất cả
                                </option>
                                @for($i =2017;$i<= 2050; $i++)
                                    <option value="{!! $i !!}" @if($yearFilter == $i) selected="selected" @endif>
                                        {!! $i !!}
                                    </option>
                                @endfor
                            </select>
                        </td>
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
                        <td></td>
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
                            $reportDay = (int)date('d', strtotime($reportDate));
                            $content = $workAllocationReport->content();
                            $workAllocation = $workAllocationReport->workAllocation;
                            $workAllocationNote = $workAllocation->noted();
                            # lay anh bao cao truc tiep tren phan viec
                            $dataWorkAllocationReportImage = $workAllocationReport->workAllocationReportImageInfo();
                            # anh bao cao thong qua bao gio ra
                            $dataTimekeepingProvisionalImage = $workAllocationReport->timekeepingProvisionalImageInfo();
                            # thong tin nv bao cao
                            $dataReceiveStaff = $workAllocation->receiveStaff;
                            ?>
                            <tr class="qc_ad3d_list_object @if($n_o%2) info @endif" data-object="{!! $reportId !!}">
                                <td class="text-center">
                                    {!! $n_o += 1 !!}
                                </td>
                                <td>
                                    <span style="color: red;">{!! $hFunction->convertDateDMYFromDatetime($reportDate) !!}</span>
                                    <span class="qc-font-bold">{!! date('H:i', strtotime($reportDate))!!}</span>
                                    <br/>
                                    <em style="color: grey;">- {!! $content !!}</em>
                                </td>
                                <td style="padding: 3px;">
                                    <div class="media">
                                        <a class="pull-left" href="#">
                                            <img class="media-object"
                                                 style="background-color: white;width: 40px;height: 40px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                                 src="{!! $dataReceiveStaff->pathAvatar($dataReceiveStaff->image()) !!}">
                                        </a>

                                        <div class="media-body">
                                            <h5 class="media-heading">{!! $dataReceiveStaff->fullName() !!}</h5>
                                            @if($workAllocation->checkRoleMain())
                                                <em class="qc-color-red"> - Làm chính</em>
                                            @else
                                                <em style="color: grey;"> - Làm phụ</em>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <label style="color: brown;">
                                        {!! $workAllocation->product->productType->name() !!}
                                    </label>
                                    <br/>
                                    <em style="color: grey;">ĐH:</em>
                                    <span>{!! $workAllocation->product->order->name() !!}</span>
                                </td>
                                <td>
                                    @if($hFunction->checkEmpty($workAllocationNote))
                                        <em>Làm theo chỉ dẫn</em>
                                    @else
                                        {!! $workAllocation->noted() !!}
                                    @endif
                                </td>
                                <td style="padding: 3px;">
                                    @if($hFunction->checkCount($dataWorkAllocationReportImage))
                                        @foreach($dataWorkAllocationReportImage as $workAllocationReportImage)
                                            <a class="qc_view_image qc-link"
                                               data-href="{!! route('qc.ad3d.work.work_allocation_report.image.view',$workAllocationReportImage->imageId()) !!}">
                                                <img style="margin-right: 5px; width: 70px; height: 70px; border: 1px solid #d7d7d7;"
                                                     src="{!! $workAllocationReportImage->pathSmallImage($workAllocationReportImage->name()) !!}">
                                            </a>
                                        @endforeach
                                    @endif
                                        @if($hFunction->checkCount($dataTimekeepingProvisionalImage))
                                            @foreach($dataTimekeepingProvisionalImage as $timekeepingProvisionalImage)
                                                <a class="qc-link" data-href="#">
                                                    <img style="margin-right: 5px; width: 70px; height: 70px; border: 1px solid #d7d7d7;"
                                                         src="{!! $timekeepingProvisionalImage->pathSmallImage($timekeepingProvisionalImage->name()) !!}">
                                                </a>
                                            @endforeach
                                        @endif
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="text-center qc-padding-top-5 qc-padding-bot-5" colspan="8">
                                {!! $hFunction->page($dataWorkAllocationReport) !!}
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td class="qc-padding-top-5 qc-padding-bot-5 text-center" colspan="8">
                                <em class="qc-color-red">Không có báo cáo</em>
                            </td>
                        </tr>
                    @endif
                </table>
            </div>

        </div>
    </div>
@endsection()
