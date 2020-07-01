/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_tool_private = {
    confirmReceive: function (href) {
        if (confirm('Tôi đã nhận đồ nghề này')) {
            qc_master_submit.ajaxHasReload(href, '#qc_master', false);
        }
    },
    return: {
        save: function (frm) {
            if (qc_work_tool_private.return.checkSelectTool()) {
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
            $('#frmWorkToolPrivateReturn .txtReturnTool').filter(function () {
                if ($(this).is(':checked')) {
                    checkStatus = true;
                }
            });
            return checkStatus;
        }
    }
}

$(document).ready(function () {
    $('#frmWorkToolPrivateReturn').on('click', '.qc_save', function () {
        qc_work_tool_private.return.save('#frmWorkToolPrivateReturn');
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