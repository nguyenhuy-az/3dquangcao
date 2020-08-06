/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_pay_activity = {
    saveAdd: function (frm) {
        var cbPayActivityList = $(frm).find("select[name='cbPayActivityList']");
        var txtMoney = $(frm).find("input[name='txtMoney']");
        if (qc_main.check.inputNull(cbPayActivityList, 'Chọn danh muc chi')) {
            $(cbPayActivityList).focus();
            return false;
        }
        if (qc_main.check.inputNull(txtMoney, 'Nhận số tiền')) {
            $(txtMoney).focus();
            return false;
        } else {
            qc_master_submit.ajaxFormHasReload(frm, '', false);
        }
    },
    delete: function (href) {
        if (confirm('Bạn muốn ủy hóa đơn này?')) {
            qc_master_submit.ajaxHasReload(href, '', false);
        }
    }
}
//-------------------- LOC THEO THONG TIN ------------
$(document).ready(function () {
    // theo thong tin duyet
    $('body').on('change', '.cbConfirmStatusFilter', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val() + '/' + $(this).val());
    });
    //khi tìm theo tên ..
    $('body').on('change', '.cbDayFilter', function () {
        qc_main.url_replace($(this).data('href') + '/' + $(this).val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val() + '/' + $('.cbConfirmStatusFilter').val());
    });

    $('body').on('change', '.cbMonthFilter', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.cbDayFilter').val() + '/' + $(this).val() + '/' + $('.cbYearFilter').val() + '/' + $('.cbConfirmStatusFilter').val());
    });
    $('body').on('change', '.cbYearFilter', function () {
        qc_main.url_replace($(this).data('href')+ '/' + $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $(this).val() + '/' + $('.cbConfirmStatusFilter').val());
    });
});

//-------------------- THEM ------------
//chi hoat dong
$(document).ready(function () {
    // them thong tin chi
    $('#frm_work_pay_activity_add').on('click', '.qc_save', function () {
        qc_work_pay_activity.saveAdd($(this).parents('#frm_work_pay_activity_add'));
    });

    // xóa
    $('.qc_work_pay_activity_wrap').on('click', '.qc_delete', function () {
        qc_work_pay_activity.delete($(this).data('href'));
    });
});