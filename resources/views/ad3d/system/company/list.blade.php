<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 12/28/2017
 * Time: 2:32 PM
 */
/*
 *$dataCompany
 */
$hFunction = new Hfunction();
$dataStaffLogin = $modelStaff->loginStaffInfo();
?>
@extends('ad3d.system.company.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="text-left col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-left: 0;padding-right: 0;">
                    <label class="qc-font-size-20">CÔNG TY</label>
                </div>
            </div>
        </div>
        <div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
            <div class="qc_ad3d_list_content row"
                 data-href-view="{!! route('qc.ad3d.system.company.view.get') !!}"
                 data-href-edit="{!! route('qc.ad3d.system.company.edit.get') !!}">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <tr>
                            <td colspan="5"></td>
                            <td class="text-right">
                                <a class="qc-font-size-16 qc-link-green-bold" title="Thêm"
                                   href="{!! route('qc.ad3d.system.company.add.get') !!}">
                                    <i class="qc-font-size-16 glyphicon glyphicon-plus"></i>
                                    Thêm
                                </a>
                            </td>
                        </tr>
                        <tr style="background-color: black; color: yellow;">
                            <th class="text-center" style="width: 20px;">STT</th>
                            <th>Logo</th>
                            <th>Tên</th>
                            <th>Thông tin liên lạc</th>
                            <th>Hình thức hoạt động</th>
                            <th class="text-center">Link tuyển dụng</th>
                        </tr>
                        @if($hFunction->checkCount($dataCompany))
                            <?php
                            $perPage = $dataCompany->perPage();
                            $currentPage = $dataCompany->currentPage();
                            $n_o = ($currentPage == 1) ? 0 : ($currentPage - 1) * $perPage; // set row number
                            ?>
                            @foreach($dataCompany as $company)
                                <?php
                                $companyId = $company->companyId();
                                $logo = $company->logo();
                                //  $recruitmentLink = route('qc.work.recruitment.login.get',$companyId );
                                ?>
                                <tr class="qc_ad3d_list_object @if($n_o%2) info @endif"
                                    data-object="{!! $companyId !!}">
                                    <td class="text-center">
                                        {!! $n_o += 1 !!}
                                    </td>
                                    <td>
                                        @if(!$hFunction->checkEmpty($logo))
                                            <img alt="..." src="{!! $company->pathSmallImage($logo) !!}"
                                                 style="max-width: 70px;">
                                        @else
                                            <em class="qc-color-grey">Chưa có</em>
                                        @endif
                                    </td>
                                    <td>
                                        {!! $company->name() !!}
                                        <br/>
                                        <a class="qc_view qc-link" href="#" title="Xem chi tiết">
                                            <i class="qc-font-size-14 glyphicon glyphicon-eye-open"></i>
                                        </a>
                                        &nbsp;|&nbsp;
                                        <a class="qc_edit qc-link-green" href="#" title="Sửa">
                                            <i class="qc-font-size-14 glyphicon glyphicon-pencil"></i>
                                        </a>
                                        &nbsp;|&nbsp;
                                        <a class="qc_delete qc-link-red" href="#" title="Xóa">
                                            <i class="qc-font-size-14 glyphicon glyphicon-trash"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <em class="qc-text-under">- Địa chỉ:</em>
                                        <span>{!! $company->address() !!}</span>
                                        <br/>
                                        <em class="qc-text-under">- Điện thoại:</em>
                                        <span>{!! $company->phone() !!}</span>
                                        <br/>
                                        <em class="qc-text-under">- Email:</em>
                                        <span>{!! $company->email() !!}</span>
                                        <br/>
                                        <em class="qc-text-under">- Website:</em>
                                        <span>{!! $company->website() !!}</span>
                                    </td>
                                    <td>
                                        @if($company->checkBranch())
                                            <em>Chi Nhánh</em>
                                        @else
                                            <b>Trụ sở chính</b>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a class="qc_recruitment_get_link qc-link-green"
                                           data-href="{!! route('qc.ad3d.system.company.recruitment_link.get',$companyId) !!}">
                                            LẤY LINK
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center" colspan="6">
                                    {!! $hFunction->page($dataCompany) !!}
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
