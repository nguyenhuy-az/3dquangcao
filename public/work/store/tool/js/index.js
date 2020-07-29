/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_store_tool = {
    viewImage: function (href) {
        qc_master_submit.ajaxNotReload(href, '#qc_master', false);
    },
    /*return: {
        save: function (frm) {
            if (qc_work_store_tool.return.checkSelectTool()) {
                if (confirm('Tôi đồng ý giao lại các đồ nghề này?')) {
                    qc_master_submit.normalForm('#frmWorkToolPrivateReturn');
                    qc_main.scrollTop();
                }
            } else {
                alert('Phải chọn đồ nghề để bàn giao');
                return false;
            }

            return false;
        },
        checkSelectTool: function () {
            var checkStatus = false;
            $('#frmWorkToolPrivateReturn .txtReturnStore').filter(function () {
                if ($(this).is(':checked')) {
                    checkStatus = true;
                }
            });
            return checkStatus;
        }
    }*/
}

$(document).ready(function () {
    // loc theo muc dich su dung
    $('.qc_work_store_tool_wrap').on('change', '.cbToolTypeFilter', function () {
        qc_main.url_replace($(this).data('href') + '/' + $(this).val());
    });

    // loc theo loai do nghe
    $('.qc_work_store_tool_wrap').on('change', '.cbToolFilter', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.cbToolTypeFilter').val() + '/' + $(this).val());
    });
    // xem anh do nghe
    $('.qc_work_store_tool_wrap').on('click', '.qc_view_image_get', function () {
        qc_work_store_tool.viewImage($(this).data('href'));
    });
});