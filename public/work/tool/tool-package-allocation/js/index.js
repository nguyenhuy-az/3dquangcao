/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_tool_package_allocation = {
    viewImage: function (href) {
        qc_master_submit.ajaxNotReload(href, '#qc_master', false);
    },
    /*confirmReceive: function (href) {
     if (confirm('Tôi đã nhận đồ nghề này')) {
     qc_master_submit.ajaxHasReload(href, '#qc_master', false);
     }
     },*/
    report: {
        getReport: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        },
        saveReport: function (frm) {
            var cbReportUseStatus = $(frm).find("select[name='cbReportUseStatus']");
            var txtReportNote = $(frm).find("textarea[name='txtReportNote']");
            var txtReportImage = $(frm).find("input[name='txtReportImage']");
            var notifyContent = $(frm).find('.frm_notify');
            if (qc_main.check.inputNull(cbReportUseStatus, 'Chọn lý do')) {
                $(cbReportUseStatus).focus();
                return false;
            } else {
                if (cbReportUseStatus.val() == 2) {
                    // bao hu phai co hinh anh
                    if (qc_main.check.inputNull(txtReportImage, 'Phải có ảnh báo cáo')) {
                        $(txtReportImage).focus();
                        return false;
                    } else {
                        qc_master_submit.ajaxFormHasReload(frm, notifyContent, true);
                        qc_main.scrollTop();
                    }
                } else {
                    if (qc_main.check.inputNull(txtReportNote, 'Phải có lý do')) {
                        $(txtReportNote).focus();
                        return false;
                    } else {
                        qc_master_submit.ajaxFormHasReload(frm, notifyContent, true);
                        qc_main.scrollTop();
                    }
                }
            }
        }
    },
    return: {
        save: function (frm) {
            if (qc_work_tool_package_allocation.return.checkSelectTool()) {
                if (qc_work_tool_package_allocation.return.checkSelectImage()) {
                    if (confirm('Tôi đồng ý giao lại các đồ nghề này?')) {
                        qc_master_submit.normalForm('#frmWorkToolPrivateReturn');
                        qc_main.scrollTop();
                    }
                } else {
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
                    if ($('#txtReturnImage_' + detailId).val().length == 0) checkImage = false;
                }
            });
            return checkImage;
        }
    }
}
//------------ ----------- Bao cao do nghe ---------------- ----------------
$(document).ready(function () {
    $('.qc_work_tool_package_allocation').on('click', '.qc_allocation_report_get', function () {
        qc_work_tool_package_allocation.report.getReport($(this).data('href'));
    });
    $('body').on('click', '#qcFrmToolPackageAllocationReport .qc_save', function () {
        qc_work_tool_package_allocation.report.saveReport($(this).parents('#qcFrmToolPackageAllocationReport'));
    });
});
//------------ ----------- ------------ ---------------- ----------------
$(document).ready(function () {
    $('.qc_work_tool_package_allocation').on('click', '.qc_view_image_get', function () {
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