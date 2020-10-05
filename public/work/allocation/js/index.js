/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_allocation = {
    filter: function (href) {
        qc_main.url_replace(href);
    },
    /*workAllocation: {
        getReport: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        },
        saveReport: function (form) {
            var notifyContent = $(form).find('.frm_notify');
            qc_master_submit.ajaxFormHasReload(form, notifyContent, true);
            qc_main.scrollTop();
        },
        deleteReport: function (href) {
            if (confirm('Bạn muốn xóa báo cáo này?')) {
                qc_master_submit.ajaxHasReload(href, '#qc_master', false);
            }
        },
        viewReportImage: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        },
        viewProductDesign: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        },
    },*/
    activity: {
        viewProductDesign: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        },

        viewReportImage: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        },
        getReport: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        },
        saveReport: function (form) {
            var notifyContent = $(form).find('.frm_notify');
            qc_master_submit.ajaxFormHasReload(form, notifyContent, true);
            qc_main.scrollTop();
        },
        deleteReport: function (href) {
            if (confirm('Bạn muốn xóa báo cáo này?')) {
                qc_master_submit.ajaxHasReload(href, '#qc_master', false);
            }
        },
        deleteReportImage: function (href) {
            if (confirm('Bạn muốn xóa ảnh này?')) {
                qc_master_submit.ajaxHasReload(href, '#qc_master', false);
            }
        },
    },
    finish: {
        viewProductDesign: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        },
        viewReportImage: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        },
    },
    /*manage: {
        productWorkAllocation: {
            addStaff: function (href) {
                qc_master_submit.ajaxNotReload(href, '#qc_product_work_allocation_staff_wrap', false);
            },
            save: function (frm) {
                if (confirm('Bạn đồng ý với thông tin phân việc này?')) {
                    qc_master_submit.normalForm(form);
                }
            },
            save_v1: function (form) {
                if ($(form).find('.qc_work_allocation_product_work_allocation_staff_add').length > 0) {
                    //qc_ad3d_submit.ajaxFormHasReload(form, '', false);
                    //qc_main.scrollTop();
                    //qc_ad3d_submit.normalForm(form);
                    //qc_ad3d_order_order.add.checkProductInput();
                    qc_work_allocation.manage.productWorkAllocation.checkSubmit(form);
                } else {
                    alert('Chọn nhân viên');
                    return false;
                }
            },
            checkSubmit: function (form) {
                $('#frmWorkAllocationManageProductConstruction .qc_work_allocation_product_work_allocation_staff_add').filter(function () {
                    if ($(this).is(':last-child')) {
                        if (qc_work_allocation.manage.productWorkAllocation.checkInfo(this)) {
                            //qc_ad3d_submit.ajaxFormNotReload(form,'',false);
                            qc_master_submit.normalForm(form);
                        }
                    } else {
                        if (!qc_work_allocation.manage.productWorkAllocation.checkInfo(this)) {
                            return false;
                        }
                    }
                });
            },
            checkInfo: function (object) {
                var cbStaff = $(object).find('.cbReceiveStaff');
                var cbDayAllocation = $(object).find('.cbDayAllocation');
                var cbMonthAllocation = $(object).find('.cbMonthAllocation');
                var cbYearAllocation = $(object).find('.cbYearAllocation');
                var cbHoursAllocation = $(object).find('.cbHoursAllocation');
                var cbMinuteAllocation = $(object).find('.cbMinuteAllocation');
                var cbDayDeadline = $(object).find('.cbDayDeadline');
                var cbMonthDeadline = $(object).find('.cbMonthDeadline');
                var cbYearDeadline = $(object).find('.cbYearDeadline');
                var cbHoursDeadline = $(object).find('.cbHoursDeadline');
                var cbMinuteDeadline = $(object).find('.cbMinuteDeadline');
                if (cbStaff.val() != '' || cbDayAllocation.val() != '' || cbMonthAllocation.val() != '' || cbHoursAllocation.val() != '' || cbDayAllocation.val() != '' || cbMonthDeadline.val() != '' || cbHoursDeadline.val() != '') {
                    if (qc_main.check.inputNull(cbStaff, 'Chọn nhân viên')) {
                        cbStaff.focus();
                        return false;
                    }
                    if (qc_main.check.inputNull(cbDayAllocation, 'Chọn ngày nhận')) {
                        cbDayAllocation.focus();
                        return false;
                    }
                    if (qc_main.check.inputNull(cbMonthAllocation, 'Chọn tháng nhận')) {
                        cbMonthAllocation.focus();
                        return false;
                    }
                    if (qc_main.check.inputNull(cbHoursAllocation, 'Chọn giờ nhận')) {
                        cbHoursAllocation.focus();
                        return false;
                    }
                    if (qc_main.check.inputNull(cbDayDeadline, 'Chọn ngày hết hạn')) {
                        cbDayDeadline.focus();
                        return false;
                    }
                    if (qc_main.check.inputNull(cbMonthDeadline, 'Chọn tháng hết hạn')) {
                        cbMonthDeadline.focus();
                        return false;
                    }
                    if (qc_main.check.inputNull(cbHoursDeadline, 'Chọn giờ nhận')) {
                        cbHoursDeadline.focus();
                        return false;
                    } else {
                        return true;
                    }
                } else {
                    return false;
                }
            },
            cancelAllocation: function (href) {
                if (confirm('Tôi đồng ý hủy phân việc trên sản phẩm này')) {
                    qc_master_submit.ajaxHasReload(href, '', false);
                }
            }
        },
    },
    construct: {
        viewProductDesign: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        },
        getProductConfirm: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        },
        postProductConfirm: function (frm) {
            if (confirm('Tôi đồng ý xác nhận hoàn thành Sản phẩm này')) {
                qc_master_submit.ajaxFormHasReload(frm, '', false);
            }
        },
        getReportFinish: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        },
        postConfirmAllocation: function (frm) {
            if (confirm('Tôi đồng ý xác nhận hoàn thành Công trình này')) {
                qc_master_submit.ajaxFormHasReload(frm, '', false);
            }
        },

    }*/
}

//------------ ---------- quản lý dơn hang duoc ban giao ----------- ---------
/*$(document).ready(function () {
    $('body').on('change', '.cbWorkAllocationConstructionFinishStatus', function () {
        qc_main.url_replace($(this).data('href') + '/' + $(this).val() + '/' + $('.cbWorkAllocationConstructionMonthFilter').val() + '/' + $('.cbWorkAllocationConstructionYearFilter').val());
    });
    $('body').on('change', '.cbWorkAllocationConstructionMonthFilter', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.cbWorkAllocationConstructionFinishStatus').val() + '/' + $(this).val() + '/' + $('.cbWorkAllocationConstructionYearFilter').val());
    });
    $('body').on('change', '.cbWorkAllocationConstructionYearFilter', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.cbWorkAllocationConstructionFinishStatus').val() + '/' + $('.cbWorkAllocationConstructionMonthFilter').val() + '/' + $(this).val());
    });
    //-------- San pham --------
    //xem anh thiet chi tiet
    $('.qc_work_allocation_construction_product_wrap').on('click', '.qc_work_allocation_construct_product_design_image_view', function () {
        qc_work_allocation.construct.viewProductDesign($(this).data('href'));
    });

    // xac nhan hoan thanh san pham
    $('.qc_work_allocation_construction_product_wrap').on('click', '.qc_confirm_finish_product_act', function () {
        qc_work_allocation.construct.getProductConfirm($(this).data('href'));
    });
    $('body').on('click', '.frmWorkAllocationProductConfirm .qc_save', function () {
        qc_work_allocation.construct.postProductConfirm($(this).parents('.frmWorkAllocationProductConfirm'));
    });

    // bao cao hoan than hoan thanh don hang ban giao
    $('.qc_work_allocation_construction_wrap').on('click', '.qc_report_finish_get', function () {
        qc_work_allocation.construct.getReportFinish($(this).data('href'));
    });
    $('body').on('click', '.frmWorkAllocationConstructionConfirm .qc_save', function () {
        qc_work_allocation.construct.postConfirmAllocation($(this).parents('.frmWorkAllocationConstructionConfirm'));
    });
});*/
//------------ ---------- quan ly phan viec ----------- ---------
/*$(document).ready(function () {
    //Theo trạng thái hoàn thành
    $('body').on('change', '.cbWorkAllocationFinishStatus', function () {
        qc_main.url_replace($(this).data('href') + '/' + $(this).val() + '/' + $('.cbWorkAllocationMonthFilter').val() + '/' + $('.cbWorkAllocationYearFilter').val());
    });
    // lọc theo ngay thang
    $('body').on('change', '.cbWorkAllocationMonthFilter', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.cbWorkAllocationFinishStatus').val() + '/' + $(this).val() + '/' + $('.cbWorkAllocationYearFilter').val());
    });
    $('body').on('change', '.cbWorkAllocationYearFilter', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.cbWorkAllocationFinishStatus').val() + '/' + $('.cbWorkAllocationMonthFilter').val() + '/' + $(this).val());
    });
    //form bao cao
    $('body').on('click', '.qc_work_allocation_report_act', function () {
        var href = $(this).parents('.qc_work_allocation_contain').data('href-report') + '/' + $(this).parents('.qc_work_allocation_object').data('work-allocation');
        qc_work_allocation.workAllocation.getReport(href);
    });
    //luu bao cao
    $('body').on('click', '.qc_frm_Work_allocation_report .qc_save', function () {
        qc_work_allocation.workAllocation.saveReport($(this).parents('.qc_frm_Work_allocation_report'));
    });
    //xoa bao cao
    $('body').on('click', '.qc_work_allocation_detail_wrap .qc_delete_report_action', function () {
        qc_work_allocation.workAllocation.deleteReport($(this).data('href'));
    });
    //xem anh bao cao chi tiet
    $('body').on('click', '.qc_work_allocation_detail_wrap .qc_image_view', function () {
        qc_work_allocation.workAllocation.viewReportImage($(this).data('href'));
    });
    //xem anh thiet chi tiet
    $('body').on('click', '.qc_work_allocation_detail_wrap .qc_design_image_view', function () {
        qc_work_allocation.workAllocation.viewProductDesign($(this).data('href'));
    });

});*/
//----------- ----------- viec dang làm --------- -----------
$(document).ready(function () {
    //form nao cao
    $('body').on('click', '.qc_work_allocation_activity_report_act', function () {
        var href = $(this).parents('.qc_work_allocation_activity_contain').data('href-report') + '/' + $(this).parents('.qc_work_allocation_activity_object').data('work-allocation');
        qc_work_allocation.activity.getReport(href);
    });
    //luu bao cao
    $('body').on('click', '.qc_frm_Work_allocation_activity_report .qc_save', function () {
        qc_work_allocation.activity.saveReport($(this).parents('.qc_frm_Work_allocation_activity_report'));
    });

    //xoa anh bao cao
    $('body').on('click', '.qc_work_allocation_activity_contain .qc_delete_image_action', function () {
        qc_work_allocation.activity.deleteReportImage($(this).data('href'));
    });

    //xoa bao cao
    $('body').on('click', '.qc_work_allocation_activity_contain .qc_delete_report_action', function () {
        qc_work_allocation.activity.deleteReport($(this).data('href'));
    });
    //xem anh thiet chi tiet
    $('body').on('click', '.qc_work_allocation_activity_contain .qc_design_image_view', function () {
        qc_work_allocation.activity.viewProductDesign($(this).data('href'));
    });
    //xem anh bao cao chi tiet
    $('body').on('click', '.qc_work_allocation_activity_contain .qc_image_view', function () {
        qc_work_allocation.activity.viewReportImage($(this).data('href'));
    });

});
//------------ ---------- viec da lam xong ----------- ---------
$(document).ready(function () {
    //xem anh thiet chi tiet
    $('body').on('click', '.qc_work_allocation_finish_contain .qc_design_image_view', function () {
        qc_work_allocation.finish.viewProductDesign($(this).data('href'));
    });
    //xem anh bao cao chi tiet
    $('body').on('click', '.qc_work_allocation_finish_contain .qc_image_view', function () {
        qc_work_allocation.finish.viewReportImage($(this).data('href'));
    });
});

//------------ ---------- quản lý dơn hang thi cong ----------- ---------
/*
$(document).ready(function () {
    var dateFilter = $('.cbAllocationManageDayFilter').val() + '/' + $('.cbAllocationManageMonthFilter').val() + '/' + $('.cbAllocationManageYearFilter').val();
    //----- --------- tim theo trang thai thi cong ----- ------------
    $('body').on('change', '.qc_work_allocation_manage_wrap .cbAllocationManageFinishStatus', function () {
        var txtOrderFilterKeyword = null;
        var txtOrderCustomerFilterKeyword = null;
        qc_main.url_replace($(this).data('href') + '/' + dateFilter + '/' + txtOrderFilterKeyword + '/' + txtOrderCustomerFilterKeyword + '/' + $(this).val());
    });

    //----- ----- tim theo ten don hang ----- -----
    $('body').on('keyup', '.qc_work_allocation_manage_wrap .txtAllocationManageKeywordFilter', function () {
        $('#qc_work_allocation_filter_customer_name_suggestions_wrap').hide();
        var name = $(this).val();
        var addHref = $(this).data('href-check-name');
        if (name.length > 0) {
            var data = {
                txtName: name
            };
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: addHref + '/' + name,
                data: data,
                beforeSend: function () {
                    //$('#Loading').show();
                },
                success: function (result) {
                    if (!result.hasOwnProperty('status')) {
                        alert('Invalid');
                        return false;
                    }
                    else if (result['status'] == 'exist') {
                        var content = result['content'];
                        $('#qc_work_allocation_filter_order_name_suggestions_wrap').show();
                        $('#qc_work_allocation_filter_order_name_suggestions_content').empty(); // xoa thong tin cu
                        for (var i = 0; i < content.length; i++) {
                            var contentName = content[i]['name'];
                            var contentAddress = content[i]['constructionAddress'];
                            $('#qc_work_allocation_filter_order_name_suggestions_content').append(
                                "<a class='qc_work_allocation_filter_order_name_suggestions_select qc-link' data-name='" + contentName + "'> " + contentName + "</a> - <em class='qc-color-grey'>" + contentAddress + "</em><br/>"
                            );
                        }
                    } else if (result['status'] == 'notExist') {
                        $('#qc_work_allocation_filter_order_name_suggestions_wrap').hide();
                        $('#qc_work_allocation_filter_order_name_suggestions_content').empty();
                    }
                },
                complete: function () {
                    //$('#Loading').hide();
                },
                error: function () {
                    //alert('Error');
                }
            });
        }
    });
    /!* click vao ten don hang goi y*!/
    $('body').on('click', '.qc_work_allocation_filter_order_name_suggestions_select', function () {
        var txtOrderFilterKeyword = $(this).data('name');
        if ($('.txtAllocationManageKeywordFilter').val(txtOrderFilterKeyword)) {
            $('.btOrderFilterKeyword').click();
        }
    });

    $('body').on('click', '.btOrderFilterKeyword', function () {
        var txtOrderCustomerFilterKeyword = 'null';
        var orderName = $('.txtAllocationManageKeywordFilter').val();
        var cbStaffFilterId = $('.cbAllocationManageFinishStatus').val();
        if (orderName.length == 0) {
            alert('Bạn phải nhập thông tin tìm kiếm');
            $('.textOrderFilterName').focus();
            return false;
        } else {
            qc_main.url_replace($(this).data('href') + '/' + 100 + '/' + 100 + '/' + 100 + '/' + orderName + '/' + txtOrderCustomerFilterKeyword + '/' + cbStaffFilterId);
        }
    });

    // theo ngay tháng ...
    $('body').on('change', '.cbAllocationManageDayFilter', function () {
        var txtOrderFilterKeyword = null;
        var txtOrderCustomerFilterKeyword = null;
        var cbStaffFilterId = $('.cbAllocationManageFinishStatus').val();
        var dateFilter = $(this).val() + '/' + $('.cbAllocationManageMonthFilter').val() + '/' + $('.cbAllocationManageYearFilter').val();
        qc_main.url_replace($(this).data('href') + '/' + dateFilter + '/' + txtOrderFilterKeyword + '/' + txtOrderCustomerFilterKeyword + '/' + cbStaffFilterId);
    });
    $('body').on('change', '.cbAllocationManageMonthFilter', function () {
        var txtOrderFilterKeyword = null;
        var txtOrderCustomerFilterKeyword = null;
        var cbStaffFilterId = $('.cbAllocationManageFinishStatus').val();
        var dateFilter = $('.cbAllocationManageDayFilter').val() + '/' + $(this).val() + '/' + $('.cbAllocationManageYearFilter').val();
        qc_main.url_replace($(this).data('href') + '/' + dateFilter + '/' + txtOrderFilterKeyword + '/' + txtOrderCustomerFilterKeyword + '/' + cbStaffFilterId);
    });
    $('body').on('change', '.cbAllocationManageYearFilter', function () {
        var txtOrderFilterKeyword = null;
        var txtOrderCustomerFilterKeyword = null;
        var cbStaffFilterId = $('.cbAllocationManageFinishStatus').val();
        var dateFilter = $('.cbAllocationManageDayFilter').val() + '/' + $('.cbAllocationManageMonthFilter').val() + '/' + $(this).val();
        qc_main.url_replace($(this).data('href') + '/' + dateFilter + '/' + txtOrderFilterKeyword + '/' + txtOrderCustomerFilterKeyword + '/' + cbStaffFilterId);
    });

    /!* ---------- --------  loc theo ten khach hang ------- ---------*!/
    $('body').on('keyup', '.qc_work_allocation_manage_wrap .txtOrderCustomerFilterKeyword', function () {
        $('#qc_work_allocation_filter_order_name_suggestions_wrap').hide();
        var name = $(this).val();
        var addHref = $(this).data('href-check-name');
        if (name.length > 0) {
            var data = {
                txtName: name
            };
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: addHref + '/' + name,
                data: data,
                beforeSend: function () {
                    //$('#Loading').show();
                },
                success: function (result) {
                    if (!result.hasOwnProperty('status')) {
                        alert('Invalid');
                        return false;
                    }
                    else if (result['status'] == 'exist') {
                        var content = result['content'];
                        $('#qc_work_allocation_filter_customer_name_suggestions_wrap').show();
                        $('#qc_work_allocation_filter_customer_name_suggestions_content').empty(); // xoa thong tin cu
                        for (var i = 0; i < content.length; i++) {
                            var contentName = content[i]['name'];
                            var phone = content[i]['phone'];
                            $('#qc_work_allocation_filter_customer_name_suggestions_content').append(
                                "<a class='qc_work_allocation_filter_customer_name_suggestions_select qc-link' data-name='" + contentName + "'> " + contentName + "</a> - <em class='qc-color-grey'>" + phone + "</em><br/>"
                            );
                        }
                    } else if (result['status'] == 'notExist') {
                        $('#qc_work_allocation_filter_customer_name_suggestions_wrap').hide();
                        $('#qc_work_allocation_filter_customer_name_suggestions_content').empty();
                    }
                },
                complete: function () {
                    //$('#Loading').hide();
                },
                error: function () {
                    //alert('Error');
                }
            });
        }
    });
    /!* click vao ten khach hang hang goi y*!/
    $('body').on('click', '.qc_work_allocation_filter_customer_name_suggestions_select', function () {
        var txtOrderCustomerFilterKeyword = $(this).data('name');
        if ($('.txtOrderCustomerFilterKeyword').val(txtOrderCustomerFilterKeyword)) {
            $('.btOrderCustomerFilterKeyword').click();
        }
    });
    $('body').on('click', '.btOrderCustomerFilterKeyword', function () {
        var txtOrderFilterKeyword = 'null';
        var txtOrderCustomerFilterKeyword = $('.txtOrderCustomerFilterKeyword').val();
        if (txtOrderCustomerFilterKeyword.length == 0) {
            alert('Nhận từ khóa tìm kiếm');
            $('.txtOrderCustomerFilterKeyword').focus();
            return false;
        } else {
            var href = $(this).data('href');
            //100 tim tat ca thoi gian
            qc_main.url_replace($(this).data('href') + '/' + 100 + '/' + 100 + '/' + 100 + '/' + txtOrderFilterKeyword + '/' + txtOrderCustomerFilterKeyword);
        }
    });

    /!* ---------- --------  xac nhan hoan thanh thi  cong ------- ---------*!/
    $('#qc_work_allocation_manage_order_construction_wrap').on('click', '.qc_confirm_finish_get', function () {
        qc_master_submit.ajaxNotReload($(this).data('href'), '#qc_master', false);
    });
    $('body').on('click', '.frmWorkAllocationConstructionConfirmFinish .qc_save', function () {
        if (confirm('Tôi đồng ý với thông tin xác nhận này')) {
            qc_master_submit.ajaxFormHasReload('.frmWorkAllocationConstructionConfirmFinish', '', false);
            //qc_master_submit.ajaxFormNotReload('.frmWorkAllocationConstructionConfirmFinish', '', false);
        }
    });

    /!* ---------- --------  huy ban giao cong trinh ------- ---------*!/
    $('#qc_work_allocation_manage_order_construction_wrap').on('click', '.qc_delete_construction', function () {
        if (confirm('Bạn muốn hủy bàn giao công trình này?')) {
            qc_master_submit.ajaxHasReload($(this).data('href'), '', false);
        }
    });

    /!* ---------- --------  trien khai thi cong san pham - v1 ------- ---------*!/
    $(document).ready(function () {
        /!*$('#frmWorkAllocationManageProductConstruction').on('click', '.qc_product_work_allocation_staff_add', function () {
         qc_work_allocation.manage.productWorkAllocation.addStaff($(this).data('href'));
         });*!/
        //xoa nhan vien
        /!*$('body').on('click', '.qc_work_allocation_product_work_allocation_staff_add .qc_delete', function () {
         $(this).parents('.qc_work_allocation_product_work_allocation_staff_add').remove();
         });*!/
        //giao viec
        $('#frmWorkAllocationManageProductConstruction').on('click', '.qc_save', function () {
            qc_work_allocation.manage.productWorkAllocation.save($(this).parents('#frmWorkAllocationManageProductConstruction'));
        });

        //------ xem chi tiet thi cong san pham -------
        $('#qc_work_allocation_manage_order_construction_wrap').on('click', '.qc_work_allocation_view', function () {
            qc_master_submit.ajaxNotReload($(this).data('href'), '#qc_master', false);
            qc_main.scrollTop();
        });

        //------ xem chi tiet anh bao cao -------
        $('#qc_work_allocation_manage_order_construction_wrap').on('click', '.qc_work_allocation_report_image_view', function () {
            // qc_ad3d_order_order.viewAllocationReportImage($(this).data('href'));
        });

        //------ in don hang -------
        $('#qc_work_allocation_order_print_wrap_act').on('click', '.qc_print', function () {
            $(this).parents('#qc_work_allocation_order_print_wrap_act').hide();
            window.print();
        });

        //------ in xac nhan don hang -------
        $('#qc_work_allocation_order_print_confirm_wrap_act').on('click', '.qc_print', function () {
            $(this).parents('#qc_work_allocation_order_print_confirm_wrap_act').hide();
            window.print();
        });

        // -------- Xoa ban giao tren san pham -------
        $('#qc_work_allocation_manage_order_construction_wrap').on('click', '.qc_cancel_allocation_product', function () {
            qc_work_allocation.manage.productWorkAllocation.cancelAllocation($(this).data('href'));
        });
    });

});*/
