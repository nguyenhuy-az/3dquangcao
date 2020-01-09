/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_tool_type = {
    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view'), $('#' + qc_ad3d.bodyIdName()), false);
    },
    edit: {
        get: function (staffObject) {
            var href = $(staffObject).parents('.qc_ad3d_list_content').data('href-edit');
            var contain = qc_ad3d.bodyIdName();
            qc_ad3d_submit.ajaxNotReload(href, $('#' + contain), false);
        },
        post: function () {

        }
    },
    delete: function (staffObject) {
        if(confirm('Bạn muốn xóa dụng cụ này')){
            alert('Chưa phát triển tính năng này');
        };
    }
}

//view
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_view', function () {
        qc_ad3d_tool_type.view($(this).parents('.qc_ad3d_list_object'));
    })
});

$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_edit', function () {
        qc_ad3d_tool_type.edit.get($(this).parents('.qc_ad3d_list_object'));
    })
});

$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_delete', function () {
        qc_ad3d_tool_type.delete($(this).parents('.qc_ad3d_list_object'));
    })
});