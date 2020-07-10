/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_finance_minus_money = {
    feedback: {
        viewImage: function (href) {
            qc_ad3d_submit.ajaxNotReload(href, $('#' + qc_ad3d.bodyIdName()), false);
            qc_main.scrollTop();
        }
    },
    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
        qc_main.scrollTop();
    },
    add: {
        save: function (formObject) {
            var cbWork = $(formObject).find("select[name='cbWork']");
            var cbDay = $(formObject).find("select[name='cbDay']");
            var cbPunishContent = $(formObject).find("select[name='cbPunishContent']");
            if (qc_main.check.inputNull(cbWork, 'Chọn nhân viên')) {
                return false;
            }
            if (qc_main.check.inputNull(cbDay, 'Chọn ngày phạt')) {
                return false;
            }
            if (qc_main.check.inputNull(cbPunishContent, 'Chọn lý do phạt')) {
                return false;
            }
            qc_ad3d_submit.ajaxFormHasReload(formObject, '', false);
            qc_main.scrollTop();
        }
    },
    edit: {
        get: function (listObject) {
            qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-edit') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
        },
        save: function (formObject) {
            var txtMoney = $(formObject).find("input[name='txtMoney']");
            var containNotify = $(formObject).find('.frm_notify');
            if (qc_main.check.inputNull(txtMoney, 'Nhập phạt')) {
                return false;
            }
            qc_ad3d_submit.ajaxFormHasReload(formObject, containNotify, true);
            $('#qc_container_content').animate({
                scrollTop: 0
            })
        }
    },
    cancel: function (listObject) {
        if (confirm('Khi Hủy sẽ KHÔNG THỂ PHỤC HỒI, đồng ý hủy')) {
            qc_ad3d_submit.ajaxHasReload($(listObject).parents('.qc_ad3d_list_content').data('href-cancel') + '/' + $(listObject).data('object'), '', false);
        }
    }
}

//-------------------- filter ------------
$(document).ready(function () {
    //khi chọn công ty...
    $('body').on('change', '.cbCompanyFilter', function () {
        var companyId = $(this).val();
        var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val();
        var href = $(this).data('href-filter');
        if (companyId != '') {
            href = href + '/' + companyId + '/' + dateFilter;
        }
        qc_main.url_replace(href);
    })

    //khi tìm theo tên ..
    $('body').on('click', '.btFilterName', function () {
        var name = $('.textFilterName').val();
        var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val() + '/' + $('.cbPunishContentFilter').val();
        if (name.length == 0) {
            alert('Bạn phải nhập thông tin tìm kiếm');
            $('.textFilterName').focus();
            return false;
        } else {
            qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + name);
        }
    })
    // theo ngay tháng ...
    $('body').on('change', '.cbDayFilter', function () {
        var name = $('.textFilterName').val();
        var dateFilter = $(this).val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val() + '/' + $('.cbPunishContentFilter').val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + name);
    })
    $('body').on('change', '.cbMonthFilter', function () {
        var name = $('.textFilterName').val();
        var dateFilter = $('.cbDayFilter').val() + '/' + $(this).val() + '/' + $('.cbYearFilter').val() + '/' + $('.cbPunishContentFilter').val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + name);
    })
    $('body').on('change', '.cbYearFilter', function () {
        var name = $('.textFilterName').val();
        var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $(this).val() + '/' + $('.cbPunishContentFilter').val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + name);
    })
    $('body').on('change', '.cbPunishContentFilter', function () {
        var name = $('.textFilterName').val();
        var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val() + '/' + $('.cbPunishContentFilter').val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + name);
    })
});
//xem anh phan hoi
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_view_image', function () {
        qc_ad3d_finance_minus_money.feedback.viewImage($(this).data('href'));
    })
});

//-------------------- add ------------
/*
$(document).ready(function () {
    //chọn công ty
    $('.qc_ad3d_index_content').on('change', '.frmAdd .cbCompany', function () {
        qc_main.url_replace($(this).data('href') + '/' + $(this).val());
    })

    //chọn nhân viên
    $('.qc_ad3d_index_content').on('change', '.frmAdd .cbWork', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompany').val() + '/' + $(this).val());
    })

    //chọn lý do phạt
    $('.qc_ad3d_index_content').on('change', '.frmAdd .cbPunishContent', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompany').val() + '/' + $('.cbWork').val() + '/' + $(this).val());
    })

    //lưu
    $('.qc_ad3d_index_content').on('click', '.frmAdd .qc_save', function () {
        qc_ad3d_finance_minus_money.add.save($(this).parents('.frmAdd'));
    })
});
*/

//-------------------- edit ------------
/*$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_edit', function () {
        qc_ad3d_finance_minus_money.edit.get($(this).parents('.qc_ad3d_list_object'));
    });

    $('body').on('click', '.frmEdit .qc_save', function () {
        qc_ad3d_finance_minus_money.edit.save($(this).parents('.frmEdit'));
    });
});*/

//delete
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_cancel_act', function () {
        qc_ad3d_finance_minus_money.cancel($(this).parents('.qc_ad3d_list_object'));
    });
});
