/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_staff_rules = {

    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
    },
    add: {
        save: function (formObject) {
            var txtTitle = $(formObject).find("input[name='txtTitle']");
            var txtRuleContent = $(formObject).find("input[name='txtRuleContent']");
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
            if (qc_main.check.inputNull(txtTitle, 'Nhập tiêu đề bài viết')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtTitle, 300, 'Tên không dài quá 300 ký tự')) return false;
            }
            if (document.getElementById("txtRuleContent").value == '') {
                alert("no");
                document.getElementById("txtRuleContent").style.display = "none";
            } else {
                qc_ad3d_submit.ajaxFormHasReload(formObject, '', false);
                qc_main.scrollTop();
            }

        }
    },
    edit: {
        get: function (listObject) {
            qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-edit') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
        },
        save: function (formObject) {
            var txtTitle = $(formObject).find("input[name='txtTitle']");
            var txtRuleContent = $(formObject).find("input[name='txtRuleContent']");
            var containNotify = $(formObject).find("input[name='notifyContain']");

            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
            if (qc_main.check.inputNull(txtTitle, 'Nhập tiêu đề bài viết')) {
                return false;
            } else {
                if (qc_main.check.inputMaxLength(txtTitle, 300, 'Tên không dài quá 300 ký tự')) return false;
            }
            if (document.getElementById("txtRuleContent").value == '') {
                alert("no");
                document.getElementById("txtRuleContent").style.display = "none";
            } else {
                qc_ad3d_submit.ajaxFormHasReload(formObject, containNotify, true);
                $('#qc_container_content').animate({
                    scrollTop: 0
                })
            }

        }
    },
    delete: function (listObject) {
        if (confirm('Bạn muốn xóa dụng cụ này')) {
            qc_ad3d_submit.ajaxHasReload($(listObject).parents('.qc_ad3d_list_content').data('href-del') + '/' + $(listObject).data('object'), '', false);
        }
    }
}

//view
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_view', function () {
        qc_ad3d_staff_rules.view($(this).parents('.qc_ad3d_list_object'));
    })
});

$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_delete', function () {
        qc_ad3d_staff_rules.delete($(this).parents('.qc_ad3d_list_object'));
    })
});
//-------------------- edit ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_edit', function () {
        qc_ad3d_staff_rules.edit.get($(this).parents('.qc_ad3d_list_object'));
    })

    $('body').on('click', '.frmEdit .qc_save', function () {
        qc_ad3d_staff_rules.edit.save($(this).parents('.frmEdit'));
    })
});

//-------------------- add ------------
$(document).ready(function () {
    $('.qc_ad3d_index_content').on('click', '.frmAdd .qc_save', function () {
        qc_ad3d_staff_rules.add.save($(this).parents('.frmAdd'));
    })
});

//view
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_delete', function () {
        qc_ad3d_staff_rules.delete($(this).parents('.qc_ad3d_list_object'));
    })
});