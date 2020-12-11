/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_allocation_order_allocation = {
    filter: function (href) {
        qc_main.url_replace(href);
    },
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

//------------ ---------- quản lý dơn hang duoc ban giao ----------- ---------
$(document).ready(function () {
    $('body').on('change', '.cbWorkAllocationConstructionFinishStatus', function () {
        qc_main.url_replace($(this).data('href') + '/' + $(this).val() + '/' + $('.cbWorkAllocationConstructionMonthFilter').val() + '/' + $('.cbWorkAllocationConstructionYearFilter').val());
    });
    $('body').on('change', '.cbWorkAllocationConstructionMonthFilter', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.cbWorkAllocationConstructionFinishStatus').val() + '/' + $(this).val() + '/' + $('.cbWorkAllocationConstructionYearFilter').val());
    });
    $('body').on('change', '.cbWorkAllocationConstructionYearFilter', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.cbWorkAllocationConstructionFinishStatus').val() + '/' + $('.cbWorkAllocationConstructionMonthFilter').val() + '/' + $(this).val());
    });
    // xem báo cáo
    //-------- San pham --------
    //xem anh thiet chi tiet
    $('.qc_work_allocation_construction_product_wrap').on('click', '.qc_work_order_product_design_image_view', function () {
        qc_master_submit.ajaxNotReload($(this).data('href'), '#qc_master', false);
    });
    //------ xem chi tiet anh bao cao -------
    $('.qc_work_allocation_construction_product_wrap').on('click', '.qc_work_order_allocation_product_report_image_view', function () {
        qc_master_submit.ajaxNotReload($(this).data('href'), '#qc_master', false);
    });

    // xem bao cao
    $('.qc_work_allocation_construction_product_wrap').on('click', '.qc_work_order_allocation_product_report_view', function () {
        qc_master_submit.ajaxNotReload($(this).data('href'), '#qc_master', false);
        qc_main.scrollTop();
    });

    // xac nhan hoan thanh san pham
    $('.qc_work_allocation_construction_product_wrap').on('click', '.qc_confirm_finish_product_act', function () {
        qc_work_allocation_order_allocation.getProductConfirm($(this).data('href'));
    });
    $('body').on('click', '.frmWorkAllocationProductConfirm .qc_save', function () {
        qc_work_allocation_order_allocation.postProductConfirm($(this).parents('.frmWorkAllocationProductConfirm'));
    });

    // bao cao hoan than hoan thanh don hang ban giao
    $('.qc_work_allocation_construction_wrap').on('click', '.qc_report_finish_get', function () {
        qc_work_allocation_order_allocation.getReportFinish($(this).data('href'));
    });
    $('body').on('click', '.frmWorkAllocationConstructionConfirm .qc_save', function () {
        qc_work_allocation_order_allocation.postConfirmAllocation($(this).parents('.frmWorkAllocationConstructionConfirm'));
    });
});

