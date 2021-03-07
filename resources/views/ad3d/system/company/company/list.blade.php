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
$dataStaffLogin = $modelStaff->loginStaffInfo();//checkRoot
$dataCompanyLogin = $dataStaffLogin->companyInfoActivity();
?>
@extends('ad3d.system.company.company.index')
@section('qc_ad3d_index_content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="qc_ad3d_list_content row"
                 data-href-view="{!! route('qc.ad3d.system.company.view.get') !!}"
                 data-href-edit="{!! route('qc.ad3d.system.company.edit.get') !!}">
                <table class="table table-hover table-bordered">
                    {{--cong ty me moi duoc them chi nhanh--}}
                    @if($dataCompanyLogin->checkParent())
                        <tr>
                            <td style="padding: 0;">
                                <a class="form-control qc-font-size-16 qc-link-white-bold btn btn-primary"
                                   title="Thêm"
                                   href="{!! route('qc.ad3d.system.company.add.get') !!}">
                                    <i class="qc-font-size-16 glyphicon glyphicon-plus"></i>
                                    THÊM CHI NHÁNH
                                </a>
                            </td>
                        </tr>
                    @endif
                    <tr style="background-color: black; color: yellow;">
                        <th>Danh sách công ty</th>
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
                            # thong tin nguoi lanh dao
                            $dataCompanyStaffWorkRoot = $company->companyStaffWorkLevelRoot();
                            $n_o += 1;
                            ?>
                            <tr class="qc_ad3d_list_object @if($n_o%2) info @endif"
                                data-object="{!! $companyId !!}">
                                <td>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                            <em>{!! $n_o !!}). </em>
                                            <label>{!! $company->name() !!}</label>
                                            <br/> &emsp;
                                            @if($company->checkBranch())
                                                <em>Chi Nhánh</em>
                                            @else
                                                <em>Trụ sở</em>
                                            @endif
                                            <span>&nbsp;|&nbsp;</span>
                                            <em style="color: grey;">Quản lý:</em>
                                            @if($hFunction->checkCount($dataCompanyStaffWorkRoot))
                                                <?php
                                                $dataStaff = $dataCompanyStaffWorkRoot->staff;
                                                ?>
                                                <img style="background-color: white; width: 30px;height: 30px; border: 1px solid #d7d7d7;border-radius: 15px;"
                                                     src="{!! $dataStaff->pathAvatar($dataStaff->image()) !!}">
                                                <b>{!! $dataStaff->lastName() !!}</b>
                                            @else
                                                <span>Chưa có</span>
                                            @endif
                                            <br/>&emsp;
                                            <a class="qc_view qc-link" href="#" title="Xem chi tiết">
                                                <i class="qc-font-size-14 glyphicon glyphicon-eye-open"></i>
                                            </a>
                                            <span>&nbsp;|&nbsp;</span>
                                            <a class="qc_edit qc-link-green" href="#" title="Sửa">
                                                <i class="qc-font-size-14 glyphicon glyphicon-pencil"></i>
                                            </a>
                                            <span>&nbsp;|&nbsp;</span>
                                            <a class="qc_delete qc-link-red" href="#" title="Xóa">
                                                <i class="qc-font-size-14 glyphicon glyphicon-trash"></i>
                                            </a>
                                            <span>&nbsp;|&nbsp;</span>
                                            <a class="qc_recruitment_get_link qc-link-green"
                                               data-href="{!! route('qc.ad3d.system.company.recruitment_link.get',$companyId) !!}">
                                                LINK TUYỂN DỤNG
                                            </a>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                            &emsp;
                                            @if(!$hFunction->checkEmpty($logo))
                                                <img alt="..." src="{!! $company->pathSmallImage($logo) !!}"
                                                     style="max-width: 70px;">
                                            @else
                                                <em class="qc-color-grey">- Chưa có logo</em>
                                            @endif
                                            <br/>&emsp;
                                            <em class="qc-text-under" style="color: grey;">- Địa chỉ:</em>
                                            <span>{!! $company->address() !!}</span>
                                            <br/>&emsp;
                                            <em class="qc-text-under" style="color: grey;">- Điện thoại:</em>
                                            <span>{!! $company->phone() !!}</span>
                                            <br/>&emsp;
                                            <em class="qc-text-under" style="color: grey;">- Email:</em>
                                            <span>{!! $company->email() !!}</span>
                                            <br/>&emsp;
                                            <em class="qc-text-under" style="color: grey;">- Website:</em>
                                            <span>{!! $company->website() !!}</span>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="text-center">
                                {!! $hFunction->page($dataCompany) !!}
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
@endsection
