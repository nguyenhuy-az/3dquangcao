/**
 * Created by HUY on 1/18/2019.
 */
var qc_ad3d_system_bonus_department = {
    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
        qc_main.scrollTop();
    },
    add: {
        get: function (href) {
            qc_ad3d_submit.ajaxNotReload(href, $('#' + qc_ad3d.bodyIdName()), false);
        },
        save: function (form) {
            var containNotify = $(form).find('.frm_notify');
            qc_ad3d_submit.ajaxFormHasReload(form, containNotify, true);
            $('#qc_container_content').animate({
                scrollTop: 0
            });
        }
    },
    edit: {
        get: function (listObject) {
            qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-edit') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
        },
        save: function (form) {
            var containNotify = $(form).find('.frm_notify');
            qc_ad3d_submit.ajaxFormHasReload(form, containNotify, true);
            $('#qc_container_content').animate({
                scrollTop: 0
            })
        }
    },
    delete: function (listObject) {
        if (confirm('Sẽ xóa các thông tin liên quan, và không phục hồi được, vẫn xóa?')) {
            qc_ad3d_submit.ajaxHasReload($(listObject).parents('.qc_ad3d_list_content').data('href-del') + '/' + $(listObject).data('object'), '', false);
        }
    }
}

//-------------------- filter ------------
$(document).ready(function () {
    //khi chọn  lĩnh vực phạt
    $('body').on('change', '.cbPunishTypeFilter', function () {
        var href = $(this).data('href') + '/' + $('.cbPunishTypeFilter').val();
        qc_main.url_replace(href);
    })

});
//view
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_view', function () {
        qc_ad3d_system_bonus_department.view($(this).parents('.qc_ad3d_list_object'));
    })
});

//-------------------- add ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_add', function () {
        qc_ad3d_system_bonus_department.add.get($(this).data('href'));
    });
    //lưu
    $('body').on('click', '.frmAdd .qc_save', function () {
        qc_ad3d_system_bonus_department.add.save($(this).parents('.frmAdd'));
    })
});
//-------------------- cap nhat thong tin ap dung thuong phat ------------
$(document).ready(function () {
    /*trang thai thuong*/
    $('.qc_ad3d_list_object').on('click', '.qc_update_apply_bonus', function () {
        qc_ad3d_submit.ajaxHasReload($(this).data('href'),'',false);
    });
    /*trang thai phat*/
    $('.qc_ad3d_list_object').on('click', '.qc_update_apply_minus', function () {
        qc_ad3d_submit.ajaxHasReload($(this).data('href'),'',false);
    });
});
//-------------------- cap nhat thong tin ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_edit', function () {
        qc_ad3d_system_bonus_department.edit.get($(this).parents('.qc_ad3d_list_object'));
    });

    $('body').on('click', '.frmEdit .qc_save', function () {
        qc_ad3d_system_bonus_department.edit.save($(this).parents('.frmEdit'));
    });
});

//delete
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_delete', function () {
        qc_ad3d_system_bonus_department.delete($(this).parents('.qc_ad3d_list_object'));
    });
});