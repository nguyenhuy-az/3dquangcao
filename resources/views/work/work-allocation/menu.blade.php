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
            {{--<li @if($objectAccess == 'workAllocationConstruction') class="active" @endif>
                <a href="{!! route('qc.work.work_allocation.construction.get') !!}">
                    <label>Công trình được giao</label>
                </a>
            </li>--}}
            @if($dataStaffLogin->checkConstructionDepartmentAndManageRank())
                <li @if($objectAccess == 'workAllocationManage') class="active" @endif>
                    <a href="{!! route('qc.work.work_allocation.manage.get') !!}" title="Đang cập nhật">
                        <label>Công trình</label>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>
