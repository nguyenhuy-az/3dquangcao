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
$dataSystemDateOff = $modelCompany->systemDateOfFOfCompanyAndDate($dataStaffLogin->companyId(), $hFunction->currentYear());
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <label style="color: brown;">Lịch nghỉ 2020</label>
        </div>
        <div class="qc-container-table col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <tr style="background-color: whitesmoke;">
                        <th class="text-center" style="width: 20px;">STT</th>
                        <th>Ngày</th>
                        <th>Mô tả</th>
                        <th>Hình thức</th>
                    </tr>
                    @if($hFunction->checkCount($dataSystemDateOff))
                        <?php
                        $n_o = 0;
                        ?>
                        @foreach($dataSystemDateOff as $systemDateOff)
                            <?php
                                $dateOff = $systemDateOff->dateOff();
                            ?>
                            <tr class="@if($dateOff < $currentDate) qc-color-red @endif @if($n_o%2) info @endif ">
                                <td class="text-center">
                                    {!! $n_o += 1 !!}
                                </td>
                                <td>
                                    {!! $hFunction->convertDateDMYFromDatetime($systemDateOff->dateOff) !!}
                                </td>
                                <td>
                                    {!! $systemDateOff->description() !!}
                                </td>
                                <td>
                                    <em class="qc-color-grey">
                                        {!! $systemDateOff->typeLabel($systemDateOff->dateOffId()) !!}
                                    </em>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center" colspan="4">
                                <em>Không có lịch nghỉ</em>
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
