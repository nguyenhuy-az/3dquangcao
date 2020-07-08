/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_store_allocation = {
    viewImage: function (href) {
        qc_master_submit.ajaxNotReload(href, '#qc_master', false);
    },
    minusMoney: {
        getForm: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        },
        save: function (frm) {
            var cbOrder = $(frm).find(".cbOrder");
            var notify = $(frm).find(".notifyConfirm");
            if (qc_main.check.inputNull(cbOrder, 'Phải chọn đơn hàng thi công')) {
                cbOrder.focus();
                return false;
            } else {
                if (confirm('Tôi đồng ý áp dụng phạt này?')) {
                    qc_master_submit.ajaxFormNotReload(frm, notify, false);
                    qc_main.scrollTop();
                    alert("ĐÃ ÁP DỤNG PHẠT");
                }
            }

        }
        /*checkSelectReturn: function () {
         var checkStatus = false;
         $('#frmWorkStoreReturnConfirm .txtToolReturn').filter(function () {
         if ($(this).is(':checked')) {
         checkStatus = true;
         }
         });
         return checkStatus;
         },*/
    }
}
// -------------- ---------- Xem chi tiet ----------- -----------
$(document).ready(function () {
    $('.qc_work_store_allocation_check_info').on('click', '.qc_view_image_get', function () {
        qc_work_store_allocation.viewImage($(this).data('href'));
    });
});

// -------------- ----------  ap dung phat ----------- -----------
$(document).ready(function () {
    //lay form xac nhan
    $('.qc_work_store_allocation_check_info').on('click', '.qc_minus_money_get', function () {
        qc_work_store_allocation.minusMoney.getForm($(this).data('href'));
    });
    //xac nhan
    $('body').on('click', '#frmWorkStoreAllocationMinusMoney .qc_save', function () {
        qc_work_store_allocation.minusMoney.save($(this).parents('#frmWorkStoreAllocationMinusMoney'));
    });
});