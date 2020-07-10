/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_timekeeping = {
    timeBegin: {
        getForm: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        },
        save: function (form) {
            var cbDayBegin = $(form).find("select[name='cbDayBegin']");
            var cbHoursBegin = $(form).find("select[name='cbHoursBegin']");
            var notifyContent = $(form).find('.frm_notify');
            if (qc_main.check.inputNull(cbDayBegin, 'Chọn ngày Vào')) {
                $(cbDayBegin).focus();
                return false;
            }
            if (qc_main.check.inputNull(cbHoursBegin, 'Chọn giờ vào')) {
                cbHoursBegin.focus();
                return false;
            } else {
                qc_master_submit.ajaxFormHasReload(form, notifyContent, true);
                qc_main.scrollTop();
            }
        },
    },
    timeEnd: {
        getForm: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        },
        save: function (form) {
            var notifyContent = $(form).find('.frm_notify');
            //qc_master_submit.ajaxFormNotReload(form, notifyContent, true);
            qc_master_submit.ajaxFormHasReload(form, notifyContent, true);
            qc_main.scrollTop();
        },
        getEdit: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        },
        saveEdit: function (form) {
            var notifyContent = $(form).find('.frm_notify');
            qc_master_submit.ajaxFormHasReload(form, notifyContent, true);
            qc_main.scrollTop();
        },

    },
    offWork: {
        getForm: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        },
        save: function (form) {
            var notifyContent = $(form).find('.frm_notify');
            qc_master_submit.ajaxFormHasReload(form, notifyContent, true);
            qc_main.scrollTop();
        },
        cancel: function (href) {
            if (confirm('Bạn muốn hủy giấy phép xin nghỉ?')) {
                qc_master_submit.ajaxHasReload(href, '#qc_master', false);
            }
        }
    },
    lateWork: {
        getForm: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        },
        save: function (form) {
            var notifyContent = $(form).find('.frm_notify');
            qc_master_submit.ajaxFormHasReload(form, notifyContent, true);
            qc_main.scrollTop();
        },
        cancel: function (href) {
            if (confirm('Bạn muốn hủy giấy phép trễ?')) {
                qc_master_submit.ajaxHasReload(href, '#qc_master', false);
            }
        }
    },
    cancel: function (href) {
        if (confirm('Bạn muốn hủy chấm công này?')) {
            qc_master_submit.ajaxHasReload(href, '#qc_master', false);
        }
    },
    image: {
        view: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        },
        getForm: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        },
        save: function (form) {
            var notifyContent = $(form).find('.frm_notify');
            //qc_master_submit.ajaxFormNotReload(form, notifyContent, true);
            qc_master_submit.ajaxFormHasReload(form, notifyContent, true);
            qc_main.scrollTop();
        },
        delete: function (href) {
            if (confirm('Bạn muốn xóa ảnh này?')) {
                qc_master_submit.ajaxHasReload(href, '#qc_master', false);
            }
        }
    }
}
//======================== CHẤM CÔNG ==============================
$(document).ready(function () {
    // xem anh cham cong
    $('.qc_timekeeping_container_wrap').on('click','.qc_work_allocation_report_image_view', function(){
        qc_work_timekeeping.image.view($(this).data('href'));
    });
    // báo giờ vào
    $('body').on('click', '.qc_time_begin_action', function () {
        var href = $(this).data('href') + '/' + $(this).parents('.qc_timekeeping_container_wrap').data('work');
        qc_work_timekeeping.timeBegin.getForm(href);
    });

    $('body').on('click', '.frm_time_begin_add .qc_save', function () {
        qc_work_timekeeping.timeBegin.save($(this).parents('.frm_time_begin_add'));
    });
    // xin nghỉ
    $('body').on('click', '.ac_off_work_action', function () {
        var href = $(this).data('href') + '/' + $(this).parents('.qc_timekeeping_container_wrap').data('work');
        qc_work_timekeeping.offWork.getForm(href);
    });
    $('body').on('click', '.frm_off_work_add .qc_save', function () {
        qc_work_timekeeping.offWork.save($(this).parents('.frm_off_work_add'));
    });

    $('body').on('click', '.ac_off_work_cancel', function () {
        var href = $(this).parents('.qc_timekeeping_off_work_object').data('href-off-cancel') + '/' + $(this).parents('.qc_timekeeping_off_work_object').data('off-work');
        qc_work_timekeeping.offWork.cancel(href);
    });

    // xin trễ
    $('body').on('click', '.ac_late_work_action', function () {
        var href = $(this).data('href') + '/' + $(this).parents('.qc_timekeeping_container_wrap').data('work');
        qc_work_timekeeping.lateWork.getForm(href);
    });
    $('body').on('click', '.frm_late_work_add .qc_save', function () {
        qc_work_timekeeping.lateWork.save($(this).parents('.frm_late_work_add'));
    });

    $('body').on('click', '.ac_late_work_cancel', function () {
        var href = $(this).parents('.qc_timekeeping_late_work_object').data('href-late-cancel') + '/' + $(this).parents('.qc_timekeeping_late_work_object').data('late-work');
        qc_work_timekeeping.lateWork.cancel(href);
    });

    // báo giờ ra
    $('body').on('click', '.qc_time_end_action', function () {
        var href = $(this).parents('.qc_timekeeping_contain').data('href-time-end') + '/' + $(this).parents('.qc_timekeeping_provisional_object').data('timekeeping-provisional');
        qc_work_timekeeping.timeEnd.getForm(href);
    });

    $('body').on('click', '.qc_frm_time_end_add .qc_save', function () {
        qc_work_timekeeping.timeEnd.save($(this).parents('.qc_frm_time_end_add'));
    });

    //sua gio ra
    $('body').on('click', '.qc_time_end_edit_action', function () {
        qc_work_timekeeping.timeEnd.getEdit($(this).data('href'));
    });
    $('body').on('click', '.qc_frm_time_end_edit .qc_save', function () {
        qc_work_timekeeping.timeEnd.saveEdit($(this).parents('.qc_frm_time_end_edit'));
    });

    // thêm ảnh xác nhận công việc
    $('body').on('click', '.qc_timekeeping_provisional_image_action', function () {
        var href = $(this).parents('.qc_timekeeping_contain').data('href-image') + '/' + $(this).parents('.qc_timekeeping_provisional_object').data('timekeeping-provisional');
        qc_work_timekeeping.image.getForm(href);
    });

    $('body').on('click', '.qc_frm_timekeeping_image_add .qc_save', function () {
        qc_work_timekeeping.image.save($(this).parents('.qc_frm_timekeeping_image_add'));
    });

    // xoa anh bao cham cong
    $('body').on('click', '.ac_delete_image_action', function () {
        qc_work_timekeeping.image.delete($(this).data('href'));
    });
    // hủy chấm công
    $('body').on('click', '.qc_time_end_cancel', function () {
        var href = $(this).parents('.qc_timekeeping_contain').data('href-cancel') + '/' + $(this).parents('.qc_timekeeping_provisional_object').data('timekeeping-provisional');
        qc_work_timekeeping.cancel(href);
    });
});
