/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_money_transfer = {}
$(document).ready(function () {
    //
    //theo tháng
    $('body').on('change', '.qc_work_money_transfer_filter_month', function () {
        qc_main.url_replace($(this).data('href') + '/' + $(this).val() + '/' + $('.qc_work_money_transfer_filter_year').val());
    });

    // năm
    $('body').on('change', '.qc_work_money_transfer_filter_year', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.qc_work_money_transfer_filter_month').val() + '/' + $(this).val());
    });
    // xem chi tiet
    $('body').on('click', '.qcMoneyTransferView', function () {
        qc_master_submit.ajaxNotReload($(this).data('href'), '#qc_master', false);
    });
    $('body').on('click', '.qcMoneyReceiveView', function () {
        qc_master_submit.ajaxNotReload($(this).data('href'), '#qc_master', false);
    });
    //------------- Nhận tiền -------------------
    $('.qc_work_money_transfer_receive_list_content').on('click', '.qc_receive_confirm_act', function () {
        if (confirm('Xác nhận tôi đã nhận số tiền ' + $(this).data('money'))) {
            qc_master_submit.ajaxHasReload($(this).data('href'), '', false);
        }

    });
});

