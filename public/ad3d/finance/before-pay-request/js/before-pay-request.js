/**
 * Created by HUY on 12/19/2018.
 */
var qc_ad3d_salary_before_pay_request = {
    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view'), $('#' + qc_ad3d.bodyIdName()), false);
        qc_main.scrollTop();
    },
    confirm: {
        get: function (listObject) {
            var href = $(listObject).parents('.qc_ad3d_list_content').data('href-confirm') + '/' + $(listObject).data('object');
            qc_ad3d_submit.ajaxNotReload(href, $('#' + qc_ad3d.bodyIdName()), false);
            qc_main.scrollTop();
        },
        save: function (form) {
            var notifyContent = $(form).find('.notifyConfirm');
            var txtNMoneyConfirm = $(form).find("input[name='txtMoneyConfirm']");
            if (txtNMoneyConfirm.val() > txtNMoneyConfirm.data('check')) {
                alert('Không được lớn hơn số tiền yêu cầu');
                txtNMoneyConfirm.focus();
                return false;
            }else{
                qc_ad3d_submit.ajaxFormHasReload(form, notifyContent, true);
                qc_main.scrollTop();
            }

        }
    },
    transfer: {
        get: function (listObject) {
            var href = $(listObject).parents('.qc_ad3d_list_content').data('href-transfer') + '/' + $(listObject).data('object');
            qc_ad3d_submit.ajaxNotReload(href, $('#' + qc_ad3d.bodyIdName()), false);
            qc_main.scrollTop();
        },
        save: function (form) {
            var notifyContent = $(form).find('.notifyConfirm');
            if(confirm('Xác nhận chuyển tiền cho yêu cầu này')){
                qc_ad3d_submit.ajaxFormHasReload(form, notifyContent, true);
                qc_main.scrollTop();
            }

        }
    }
}
//-------------------- filter ------------
$(document).ready(function () {
    //khi chọn công ty...
    $('body').on('change', '.cbCompanyFilter', function () {
        var companyId = $(this).val();
        //var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val();
        var dateFilter = 0 + '/' + 0 + '/' + 0;
        var href = $(this).data('href-filter');
        if (companyId != '') {
            href = href + '/' + companyId + '/' + dateFilter;
        }
        qc_main.url_replace(href);
    });
});

//-------------------- xác nhận yêu cấu ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_confirm', function () {
        qc_ad3d_salary_before_pay_request.confirm.get($(this).parents('.qc_ad3d_list_object'));
    })
    $('body').on('click', '.qc_ad3d_frm_confirm .qc_save', function () {
        qc_ad3d_salary_before_pay_request.confirm.save($(this).parents('.qc_ad3d_frm_confirm'));
    })
});

//-------------------- xác nhận chuyển tiền ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_transfer', function () {
        qc_ad3d_salary_before_pay_request.transfer.get($(this).parents('.qc_ad3d_list_object'));
    })
    $('body').on('click', '.qc_ad3d_salary_frm_transfer .qc_save', function () {
        qc_ad3d_salary_before_pay_request.transfer.save($(this).parents('.qc_ad3d_salary_frm_transfer'));
    })
});


