/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_allocation_work_allocation = {
    filter: function (href) {
        qc_main.url_replace(href);
    },
    getReport: function (href) {
        qc_master_submit.ajaxNotReload(href, '#qc_master', false);
    },
    saveReport: function (form) {
        var notifyContent = $(form).find('.frm_notify');
        qc_master_submit.ajaxFormHasReload(form, notifyContent, true);
        qc_main.scrollTop();
    },
    deleteReportImage: function (href) {
        if (confirm('Bạn muốn xóa ảnh báo cáo này?')) {
            qc_master_submit.ajaxHasReload(href, '#qc_master', false);
        }
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
    }
}

//------------ ---------- quan ly phan viec ----------- ---------
$(document).ready(function () {
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
        qc_work_allocation_work_allocation.getReport($(this).data('href') );
    });
    //luu bao cao
    $('body').on('click', '.qc_frm_Work_allocation_report .qc_save', function () {
        qc_work_allocation_work_allocation.saveReport($(this).parents('.qc_frm_Work_allocation_report'));
    });
    //Xoa anh bao cao
    $('body').on('click', '.qc_work_allocation_work_allocation_detail_wrap .qc_report_image_delete  ', function () {
        qc_work_allocation_work_allocation.deleteReportImage($(this).data('href'));
    });
    //xoa bao cao
    $('body').on('click', '.qc_work_allocation_work_allocation_detail_wrap .qc_delete_report_action', function () {
        qc_work_allocation_work_allocation.deleteReport($(this).data('href'));
    });
    //xem anh bao cao chi tiet
    $('body').on('click', '.qc_work_allocation_work_allocation_detail_wrap .qc_image_view', function () {
        qc_work_allocation_work_allocation.viewReportImage($(this).data('href'));
    });
    //xem anh thiet chi tiet
    $('body').on('click', '.qc_work_allocation_work_allocation_detail_wrap .qc_design_image_view', function () {
        qc_work_allocation_work_allocation.viewProductDesign($(this).data('href'));
    });
});
