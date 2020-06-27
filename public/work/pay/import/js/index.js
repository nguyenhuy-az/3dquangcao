/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_pay_import = {
    filter: function (href) {
        qc_main.url_replace(href);
    },
    add: {
        addImage: function (href) {
            qc_master_submit.ajaxNotReloadNoScrollTop(href, '#qc_work_pay_import_import_add_image_wrap', false);
        },
        addSupplies: function (href) {
            qc_master_submit.ajaxNotReloadNoScrollTop(href, '#qc_work_pay_import_import_add_supplies_wrap', false);
        },
        addTool: function (href) {
            qc_master_submit.ajaxNotReloadNoScrollTop(href, '#qc_work_pay_import_import_add_tool_wrap', false);
        },
        addSuppliesToolNew: function (href) {
            qc_master_submit.ajaxNotReloadNoScrollTop(href, '#qc_work_pay_import_import_supplies_tool_new_wrap', false);
        },
        save: function (form) {
            //var cbDayBegin = $(form).find("select[name='cbDayBegin']");
            //var cbHoursBegin = $(form).find("select[name='cbHoursBegin']");
            //var notifyContent = $(form).find('.frm_notify');
            //qc_master_submit.ajaxFormHasReload(form, '', true);
            //qc_main.scrollTop();
            //$('.qc_work_pay_import_import_reset').click();
            qc_master_submit.normalForm(form);
        },
    },
    delete: function (href) {
        if (confirm('Bạn muốn xóa hóa đơn này?')) {
            qc_master_submit.ajaxHasReload(href, '#qc_master', false);
        }
    },
    confirmPay: function (href) {
        if (confirm('Tôi đã được thanh toán hóa đơn này')) {
            qc_master_submit.ajaxHasReload(href, '#qc_master', false);
        }
    }
}

//====================== MUA VAT TU ========================
$(document).ready(function () {
    //theo ngày
    $('body').on('change', '.qc_work_pay_import_day_filter', function () {
        qc_work_pay_import.import.filter($(this).data('href') + '/' + $(this).val() + '/' + $('.qc_work_pay_import_month_filter').val() + '/' + $('.qc_work_pay_import_year_filter').val() + '/' + $('.qc_work_pay_import_pay_status').val());
    });
    //theo tháng
    $('body').on('change', '.qc_work_pay_import_month_filter', function () {
        qc_work_pay_import.import.filter($(this).data('href') + '/' + $('.qc_work_pay_import_day_filter').val() + '/' + $(this).val() + '/' + $('.qc_work_pay_import_year_filter').val() + '/' + $('.qc_work_pay_import_pay_status').val());
    });
    // năm
    $('body').on('change', '.qc_work_pay_import_year_filter', function () {
        qc_work_pay_import.import.filter($(this).data('href') + '/' + $('.qc_work_pay_import_day_filter').val() + '/' + $('.qc_work_pay_import_month_filter').val() + '/' + $(this).val() + '/' + $('.qc_work_pay_import_pay_status').val());
    })
    //theo trang thai thanh toan
    $('body').on('change', '.qc_work_pay_import_pay_status', function () {
        qc_work_pay_import.import.filter($(this).data('href') + '/' + $('.qc_work_pay_import_day_filter').val() + '/' + $('.qc_work_pay_import_month_filter').val() + '/' + $('.qc_work_pay_import_year_filter').val() + '/' + $(this).val());
    });
});
$(document).ready(function () {
    $('body').on('click', '#frm_work_import_add .qc_work_pay_import_import_save', function () {
        //qc_work_pay_import.login($(this).parents('.frmWorkLogin'));
    });

    // thêm hình ảnh
    $('body').on('click', '#frm_work_import_add .qc_work_pay_import_import_add_image', function () {
        qc_work_pay_import.import.add.addImage($(this).data('href'));
    });
    $('body').on('click', '#frm_work_import_add .qc_work_pay_import_import_image_add .qc_delete', function () {
        $(this).parents('.qc_work_pay_import_import_image_add').remove();
    });

    // thêm vật tư
    $('body').on('click', '#frm_work_import_add .qc_work_pay_import_import_add_supplies', function () {
        qc_work_pay_import.import.add.addSupplies($(this).data('href'));
    });
    $('body').on('click', '#frm_work_import_add .qc_work_pay_import_import_supplies_add .qc_delete', function () {
        $(this).parents('.qc_work_pay_import_import_supplies_add').remove();
    });

    // thêm dụng cụ
    $('body').on('click', '#frm_work_import_add .qc_work_pay_import_import_add_tool', function () {
        qc_work_pay_import.import.add.addTool($(this).data('href'));
    });
    $('body').on('click', '#frm_work_import_add .qc_work_pay_import_import_tool_add .qc_delete', function () {
        $(this).parents('.qc_work_pay_import_import_tool_add').remove();
    });

    // thêm vật tư mới
    $('body').on('click', '#frm_work_import_add .qc_work_pay_import_import_supplies_tool_add_new', function () {
        qc_work_pay_import.import.add.addSuppliesToolNew($(this).data('href'));
    });
    $('body').on('click', '#frm_work_import_add .qc_work_pay_import_import_supplies_tool_new_add .qc_delete', function () {
        $(this).parents('.qc_work_pay_import_import_supplies_tool_new_add').remove();
    });

    //lưu
    $('body').on('click', '#frm_work_import_add .qc_work_pay_import_import_save', function () {
        qc_work_pay_import.import.add.save($(this).parents('#frm_work_import_add'));
    });

    //xác nhận thanh toán
    $('body').on('click', '.qc_work_pay_import_import_wrap .qc_work_pay_import_import_confirm_pay_act', function () {
        qc_work_pay_import.import.confirmPay($(this).data('href'));
    });

    //xóa
    $('body').on('click', '.qc_work_pay_import_import_wrap .qc_work_pay_import_import_delete', function () {
        qc_work_pay_import.import.delete($(this).data('href'));
    });
});
