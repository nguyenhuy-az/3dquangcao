/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_order_statistics = {
    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view'), $('#' + qc_ad3d.bodyIdName()), false);
        qc_main.scrollTop();
    },
}

//view
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_view', function () {
        qc_ad3d_order_statistics.view($(this).parents('.qc_ad3d_list_object'));
    })
});