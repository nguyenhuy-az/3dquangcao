/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_tool_check_store = {
    confirm: function (frm) {
        if (confirm('Tôi đồng ý với xác nhận đồ nghề này')) {
            qc_master_submit.ajaxFormHasReload(frm, '', false);
        }
    }
}

$(document).ready(function () {
    $('#frmWorkToolCheckStore').on('click', '.qc_save', function () {
        qc_work_tool_check_store.confirm('#frmWorkToolCheckStore');
    });
});