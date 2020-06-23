/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_finance_keep_money = {
    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
        qc_main.scrollTop();
    },
    /* confirm: {
     get: function (href) {
     var contain = qc_ad3d.bodyIdName();
     qc_ad3d_submit.ajaxNotReload(href, $('#' + contain), false);
     },
     save: function (frm) {
     if(confirm('Tôi đã nhận được tiền bàn giao?')){
     qc_ad3d_submit.ajaxFormHasReload(frm);
     }
     }
     }*/
}

//-------------------- lọc thông tin ------------
$(document).ready(function () {
    //khi chọn công ty...
    $('body').on('change', '.cbCompanyFilter', function () {
        var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val();
        qc_main.url_replace($(this).data('href-filter') + '/' + $(this).val() + '/' + dateFilter + '/' + $('.cbStaffFilterId').val() + '/' + $('.cbPayStatus').val());
    });
    //khi tìm theo tên ..
    $('body').on('change', '.cbStaffFilterId', function () {
        var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + $(this).val() + '/' + $('.cbPayStatus').val());
    });

    //khi tìm theo tên ..
    $('body').on('change', '.cbPayStatus', function () {
        var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + $('.cbStaffFilterId').val() + '/' + $(this).val());
    });
    // theo ngay tháng ...
    $('body').on('change', '.cbDayFilter', function () {
        var dateFilter = $(this).val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + $('.cbStaffFilterId').val() + '/' + $('.cbPayStatus').val());
    });
    $('body').on('change', '.cbMonthFilter', function () {
        var dateFilter = $('.cbDayFilter').val() + '/' + $(this).val() + '/' + $('.cbYearFilter').val()
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + $('.cbStaffFilterId').val() + '/' + $('.cbPayStatus').val());
    });
    $('body').on('change', '.cbYearFilter', function () {
        var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $(this).val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + $('.cbStaffFilterId').val() + '/' + $('.cbPayStatus').val());
    });
});
//view
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_view', function () {
        qc_ad3d_finance_keep_money.view($(this).parents('.qc_ad3d_list_object'));
    })
});


