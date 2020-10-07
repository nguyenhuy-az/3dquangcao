/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_finance_transfers_receive = {
    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
        qc_main.scrollTop();
    },
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
    }
}

//-------------------- lọc thông tin ------------
$(document).ready(function () {
    ;
    //khi chọn công ty...
    $('body').on('change', '.cbCompanyFilter', function () {
        var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val();
        var href = $(this).data('href-filter') + '/' + $(this).val() + '/' + dateFilter + '/' + $('.cbTransfersType').val() + '/' + $('.cbStaffFilterId').val();
        qc_main.url_replace(href);
    });

    //khi tìm theo tên ..
    $('body').on('change', '.cbTransfersType', function () {
        var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val()
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + $(this).val() + '/' + $('.cbStaffFilterId').val());
    });
    // theo ngay tháng ...
    $('body').on('change', '.cbDayFilter', function () {
        var dateFilter = $(this).val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + $('.cbTransfersType').val() + '/' + $('.cbStaffFilterId').val());
    });
    $('body').on('change', '.cbMonthFilter', function () {
        var dateFilter = $('.cbDayFilter').val() + '/' + $(this).val() + '/' + $('.cbYearFilter').val()
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + $('.cbTransfersType').val() + '/' + $('.cbStaffFilterId').val());
    });
    $('body').on('change', '.cbYearFilter', function () {
        var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $(this).val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + $('.cbTransfersType').val() + '/' + $('.cbStaffFilterId').val());
    });
    // theo nhan vien
    $('body').on('change', '.cbStaffFilterId', function () {
        var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + $('.cbTransfersType').val() + '/' + $(this).val());
    });
});


//-------------------- xác nhận ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_ad3d_transfer_confirm_receive_act', function () {
        qc_ad3d_finance_transfers_receive.confirm.get($(this).data('href'));
    });

    $('body').on('click', '.frmAd3dTransferConfirmReceive .qc_save', function () {
        qc_ad3d_finance_transfers_receive.confirm.save($(this).parents('.frmAd3dTransferConfirmReceive'));
    });
});
