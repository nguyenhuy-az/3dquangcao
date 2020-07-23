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
$mobile = new Mobile_Detect();
$mobileStatus = $mobile->isMobile();
$dataStaff = $modelStaff->loginStaffInfo();
$loginStaffId = $dataStaff->staffId();
$companyId = $dataStaff->companyId();
$hrefIndex = route('qc.work.tool.check_store.get');
$currentMonth = $hFunction->currentMonth();
?>
@extends('work.tool.check-store.index')
@section('qc_work_tool_check_store_body')
    <div class="row qc_work_tool_wrap">
        <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-6 col-lg-6">
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <b style="background-color: red; color: white; padding: 5px;">KIỂM TRA VÀ XÁC NHẬN KHÔNG ĐÚNG SẼ BỊ PHẠT THEO NỘI QUY</b> <br/><br/>
                <span style="color:deeppink;">Nếu không xác nhận, cuối ngày HỆ THỐNG TỰ XÁC NHẬN ĐỦ và Sẽ bị phạt nếu hôm sau bị báo mất</span>
            </div>
            <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <tr style="background-color: black; color: yellow;">
                                <th class="text-center" style="width: 20px;">STT</th>
                                <th>Đồ nghề</th>
                                <th>
                                    Xác nhận
                                </th>
                            </tr>
                            @for($i = 1; $i <=10; $i++)
                                <tr>
                                    <td>
                                        {!! $i !!}
                                    </td>
                                    <td>
                                        Tên đồ nghề {!! $i !!}
                                    </td>
                                    <td style="padding: 0;">
                                        <select class="form-control">
                                            <option>Có - Dùng được</option>
                                            <option>Có - Không dùng được</option>
                                            <option>Không có</option>
                                        </select>
                                    </td>
                                </tr>
                            @endfor
                            <tr>
                                <td colspan="2" style="background-color: whitesmoke;"></td>
                                <td class="text-center">
                                    <b style="color: red; font-size: 16px;">SAU KHI XÁC NHẬN SẼ KHÔNG ĐƯỢC THAY ĐỔI</b>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="background-color: whitesmoke;"></td>
                                <td style="padding: 0;">
                                    <button class="btn btn-primary" type="button" style="width: 100%;">XÁC NHẬN ĐÚNG</button>
                                </td>
                            </tr>
                            @if($hFunction->checkCount($dataToolCheckStore))
                                @foreach($dataToolCheckStore as $toolCheckStore)
                                    <?php

                                    ?>
                                    <tr>
                                        <td class="text-center" style="padding: 0;">
                                            <div class="form-group" style="margin: 0;">
                                                <input type="checkbox" class="form-control" disabled
                                                       name="txtAllocationDetail[]" style="margin: 0;"
                                                       checked="checked">
                                            </div>
                                        </td>
                                        <td>
                                        </td>
                                        <td class="text-center">
                                        </td>
                                        <td>

                                        </td>
                                        <td class="text-center">

                                        </td>
                                        <td class="text-center">

                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                {{--<tr>--}}
                                    {{--<td class="text-center" colspan="6">--}}
                                        {{--Chưa có đồ nghề--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
