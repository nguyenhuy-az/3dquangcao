/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_work_time_keeping_provisional = {
    viewImage: function (href) {
        qc_ad3d_submit.ajaxNotReload(href, $('#' + qc_ad3d.bodyIdName()), false);
        qc_main.scrollTop();
    },
    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
        qc_main.scrollTop();
    },
    warning: {
        timeBegin: {
            get: function (href) {
                qc_ad3d_submit.ajaxNotReload(href, $('#' + qc_ad3d.bodyIdName()), false);
                qc_main.scrollTop();
            },
            save: function (frm) {
                var notifyContent = $(frm).find('.qc_notify');
                if (confirm('Xác nhận đồng ý gửi cảnh báo này?')) {
                    qc_ad3d_submit.ajaxFormHasReload(frm, notifyContent, true);
                    qc_main.scrollTop();
                }
            },
        },
        timeEnd: {
            get: function (href) {
                qc_ad3d_submit.ajaxNotReload(href, $('#' + qc_ad3d.bodyIdName()), false);
                qc_main.scrollTop();
            },
            save: function (frm) {
                var notifyContent = $(frm).find('.qc_notify');
                if (confirm('Xác nhận đồng ý gửi cảnh báo này?')) {
                    qc_ad3d_submit.ajaxFormHasReload(frm, notifyContent, true);
                    qc_main.scrollTop();
                }
            },
        },
    },
    confirm: {
        get: function (href) {
            qc_ad3d_submit.ajaxNotReload(href, $('#' + qc_ad3d.bodyIdName()), false);
            qc_main.scrollTop();
        },
        save: function (frm) {
            var notifyContent = $(frm).find('.notifyConfirm');
            if (confirm('Xác nhận đồng ý chấm công này?')) {
                //qc_ad3d_submit.ajaxFormNotReload(frm, notifyContent, true);
                qc_ad3d_submit.ajaxFormHasReload(frm, notifyContent, true);
                qc_main.scrollTop();
            }
        },
    },
    //yeu cau tang ca
    overtTime: {
        getForm: function (href) {
            qc_ad3d_submit.ajaxNotReload(href, $('#' + qc_ad3d.bodyIdName()), false);
            qc_main.scrollTop();
        },
        save: function (frm) {
            var notifyContent = $(frm).find('.qc_notify_content');
            if (confirm('Xác nhận đồng ý báo tăng ca này?')) {
                //qc_ad3d_submit.ajaxFormNotReload(frm, notifyContent, true);
                qc_ad3d_submit.ajaxFormHasReload(frm, notifyContent, true);
                qc_main.scrollTop();
            }
        },
        cancel: function (href) {
            if (confirm('Xác nhận đồng ý hủy báo tăng ca này?')) {
                qc_ad3d_submit.ajaxHasReload(href, '', false);
            }
        }

    }
}
//-------------------- lọc ------------
$(document).ready(function () {
    //khi chọn công ty...
    $('body').on('change', '.cbCompanyFilter', function () {
        var companyId = $(this).val();
        //var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val();
        var dateFilter = 0 + '/' + 0 + '/' + 0;
        var href = $(this).data('href-filter');
        if (companyId != '') {
            href = href + '/' + companyId + '/' + dateFilter;
        }
        qc_main.url_replace(href);
    })
});

$(document).ready(function () {
    // xem anh bao cao
    $('body').on('click', '.qc_ad3d_timekeeping_provisional_image_view', function () {
        qc_ad3d_work_time_keeping_provisional.viewImage($(this).data('href'));
    });
})

//-------------------- CANH BAO CHAM CONG ------------
$(document).ready(function () {
    // Canh bao gio vao
    $('.qc_ad3d_list_object').on('click', '.qc_warning_time_begin', function () {
        qc_ad3d_work_time_keeping_provisional.warning.timeBegin.get($(this).data('href'));
    });
    $('body').on('click', '.qc_frm_warming_time_begin_add .qc_save', function () {
        qc_ad3d_work_time_keeping_provisional.warning.timeBegin.save($(this).parents('.qc_frm_warming_time_begin_add'));
    });
    // Canh bao gio ra
    $('.qc_ad3d_list_object').on('click', '.qc_warning_time_end', function () {
        qc_ad3d_work_time_keeping_provisional.warning.timeEnd.get($(this).data('href'));
    });
    $('body').on('click', '.qc_frm_warming_time_end_add .qc_save', function () {
        qc_ad3d_work_time_keeping_provisional.warning.timeEnd.save($(this).parents('.qc_frm_warming_time_end_add'));
    });
    // Huy canh bao gio vao
    $('body').on('click', '.qc_warning_time_begin_cancel', function () {
        if (confirm('Xác nhận hủy cảnh báo giờ vào')) {
            qc_ad3d_submit.ajaxHasReload($(this).data('href'), '', false);
        }
    });
    // Huy canh bao gio ra
    $('body').on('click', '.qc_warning_time_end_cancel', function () {
        if (confirm('Xác nhận hủy cảnh báo giờ ra')) {
            qc_ad3d_submit.ajaxHasReload($(this).data('href'), '', false);
        }
    });
});

//-------------------- xac nhan cham cong ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_confirm', function () {
        qc_ad3d_work_time_keeping_provisional.confirm.get($(this).data('href'));
    });
    $('body').on('click', '.qc_ad3d_frm_confirm .qc_save', function () {
        qc_ad3d_work_time_keeping_provisional.confirm.save($(this).parents('.qc_ad3d_frm_confirm'));
    });
});

//-------------------- yeu cau tang ca ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_over_time_request_get', function () {
        qc_ad3d_work_time_keeping_provisional.overtTime.getForm($(this).data('href'));
    });
    $('body').on('click', '.qc_ad3d_frm_over_time_add .qc_save', function () {
        qc_ad3d_work_time_keeping_provisional.overtTime.save($(this).parents('.qc_ad3d_frm_over_time_add'));
    });
    $('.qc_ad3d_list_object').on('click', '.qc_over_time_request_cancel', function () {
        qc_ad3d_work_time_keeping_provisional.overtTime.cancel($(this).data('href'));
    });
});

//-------------------- off ------------
$(document).ready(function () {
    //select company
    $('.qc_ad3d_index_content').on('change', '.frmAddOff .cbCompany', function () {
        qc_main.url_replace($(this).data('href') + '/' + $(this).val());
    })

    //select workId
    $('.qc_ad3d_index_content').on('change', '.frmAddOff .cbWork', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompany').val() + '/' + $(this).val());
    })

    //save
    $('.qc_ad3d_index_content').on('click', '.frmAddOff .qc_save', function () {
        qc_ad3d_work_time_keeping_provisional.off.save($(this).parents('.frmAddOff'));
    })
});
