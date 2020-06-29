/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_pay_import = {
    view: function (listObject) {
        qc_master_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view'), $('#' + qc_ad3d.bodyIdName()), false);
        qc_main.scrollTop();
    },
    confirm: {
        get: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
            qc_main.scrollTop();
        },
        save: function (form) {
            var payStatus = $(form).find('.cbPayStatus').val();
            if (payStatus == 2) {
                qc_master_submit.ajaxFormHasReload(form);
            } else {
                if (qc_work_pay_import.confirm.checkInput()) {
                    qc_master_submit.ajaxFormHasReload(form);
                    //qc_master_submit.normalForm(form);
                } else {
                    alert('Chọn phân loại vật tư / đơn vị tính');
                    return false;
                }
            }
            //qc_main.scrollTop();
        },
        checkInput: function () {
            var result = true;
            $('#frm_work_import_confirm .cbNewSuppliesTool').filter(function () {
                var cbNewSuppliesTool = $(this).val();
                if (cbNewSuppliesTool == '') {
                    result = false;
                }
            });
            $('#frm_work_import_confirm .txtUnit').filter(function () {
                var txtUnit = $(this).val();
                if (txtUnit == '') {
                    result = false;
                }
            });
            return result;
        }
    },
    pay: {
        getPay: function (href) {
            qc_master_submit.ajaxNotReload(href, $('#' + qc_ad3d.bodyIdName()), false);
            qc_main.scrollTop();
        },
        savePay: function (form) {
            if (confirm('Bạn xác nhận đã thanh toán hóa dơn này')) {
                qc_master_submit.ajaxFormHasReload(form, ''.false);
            }
        }
    }
}
//-------------------- filter ------------
$(document).ready(function () {
    //khi tìm theo tên ..
    $('body').on('change', '.cbStaffFilterId', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val() + '/' + $('.cbPayStatusFilter').val() + '/' + $(this).val());
    });

    $('body').on('change', '.cbMonthFilter', function () {
        qc_main.url_replace($(this).data('href') + '/' + $(this).val() + '/' + $('.cbYearFilter').val() + '/' + $('.cbPayStatusFilter').val() + '/' + $('.cbStaffFilterId').val());
    });
    $('body').on('change', '.cbYearFilter', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.cbMonthFilter').val() + '/' + $(this).val() + '/' + $('.cbPayStatusFilter').val() + '/' + $('.cbStaffFilterId').val());
    });
    $('body').on('change', '.cbPayStatusFilter', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val() + '/' + $(this).val() + '/' + $('.cbStaffFilterId').val());
    });
});


//view
/*
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_ad3d_import_pay_act', function () {
        qc_work_pay_import.pay.getPay($(this).data('href'));
    });

    $('body').on('click', '.qc_ad3d_frm_import_pay .qc_save', function () {
        qc_work_pay_import.pay.savePay($(this).parents('.qc_ad3d_frm_import_pay'));
    })
});
*/

//-------------------- confirm ------------
$(document).ready(function () {
    $('.qc_work_pay_import_wrap').on('click', '.qc_confirm_get', function () {
        qc_work_pay_import.confirm.get($(this).data('href'));
    });
    $('body').on('click', '#frm_work_import_confirm .qc_save', function () {
        qc_work_pay_import.confirm.save($(this).parents('#frm_work_import_confirm'));
    })
});
