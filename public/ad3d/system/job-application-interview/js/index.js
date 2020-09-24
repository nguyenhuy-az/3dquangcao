/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_system_job_application_interview = {
    filter: function (href) {
        qc_main.url_replace(href);
    },
    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
    },
    confirm: {
        save: function (frm) {
            var agreeStatus = $(frm).find("select[name='cbAgreeStatus']").val();
            if (agreeStatus == 1) {
                /*chon dong y*/
                var txtSalary = $(frm).find("input[name='txtSalary']");
                var cbDay = $(frm).find("select[name='cbDay']");
                if (qc_main.check.inputNull(txtSalary, 'PHẢI NHẬP MỨC LƯƠNG THỬ VIỆC')) {
                    qc_main.scrollTop();
                    return false;
                } else {
                    var money = qc_main.getNumberInput(txtSalary.val());
                    if (money > 50000000) {
                        alert('MỨC LƯƠNG THỬ VIỆC KHÔNG QUÁ 50.000.000');
                        $(txtSalary).focus();
                        return false;
                    }
                }
                if (qc_main.check.inputNull(cbDay, 'PHẢI CHỌN NGÀY BẮT ĐẦU LÀM VIỆC')) {
                    $(cbDay).focus();
                    return false;
                } else {
                    if (confirm('xác nhận ĐỒNG Ý TUYỂN DỤNG?')) {
                        qc_ad3d_submit.ajaxFormHasReload(frm, '', false);
                       // qc_ad3d_submit.ajaxFormNotReload(frm, '', false);
                    }
                }
            } else {
                if (confirm('Xác nhận KHÔNG ĐỒNG Ý TUYỂN DỤNG?')) {
                    qc_ad3d_submit.ajaxFormHasReload(frm, '', false);
                }
            }

        }
    }
}

//-------------------- loc thong tin ------------
$(document).ready(function () {
    $('.qc_ad3d_list_content').on('change', '.cbCompanyFilter', function () {
        var href = $(this).data('href') + '/' + $(this).val() + '/' + $('.cbConfirmStatusFilter').val();
        qc_ad3d_system_job_application_interview.filter(href);
    });
    $('.qc_ad3d_list_content').on('change', '.cbConfirmStatusFilter', function () {
        var href = $(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + $(this).val();
        qc_ad3d_system_job_application_interview.filter(href);
    });
    /*$('body').on('click', '.qc_ad_frm_reset_pass .qc_save ', function () {
     qc_ad3d_system_job_application_interview.resetPass.save($(this).parents('.qc_ad_frm_reset_pass'));
     });*/
});

//-------------------- xac nhan ho so ------------
$(document).ready(function () {
    $('.ad3dFrmConfirmJobApplicationInterview').on('change', '.cbAgreeStatus', function () {
        var agreeStatus = $(this).val();
        if (agreeStatus == 0) {
            $('#jobApplicationInterviewContent').hide();
        } else if (agreeStatus == 1) {
            $('#jobApplicationInterviewContent').show();
        }
    });
    $('.ad3dFrmConfirmJobApplicationInterview').on('click', '.qc_save', function () {
        qc_ad3d_system_job_application_interview.confirm.save($(this).parents('.ad3dFrmConfirmJobApplicationInterview'));
    });
});