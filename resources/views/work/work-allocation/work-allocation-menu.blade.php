<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 7/4/2019
 * Time: 2:02 PM
 */
$objectAccess = $dataAccess['object'];
?>
<div class="row">
    <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
        <ul class="nav nav-tabs" role="tablist">
            <li @if($objectAccess == 'workAllocationActivity') class="active " @endif>
                <a href="{!! route('qc.work.work_allocation.activity.get') !!}">
                    <label>Việc đang làm</label>
                </a>
            </li>
            <li @if($objectAccess == 'workAllocationFinish') class="active" @endif>
                <a href="{!! route('qc.work.work_allocation.finish.get') !!}">
                    <label>Việc đã làm</label>
                </a>
            </li>
            <li @if($objectAccess == 'workAllocationConstruction') class="active" @endif>
                <a href="{!! route('qc.work.work_allocation.construction.get') !!}">
                    <label>Công trình</label>
                </a>
            </li>
        </ul>
    </div>
</div>
