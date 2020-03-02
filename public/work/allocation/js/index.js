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

//---------------------- viec dang làm --------------------
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
//---------------------- viec da lam xong --------------------
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

