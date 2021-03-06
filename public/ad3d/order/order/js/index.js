/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_order_order = {
    view: function (listObject) {
        qc_main.url_replace($(listObject).parents('.qc_ad3d_list_content').data('href-view') + '/' + $(listObject).data('object'));
    },
    viewCustomer: function (href) {
        qc_ad3d_submit.ajaxNotReload(href, $('#' + qc_ad3d.bodyIdName()), false);
        qc_main.scrollTop();
    },
    viewAllocationReportImage: function (href) {
        qc_ad3d_submit.ajaxNotReload(href, $('#' + qc_ad3d.bodyIdName()), false);
        qc_main.scrollTop();
    },
    viewProductDesignImage: function (href) {
        qc_ad3d_submit.ajaxNotReload(href, $('#' + qc_ad3d.bodyIdName()), false);
        qc_main.scrollTop();
    },
    confirm: {
        getForm: function (listObject) {
            qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-confirm') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
            qc_main.scrollTop();
        },
        save: function (frm) {
            if (confirm('Bạn Đồng ý với đơn hàng này?')) {
                var containNotify = $(frm).find('.frm_notify');
                //alert('yes');
                qc_ad3d_submit.ajaxFormHasReload(frm, containNotify, true);
            }
        }
    }
}
/*kiem tra so dt khi nhap don hang*/
$(document).ready(function () {
    //nhap so dien thoai
    $('body').on('keyup', '.txtPhone', function () {
        var phone = $(this).val();
        var addHref = $(this).data('href-replace');
        var checkHref = $(this).data('href-check');
        if (phone.length > 9) {
            var data = {
                txtPhone: phone
            };
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: checkHref + '/' + phone,
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
                        var customerId = result['customerId'];
                        qc_main.url_replace(addHref + '/' + customerId);
                        //alert('Successful customerId:' + customerId);
                    }
                },
                complete: function () {
                    //$('#Loading').hide();
                },
                error: function () {
                    alert('Error');
                }
            });
        }
    })
});

// in
$(document).ready(function () {
    /*in hoa don*/
    $('#qc_ad3d_order_order_print_wrap').on('click', '.qc_print', function () {
        $(this).parents('#qc_ad3d_order_order_print_wrap_act').remove();
        window.print();
    });

    /*in nghiem thu*/
    $('#qc_ad3d_order_order_print_confirm_wrap').on('click', '.qc_print', function () {
        $(this).parents('#qc_ad3d_order_order_print_confirm_wrap_act').remove();
        window.print();
    });
});

//-------------------- LOC DON HANG ------------
$(document).ready(function () {
    //khi chọn công ty...
    var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val() + '/' + $('.cbPaymentStatus').val();
    $('body').on('change', '.cbCompanyFilter', function () {
        var txtOrderFilterKeyword = null;
        var txtOrderCustomerFilterKeyword = null;
        var cbStaffFilterId = $('.cbStaffFilterId').val();
        var companyId = $(this).val();
        var href = $(this).data('href-filter');
        if (companyId != '') {
            href = href + '/' + companyId +  '/' + dateFilter + '/' + txtOrderFilterKeyword + '/' + txtOrderCustomerFilterKeyword +'/' + cbStaffFilterId;
        }
        qc_main.url_replace(href);
    });
    // theo ngay tháng ...
    $('body').on('change', '.cbDayFilter', function () {
        var txtOrderFilterKeyword = null;
        var txtOrderCustomerFilterKeyword = null;
        var cbStaffFilterId = $('.cbStaffFilterId').val();
        var dateFilter = $(this).val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val() + '/' + $('.cbPaymentStatus').val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + txtOrderFilterKeyword + '/' +  txtOrderCustomerFilterKeyword + '/' + cbStaffFilterId);
    });
    $('body').on('change', '.cbMonthFilter', function () {
        var txtOrderFilterKeyword = null;
        var txtOrderCustomerFilterKeyword = null;
        var cbStaffFilterId = $('.cbStaffFilterId').val();
        var dateFilter = $('.cbDayFilter').val() + '/' + $(this).val() + '/' + $('.cbYearFilter').val() + '/' + $('.cbPaymentStatus').val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + txtOrderFilterKeyword + '/' +  txtOrderCustomerFilterKeyword + '/' + cbStaffFilterId);
    });
    $('body').on('change', '.cbYearFilter', function () {
        var txtOrderFilterKeyword = null;
        var txtOrderCustomerFilterKeyword = null;
        var cbStaffFilterId = $('.cbStaffFilterId').val();
        var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $(this).val() + '/' + $('.cbPaymentStatus').val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + txtOrderFilterKeyword + '/' +  txtOrderCustomerFilterKeyword + '/' + cbStaffFilterId);
    });



    //----- ----- tim theo ten don hang ----- -----
    /* loc theo ten don hang*/
    $('body').on('keyup', '.qc_ad3d_list_content .txtOrderFilterKeyword', function () {
        $('#qc_order_filter_customer_name_suggestions_wrap').hide();
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
                        $('#qc_order_filter_order_name_suggestions_wrap').show();
                        $('#qc_order_filter_order_name_suggestions_content').empty(); // xoa thong tin cu
                        for (var i = 0; i < content.length; i++) {
                            var contentName = content[i]['name'];
                            var contentAddress = content[i]['constructionAddress'];
                            $('#qc_order_filter_order_name_suggestions_content').append(
                                "<a class='qc_order_filter_order_name_suggestions_select qc-link' data-name='" + contentName + "'> " + contentName + "</a> - <em class='qc-color-grey'>" + contentAddress + "</em><br/>"
                            );
                        }
                    } else if (result['status'] == 'notExist') {
                        $('#qc_order_filter_order_name_suggestions_wrap').hide();
                        $('#qc_order_filter_order_name_suggestions_content').empty();
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
    $('body').on('click', '.qc_order_filter_order_name_suggestions_select', function () {
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
            qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + 100 + '/' + 100 + '/' + 100 + '/' + + $('.cbPaymentStatus').val() + '/' + orderName + '/' + txtOrderCustomerFilterKeyword + '/' + cbStaffFilterId);
        }
    });

    /* ---------- --------  loc theo ten khach hang ------- ---------*/
    $('body').on('keyup', '.qc_ad3d_list_content .txtOrderCustomerFilterKeyword', function () {
        $('#qc_order_filter_order_name_suggestions_wrap').hide();
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
                        $('#qc_order_filter_customer_name_suggestions_wrap').show();
                        $('#qc_order_filter_customer_name_suggestions_content').empty(); // xoa thong tin cu
                        for (var i = 0; i < content.length; i++) {
                            var contentName = content[i]['name'];
                            var phone = content[i]['phone'];
                            $('#qc_order_filter_customer_name_suggestions_content').append(
                                "<a class='qc_order_filter_customer_name_suggestions_select qc-link' data-name='" + contentName + "'> " + contentName + "</a> - <em class='qc-color-grey'>" + phone + "</em><br/>"
                            );
                        }
                    } else if (result['status'] == 'notExist') {
                        $('#qc_order_filter_customer_name_suggestions_wrap').hide();
                        $('#qc_order_filter_customer_name_suggestions_content').empty();
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
    $('body').on('click', '.qc_order_filter_customer_name_suggestions_select', function () {
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
            qc_main.url_replace($(this).data('href') + '/'+ 1000+ '/' + 100 + '/' + 100 + '/' + 100 + '/' + $('.cbPaymentStatus').val() + '/' + txtOrderFilterKeyword + '/' + txtOrderCustomerFilterKeyword);
        }
    });

    //----- --------- tim theo ten nv vien nhan ----- ------------
    $('body').on('change', '.cbStaffFilterId', function () {
        var txtOrderFilterKeyword = null;
        var txtOrderCustomerFilterKeyword = null;
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + txtOrderFilterKeyword + '/' +  txtOrderCustomerFilterKeyword + '/' + $(this).val());
    });


    //----- ----- tim theo trang thai thanh toan ----- -----
    $('body').on('change', '.cbPaymentStatus', function () {
        var txtOrderFilterKeyword = null;
        var txtOrderCustomerFilterKeyword = null;
        var cbStaffFilterId = $('.cbStaffFilterId').val();
        var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val() + '/' + $(this).val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + txtOrderFilterKeyword + '/' +  txtOrderCustomerFilterKeyword + '/' + cbStaffFilterId);
    });
});

//====== ======= ====== XEM CHI TIET ====== ======= ======
$(document).ready(function () {
    //------ xem chi tiet don hang -------
    $('#qc_ad3d_order_order_wrap').on('click', '.qc_view', function () {
        qc_ad3d_order_order.view($(this).parents('.qc_ad3d_list_object'));
    });

    //------ xem chi tiet khach hang -------
    $('#qc_ad3d_order_order_wrap').on('click', '.qc_view_customer', function () {
        var href = $(this).parents('.qc_ad3d_list_content').data('href-view-customer') + '/' + $(this).data('customer');
        qc_ad3d_order_order.viewCustomer(href);
    });

    //------ xem chi tiet anh bao cao -------
    $('#qc_ad3d_order_order_wrap').on('click', '.qc_work_allocation_report_image_view', function () {
        qc_ad3d_order_order.viewAllocationReportImage($(this).data('href'));
    });


});

//====== ======= ====== TRIEN KHAI THI CONG ====== ======= ======
// huy ban giao
$(document).ready(function () {
    //------ xem chi tiet anh thiet ke -------
    $('#qc_ad3d_order_order_wrap').on('click', '.qc_product_design_image_view', function () {
        qc_ad3d_order_order.viewProductDesignImage($(this).data('href'));
    });
});

