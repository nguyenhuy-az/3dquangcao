/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_minus_money = {
    feedback: {
        getForm: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        },
        save: function (frm) {
            if (confirm('Tôi đồng ý xác nhận này?')) {
                qc_master_submit.ajaxFormHasReload(frm);
                qc_main.scrollTop();
            }
        }
    }
}
// phan hoi
$(document).ready(function () {
    $('body').on('click', '.qc_work_minius_money_wrap .qc_minus_money_feedback', function () {
        //qc_main.url_replace($(this).data('href') + '/' + $(this).val() + '/' + $('.qc_work_minus_money_year').val());
    });
});
// loc thong tin
$(document).ready(function () {
    $('body').on('change', '.qc_work_minus_money_month', function () {
        qc_main.url_replace($(this).data('href') + '/' + $(this).val() + '/' + $('.qc_work_minus_money_year').val());
    });
    $('body').on('change', '.qc_work_minus_money_year', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.qc_work_minus_money_month').val() + '/' + $(this).val());
    });
});

