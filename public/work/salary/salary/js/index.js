/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_salary = {
    confirm: {
        getConfirm: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        },
        save: function (form) {
            var notifyContent = $(form).find('.frm_notify');
            if (confirm('Tôi đã nhận số lương này')) {
                qc_master_submit.ajaxFormHasReload(form, notifyContent, true);
                qc_main.scrollTop();
            }

        },
    },
    kpi: {
        getRegister: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        }
    },
}

//===================== KPI ===========================
$(document).ready(function () {
    // loc loai SP theo ten
    $('.qc_work_kpi_statistic_wrap').on('click', '.qc_work_kpi_register_get', function () {
        qc_work_salary.kpi.getRegister($(this).data('href'));
    });
    $('body').on('change', '.frm_work_kpi_register_add .cbKpi', function () {
        var kpiId = $(this).val();
        var href = $(this).data('href');
        if (kpiId > 0) {
            href = href + '/' + kpiId;
        }
        qc_main.remove('.frm_work_kpi_register_add_wrap');
        qc_work_salary.kpi.getRegister(href);
    });
});

//======== ========= ======== LƯƠNG ========= ============= =========
$(document).ready(function () {
    $('.qc_work_salary_salary_content').on('click', '.qc_salary_pay_confirm_get', function () {
        qc_work_salary.confirm.getConfirm($(this).data('href'));
    });

    $('body').on('click', '#frmWorkSalaryPayConfirm .qc_save', function () {
        qc_work_salary.confirm.save($(this).parents('#frmWorkSalaryPayConfirm'));
    });
});