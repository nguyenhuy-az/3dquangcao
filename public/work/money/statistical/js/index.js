/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_money_statistical = {
    filter: function (href) {
        qc_main.url_replace(href);
    },
    transfers: {
        save: function (frm) {
            var cbReceiveStaff = $(frm).find("select[name='cbReceiveStaff']");
            var txtMoney = $(frm).find("input[name='txtMoney']");
            var txtReason = $(frm).find("input[name='txtReason']");
            if (qc_main.check.inputNull(cbReceiveStaff, 'PHẢI CHỌN NGƯỜI NHẬN')) {
                cbReceiveStaff.focus();
                return false;
            }
            if (qc_main.check.inputNull(txtMoney, 'Nhập số tiền thanh toán')) {
                txtMoney.focus();
                return false;
            } else {
                var money = qc_main.getNumberInput(txtMoney.val());
                var checkMoney = txtMoney.data('limit');
                if (money > checkMoney) {
                    alert('Số tiền chuyển không được lớn hơn ' + checkMoney);
                    txtMoney.focus();
                    return false;
                }
                if (money <= 0) {
                    alert('Số tiền nhập không đúng');
                    txtMoney.focus();
                    return false;
                }
            }
            if (qc_main.check.inputNull(txtReason, 'Phải nhập lý do chuyển')) {
                txtReason.focus();
                return false;
            } else {
                qc_master_submit.ajaxFormHasReload(frm);
            }
            //$('#qc_frm_work_orders_payment').submit();
            //qc_master_submit.normalForm('#qc_frm_work_orders_payment');
        },
    }
}
//----------- chuyen tien - nhan tien chuyen ------------------------
$(document).ready(function () {
    //theo tháng
    $('.qc_work_money_statistical_wrap').on('change', '.qc_month_filter', function () {
        qc_work_money_statistical.filter($(this).data('href') + '/' + $(this).val() + '/' + $('.qc_year_filter').val());
    });

    // năm
    $('.qc_work_money_statistical_wrap').on('change', '.qc_year_filter', function () {
        qc_work_money_statistical.filter($(this).data('href') + '/' + $('.qc_month_filter').val() + '/' + $(this).val());
    });
});
// chuyen tien
$(document).ready(function () {
    $('#qc_frm_money_statistic_transfer').on('click', '.qc_save', function () {

        qc_work_money_statistical.transfers.save($(this).parents('#qc_frm_money_statistic_transfer'));
    });
});