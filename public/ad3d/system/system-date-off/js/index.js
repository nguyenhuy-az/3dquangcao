/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_system_date_off = {
    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
    },
    add: {
        addDate: function (href) {
            qc_ad3d_submit.ajaxNotReload(href, '#qc_date_off_container', false);
        },
        save: function (formObject) {
            var txtName = $(formObject).find("input[name='txtName']");

            if (qc_main.check.inputNull(txtName, 'Nhập tên danh mục')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtName, 100, 'Tên không dài quá 100 ký tự')) return false;
            }
            qc_ad3d_submit.ajaxFormHasReload(formObject, '', false);
            qc_main.scrollTop();
        }
    },
    edit: {
        get: function (href) {
            qc_ad3d_submit.ajaxNotReload(href, $('#' + qc_ad3d.bodyIdName()), false);
        },
        save: function (frm) {
            var containNotify = $(frm).find('.frm_notify');
            if (confirm('Tôi dồng ý cập nhật thông tin này')) {
                qc_ad3d_submit.ajaxFormHasReload(frm, containNotify, true);
                qc_main.scrollTop();
            }
        }
    },
    delete: function (href) {
        if (confirm('Sẽ xóa thông tin liên quan, Đồng ý xóa?')) {
            qc_ad3d_submit.ajaxHasReload(href, '', false);
        }
    }
}
$(document).ready(function () {
    //khi chọn công ty...
    $('body').on('change', '.cbCompanyFilter', function () {
        var companyId = $(this).val();
        var href = $(this).data('href-filter');
        if (companyId != '') {
            href = href + '/' + companyId + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val();
        }
        qc_main.url_replace(href);
    });
    // theo ngay tháng ...
    $('body').on('change', '.cbMonthFilter', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + $(this).val() + '/' + $('.cbYearFilter').val());
    });
    $('body').on('change', '.cbYearFilter', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $(this).val());
    });
});

//--------- ------ ----- Sao chep ngay nghi --------- ------- -------
$(document).ready(function () {
    //chon nam
    $('.frmAd3dSystemDateOffCopy').on('change', '.cbYearCopy', function () {
        var cbCompanyCopy = $('.frmAd3dSystemDateOffCopy').find('.cbCompanyCopy').val();
        qc_main.url_replace($(this).data('href') + '/' + cbCompanyCopy + '/' + $(this).val());
    });
    //chon cty
    $('.frmAd3dSystemDateOffCopy').on('change', '.cbCompanyCopy', function () {
        var year = $('.frmAd3dSystemDateOffCopy').find('.cbYearCopy').val();
        qc_main.url_replace($(this).data('href') + '/' + $(this).val() + '/' + year);
    });

    $('.frmAd3dSystemDateOffCopy').on('click', '.qc_save', function () {
        var frm = $(this).parents('.frmAd3dSystemDateOffCopy');
        if(confirm('Tôi đồng ý sao chép thông tin ngà nghỉ này')){
            qc_ad3d_submit.ajaxFormHasReload(frm, '', false);
            qc_main.scrollTop();
        }

    });
});
//--------- ------ ----- Sua --------- ------- -------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_edit', function () {
        qc_ad3d_system_date_off.edit.get($(this).data('href'));
    });
    $('body').on('click', '#frmAd3dSystemDateOffEdit .qc_save', function () {
        qc_ad3d_system_date_off.edit.save($(this).parents('#frmAd3dSystemDateOffEdit'));
    });
});

//--------- ------ ----- Xoa --------- ------- -------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_delete', function () {
        qc_ad3d_system_date_off.delete($(this).data('href'));
    });
});

//--------- ------ ----- Them --------- ------- -------
$(document).ready(function () {
    $('.frmAdd').on('click', '.qc_date_off_add_act', function () {
        qc_ad3d_system_date_off.add.addDate($(this).data('href'));
    });

    $('body').on('click', '.frmAdd .qc_date_off_add_cancel', function () {
        $(this).parents('.qc_date_off_add_wrap').remove();
    });

    $('.qc_ad3d_index_content').on('click', '.frmAdd .qc_save', function () {
        qc_ad3d_system_date_off.add.save($(this).parents('.frmAdd'));
    });
});