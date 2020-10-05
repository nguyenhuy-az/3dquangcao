/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_allocation_orders = {
    filter: function (href) {
        qc_main.url_replace(href);
    },
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
            if ($(form).find('.qc_work_allocation_orders_product_work_allocation_staff_add').length > 0) {
                //qc_ad3d_submit.ajaxFormHasReload(form, '', false);
                //qc_main.scrollTop();
                //qc_ad3d_submit.normalForm(form);
                //qc_ad3d_order_order.add.checkProductInput();
                qc_work_allocation_orders.productWorkAllocation.checkSubmit(form);
            } else {
                alert('Chọn nhân viên');
                return false;
            }
        },
        checkSubmit: function (form) {
            $('#frmWorkAllocationOrderProductConstruction .qc_work_allocation_orders_product_work_allocation_staff_add').filter(function () {
                if ($(this).is(':last-child')) {
                    if (qc_work_allocation_orders.productWorkAllocation.checkInfo(this)) {
                        //qc_ad3d_submit.ajaxFormNotReload(form,'',false);
                        qc_master_submit.normalForm(form);
                    }
                } else {
                    if (!qc_work_allocation_orders.productWorkAllocation.checkInfo(this)) {
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

    }
}
//------------ ---------- quản lý dơn hang thi cong ----------- ---------
$(document).ready(function () {
    var dateFilter = $('.cbAllocationManageDayFilter').val() + '/' + $('.cbAllocationManageMonthFilter').val() + '/' + $('.cbAllocationManageYearFilter').val();
    //----- --------- tim theo trang thai thi cong ----- ------------
    $('body').on('change', '.qc_work_allocation_orders_manage_wrap .cbAllocationManageFinishStatus', function () {
        var txtOrderFilterKeyword = null;
        var txtOrderCustomerFilterKeyword = null;
        qc_main.url_replace($(this).data('href') + '/' + dateFilter + '/' + txtOrderFilterKeyword + '/' + txtOrderCustomerFilterKeyword + '/' + $(this).val());
    });

    //----- ----- tim theo ten don hang ----- -----
    $('body').on('keyup', '.qc_work_allocation_orders_manage_wrap .txtAllocationManageKeywordFilter', function () {
        $('#qc_work_allocation_orders_filter_customer_name_suggestions_wrap').hide();
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
                        $('#qc_work_allocation_orders_filter_order_name_suggestions_wrap').show();
                        $('#qc_work_allocation_orders_filter_order_name_suggestions_content').empty(); // xoa thong tin cu
                        for (var i = 0; i < content.length; i++) {
                            var contentName = content[i]['name'];
                            var contentAddress = content[i]['constructionAddress'];
                            $('#qc_work_allocation_orders_filter_order_name_suggestions_content').append(
                                "<a class='qc_work_allocation_orders_filter_order_name_suggestions_select qc-link' data-name='" + contentName + "'> " + contentName + "</a> - <em class='qc-color-grey'>" + contentAddress + "</em><br/>"
                            );
                        }
                    } else if (result['status'] == 'notExist') {
                        $('#qc_work_allocation_orders_filter_order_name_suggestions_wrap').hide();
                        $('#qc_work_allocation_orders_filter_order_name_suggestions_content').empty();
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
    /* click vao ten don hang goi y*/
    $('body').on('click', '.qc_work_allocation_orders_filter_order_name_suggestions_select', function () {
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

    /* ---------- --------  loc theo ten khach hang ------- ---------*/
    $('body').on('keyup', '.qc_work_allocation_orders_manage_wrap .txtOrderCustomerFilterKeyword', function () {
        $('#qc_work_allocation_orders_filter_order_name_suggestions_wrap').hide();
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
                        $('#qc_work_allocation_orders_filter_customer_name_suggestions_wrap').show();
                        $('#qc_work_allocation_orders_filter_customer_name_suggestions_content').empty(); // xoa thong tin cu
                        for (var i = 0; i < content.length; i++) {
                            var contentName = content[i]['name'];
                            var phone = content[i]['phone'];
                            $('#qc_work_allocation_orders_filter_customer_name_suggestions_content').append(
                                "<a class='qc_work_allocation_orders_filter_customer_name_suggestions_select qc-link' data-name='" + contentName + "'> " + contentName + "</a> - <em class='qc-color-grey'>" + phone + "</em><br/>"
                            );
                        }
                    } else if (result['status'] == 'notExist') {
                        $('#qc_work_allocation_orders_filter_customer_name_suggestions_wrap').hide();
                        $('#qc_work_allocation_orders_filter_customer_name_suggestions_content').empty();
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
    /* click vao ten khach hang hang goi y*/
    $('body').on('click', '.qc_work_allocation_orders_filter_customer_name_suggestions_select', function () {
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

    /* ---------- --------  xac nhan hoan thanh thi  cong ------- ---------*/
    $('#qc_work_allocation_order_construction_wrap').on('click', '.qc_confirm_finish_get', function () {
        qc_master_submit.ajaxNotReload($(this).data('href'), '#qc_master', false);
    });
    $('body').on('click', '.frmWorkAllocationConstructionConfirmFinish .qc_save', function () {
        if (confirm('Tôi đồng ý với thông tin xác nhận này')) {
            qc_master_submit.ajaxFormHasReload('.frmWorkAllocationConstructionConfirmFinish', '', false);
            //qc_master_submit.ajaxFormNotReload('.frmWorkAllocationConstructionConfirmFinish', '', false);
        }
    });

    /* ---------- --------  huy ban giao cong trinh ------- ---------*/
    $('#qc_work_allocation_order_construction_wrap').on('click', '.qc_construction_cancel', function () {
        if (confirm('Bạn muốn hủy bàn giao công trình này?')) {
            qc_master_submit.ajaxHasReload($(this).data('href'), '', false);
        }
    });

});
/* ---------- --------  trien khai thi cong san pham - v1 ------- ---------*/
$(document).ready(function () {
    /*$('#frmWorkAllocationOrderProductConstruction').on('click', '.qc_product_work_allocation_staff_add', function () {
     qc_work_allocation_orders.manage.productWorkAllocation.addStaff($(this).data('href'));
     });*/
    //xoa nhan vien
    /*$('body').on('click', '.qc_work_allocation_orders_product_work_allocation_staff_add .qc_delete', function () {
     $(this).parents('.qc_work_allocation_orders_product_work_allocation_staff_add').remove();
     });*/
    //giao viec
    $('#frmWorkAllocationOrderProductConstruction').on('click', '.qc_save', function () {
        qc_work_allocation_orders.productWorkAllocation.save($(this).parents('#frmWorkAllocationOrderProductConstruction'));
    });

    //------ xem chi tiet thi cong san pham -------
    $('#qc_work_allocation_order_construction_wrap').on('click', '.qc_work_allocation_orders_view', function () {
        qc_master_submit.ajaxNotReload($(this).data('href'), '#qc_master', false);
        qc_main.scrollTop();
    });

    //------ xem chi tiet anh bao cao -------
    $('#qc_work_allocation_order_construction_wrap').on('click', '.qc_work_allocation_orders_report_image_view', function () {
        //qc_ad3d_order_order.viewAllocationReportImage($(this).data('href'));
    });

    //------ in don hang -------
    $('#qc_work_allocation_orders_order_print_wrap_act').on('click', '.qc_print', function () {
        $(this).parents('#qc_work_allocation_orders_order_print_wrap_act').hide();
        window.print();
    });

    //------ in xac nhan don hang -------
    $('#qc_work_allocation_orders_order_print_confirm_wrap_act').on('click', '.qc_print', function () {
        $(this).parents('#qc_work_allocation_orders_order_print_confirm_wrap_act').hide();
        window.print();
    });

    // -------- Xoa ban giao tren san pham -------
    $('#qc_work_allocation_order_construction_wrap').on('click', '.qc_cancel_allocation_product', function () {
        qc_work_allocation_orders.productWorkAllocation.cancelAllocation($(this).data('href'));
    });
    // xem bao cao
    $('#qc_work_allocation_order_construction_wrap').on('click', '.qc_work_allocation_view', function () {
        qc_master_submit.ajaxNotReload($(this).data('href'), '#qc_master', false);
        qc_main.scrollTop();
    });
});


