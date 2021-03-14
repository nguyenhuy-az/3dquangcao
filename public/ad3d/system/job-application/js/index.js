/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_system_job_application = {
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
                var cbDay = $(frm).find("select[name='cbDay']");
                if (qc_main.check.inputNull(cbDay, 'Phải chọn ngày phỏng vấn')) {
                    $(cbDay).focus();
                    return false;
                } else {
                    if (confirm('xác nhận ĐỒNG Ý với hồ sơ này?')) {
                        qc_ad3d_submit.ajaxFormHasReload(frm, '', false);
                        //qc_ad3d_submit.ajaxFormNotReload(frm, '', false);
                    }
                }
            } else {
                if (confirm('Xác nhận KHÔNG ĐỒNG Ý với hồ sơ này?')) {
                    qc_ad3d_submit.ajaxFormHasReload(frm, '', false);
                }
            }

        }
    }
}

//-------------------- loc thong tin ------------
$(document).ready(function () {
    $('#qc_ad3d_container_content').on('change', '.cbCompanyFilter', function () {
        var href = $(this).data('href') + '/' + $(this).val() + '/' + $('.cbConfirmStatusFilter').val();
        qc_ad3d_system_job_application.filter(href);
    });
    $('#qc_ad3d_container_content').on('change', '.cbConfirmStatusFilter', function () {
        var href = $(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + $(this).val();
        qc_ad3d_system_job_application.filter(href);
    });
    /*$('body').on('click', '.qc_ad_frm_reset_pass .qc_save ', function () {
     qc_ad3d_system_job_application.resetPass.save($(this).parents('.qc_ad_frm_reset_pass'));
     });*/
});

//-------------------- xac nhan ho so ------------
$(document).ready(function () {
    $('.ad3dFrmConfirmJobApplication').on('change', '.cbAgreeStatus', function () {
        var agreeStatus = $(this).val();
        if (agreeStatus == 0) {
            $('#jobApplicationInterview').hide();
        } else if (agreeStatus == 1) {
            $('#jobApplicationInterview').show();
        }
    });
    $('.ad3dFrmConfirmJobApplication').on('click', '.qc_save', function () {
        qc_ad3d_system_job_application.confirm.save($(this).parents('.ad3dFrmConfirmJobApplication'));
    });
});