/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_store_tool = {
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
    $('.qc_work_store_tool_wrap').on('change', '.cbToolTypeFilter', function () {
        qc_main.url_replace($(this).data('href') + '/' + $(this).val());
    });

    $(".txtCheckAll").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

    /*$('#frmWorkToolPrivateReturn').on('click', '.txtCheckAll', function () {
     if ($(this).is(':checked')) {
     //alert('co chon');
     $('.txtReturnTool').attr('checked','checked');
     }else{
     //alert('khong chon');
     }
     });*/
});