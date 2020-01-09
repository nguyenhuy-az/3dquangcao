/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_finance_pay_before = {
    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
        qc_main.scrollTop();
    },
    add: {
        save: function (formObject) {
            var cbWork = $(formObject).find("select[name='cbWork']");
            var cbDay = $(formObject).find("select[name='cbDay']");
            var txtMoney = $(formObject).find("input[name='txtMoney']");
            if (qc_main.check.inputNull(cbWork, 'Chọn nhân viên')) {
                return false;
            }
            if (qc_main.check.inputNull(cbDay, 'Chọn ngày ứng')) {
                return false;
            }
            if (qc_main.check.inputNull(txtMoney, 'Nhập số tiền ứng')) {
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
            if (qc_main.check.inputNull(txtMoney, 'Nhập tiền ứng')) {
                return false;
            }
            qc_ad3d_submit.ajaxFormHasReload(formObject, containNotify, true);
            $('#qc_container_content').animate({
                scrollTop: 0
            })
        }
    },
    delete: function (listObject) {
        if (confirm('Bạn muốn xóa tin này')) {
            qc_ad3d_submit.ajaxHasReload($(listObject).parents('.qc_ad3d_list_content').data('href-del') + '/' + $(listObject).data('object'), '', false);
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
        var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val()
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
        var dateFilter = $(this).val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + name);
    })
    $('body').on('change', '.cbMonthFilter', function () {
        var name = $('.textFilterName').val();
        var dateFilter = $('.cbDayFilter').val() + '/' + $(this).val() + '/' + $('.cbYearFilter').val()
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + name);
    })
    $('body').on('change', '.cbYearFilter', function () {
        var name = $('.textFilterName').val();
        var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $(this).val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + name);
    })
});
//view
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_view', function () {
        qc_ad3d_finance_pay_before.view($(this).parents('.qc_ad3d_list_object'));
    })
});

//-------------------- add ------------
$(document).ready(function () {
    //select company
    $('.qc_ad3d_index_content').on('change', '.frmAdd .cbCompany', function () {
        qc_main.url_replace($(this).data('href') + '/' + $(this).val());
    })

    //select workId
    $('.qc_ad3d_index_content').on('change', '.frmAdd .cbWork', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompany').val() + '/' + $(this).val());
    })

    //save
    $('.qc_ad3d_index_content').on('click', '.frmAdd .qc_save', function () {
        qc_ad3d_finance_pay_before.add.save($(this).parents('.frmAdd'));
    })
});

//-------------------- edit ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_edit', function () {
        qc_ad3d_finance_pay_before.edit.get($(this).parents('.qc_ad3d_list_object'));
    });

    $('body').on('click', '.frmEdit .qc_save', function () {
        qc_ad3d_finance_pay_before.edit.save($(this).parents('.frmEdit'));
    });
});

//delete
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_delete', function () {
        qc_ad3d_finance_pay_before.delete($(this).parents('.qc_ad3d_list_object'));
    });
});
