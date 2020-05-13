<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 8:57 AM
 *
 * modelStaff
 */
$hFunction = new Hfunction();
$dataStaffLogin = $modelStaff->loginStaffInfo();
$currentDate = $hFunction->currentDate();
$dataStaffNotify = $dataStaffLogin->selectAllNotify($dataStaffLogin->staffId())->paginate(100);
//$dataStaffNotify =  $dataStaffNotify
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <label style="color: brown;">Danh sách thông báo</label>
        </div>
        <div class="qc-container-table col-xs-12 col-sm-12 col-md-12 col-lg-12"
             style="max-height: 200px; overflow-y: scroll;">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <tr style="background-color: whitesmoke;">
                        <th class="text-center" style="width: 20px;">STT</th>
                        <th style="width: 100px;">Ngày</th>
                        <th></th>
                        <th>Mô tả</th>
                    </tr>
                    @if($hFunction->checkCount($dataStaffNotify))
                        <?php
                        $n_o = 0;
                        ?>
                        @foreach($dataStaffNotify as $staffNotify)
                            <?php
                            $notifyDate = $staffNotify->createdAt();
                            $orderId = $staffNotify->orderId();
                            #ban giao don hang
                            $orderAllocationId = $staffNotify->orderAllocationId();
                            # thong bao giao thi cong san pham
                            $workAllocationId = $staffNotify->workAllocationId();
                            # thuong nong
                            $bonusId = $staffNotify->bonusId();
                            ?>
                            @if(!$hFunction->checkEmpty($orderId))
                                <tr class="@if($n_o%2) info @endif ">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td>
                                        {!! $hFunction->convertDateDMYFromDatetime($notifyDate) !!}
                                    </td>
                                    <td>
                                        <a class="qc-link-green"
                                           href="{!! route('qc.work.work_allocation.manage.order.view', $orderId) !!}">
                                            Xem
                                        </a>
                                        @if($staffNotify->checkNewInfo())
                                            <em style="color: red;"> - Chưa xem</em>
                                        @else
                                            <em style="color: grey;">- Đã xem</em>
                                        @endif
                                    </td>
                                    <td>
                                        <span style="color: deeppink;">Đã thêm ĐH:</span>
                                        <a class="qc-link-green-bold"
                                           href="{!! route('qc.work.work_allocation.manage.order.view', $orderId) !!}">
                                            {!! $staffNotify->orders->name() !!}
                                        </a>
                                    </td>
                                </tr>
                            @elseif(!$hFunction->checkEmpty($orderAllocationId))
                                <tr class="@if($n_o%2) info @endif ">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td>
                                        {!! $hFunction->convertDateDMYFromDatetime($notifyDate) !!}
                                    </td>
                                    <td>
                                        <a class="qc-link-green"
                                           href="{!! route('qc.work.work_allocation.construction.product.get',$orderAllocationId) !!}">
                                            Xem
                                        </a>
                                        @if($staffNotify->checkNewInfo())
                                            <em style="color: red;"> - Chưa xem</em>
                                        @else
                                            <em style="color: grey;">- Đã xem</em>
                                        @endif
                                    </td>
                                    <td>
                                        <span style="color: blue;">{!! $staffNotify->note() !!}:</span>
                                        <a class="qc-link-green-bold"
                                           href="{!! route('qc.work.work_allocation.construction.product.get',$orderAllocationId) !!}">
                                            {!! $staffNotify->orderAllocation->orders->name() !!}
                                        </a>
                                    </td>
                                </tr>
                            @elseif(!$hFunction->checkEmpty($workAllocationId))
                                <tr class="@if($n_o%2) info @endif ">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td>
                                        {!! $hFunction->convertDateDMYFromDatetime($notifyDate) !!}
                                    </td>
                                    <td>
                                        <a class="qc-link-green"
                                           href="{!! route('qc.work.work_allocation.detail.get', $workAllocationId) !!}">
                                            Xem
                                        </a>
                                        @if($staffNotify->checkNewInfo())
                                            <em style="color: red;"> - Chưa xem</em>
                                        @else
                                            <em style="color: grey;">- Đã xem</em>
                                        @endif
                                    </td>
                                    <td>
                                        <span style="color: brown;">{!! $staffNotify->note() !!}:</span>
                                        <a class="qc-link-green-bold"
                                           href="{!! route('qc.work.work_allocation.detail.get', $workAllocationId) !!}">
                                            {!! $staffNotify->workAllocation->product->productType->name() !!}
                                        </a>
                                    </td>
                                </tr>
                            @elseif(!$hFunction->checkEmpty($bonusId))
                                <?php
                                       // $dataBonus
                                ?>
                                <tr class="@if($n_o%2) info @endif ">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td>
                                        {!! $hFunction->convertDateDMYFromDatetime($notifyDate) !!}
                                    </td>
                                    <td>
                                        <a class="qc-link-green"
                                           href="#">
                                            Xem
                                        </a>
                                        @if($staffNotify->checkNewInfo())
                                            <em style="color: red;"> - Chưa xem</em>
                                        @else
                                            <em style="color: grey;">- Đã xem</em>
                                        @endif
                                    </td>
                                    <td>
                                        <span style="color: black;">{!! $staffNotify->note() !!}:</span>
                                        <a class="qc-link-green-bold"
                                           href="#">
                                            {!! $staffNotify->bonus->orderAllocation->orders->name() !!}
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center" colspan="4">
                                <em>Không có thông báo</em>
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
