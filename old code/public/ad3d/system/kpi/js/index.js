/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_system_kpi = {

    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
    },
    add: {
        save: function (frm) {
            /* var txtName = $(frm).find("input[name='txtName']");

             if (qc_main.check.inputNull(txtName, 'Nhập tên lĩnh vực')) {
             return false;
             } else {
             if (qc_main.check.inputMaxLength(txtName, 100, 'Tên không dài quá 10 ký tự')) return false;
             }*/
            if (confirm('Bạn đồng ý thêm mức KPI này?')) {
                qc_ad3d_submit.ajaxFormHasReload(frm, '', false);
                qc_main.scrollTop();
            }
        }
    },
    edit: {
        get: function (listObject) {
            qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-edit') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
        },
        save: function (formObject) {
            var txtName = $(formObject).find("input[name='txtName']");
            var containNotify = $(formObject).find('.frm_notify');

            if (qc_main.check.inputNull(txtName, 'Nhập tên Lĩnh vực')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtName, 100, 'Tên không dài quá 100 ký tự')) return false;
            }
            qc_ad3d_submit.ajaxFormHasReload(formObject, containNotify, true);
            $('#qc_container_content').animate({
                scrollTop: 0
            })
        }
    },
    delete: function (formObject) {
        if (confirm('Bạn muốn xóa thông tin này này')) {
            alert('Chưa phát triển tính năng này');
        }
        ;
    }
}

//view
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_view', function () {
        qc_ad3d_system_kpi.view($(this).parents('.qc_ad3d_list_object'));
    })
});

$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_delete', function () {
        qc_ad3d_system_kpi.delete($(this).parents('.qc_ad3d_list_object'));
    })
});
//-------------------- edit ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_edit', function () {
        qc_ad3d_system_kpi.edit.get($(this).parents('.qc_ad3d_list_object'));
    })

    $('body').on('click', '.frmEdit .qc_save', function () {
        qc_ad3d_system_kpi.edit.save($(this).parents('.frmEdit'));
    })
});

//-------------------- add ------------
$(document).ready(function () {
    $('.qc_ad3d_index_content').on('click', '.frmAdd .qc_save', function () {
        qc_ad3d_system_kpi.add.save($(this).parents('.frmAdd'));
    })
});