/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_staff_salary = {
    filter: function (href) {
        qc_main.url_replace(href);
    },
    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view') + '/' + $(listObject).data('staff'), $('#' + qc_ad3d.bodyIdName()), false);
        qc_main.scrollTop();
    },
    edit: {
        get: function (listObject) {
            qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-edit') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
        },
        save: function (formObject) {
            var txtNewSalary = $(formObject).find("input[name='txtNewSalary']");
            var containNotify = $(formObject).find('.frm_notify');
            if (qc_main.check.inputNull(txtNewSalary, 'Nhập lương mới')) {
                return false;
            }
            qc_ad3d_submit.ajaxFormHasReload(formObject, containNotify, true);
            $('#qc_container_content').animate({
                scrollTop: 0
            })
        }
    },
    delete: function (salaryObject) {
        if (confirm('Bạn muốn xóa nhân viên này')) {
            alert('Chưa phát triển tính năng này');
        }
        ;
    }
}
//view
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_view', function () {
        qc_ad3d_staff_salary.view($(this).parents('.qc_ad3d_list_object'));
    })
});

//-------------------- filter ------------
$(document).ready(function () {
    $('body').on('change', '.cbCompanyFilter', function () {
        var companyId = $(this).val();
        var href = $(this).data('href-filter');
        if (companyId != '') {
            href = href + '/' + companyId;
        }
        qc_ad3d_staff_salary.filter(href);
    });
});

//-------------------- edit ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_edit', function () {
        qc_ad3d_staff_salary.edit.get($(this).parents('.qc_ad3d_list_object'));
    });

    $('body').on('click', '.frmEdit .qc_save', function () {
        qc_ad3d_staff_salary.edit.save($(this).parents('.frmEdit'));
    });
});

//delete
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_delete', function () {
        qc_ad3d_staff_salary.delete($(this).parents('.qc_ad3d_list_object'));
    });
});