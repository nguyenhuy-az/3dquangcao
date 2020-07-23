/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_tool_tool = {
    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
    },
    add: {
        save: function (form) {
            var cbToolType = $(form).find("select[name='cbToolType']");
            var txtName = $(form).find("input[name='txtName']");
            var txtUnit = $(form).find("input[name='txtUnit']");
            if (qc_main.check.inputNull(cbToolType, 'Chọn loại dụng cụ')) {
                return false;
            }
            if (qc_main.check.inputNull(txtName, 'Nhập tên dụng cụ')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtName, 50, 'Tên không dài quá 50 ký tự')) return false;
            }
            if (qc_main.check.inputNull(txtUnit, 'Nhập đơn vị tính')) {
                return false;
            }
            qc_ad3d_submit.ajaxFormHasReload(form, '', false);
            qc_main.scrollTop();
        }
    },
    edit: {
        get: function (listObject) {
            qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-edit') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
        },
        save: function (form) {
            var txtName = $(form).find("input[name='txtName']");
            var txtUnit = $(form).find("input[name='txtUnit']");
            var containNotify = $(form).find('.frm_notify');

            if (qc_main.check.inputNull(txtName, 'Nhập tên dụng cụ')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtName, 50, 'Tên không dài quá 50 ký tự')) return false;
            }
            if (qc_main.check.inputNull(txtUnit, 'Nhập đơn vị tính')) {
                return false;
            }
            qc_ad3d_submit.ajaxFormHasReload(form, containNotify, true);
            $('#qc_container_content').animate({
                scrollTop: 0
            })
        }
    },
    delete: function (listObject) {
        if (confirm('SẼ HỦY NHỮNG THÔNG TIN LIÊN QUAN, Bạn muốn xóa dụng cụ này')) {
            qc_ad3d_submit.ajaxHasReload($(listObject).parents('.qc_ad3d_list_content').data('href-del') + '/' + $(listObject).data('object'), '', false);
        }
    }
}
//loc thong tin
$(document).ready(function () {
    $('.qc_ad3d_index_content').on('change', '.cbToolTypeFilter', function () {
        qc_main.url_replace($(this).data('href') + '/' + $(this).val());
    })
});
//xem chi tiet
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_view', function () {
        qc_ad3d_tool_tool.view($(this).parents('.qc_ad3d_list_object'));
    })
});

$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_delete', function () {
        qc_ad3d_tool_tool.delete($(this).parents('.qc_ad3d_list_object'));
    })
});
//-------------------- edit ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_edit', function () {
        qc_ad3d_tool_tool.edit.get($(this).parents('.qc_ad3d_list_object'));
    })

    $('body').on('click', '.frmEdit .qc_save', function () {
        qc_ad3d_tool_tool.edit.save($(this).parents('.frmEdit'));
    })
});

//-------------------- add ------------
$(document).ready(function () {
    $('.qc_ad3d_index_content').on('click', '.frmAdd .qc_save', function () {
        qc_ad3d_tool_tool.add.save($(this).parents('.frmAdd'));
    })
});