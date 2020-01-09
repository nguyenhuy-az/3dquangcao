/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_finance_collect_money = {
    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view'), $('#' + qc_ad3d.bodyIdName()), false);
        qc_main.scrollTop();
    },
    add:{
        post:function(formObject){
            alert('Phát triển sau');
            return false;
        }
    },
    edit: {
        get: function (orderObject) {
            var href = $(orderObject).parents('.qc_ad3d_list_content').data('href-edit');
            var contain = qc_ad3d.bodyIdName();
            qc_ad3d_submit.ajaxNotReload(href, $('#' + contain), false);
        },
        post: function () {

        }
    },
    delete: function (orderObject) {
        if(confirm('Bạn muốn xóa đơn hàng này')){
            alert('Chưa phát triển tính năng này');
        };
    }
}
//view
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_view', function () {
        qc_ad3d_finance_collect_money.view($(this).parents('.qc_ad3d_list_object'));
    })
});

$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_delete', function () {
        qc_ad3d_finance_collect_money.delete($(this).parents('.qc_ad3d_list_object'));
    })
});

$(document).ready(function () {
    $('#frmAd3dAdd').on('click', '.qc_save', function () {
        qc_ad3d_finance_collect_money.add.post($(this).parents('#frmAd3dAdd'));
    })
});
