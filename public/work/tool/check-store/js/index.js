/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_tool_check_store = {
    confirm: function (frm) {
        if (confirm('Tôi đồng ý với xác nhận đồ nghề này')) {
            qc_master_submit.ajaxFormHasReload(frm, '', false);
            //qc_master_submit.ajaxFormNotReload(frm, '', false);
            //qc_master_submit.normalForm(frm);
        }
    }
}

$(document).ready(function () {
    $('#frmWorkToolCheckCompanyStore').on('click', '.qc_save', function () {
        qc_work_tool_check_store.confirm('#frmWorkToolCheckCompanyStore');
    });
});