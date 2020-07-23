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
$currentDate = $hFunction->currentDate();
$dataStaffLogin = $modelStaff->loginStaffInfo();
$companyId = $dataStaffLogin->companyId();
$dataWorkActivity = $modelCompany->getWorkActivityInfo($companyId);
$dataTimekeepingProvisional = $modelCompany->timekeepingProvisionalOfCompanyAndDate($companyId, $currentDate);
?>
<div class="qc-container-table col-xs-12 col-sm-12 col-md-12 col-lg-12" style="max-height: 200px; overflow-y: scroll;">
    <div class="table-responsive">
        <table class="table table-hover">
            <tr style="background-color: black; color: yellow;">
                <th style="width: 20px;">STT</th>
                <th>
                    Tên
                </th>
                <th class="text-center">Giờ vào</th>
                <th class="text-center">Giờ chấm</th>
            </tr>
            @if($hFunction->getCount($dataWorkActivity))
                @foreach($dataWorkActivity as $workActivity)
                    <?php
                    $workActivityId = $workActivity->workId();
                    # thong tin cham cong
                    $dataTimekeeping = $workActivity->timekeepingProvisionalOfDate($workActivityId, $currentDate);
                    $timeKeepingStatus = $hFunction->checkCount($dataTimekeeping);
                    # kiem tra nghi co phep
                    if (!$timeKeepingStatus) { # chi kiem tra khi khong co cham cong
                        # thong tin tin vien
                        $dataStaffWork = $workActivity->companyStaffWork->staff;
                        $offWorkAcceptedStatus = $dataStaffWork->existAcceptedOffWork($dataStaffWork->staffId(), $currentDate);
                    }else{
                        $offWorkAcceptedStatus = false;
                    }
                    $n_o = (isset($n_o)) ? $n_o + 1 : 1;

                    ?>
                    <tr class="@if($n_o%2) info @endif">
                        <td class="text-center" style="width: 20px;">
                            {!! $n_o !!}
                        </td>
                        <td>
                            {!! $workActivity->companyStaffWork->staff->fullName() !!}
                        </td>
                        <td class="text-center">
                            @if($timeKeepingStatus)
                                <span class="qc-font-bold" style="color: green;">
                                    {!! date('H:i', strtotime($dataTimekeeping->timeBegin())) !!}
                                </span>
                            @elseif($offWorkAcceptedStatus)
                                <span style="color: red;">Nghỉ có phép</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($timeKeepingStatus)
                                <span class="qc-color-grey qc-font-bold">{!! date('H:i', strtotime($dataTimekeeping->createdAt())) !!}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4">
                        <em>Không có thông tin làm việc</em>
                    </td>
                </tr>
            @endif
        </table>
    </div>
</div>