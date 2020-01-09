/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_order_product_type = {

    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
    },
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
        save: function (formObject) {
            var txtName = $(formObject).find("input[name='txtName']");
            var txtTypeCode = $(formObject).find("input[name='txtTypeCode']");
            var containNotify = $(formObject).find('.frm_notify');

            if (qc_main.check.inputNull(txtName, 'Nhập tên công ty')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtName, 50, 'Tên không dài quá 50 ký tự')) return false;
            }
            if (qc_main.check.inputNull(txtTypeCode, 'Nhập mã số thuế')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtTypeCode, 30, 'Mã số thuế dài không quá 30 ký tự')) return false;
            }
            qc_ad3d_submit.ajaxFormHasReload(formObject, containNotify, true);
            $('#qc_container_content').animate({
                scrollTop: 0
            })
        }
    },
    delete: function (listObject) {
        if (confirm('Bạn muốn xóa loại sản phẩm này')) {
            qc_ad3d_submit.ajaxHasReload($(listObject).parents('.qc_ad3d_list_content').data('href-del') + '/' + $(listObject).data('object'), '', false);
        }
    }
}

//view
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_view', function () {
        qc_ad3d_order_product_type.view($(this).parents('.qc_ad3d_list_object'));
    })
});

$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_delete', function () {
        qc_ad3d_order_product_type.delete($(this).parents('.qc_ad3d_list_object'));
    })
});
//-------------------- edit ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_edit', function () {
        qc_ad3d_order_product_type.edit.get($(this).parents('.qc_ad3d_list_object'));
    })

    $('body').on('click', '.frmEdit .qc_save', function () {
        qc_ad3d_order_product_type.edit.save($(this).parents('.frmEdit'));
    })
});

//-------------------- add ------------
$(document).ready(function () {
    $('.qc_ad3d_index_content').on('click', '.frmAdd .qc_save', function () {
        qc_ad3d_order_product_type.add.save($(this).parents('.frmAdd'));
    })
});