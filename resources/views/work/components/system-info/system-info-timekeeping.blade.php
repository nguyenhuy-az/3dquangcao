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
<style type="text/css">
    .sys_info_has_work {
        background-color: lightskyblue;
    }

    .sys_info_off_work {
        background-color: whitesmoke;
    }
</style>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="height: 150px; overflow-y: scroll;">
        @if($hFunction->checkCount($dataWorkActivity))
            @foreach($dataWorkActivity as $workActivity)
                <?php
                $workActivityId = $workActivity->workId();
                # thong tin cham cong
                $dataTimekeeping = $workActivity->timekeepingProvisionalOfDate($workActivityId, $currentDate);
                $timeKeepingStatus = $hFunction->checkCount($dataTimekeeping);
                # thong tin nhan vien
                $dataStaffTimekeepingProvisional = $workActivity->companyStaffWork->staff;
                ?>
                @if($timeKeepingStatus)
                    <div class="col-xs-6 col-sm-2 col-md-2 col-lg-2" style="margin-bottom: 10px;">
                        <div class="media sys_info_has_work"
                             style="height: 52px;border: 1px solid brown; border-radius: 10px; ">
                            <a class="pull-left" href="#">
                                <img class="media-object"
                                     style="background-color: white; width: 50px;height: 50px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                     src="{!! $dataStaffTimekeepingProvisional->pathAvatar($dataStaffTimekeepingProvisional->image()) !!}">
                            </a>

                            <div class="media-body" style="padding-top: 5px;">
                                <h5 class="media-heading">{!! $dataStaffTimekeepingProvisional->lastName() !!}</h5>
                                <label class="qc-font-bold" style="color: green;">
                                    {!! date('H:i', strtotime($dataTimekeeping->timeBegin())) !!}
                                </label>
                                <em style="color: grey;">- {!! date('H:i', strtotime($dataTimekeeping->createdAt())) !!}</em>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
            @foreach($dataWorkActivity as $workActivity)
                <?php
                $workActivityId = $workActivity->workId();
                # thong tin cham cong
                $dataTimekeeping = $workActivity->timekeepingProvisionalOfDate($workActivityId, $currentDate);
                $timeKeepingStatus = $hFunction->checkCount($dataTimekeeping);
                # kiem tra nghi co phep
                if (!$timeKeepingStatus) { # chi kiem tra khi khong co cham cong

                } else {
                    $offWorkAcceptedStatus = false;
                }
                $n_o = (isset($n_o)) ? $n_o + 1 : 1;
                # thong tin nhan vien
                $dataStaffTimekeepingProvisional = $workActivity->companyStaffWork->staff;
                ?>
                @if(!$timeKeepingStatus)
                    <?php
                    # thong tin tin vien
                    $dataStaffWork = $workActivity->companyStaffWork->staff;
                    $offWorkAcceptedStatus = $dataStaffWork->existAcceptedOffWork($dataStaffWork->staffId(), $currentDate);
                    ?>
                    @if(!$offWorkAcceptedStatus)
                        <div class="col-xs-6 col-sm-2 col-md-2 col-lg-2" style="margin-bottom: 10px;">
                            <div class="media sys_info_off_work"
                                 style="height: 52px;border: 1px solid brown; border-radius: 10px; ">
                                <a class="pull-left" href="#">
                                    <img class="media-object"
                                         style="background-color: white; width: 50px;height: 50px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                         src="{!! $dataStaffTimekeepingProvisional->pathAvatar($dataStaffTimekeepingProvisional->image()) !!}">
                                </a>

                                <div class="media-body" style="padding-top: 5px;">
                                    <h5 class="media-heading">{!! $dataStaffTimekeepingProvisional->lastName() !!}</h5>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            @endforeach
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
                } else {
                    $offWorkAcceptedStatus = false;
                }
                $n_o = (isset($n_o)) ? $n_o + 1 : 1;
                # thong tin nhan vien
                $dataStaffTimekeepingProvisional = $workActivity->companyStaffWork->staff;
                ?>
                @if(!$timeKeepingStatus)
                    <?php
                    # thong tin tin vien
                    $dataStaffWork = $workActivity->companyStaffWork->staff;
                    $offWorkAcceptedStatus = $dataStaffWork->existAcceptedOffWork($dataStaffWork->staffId(), $currentDate);
                    ?>
                    @if($offWorkAcceptedStatus)
                        <div class="col-xs-6 col-sm-2 col-md-2 col-lg-2" style="margin-bottom: 10px;">
                            <div class="media sys_info_off_work"
                                 style="height: 52px;border: 1px solid red; border-radius: 10px; ">
                                <a class="pull-left" href="#">
                                    <img class="media-object"
                                         style="background-color: white; width: 50px;height: 50px; border: 1px solid #d7d7d7;border-radius: 10px;"
                                         src="{!! $dataStaffTimekeepingProvisional->pathAvatar($dataStaffTimekeepingProvisional->image()) !!}">
                                </a>
                                <div class="media-body" style="padding-top: 5px;">
                                    <h5 class="media-heading">{!! $dataStaffTimekeepingProvisional->lastName() !!}</h5>
                                    <span style="color: red;">Nghỉ có phép</span>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            @endforeach
        @else
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <em>KHÔNG CÓ THÔNG TIN LÀM VIỆC</em>
            </div>
        @endif
    </div>
</div>