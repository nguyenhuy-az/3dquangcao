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
$dataTimekeepingProvisional = $modelCompany->timekeepingProvisionalOfCompanyAndDate($dataStaffLogin->companyId(), $hFunction->currentDate());
?>
<div class="qc-container-table col-xs-12 col-sm-12 col-md-12 col-lg-12" style="max-height: 200px; overflow-y: scroll;">
    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <tr style="background-color: black;color: yellow;">
                <th class="text-center" style="width: 20px;">STT</th>
                <th>Tên</th>
                <th class="text-center">Giờ vào</th>
                <th class="text-center">Giờ Chấm</th>
            </tr>
            @if($hFunction->checkCount($dataTimekeepingProvisional))
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
                            <span style="color: brown;">{!! $hFunction->convertDateDMYFromDatetime($timeBegin)!!}</span>
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