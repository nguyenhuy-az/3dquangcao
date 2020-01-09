/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_finance_pay_activity = {
    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
        qc_main.scrollTop();
    },
    confirm: {
        get: function (href) {
            qc_ad3d_submit.ajaxNotReload(href, $('#' + qc_ad3d.bodyIdName()), false);
        },
        save: function (formObject) {
            var txtMoney = $(formObject).find("input[name='txtMoney']");
            var txtReason = $(formObject).find("input[name='txtReason']");
            var containNotify = $(formObject).find('.frm_notify');
            if (qc_main.check.inputNull(txtMoney, 'Nhập số tiền chi')) {
                return false;
            }
            if (qc_main.check.inputNull(txtReason, 'Nhập lý do chi chi')) {
                return false;
            } else {
                qc_ad3d_submit.ajaxFormHasReload(formObject, containNotify, true);
                $('#qc_container_content').animate({
                    scrollTop: 0
                })
            }

        }
    },
}

//-------------------- filter ------------
$(document).ready(function () {
    //khi chọn công ty...
    $('body').on('change', '.cbCompanyFilter', function () {
        var href = $(this).data('href-filter') + '/' + $(this).val() + '/' + $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val() + '/' + $('.cbConfirmStatusFilter').val() + '/' + $('.cbStaffFilterId').val();
        qc_main.url_replace(href);
    });

    //loc theo nhan vien
    $('body').on('change', '.cbStaffFilterId', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val() + '/' + $('.cbConfirmStatusFilter').val() + '/' + $(this).val());
    });

    //khi trang thai xac nhan
    $('body').on('change', '.cbConfirmStatusFilter', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val() + '/' + $(this).val() + '/' + $('.cbStaffFilterId').val());
    });
    // theo ngay tháng ...
    $('body').on('change', '.cbDayFilter', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + $(this).val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val() + '/' + $('.cbConfirmStatusFilter').val() + '/' + $('.cbStaffFilterId').val());
    });
    $('body').on('change', '.cbMonthFilter', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + $('.cbDayFilter').val() + '/' + $(this).val() + '/' + $('.cbYearFilter').val() + '/' + $('.cbConfirmStatusFilter').val() + '/' + $('.cbStaffFilterId').val());
    });
    $('body').on('change', '.cbYearFilter', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $(this).val() + '/' + $('.cbConfirmStatusFilter').val() + '/' + $('.cbStaffFilterId').val());
    });
});
//view
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_view', function () {
        qc_ad3d_finance_pay_activity.view($(this).parents('.qc_ad3d_list_object'));
    })
});

//-------------------- xac nhan ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_confirm_get', function () {
        qc_ad3d_finance_pay_activity.confirm.get($(this).data('href'));
    });

    $('body').on('click', '#frmAd3dPayActivityConfirm .qc_save', function () {
        qc_ad3d_finance_pay_activity.confirm.save($(this).parents('#frmAd3dPayActivityConfirm'));
    });
});

