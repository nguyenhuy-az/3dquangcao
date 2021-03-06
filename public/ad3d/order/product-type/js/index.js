/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_order_product_type = {

    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
    },
    add: {
        save: function (form) {
            var txtName = $(form).find("input[name='txtName']");;

            if (qc_main.check.inputNull(txtName, 'Nhập tên loại sản phẩm')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtName, 50, 'Tên không dài quá 50 ký tự')) return false;
            }
            qc_ad3d_submit.ajaxFormHasReload(form, '', false);
            //qc_ad3d_submit.ajaxFormNotReload(form, '', false);
            qc_main.scrollTop();
            $(form).find('.qc_reset').click();
        }
    },
    edit: {
        get: function (listObject) {
            qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-edit') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
        },
        save: function (formObject) {
            var txtName = $(formObject).find("input[name='txtName']");
            var containNotify = $(formObject).find('.frm_notify');

            if (qc_main.check.inputNull(txtName, 'Nhập tên công ty')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtName, 50, 'Tên không dài quá 50 ký tự')) return false;
            }
            qc_ad3d_submit.ajaxFormHasReload(formObject, containNotify, true);
            $('#qc_container_content').animate({
                scrollTop: 0
            })
        }
    },
    confirm: {
        get: function (href) {
            qc_ad3d_submit.ajaxNotReload(href, $('#' + qc_ad3d.bodyIdName()), false);
            qc_main.scrollTop();
        },
        save: function (formObject) {
            var containNotify = $(formObject).find('.frm_notify');
            if(confirm('Tôi đồng ý với xác nhận này.')){
                qc_ad3d_submit.ajaxFormHasReload(formObject, containNotify, true);
                qc_main.scrollTop();
            }

        }
    },
    image: {
        getAdd: function (listObject) {
            qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-add-img') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
        },
        save: function (formObject) {
            var containNotify = $(formObject).find('.frm_notify');
            qc_ad3d_submit.ajaxFormHasReload(formObject, containNotify, true);
            $('#qc_container_content').animate({
                scrollTop: 0
            })
        },
        view: function (href) {
            qc_ad3d_submit.ajaxNotReload(href, $('#' + qc_ad3d.bodyIdName()), false);
        },
        delete: function (href) {
            if (confirm('Bạn muốn xóa ảnh mẫu này')) {
                qc_ad3d_submit.ajaxHasReload(href, '', false);
            }
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
    });
});
//-------------------- Duyet loai san pham ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_confirm_apply', function () {
        qc_ad3d_order_product_type.confirm.get($(this).data('href'));
    });
    $('body').on('click', '.frmAd3dOrderProductTypeConfirm .qc_save', function () {
        qc_ad3d_order_product_type.confirm.save($(this).parents('.frmAd3dOrderProductTypeConfirm'));
    });
});
//-------------------- ANH MAU ------------
$(document).ready(function () {
    // xem anh
    $('.qc_ad3d_list_object').on('click', '.qc_image_view', function () {
        qc_ad3d_order_product_type.image.view($(this).data('href'));
    });
    // them
    $('.qc_ad3d_list_object').on('click', '.qc_add_image_action', function () {
        qc_ad3d_order_product_type.image.getAdd($(this).parents('.qc_ad3d_list_object'));
    });

    $('body').on('click', '.frmAddImage .qc_save', function () {
        qc_ad3d_order_product_type.image.save($(this).parents('.frmAddImage'));
    });
    // xoa anh
    $('.qc_ad3d_list_object').on('click', '.qc_delete_image_action', function () {
        qc_ad3d_order_product_type.image.delete($(this).data('href'));
    });
});
//-------------------- sua thong tin ------------
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