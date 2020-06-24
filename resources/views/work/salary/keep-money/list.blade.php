<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 5/15/2018
 * Time: 10:12 AM
 *
 * dataStaff
 */
$hFunction = new Hfunction();
$hrefIndex = route('qc.work.salary.keep_money.get');
?>
@extends('work.salary.keep-money.index')
@section('qc_work_salary_keep_money_body')
    <div class="qc_work_salary_keep_money_wrap row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <tr style="background-color: black; color: yellow;">
                        <th style="width:20px;">STT</th>
                        <th>Ngày giữ</th>
                        <th>Ghi chú</th>
                        <th>Tháng lương giữ</th>
                        <th class="text-center">Thanh toán</th>
                        <th class="text-right">Số tiền</th>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="padding: 0;">
                            <select class="cbMonthFilter" style="height: 30px;" data-href="{!! $hrefIndex !!}">
                                <option value="100" @if((int)$monthFilter == 100) selected="selected" @endif >
                                    Tất cả
                                </option>
                                @for($m =1;$m<= 12; $m++)
                                    <option value="{!! $m !!}"
                                            @if((int)$monthFilter == $m) selected="selected" @endif>
                                        {!! $m !!}
                                    </option>
                                @endfor
                            </select>
                            <select class="cbYearFilter" style="height: 30px;" data-href="{!! $hrefIndex !!}">
                                @for($y =2017;$y<= 2050; $y++)
                                    <option value="{!! $y !!}"
                                            @if($yearFilter == $y) selected="selected" @endif>
                                        {!! $y !!}
                                    </option>
                                @endfor
                            </select>
                        </td>
                        <td></td>
                        <td></td>
                        <td style="padding: 0;">
                            <select class="cbPayStatus" name="cbPayStatus" style="height: 30px; width: 100%;"
                                    data-href="{!! $hrefIndex !!}">
                                <option value="0" @if($payStatus == 0) selected="selected" @endif>
                                    Tất cả
                                </option>
                                <option value="1" @if($payStatus == 1) selected="selected" @endif>
                                    Chưa Thanh toán
                                </option>
                                <option value="2" @if($payStatus == 2) selected="selected" @endif >
                                    Đã Thanh toán
                                </option>
                            </select>
                        </td>
                        <td></td>
                    </tr>
                    @if($hFunction->checkCount($dataKeepMoney))
                        <?php
                        $perPage = $dataKeepMoney->perPage();
                        $currentPage = $dataKeepMoney->currentPage();
                        $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                        ?>
                        @foreach($dataKeepMoney as $keepMoney)
                            <?php
                            $keepMoneyId = $keepMoney->keepId();
                            $dataWork = $keepMoney->salary->work;
                            ?>
                            <tr class="qc_ad3d_list_object @if($n_o%2) info @endif"
                                data-object="{!! $keepMoneyId !!}">
                                <td class="text-center">
                                    {!! $n_o += 1 !!}
                                </td>
                                <td>
                                    {!! date('d/m/Y', strtotime($keepMoney->keepDate())) !!}
                                </td>
                                <td>
                                    {!! $keepMoney->description() !!}
                                </td>
                                <td>
                                    {!! date('m/Y', strtotime($dataWork->fromDate())) !!}
                                </td>
                                <td class="text-center">
                                    @if($keepMoney->checkPaid($keepMoneyId))
                                        <span>Đã thanh toán</span>
                                    @else
                                        <span>Chưa thanh toán</span>
                                    @endif
                                </td>
                                <td class="text-right qc-color-red">
                                    {!! $hFunction->currencyFormat($keepMoney->money()) !!}
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="text-center" colspan="6">
                                {!! $hFunction->page($dataKeepMoney) !!}
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td class="text-center" colspan="6">
                                <em class="qc-color-red">Không tìm thấy thông tin</em>
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
@endsection
