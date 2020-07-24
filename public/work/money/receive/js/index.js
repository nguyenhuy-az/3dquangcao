/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_money_receive = {
    filter: function (href) {
        qc_main.url_replace(href);
    },
    deleteOrderPay: function (href) {
        if (confirm('Bạn muốn hủy thanh toán đơn hàng này?')) {
            qc_master_submit.ajaxHasReload(href, '', false);
        }
    },
    postTransfer: function (form) {
        var staff = $(form).find("select[name='cbStaffReceive']");
        if (qc_main.check.inputNull(staff, 'Chọn người nhận')) {
            $(staff).focus();
            return false;
        } else {
            qc_master_submit.normalForm(form);
        }
    }
}
//----------- thu tien giao tien ------------------------
$(document).ready(function () {
    // huy thu dien
    $('.qc_work_money_receive_wrap').on('click', '.qc_pay_del', function () {
        qc_work_money_receive.deleteOrderPay($(this).data('href'));
    });

    //theo tháng
    $('body').on('change', '.qc_work_money_receive_filter_month', function () {
        qc_work_money_receive.filter($(this).data('href') + '/' + $(this).val() + '/' + $('.qc_work_money_receive_filter_year').val());
    });

    // năm
    $('body').on('change', '.qc_work_money_receive_filter_year', function () {
        qc_work_money_receive.filter($(this).data('href') + '/' + $('.qc_work_money_receive_filter_month').val() + '/' + $(this).val());
    });

    // giao tien
    $('.qc_work_frm_transfer_receive').on('click', '.qc_transfer_save', function () {
        qc_work_money_receive.postTransfer($(this).parents('.qc_work_frm_transfer_receive'));
    });
});