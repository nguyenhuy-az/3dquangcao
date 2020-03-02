/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_allocation = {
    filter: function (href) {
        qc_main.url_replace(href);
    },
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
    manage: {}
}

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

//------------ ---------- quản lý dơn hang ----------- ---------
$(document).ready(function(){
    var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val();
    //----- --------- tim theo ten nv vien nhan ----- ------------
    $('body').on('change', '.qc_work_allocation_manage_wrap .cbStaffFilterId', function () {
        var txtOrderFilterKeyword = null;
        var txtOrderCustomerFilterKeyword = null;
        qc_main.url_replace($(this).data('href') + '/' + dateFilter + '/' + txtOrderFilterKeyword + '/' +  txtOrderCustomerFilterKeyword + '/' + $(this).val());
    });

    //----- ----- tim theo ten don hang ----- -----
    /* loc theo ten don hang*/
    $('body').on('keyup', '.qc_work_allocation_manage_wrap .txtOrderFilterKeyword', function () {
        //alert('yesssssssssss');
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
    /* click vao ten don hang goi y*/
    $('body').on('click', '.qc_work_allocation_filter_order_name_suggestions_select', function () {
        var txtOrderFilterKeyword = $(this).data('name');
        if ($('.txtOrderFilterKeyword').val(txtOrderFilterKeyword)) {
            $('.btOrderFilterKeyword').click();
        }
    });

    $('body').on('click', '.btOrderFilterKeyword', function () {
        var txtOrderCustomerFilterKeyword = 'null';
        var orderName = $('.txtOrderFilterKeyword').val();
        var cbStaffFilterId = $('.cbStaffFilterId').val();
        if (orderName.length == 0) {
            alert('Bạn phải nhập thông tin tìm kiếm');
            $('.textOrderFilterName').focus();
            return false;
        } else {
            qc_main.url_replace($(this).data('href') + '/' + 100 + '/' + 100 + '/' + 100 + '/' + orderName + '/' + txtOrderCustomerFilterKeyword + '/' + cbStaffFilterId);
        }
    });

    /* ---------- --------  loc theo ten khach hang ------- ---------*/
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
    /* click vao ten khach hang hang goi y*/
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
            qc_main.url_replace($(this).data('href') + '/' + 100 + '/' + 100 + '/' + 100 +  '/' + txtOrderFilterKeyword + '/' + txtOrderCustomerFilterKeyword);
        }
    });

});
