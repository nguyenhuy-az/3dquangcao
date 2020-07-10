/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_minus_money = {
    feedback: {
        viewImage: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        },
        getForm: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        },
        save: function (frm) {
            var content = $(frm).find('.txtFeedbackContent');
            if (qc_main.check.inputNull(content, 'Phải nhập nội dung phản hồi.')) {
                content.focus();
                return false;
            } else {
                if (confirm('Tôi đồng ý thông tin này?')) {
                    qc_master_submit.ajaxFormHasReload(frm);
                    qc_main.scrollTop();
                }
            }
        },
        cancel: function (href) {
            if (confirm('Tôi đồng ý hủy phản hồi này')) {
                qc_master_submit.ajaxHasReload(href, '', false);
            }
        }
    }
}
// phan hoi
$(document).ready(function () {
    // xem anh phan hoi
    $('.qc_work_minius_money_wrap').on('click', '.qc_view_image', function () {
        qc_work_minus_money.feedback.viewImage($(this).data('href'));
    });
    // them
    $('.qc_work_minius_money_wrap').on('click', '.qc_minus_money_feedback', function () {
        qc_work_minus_money.feedback.getForm($(this).data('href'));
    });
    $('body').on('click', '#qcFrmMinusMoneyFeedback .qc_save', function () {
        qc_work_minus_money.feedback.save('#qcFrmMinusMoneyFeedback');
    });
    // huy phan hoi
    $('.qc_work_minius_money_wrap').on('click', '.qc_minus_money_feedback_cancel', function () {
        qc_work_minus_money.feedback.cancel($(this).data('href'));
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

