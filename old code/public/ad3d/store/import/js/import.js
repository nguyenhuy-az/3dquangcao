/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_store_import = {
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
            var payStatus = $(form).find('.cbPayStatus').val();
            if (payStatus == 2) {
                qc_ad3d_submit.normalForm(form);
            } else {
                if (qc_ad3d_store_import.confirm.checkInput()) {
                    qc_ad3d_submit.normalForm(form);
                } else {
                    alert('Chọn phân loại vật tư / đơn vị tính');
                    return false;
                }
            }
            //qc_main.scrollTop();
        },
        checkInput: function () {
            var result = true;
            $('#frm_ad3d_import_confirm .cbNewSuppliesTool').filter(function () {
                var cbNewSuppliesTool = $(this).val();
                if (cbNewSuppliesTool == '') {
                    result = false;
                }
            });
            $('#frm_ad3d_import_confirm .txtUnit').filter(function () {
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
            qc_ad3d_submit.ajaxNotReload(href, $('#' + qc_ad3d.bodyIdName()), false);
            qc_main.scrollTop();
        },
        savePay: function (form) {
            if (confirm('Bạn xác nhận đã thanh toán hóa dơn này')) {
                qc_ad3d_submit.ajaxFormHasReload(form, ''.false);
            }
        }
    }
}
//-------------------- filter ------------
$(document).ready(function () {
    //khi chọn công ty...
    $('body').on('change', '.cbCompanyFilter', function () {
        var companyId = $(this).val();
        var href = $(this).data('href-filter');
        if (companyId != '') {
            href = href + '/' + companyId + '/' + $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val() + '/' + $('.cbPayStatusFilter').val() + '/' + $('.cbStaffFilterId').val();
        }
        qc_main.url_replace(href);
    })

    //khi tìm theo tên ..
    $('body').on('change', '.cbStaffFilterId', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val() + '/' + $('.cbPayStatusFilter').val() + '/' + $(this).val());
    })
    // theo ngay tháng ...
    $('body').on('change', '.cbDayFilter', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + $(this).val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val() + '/' + $('.cbPayStatusFilter').val() + '/' + $('.cbStaffFilterId').val());
    }),
        $('body').on('change', '.cbMonthFilter', function () {
            qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + $('.cbDayFilter').val() + '/' + $(this).val() + '/' + $('.cbYearFilter').val() + '/' + $('.cbPayStatusFilter').val() + '/' + $('.cbStaffFilterId').val());
        }),
        $('body').on('change', '.cbYearFilter', function () {
            qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $(this).val() + '/' + $('.cbPayStatusFilter').val() + '/' + $('.cbStaffFilterId').val());
        }),
        $('body').on('change', '.cbPayStatusFilter', function () {
            qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val() + '/' + $(this).val() + '/' + $('.cbStaffFilterId').val());
        })
});


//view
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_ad3d_import_pay_act', function () {
        qc_ad3d_store_import.pay.getPay($(this).data('href'));
    });

    $('body').on('click', '.qc_ad3d_frm_import_pay .qc_save', function () {
        qc_ad3d_store_import.pay.savePay($(this).parents('.qc_ad3d_frm_import_pay'));
    })
});

//-------------------- confirm ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_confirm', function () {
        //qc_ad3d_store_import.confirm.get($(this).parents('.qc_ad3d_list_object'));
    });
    $('body').on('click', '#frm_ad3d_import_confirm .qc_ad3d_import_confirm_save', function () {
        qc_ad3d_store_import.confirm.save($(this).parents('#frm_ad3d_import_confirm'));
    })
});
