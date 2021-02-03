<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:34 PM
 *
 * dataStaff
 */

$hFunction = new Hfunction();
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$dataCompanyStaffWork = $dataWork->companyStaffWork;
$fromDate = $dataWork->fromDate();
$dateFilter = date('Y-m', strtotime($fromDate));
$companyStaffWorkId = $dataCompanyStaffWork->workId();
$dataStaff = $dataCompanyStaffWork->staff;
$statisticStaffId = $dataStaff->staffId();
$dataCompany = $dataCompanyStaffWork->company;

# tong thi cong san pham - tat ca
if ($constructionStatus == 'get-all') {
    $dataWorkAllocation = $modelStatistical->statisticGetReceiveWorkAllocation($statisticStaffId, $dateFilter);
    $title = 'TẤT CẢ CÔNG VIỆC ĐƯỢC GIAO';
} elseif ($constructionStatus == 'get-all-late') {
    $dataWorkAllocation = $modelStatistical->statisticGetWorkAllocationHasLate($statisticStaffId, $dateFilter);
    $title = 'TẤT CẢ CÔNG VIỆC BỊ TRỄ';
} elseif ($constructionStatus == 'get-all-finish') {
    $dataWorkAllocation = $modelStatistical->statisticGetWorkAllocationHasFinish($statisticStaffId, $dateFilter);
    $title = 'TẤT CẢ CÔNG VIỆC ĐÃ HOÀN THÀNH';
}elseif ($constructionStatus == 'get-finish-not-late') {
    $dataWorkAllocation = $modelStatistical->statisticGetWorkAllocationFinishNotLate($statisticStaffId, $dateFilter);
    $title = 'CÔNG VIỆC ĐÃ HOÀN THÀNH ĐÚNG HẸN';
}
elseif ($constructionStatus == 'get-finish-has-late') {
    $dataWorkAllocation = $modelStatistical->statisticGetWorkAllocationFinishHasLate($statisticStaffId, $dateFilter);
    $title = 'CÔNG VIỆC ĐÃ HOÀN THÀNH TRỄ HẸN';
}else {
    $dataWorkAllocation = null;
    $title = 'KHÔNG TÌM THẤY THÔNG TIN';
}
?>
@extends('work.staff.index')
@section('qc_work_staff_body')
    <div class="qc_work_staff_statistic_wrap qc-padding-bot-30 col-xs-12 col-sm-12 col-md-12 col-lg-12"
         @if($mobileStatus) style="padding: 0;" @endif>
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <a class="qc-link-white-bold btn btn-sm btn-primary" onclick="qc_main.page_back_go();">
                    <i class="glyphicon glyphicon-backward"></i> Về trang trước
                </a>
            </div>
        </div>
        <div class="row" style="margin-bottom: 10px;">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="media">
                    <a class="pull-left">
                        <img class="media-object"
                             style="background-color: white; width: 60px;height: 60px; border: 1px solid #d7d7d7;border-radius: 10px;"
                             src="{!! $dataStaff->pathAvatar($dataStaff->image()) !!}">
                    </a>

                    <div class="media-body">
                        <h5 class="media-heading">{!! $dataStaff->fullName() !!}</h5>
                        <em style="color: grey;">{!! $dataCompany->name() !!}</em>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <label style="color: blue; font-size: 1.5em;">
                    {!! $title !!}
                </label>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                @if($hFunction->checkCount($dataWorkAllocation))
                    @foreach($dataWorkAllocation as $workAllocation)
                        <?php
                        $allocationId = $workAllocation->allocationId();
                        $allocationDate = $workAllocation->allocationDate();
                        $receiveDeadline = $workAllocation->receiveDeadline();
                        $allocationNote = $workAllocation->noted();
                        $cancelStatus = $workAllocation->checkCancel();
                        //$dataWorkAllocationReport = $workAllocation->workAllocationReportInfo();
                        # bao cao tien do
                        $dataWorkAllocationReport = $workAllocation->workAllocationReportInfo($allocationId, 1);
                        $dataProduct = $workAllocation->product;
                        $productWidth = $dataProduct->width();
                        $productHeight = $dataProduct->height();
                        $productAmount = $dataProduct->amount();
                        $productDescription = $dataProduct->description();
                        # thiet ke san pham
                        $dataProductDesign = $dataProduct->productDesignInfoApplyActivity();
                        # thiet ke san pham thi cong
                        $dataProductDesignConstruction = $dataProduct->productDesignInfoConstructionHasApply();

                        # lay danh sach cung thi cong san pham lien quan khong bi huy
                        $dataWorkAllocationRelation = $dataProduct->workAllocationInfoNotCancelOfProduct();
                        # thong ket thuc phan viec
                        $dataWorkAllocationFinish = $workAllocation->workAllocationFinishInfo();
                        $n_o = (isset($n_o)) ? $n_o + 1 : 1;
                        ?>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="margin-bottom: 10px; padding-left: 0;">
                            <div class="table-responsive">
                                <table class="table table-bordered qc-margin-bot-none">
                                    <tr>
                                        <td class="text-center"
                                            style="width: 100px; padding: 0; @if($dataWorkAllocationFinish || $cancelStatus) background-color: pink; @endif">
                                            <span style="color: grey;">
                                                {!! date('d-m-Y ', strtotime($allocationDate)) !!}
                                            </span>
                                            <span class="qc-font-bold">
                                                {!! date('H:i', strtotime($allocationDate)) !!}
                                            </span>
                                            <br/>
                                             <span class="qc-font-bold" style="color: red;">
                                                {!! date('d-m-Y ', strtotime($receiveDeadline)) !!}
                                            </span>
                                            <span class="qc-font-bold">
                                                {!! date('H:i', strtotime($receiveDeadline)) !!}
                                            </span>
                                            @if($cancelStatus)
                                                <br/>
                                                <em style="color: blue;">Đã hủy</em>
                                            @else
                                                @if($hFunction->checkCount($dataWorkAllocationFinish))
                                                    <br/>
                                                    <em style="color: grey;">Đã kết thúc</em>
                                                    <br/>
                                                    <span class="qc-font-bold" style="color: blue;">
                                                            {!! date('d-m-Y ', strtotime($dataWorkAllocationFinish->finishDate())) !!}
                                                        </span>
                                                    <span class="qc-font-bold">
                                                            {!! date('H:i', strtotime($dataWorkAllocationFinish->finishDate())) !!}
                                                        </span>
                                                @endif
                                                @if($workAllocation->checkLate($allocationId))
                                                    <br/>
                                                    <span style="color: white; padding: 3px; background-color: red;">TRỄ</span>
                                                @endif
                                            @endif
                                            <br/>
                                            <em>
                                                - Thiết kế SP:
                                            </em>
                                            @if($hFunction->checkCount($dataProductDesign))
                                                <a class="qc-link qc_work_allocation_design_image_view"
                                                   data-href="{!! route('qc.work.work_allocation.work_allocation.design_image.view',$dataProductDesign->designId()) !!}">
                                                    <img style="width: 70px; height: auto; margin-right: 5px; border: 1px solid grey;"
                                                         title="Đang áp dụng"
                                                         src="{!! $dataProductDesign->pathSmallImage($dataProductDesign->image()) !!}">
                                                </a>
                                            @else
                                                <em class="qc-color-grey">Không có thiết kế</em>
                                            @endif
                                        </td>
                                        <td>
                                            <b style="font-size: 14px;">{!! $n_o !!}). </b>
                                            <b style="background-color: black; color: lime; padding: 3px; font-size: 14px;">
                                                {!! $workAllocation->product->productType->name() !!}
                                            </b>
                                            <br/>
                                            <em style="color:grey;">
                                                - ĐH: {!! $workAllocation->product->order->name() !!}
                                            </em>
                                            <br/>
                                            <em>- Ngang: </em>
                                            <span> {!! $productWidth !!} mm</span>
                                            <em>- Cao: </em>
                                            <span>{!! $productHeight !!} mm</span>
                                            <em>- Số lượng: </em>
                                            <span style="color: red;">{!! $productAmount !!}</span>
                                            <br/>
                                            <em style="color: blue;">
                                                - Mô tả SP:
                                            </em>
                                                <span style="color: red;">
                                                    @if($hFunction->checkEmpty($productDescription))
                                                        {!! $productDescription !!}
                                                    @else
                                                        Không có
                                                    @endif
                                                </span>
                                            <br/>
                                            <em>
                                                - Thiết kế Thi công:
                                            </em>
                                            @if($hFunction->checkCount($dataProductDesignConstruction))
                                                @foreach($dataProductDesignConstruction as $productDesignConstruction)
                                                    <a class="qc-link qc_work_allocation_design_image_view"
                                                       data-href="{!! route('qc.work.work_allocation.work_allocation.design_image.view',$productDesignConstruction->designId()) !!}">
                                                        <img style="width: 70px; height: auto; margin-right: 5px; border: 1px solid grey;"
                                                             title="Đang áp dụng"
                                                             src="{!! $productDesignConstruction->pathSmallImage($productDesignConstruction->image()) !!}">
                                                    </a>
                                                @endforeach
                                            @else
                                                <em class="qc-color-grey">Không có thiết kế</em>
                                            @endif
                                            <br/>
                                            <em>
                                                - Ghi chú:
                                            </em>
                                            @if(!empty($allocationNote))
                                                {!! $allocationNote !!}
                                            @else
                                                <span>Làm theo phân công</span>
                                            @endif
                                            <br/>
                                            <em style="color: blue;">- Nhân sự:</em>
                                            @if($hFunction->checkCount($dataWorkAllocationRelation))
                                                @foreach($dataWorkAllocationRelation as $workAllocationRelation)
                                                    <b>{!! $workAllocationRelation->receiveStaff->lastName() !!}
                                                        , </b>
                                                @endforeach
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection
