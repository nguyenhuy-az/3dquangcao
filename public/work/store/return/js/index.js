/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_store_return = {
    confirm: {
        getForm: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        },
        save: function (frm) {
            if (confirm('Tôi đồng ý xác nhận này?')) {
                qc_master_submit.ajaxFormHasReload(frm);
                qc_main.scrollTop();
            }
        }
    }
}
// -------------- ----------   Loc thong tin ----------- -----------
$(document).ready(function () {
    //loc theo trang thai xac nhan
    $('.qc_work_store_return_wrap').on('change', '.cbConfirmStatusFilter', function () {
        qc_main.url_replace($(this).data('href') + '/' + $(this).val());
    });
});

// -------------- ----------  Xac nhan tra ----------- -----------
$(document).ready(function () {
    //lay form xac nhan
    $('.qc_work_store_return_wrap').on('click', '.qc_confirm_get', function () {
        qc_work_store_return.confirm.getForm($(this).data('href'));
    });
    //xac nhan
    $('body').on('click', '#frmWorkStoreReturn .qc_save', function () {
        qc_work_store_return.confirm.save($(this).parents('#frmWorkStoreReturn'));
    });
});