<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 8:57 AM
 *
 * modelStaff
 */
$dataStaffLogin = $modelStaff->loginStaffInfo();
?>
<div class="row">
    <div class="qc-color-red col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <Label>BẢN TIN HỆ THỐNG</Label>
    </div>
</div>
<div class="row" style="margin-bottom: 10px; border-bottom: dotted 3px brown;">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: 10px;">
        <ul class="nav nav-tabs" role="tablist">
            <li class="active">
                <a href="#">Chấm công</a>
            </li>
            <li>
                <a href="#">Thông báo</a>
            </li>
        </ul>
    </div>
    <div class="qc-container-table col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <tr style="background-color: whitesmoke;">
                    <th class="text-center">STT</th>
                    <th>Tên</th>
                    <th class="text-center qc-padding-none">Giờ vào</th>
                    <th class="text-center qc-padding-none">Giờ Chấm</th>
                </tr>
                @if(count($dataTimekeepingProvisional) > 0)
                    @foreach($dataTimekeepingProvisional as $timekeepingProvisional)
                        <?php
                        $dataWork = $timekeepingProvisional->work;
                        $timeBegin = $timekeepingProvisional->timeBegin();
                        $createdAt = $timekeepingProvisional->createdAt();
                        ?>
                        <tr>
                            <td class="text-center">
                                {!! $n_o = (isset($n_o)) ? $n_o + 1 : 1 !!}
                            </td>
                            <td>
                                {!! $dataWork->companyStaffWork->staff->fullName() !!}
                            </td>
                            <td class="text-center">
                                <span style="color: brown;">{!! date('d-m-Y', strtotime($timeBegin)) !!}</span>
                                <span class="qc-font-bold">{!! date('H:i', strtotime($timeBegin)) !!}</span>
                            </td>
                            <td class="text-center">
                                <span class="qc-color-grey qc-font-bold">{!! date('H:i', strtotime($createdAt)) !!}</span>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="4">
                            <em>Không có thông tin</em>
                        </td>
                    </tr>
                @endif
            </table>
        </div>
    </div>
</div>