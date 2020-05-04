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
    cancel: function (listObject) {
        if (confirm('Bạn muốn hủy tin này')) {
            qc_ad3d_submit.ajaxHasReload($(listObject).parents('.qc_ad3d_list_content').data('href-cancel') + '/' + $(listObject).data('object'), '#', false);
        }
    },
    confirm: {
        get: function (listObject) {
            var href = $(listObject).parents('.qc_ad3d_list_content').data('href-confirm') + '/' + $(listObject).data('object');
            qc_ad3d_submit.ajaxNotReload(href, $('#' + qc_ad3d.bodyIdName()), false);
            qc_main.scrollTop();
        },
        save: function (frm) {
            var notifyContent = $(frm).find('.notifyConfirm');
            //qc_ad3d_submit.ajaxFormNotReload(frm, notifyContent, true);
            qc_ad3d_submit.ajaxFormHasReload(frm, notifyContent, true);
            qc_main.scrollTop();
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

//-------------------- hủy ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_cancel', function () {
        qc_ad3d_work_time_keeping_provisional.cancel($(this).parents('.qc_ad3d_list_object'));
    });
});

//-------------------- xác nhận ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_confirm', function () {
        qc_ad3d_work_time_keeping_provisional.confirm.get($(this).parents('.qc_ad3d_list_object'));
    });
    $('body').on('click', '.qc_ad3d_frm_confirm .qc_save', function () {
        qc_ad3d_work_time_keeping_provisional.confirm.save($(this).parents('.qc_ad3d_frm_confirm'));
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
