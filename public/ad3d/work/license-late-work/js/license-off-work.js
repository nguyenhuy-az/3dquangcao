/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_work_license_off_work = {
    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view'), $('#' + qc_ad3d.bodyIdName()), false);
        qc_main.scrollTop();
    },
    confirm: {
        get: function (listObject) {
            var href = $(listObject).parents('.qc_ad3d_list_content').data('href-confirm') + '/' + $(listObject).data('object');
            qc_ad3d_submit.ajaxNotReload(href, $('#' + qc_ad3d.bodyIdName()), false);
            qc_main.scrollTop();
        },
        save: function (formObject) {
            var notifyContent = $(formObject).find('.notifyConfirm');
            qc_ad3d_submit.ajaxFormHasReload(formObject, notifyContent, true);
            qc_main.scrollTop();
        }
    }
}
//-------------------- filter ------------
$(document).ready(function () {
    //khi chọn công ty...
    $('body').on('change', '.cbCompanyFilter', function () {
        var companyId = $(this).val();
        //var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val();
        var dateFilter = 0 + '/' + 0 + '/' + 0;
        var href = $(this).data('href-filter');
        if (companyId != '') {
            href = href + '/' + companyId + '/' + dateFilter;
        }
        qc_main.url_replace(href);
    })
});

//-------------------- confirm ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_confirm', function () {
        qc_ad3d_work_license_off_work.confirm.get($(this).parents('.qc_ad3d_list_object'));
    })
    $('body').on('click', '.qc_ad3d_frm_confirm .qc_save', function () {
        qc_ad3d_work_license_off_work.confirm.save($(this).parents('.qc_ad3d_frm_confirm'));
    })
});

