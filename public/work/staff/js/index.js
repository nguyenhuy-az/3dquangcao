/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_staff = {
    workSkill: {
        getUpdate: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        },
        postUpdate: function (frm) {
            var containerNotify = $(frm).find(".qc_notify_content");
            qc_master_submit.ajaxFormHasReload(frm, containerNotify, true);
        }
    },
    account: function (frm) {
        var txtOldAccount = $(frm).find("input[name='txtOldAccount']");
        var txtNewAccount = $(frm).find("input[name='txtNewAccount']");

        if (qc_main.check.inputNull(txtOldAccount, 'Nhập mã truy cập hiện tại')) {
            $(txtOldAccount).focus();
            return false;
        }

        if (qc_main.check.inputNull(txtNewAccount, 'Nhập mã truy cập mới')) {
            $(txtNewAccount).focus();
            return false;
        } else {
            qc_master_submit.normalForm(frm);
        }
    }
}


//=========== ========== THONG TIN ============ ===============
$(document).ready(function () {
    // cap nhat ky nang
    $('.qc_work_staff_info_wrap').on('click', '.qc_update_work_skill_get', function () {
        qc_work_staff.workSkill.getUpdate($(this).data('href'));
    });
    $('body').on('click', '.frmWorkSkillUpdate .qc_save', function () {
        qc_work_staff.workSkill.postUpdate($(this).parents('.frmWorkSkillUpdate'));
    });
});

//====================== DANG NHAP ========================
$(document).ready(function () {
    $('body').on('click', '.qc_work_login', function () {
        qc_work.login($(this).parents('.frmWorkLogin'));
    });
});

$(document).ready(function () {
    $('.frmWorkStaffChangeAccount').on('click', '.qc_save', function () {
        qc_work_staff.account($(this).parents('.frmWorkStaffChangeAccount'));
    });
});