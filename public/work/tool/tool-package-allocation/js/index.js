/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_tool_package_allocation = {
    viewImage: function (href) {
        qc_master_submit.ajaxNotReload(href, '#qc_master', false);
    },
    confirmReceive: function (href) {
        if (confirm('Tôi đã nhận đồ nghề này')) {
            qc_master_submit.ajaxHasReload(href, '#qc_master', false);
        }
    },
    return: {
        save: function (frm) {
            if (qc_work_tool_package_allocation.return.checkSelectTool()) {
                if(qc_work_tool_package_allocation.return.checkSelectImage()){
                    if (confirm('Tôi đồng ý giao lại các đồ nghề này?')) {
                        qc_master_submit.normalForm('#frmWorkToolPrivateReturn');
                        qc_main.scrollTop();
                    }
                }else{
                    alert('Đồ nghề trả phải có hình ảnh');
                    return false;
                }

            } else {
                alert('Phải chọn đồ nghề để bàn giao');
                return false;
            }

            return false;
        },
        checkSelectTool: function () {
            var checkStatus = false;
            $('#frmWorkToolPrivateReturn .txtAllocationDetail').filter(function () {
                if ($(this).is(':checked')) {
                    checkStatus = true;
                }
            });
            return checkStatus;
        },
        checkSelectImage: function () {
            var checkImage = true;
            $('#frmWorkToolPrivateReturn .txtAllocationDetail').filter(function () {
                if ($(this).is(':checked')) {
                    var detailId = $(this).val();
                    //var imageName = $('#txtReturnImage_' + detailId).val();
                    if ($('#txtReturnImage_' + detailId).val().length == 0 ) checkImage = false;
                }
            });
            return checkImage;
        }
    }
}

$(document).ready(function () {
    $('.qc_work_tool_wrap').on('click', '.qc_view_image_get', function () {
        qc_work_tool_package_allocation.viewImage($(this).data('href'));
    });
});

$(document).ready(function () {
    $('#frmWorkToolPrivateReturn').on('click', '.qc_save', function () {
        qc_work_tool_package_allocation.return.save('#frmWorkToolPrivateReturn');
    });

    $(".txtCheckAll").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
});