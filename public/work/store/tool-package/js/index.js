/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_store_tool_package = {
    /*viewImage: function (href) {
     qc_master_submit.ajaxNotReload(href, '#qc_master', false);
     },*/
    autoAllocation: {
        save: function (href) {
            //alert(href);
            if (confirm('Hệ thống sẽ tự động bàn giao túi đồ nghề cho nhân viên')) {
                qc_master_submit.ajaxHasReload(href, '', false);
                //qc_master_submit.ajaxNotReload(href, '', false);
            }
        }
    }
}

$(document).ready(function () {
    // xem anh do nghe
    $('.qc_work_store_tool_package_wrap').on('click', '.qc_auto_allocation_get', function () {
        qc_work_store_tool_package.autoAllocation.save($(this).data('href'));
    });
});