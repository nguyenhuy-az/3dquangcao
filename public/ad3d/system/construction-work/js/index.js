/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_system_construction_work = {
    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
    },
    add: {
        save: function (frm) {
            var txtName = $(frm).find("input[name='txtName']");
            if (qc_main.check.inputNull(txtName, 'Nhập tên danh mục thi công')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtName, 100, 'Tên không dài quá 100 ký tự')) return false;
            }
            if (confirm('Bạn đồng ý thêm danh mục thi công này?')) {
                qc_ad3d_submit.ajaxFormHasReload(frm, '', false);
                qc_main.scrollTop();
            }
        }
    },
    edit: {
        get: function (listObject) {
            qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-edit') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
        },
        save: function (frm) {
            var txtName = $(frm).find("input[name='txtName']");
            var containNotify = $(frm).find('.frm_notify');

            if (qc_main.check.inputNull(txtName, 'Nhập tên danh mục thi công')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtName, 100, 'Tên không dài quá 100 ký tự')) return false;
            }
            qc_ad3d_submit.ajaxFormHasReload(frm, containNotify, true);
            $('#qc_container_content').animate({
                scrollTop: 0
            })
        }
    },
    delete: function (listObject) {
        if (confirm('Các thông tin liên quan sẽ bị xóa, Bạn muốn xóa thông tin này?')) {
            qc_ad3d_submit.ajaxHasReload($(listObject).parents('.qc_ad3d_list_content').data('href-del') + '/' + $(listObject).data('object'),'', false);
        }
        ;
    }
}

//---------- ---------- xem chi tiet ------------ -----------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_view', function () {
        qc_ad3d_system_construction_work.view($(this).parents('.qc_ad3d_list_object'));
    })
});
//---------- ---------- xoa thong tin ------------ -----------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_delete', function () {
        qc_ad3d_system_construction_work.delete($(this).parents('.qc_ad3d_list_object'));
    })
});
//---------- ---------- cap nhat thong tin ------------ -----------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_edit', function () {
        qc_ad3d_system_construction_work.edit.get($(this).parents('.qc_ad3d_list_object'));
    })

    $('body').on('click', '.frmEdit .qc_save', function () {
        qc_ad3d_system_construction_work.edit.save($(this).parents('.frmEdit'));
    })
});

//-------------------- them moi ------------
$(document).ready(function () {
    $('.qc_ad3d_index_content').on('click', '.frmAdd .qc_save', function () {
        qc_ad3d_system_construction_work.add.save($(this).parents('.frmAdd'));
    })
});