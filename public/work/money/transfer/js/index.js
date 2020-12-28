/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_money_transfer = {
    info: {
        delete: function (href) {
            if (confirm('Sau khi hủy sẽ không phục hồi được')) {
                qc_master_submit.ajaxHasReload(href, '', false);
                return false;
            }
        }
    }
}
//----------- chuyen tien - nhan tien chuyen ------------------------
$(document).ready(function () {
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
// huy 1 giao dich  chuyen tien
$(document).ready(function () {
    $('.qc_work_money_transfer_wrap').on('click', '.qc_transfer_cancel', function () {
        if (confirm('Sau khi hủy KHÔNG ĐƯỢC PHỤC HỔI, đồng ý hủy?')) {
            qc_master_submit.ajaxHasReload($(this).data('href'), '', false);
        }

    });
});
// huy 1 thong tin chi tiet chuyen tien
$(document).ready(function () {
    $('.qc_work_money_transfer_transfer_info').on('click', '.qc_transfer_detail_del', function () {
        qc_work_money_transfer.info.delete($(this).data('href'));
    });
});