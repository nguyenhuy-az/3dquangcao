/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_finance_transfers_transfers = {
    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
        qc_main.scrollTop();
    },
    add: {
        save: function (formObject) {
            var cbReceiveStaff = $(formObject).find("select[name='cbReceiveStaff']");
            var txtMoney = $(formObject).find("input[name='txtMoney']");
            var txtReason = $(formObject).find("input[name='txtReason']");
            if (qc_main.check.inputNull(cbReceiveStaff, 'Chọn nhân viên')) {
                return false;
            }
            if (qc_main.check.inputNull(txtMoney, 'Nhập số tiền chuyển')) {
                return false;
            }
            if (qc_main.check.inputNull(txtReason, 'Nhập lý do chuyển')) {
                return false;
            }
            qc_ad3d_submit.ajaxFormHasReload(formObject, '', false);
            qc_main.scrollTop();
        }
    },
    edit: {
        get: function (listObject) {
            qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-edit') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
        },
        save: function (formObject) {
            var txtMoney = $(formObject).find("input[name='txtMoney']");
            var txtReason = $(formObject).find("input[name='txtReason']");
            var containNotify = $(formObject).find('.frm_notify');
            if (qc_main.check.inputNull(txtMoney, 'Nhập số tiền chuyển')) {
                return false;
            }
            if (qc_main.check.inputNull(txtReason, 'Nhập lý do chuyển')) {
                return false;
            } else {
                qc_ad3d_submit.ajaxFormHasReload(formObject, containNotify, true);
                $('#qc_container_content').animate({
                    scrollTop: 0
                })
            }

        }
    }/*,
    confirm: {
        get: function (href) {
            var contain = qc_ad3d.bodyIdName();
            qc_ad3d_submit.ajaxNotReload(href, $('#' + contain), false);
        },
        save: function (frm) {
            if(confirm('Tôi đã nhận được tiền bàn giao?')){
                qc_ad3d_submit.ajaxFormHasReload(frm);
            }
        }
    },
    delete: function (listObject) {
        if (confirm('Bạn muốn xóa thông tin ')) {
            qc_ad3d_submit.ajaxHasReload($(listObject).parents('.qc_ad3d_list_content').data('href-del') + '/' + $(listObject).data('object'), '', false);
        }
    }*/
}

//-------------------- lọc thông tin ------------
$(document).ready(function () {;
    //khi chọn công ty...
    $('body').on('change', '.cbCompanyFilter', function () {
        var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val();
        var href = $(this).data('href-filter') + '/' + $(this).val() + '/' + dateFilter + '/' + $('.cbTransfersStatus').val();
        qc_main.url_replace(href);
    })

    //khi tìm theo tên ..
    $('body').on('change', '.cbTransfersStatus', function () {
        var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val()
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + $('.cbTransfersStatus').val());
    })
    // theo ngay tháng ...
    $('body').on('change', '.cbDayFilter', function () {
        var dateFilter = $(this).val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + $('.cbTransfersStatus').val());
    })
    $('body').on('change', '.cbMonthFilter', function () {
        var dateFilter = $('.cbDayFilter').val() + '/' + $(this).val() + '/' + $('.cbYearFilter').val()
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + $('.cbTransfersStatus').val());
    })
    $('body').on('change', '.cbYearFilter', function () {
        var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $(this).val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + $('.cbTransfersStatus').val());
    })
});
//view
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_view', function () {
        qc_ad3d_finance_transfers_transfers.view($(this).parents('.qc_ad3d_list_object'));
    })
});

//-------------------- thêm mới ------------
$(document).ready(function () {
    //save
    $('.qc_ad3d_index_content').on('click', '.frmAdd .qc_save', function () {
        qc_ad3d_finance_transfers_transfers.add.save($(this).parents('.frmAdd'));
    })
});

//-------------------- sửa ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_edit', function () {
        qc_ad3d_finance_transfers_transfers.edit.get($(this).parents('.qc_ad3d_list_object'));
    });

    $('body').on('click', '.frmEdit .qc_save', function () {
        qc_ad3d_finance_transfers_transfers.edit.save($(this).parents('.frmEdit'));
    });
});

//-------------------- xác nhận ------------
/*$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_ad3d_transfer_confirm_receive_act', function () {
        qc_ad3d_finance_transfers_transfers.confirm.get($(this).data('href'));
    });

    $('body').on('click', '.frmAd3dTransferConfirmReceive .qc_save', function () {
        qc_ad3d_finance_transfers_transfers.confirm.save($(this).parents('.frmAd3dTransferConfirmReceive'));
    });
});*/

//xóa
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_delete', function () {
        qc_ad3d_finance_transfers_transfers.delete($(this).parents('.qc_ad3d_list_object'));
    });
});
