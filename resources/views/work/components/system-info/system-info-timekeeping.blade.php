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
$companyId = $dataStaffLogin->companyId();
$dataWorkActivity = $modelCompany->getWorkActivityInfo($companyId);
$dataTimekeepingProvisional = $modelCompany->timekeepingProvisionalOfCompanyAndDate($companyId, $hFunction->currentDate());
?>
<div class="qc-container-table col-xs-12 col-sm-12 col-md-12 col-lg-12" style="max-height: 200px; overflow-y: scroll;">
    <div class="table-responsive">
        <table class="table table-hover">
            <tr>
                <th colspan="4" style="border: none;">
                    <i class="glyphicon glyphicon-user"></i>
                    <label style="color: forestgreen;">Có làm việc</label>
                </th>
            </tr>
            @if($hFunction->checkCount($dataTimekeepingProvisional))
                @foreach($dataTimekeepingProvisional as $timekeepingProvisional)
                    <?php
                    $dataWork = $timekeepingProvisional->work;
                    $timeBegin = $timekeepingProvisional->timeBegin();
                    $createdAt = $timekeepingProvisional->createdAt();
                    ?>
                    <tr>
                        <td class="text-center" style="width: 20px;">
                            {!! $n_o = (isset($n_o)) ? $n_o + 1 : 1 !!}
                        </td>
                        <td>
                            {!! $dataWork->companyStaffWork->staff->fullName() !!}
                        </td>
                        <td class="text-center">
                            Giờ vào:
                            <span class="qc-font-bold"
                                  style="color: red;">{!! date('H:i', strtotime($timeBegin)) !!}</span>
                        </td>
                        <td class="text-center">
                            Chờ chấm
                            <span class="qc-color-grey qc-font-bold">{!! date('H:i', strtotime($createdAt)) !!}</span>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="text-center" colspan="4">
                        <em>Không có</em>
                    </td>
                </tr>
            @endif
            @if($hFunction->getCount($dataWorkActivity) > $hFunction->getCount($dataTimekeepingProvisional))
                @foreach($dataWorkActivity as $workActivity)
                    <?php
                    $workActivityId = $workActivity->workId();
                    $showStatus = true;
                    ?>
                    @if($hFunction->checkCount($dataTimekeepingProvisional))
                        @foreach($dataTimekeepingProvisional as $timekeepingProvisional)
                            <?php
                            $checkWorkId = $timekeepingProvisional->work->workId();
                            if ($workActivityId == $checkWorkId) $showStatus = false;
                            ?>
                        @endforeach;pp
                    @endif
                    @if($showStatus)

                    @endif
                @endforeach
            @endif
        </table>
    </div>
</div>