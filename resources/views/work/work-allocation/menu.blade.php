<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 7/4/2019
 * Time: 2:02 PM
 */
$dataStaffLogin = $modelStaff->loginStaffInfo();
$objectAccess = $dataAccess['object'];
?>
<div class="row">
    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <ul class="nav nav-tabs" role="tablist">
            <li @if($objectAccess == 'workAllocationActivity') class="active " @endif>
                <a class="qc-link" href="{!! route('qc.work.work_allocation.activity.get') !!}">
                    Việc đang làm
                </a>
            </li>
            <li @if($objectAccess == 'workAllocationFinish') class="active" @endif>
                <a class="qc-link" href="{!! route('qc.work.work_allocation.finish.get') !!}">
                    Việc đã làm
                </a>
            </li>
            <li @if($objectAccess == 'workAllocationConstruction') class="active" @endif>
                <a href="{!! route('qc.work.work_allocation.construction.get') !!}">
                    <label>Đơn hàng được giao</label>
                </a>
            </li>
            @if($dataStaffLogin->checkConstructionDepartmentAndManageRank())
                <?php
                $totalNotifyNewOrder = $dataStaffLogin->totalNotifyNewOrder();
                ?>
                <li @if($objectAccess == 'workAllocationManage') class="active" @endif>
                    <a href="{!! route('qc.work.work_allocation.manage.get') !!}" title="Đang cập nhật">
                        <label>Danh sách đơn hàng</label>
                        @if($totalNotifyNewOrder > 0)
                            &nbsp;
                            <i class="qc-font-size-14 glyphicon glyphicon-bullhorn" style="color: red;"></i>
                        @endif
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>
