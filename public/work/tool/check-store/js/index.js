/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_tool_check_store = {
    viewImage: function (href) {
        qc_master_submit.ajaxNotReload(href, '#qc_master', false);
    },
    confirm: function (frm) {
        if (confirm('Tôi đồng ý với xác nhận đồ nghề này')) {
            qc_master_submit.ajaxFormHasReload(frm, '', false);
            //qc_master_submit.ajaxFormNotReload(frm, '', false);
            //qc_master_submit.normalForm(frm);
        }
    }
}
// loc thong tin
$(document).ready(function () {
    $('.qc_work_tool_wrap').on('change', '.cbCompanyStoreCheckFilter', function () {
        qc_main.url_replace($(this).data('href') + '/' + $(this).val());
    });
    // xem anh do nghe
    $('.qc_work_tool_wrap').on('click', '.qc_view_image_get', function () {
        qc_work_tool_check_store.viewImage($(this).data('href'));
    });
});
// bao cao
$(document).ready(function () {
    $('#frmWorkToolCheckCompanyStore').on('click', '.qc_save', function () {
        qc_work_tool_check_store.confirm('#frmWorkToolCheckCompanyStore');
    });
});