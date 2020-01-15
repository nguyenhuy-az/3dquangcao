/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_order_product_type_price = {
    add: {
        save: function (formObject) {
            var txtName = $(formObject).find("input[name='txtName']");
            var txtTypeCode = $(formObject).find("input[name='txtTypeCode']");

            if (qc_main.check.inputNull(txtName, 'Nhập tên loại sản phẩm')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtName, 50, 'Tên không dài quá 50 ký tự')) return false;
            }
            if (qc_main.check.inputNull(txtTypeCode, 'Nhập mã loại sản phẩm')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtTypeCode, 30, 'Mã số thuế dài không quá 30 ký tự')) return false;
            }
            qc_ad3d_submit.ajaxFormHasReload(formObject, '', false);
            qc_main.scrollTop();
        }
    },
    edit: {
        get: function (listObject) {
            qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-edit') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
        },
        save: function (form) {
            var containNotify = $(form).find('.frm_notify');
            qc_ad3d_submit.ajaxFormHasReload(form, containNotify, true);
            qc_main.scrollTop();
        }
    },
    delete: function (listObject) {
        if (confirm('Bạn muốn xóa bảng giá này')) {
            qc_ad3d_submit.ajaxHasReload($(listObject).parents('.qc_ad3d_list_content').data('href-del') + '/' + $(listObject).data('object'), '', false);
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
            href = href + '/' + companyId + '/' + $('.textFilterName').val();
        }
        qc_main.url_replace(href);
    })

    //khi tìm theo tên ..
    $('body').on('click', '.btn_filterName', function () {
        var name = $('.textFilterName').val();
        if (name.length == 0) {
            alert('Bạn phải nhập thông tin tìm kiếm');
            $('.textFilterName').focus();
            return false;
        } else {
            qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + name);
        }
    })
});

//-------------------- Xoa ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_delete', function () {
        qc_ad3d_order_product_type_price.delete($(this).parents('.qc_ad3d_list_object'));
    });
});


//-------------------- sua ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_edit', function () {
        qc_ad3d_order_product_type_price.edit.get($(this).parents('.qc_ad3d_list_object'));
    });

    $('body').on('click', '.frmEdit .qc_save', function () {
        qc_ad3d_order_product_type_price.edit.save($(this).parents('.frmEdit'));
    });
});
//----------- --------- sao chep bang gia ------------ -----
$(document).ready(function () {
    $('.frmAd3dProductTypePriceCopy').on('change', '.cbCompanyCopy', function () {
        qc_main.url_replace($(this).data('href') + '/' + $(this).val());
    });

    $('.frmAd3dProductTypePriceCopy').on('click', '.qc_save', function () {
        var frm = $(this).parents('.frmAd3dProductTypePriceCopy');
        if(confirm('Tôi đồng ý sao chép bảng giá n')){
            qc_ad3d_submit.ajaxFormHasReload(frm, '', false);
            qc_main.scrollTop();
        }

    });
});