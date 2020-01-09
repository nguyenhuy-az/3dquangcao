/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_salary_before_pay = {
    request: {
        getForm: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        },
        save: function (form) {
            var txtMoneyRequest = $(form).find("input[name='txtMoneyRequest']");
            if (txtMoneyRequest.val().length > 0) {
                if (parseInt(txtMoneyRequest.val()) > parseInt(txtMoneyRequest.data('limit'))) {
                    alert('Số tiền không được vượt hạn mức');
                    txtMoneyRequest.focus();
                    return false;
                } else {
                    var notifyContent = $(form).find('.frm_notify');
                    if (confirm('Bạn muốn ứng ' + txtMoneyRequest.val() + '?')) {
                        qc_master_submit.ajaxFormHasReload(form, notifyContent, true);
                        qc_main.scrollTop();
                    }
                }
            } else {
                alert('Nhập số tiền ứng');
                txtMoneyRequest.focus();
                return false;
            }

        },
        cancel: function (href) {
            if (confirm('Bạn muốn hủy yêu cầu?')) {
                qc_master_submit.ajaxHasReload(href, '#qc_master', false);
            }
        }
    }
}

//========================= UNG LUONG ===============================
$(document).ready(function () {
    //----------------  UNG LUONG ---------------------------------
    $('body').on('change', '.qc_work_salary_before_pay_month', function () {
        qc_main.url_replace($(this).data('href') + '/' + $(this).val() + '/' + $('.qc_work_salary_before_pay_year').val());
    });
    $('body').on('change', '.qc_work_salary_before_pay_year', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.qc_work_salary_before_pay_month').val() + '/' + $(this).val());
    });

    $('body').on('click', '.qc_work_before_pay_request_action', function () {
        qc_work_salary_before_pay.request.getForm($(this).data('href'));
    });
    $('body').on('click', '.frm_work_before_pay_request .qc_save', function () {
        qc_work_salary_before_pay.request.save($(this).parents('.frm_work_before_pay_request'));
    });
    //hủy yêu cầu ứng lương
    $('body').on('click', '.qc_salary_before_pay_request_cancel', function () {
        qc_work_salary_before_pay.request.cancel($(this).data('href'));
    });
    //----------------  xac nhan ung luon ---------------------------------
    $('.qc_work_salary_before_pay_index').on('click', '.qc_confirm_receive_money', function () {
        if (confirm('Tôi đã nhận số tiền: ' + $(this).data('money'))) {
            qc_master_submit.ajaxHasReload($(this).data('href'), '', false);
        }

    });
});
