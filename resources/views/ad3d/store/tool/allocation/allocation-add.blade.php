<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 5/15/2018
 * Time: 10:12 AM
 * dataStaff
 */
$hFunction = new Hfunction();
?>
@extends('ad3d.store.tool.allocation.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <form id="frm_work_tool_allocation_add" role="form" method="post" enctype="multipart/form-data"
              action="{!! route('qc.ad3d.Store.tool.allocation.add.post') !!}">
            <div class="qc-padding-bot-20 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12" style="border-bottom: 2px dashed #C2C2C2;">
                    <h3>Phát đồ nghề</h3>
                </div>

                {{-- chi tiêt --}}
                <div class="qc-padding-top-5 qc-padding-bot-5 col-sx-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding: 2px 0 2px 0; ">
                            <select class="cbCompanyAllocation" name="cbCompanyAllocation"
                                    data-href="{!! route('qc.ad3d.store.tool.allocation.add.get') !!}">
                                @if(count($dataCompany) > 0)
                                    @foreach($dataCompany as $company)
                                        <option value="{!! $company->companyId() !!}"
                                                @if($company->companyId() == $selectCompanyId) selected="selected" @endif>
                                            {!! $company->name() !!}
                                        </option>
                                    @endforeach
                                @endif
                            </select>

                            <select class="cbReceiveStaff" name="cbReceiveStaff">
                                <option value="">Chọn nhân viên</option>
                                @if(count($dataReceiveStaff) > 0)
                                    @foreach($dataReceiveStaff as $receiveStaff)
                                        <option value="{!! $receiveStaff->staffId() !!}">{!! $receiveStaff->fullName() !!}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <tr style="background-color: whitesmoke;">
                                    <th class="text-center qc-padding-none"></th>
                                    <th class="qc-padding-none">Dụng cụ</th>
                                    <th class="text-center qc-padding-none">Số lượng</th>
                                    <th class="text-center qc-padding-none">Đã phát</th>
                                    <th class="text-center qc-padding-none">còn lại</th>
                                    <th class="text-center qc-padding-none">Chọn Số lượng</th>
                                    <th class="text-center qc-padding-none">Mới/Cũ</th>
                                </tr>
                                @if(count($dataCompanyStore) > 0)
                                    @foreach($dataCompanyStore as $companyStore)
                                        <?php
                                        $storeId = $companyStore->storeId();
                                        $amount = $companyStore->amount();
                                        $toolAllocationDetailTotalAmount = $companyStore->toolAllocationDetailTotalAmount();
                                        $amountInventoryInStore = $amount - $toolAllocationDetailTotalAmount;
                                        ?>
                                        <tr class="qc_store_select">
                                            <td class="text-center qc-padding-none" @if($amountInventoryInStore <= 0) style="background-color: #d7d7d7;" @endif>
                                                <input class="qc_store" type="checkbox"
                                                       @if($amountInventoryInStore <= 0) disabled
                                                       @endif name="txtStore[]" value="{!! $storeId !!}">
                                            </td>
                                            <td class="qc-padding-none">
                                                {!! $companyStore->tool->name() !!}
                                            </td>
                                            <td class="text-center qc-padding-none">
                                                {!! $amount !!}
                                            </td>
                                            <td class="text-center qc-padding-none">
                                                {!! $toolAllocationDetailTotalAmount !!}
                                            </td>
                                            <td class="text-center qc-color-red qc-padding-none">
                                                {!! $amountInventoryInStore !!}
                                            </td>
                                            <td class="text-center qc-padding-none">
                                                <input class="qc_txtAllocationAmount" type="number" disabled
                                                       name="txtAllocationAmount[]"
                                                       data-inventor="{!! $amountInventoryInStore !!}" value="1">
                                            </td>
                                            <td class="text-center qc-padding-none">
                                                <select class="cbNewStatus" name="cbNewStatus[]" disabled>
                                                    <option value="0">Cũ</option>
                                                    <option value="1">Mới</option>
                                                </select>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center qc-padding-none" colspan="7">
                                            Không có đữ liệu
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="qc-padding-top-20 qc-padding-bot-20 qc-border-none text-center col-sx-12 col-sm-12 col-md-12 col-lg-12">
                        <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        <button type="button" class="qc_ad3d_tool_allocation_save btn btn-sm btn-primary">
                            Đồng ý
                        </button>
                        <a href="{!! route('qc.ad3d.store.tool.allocation.get') !!}">
                            <button type="button" class="btn btn-sm btn-default">
                                Đóng
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
