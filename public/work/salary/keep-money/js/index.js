/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_salary_keep_money = {
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
    //khi tìm theo tên ..
    $('body').on('change', '.cbPayStatus', function () {
        var dateFilter = $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val();
        qc_main.url_replace($(this).data('href') + '/' + dateFilter + '/' + $(this).val());
    });

    $('body').on('change', '.cbMonthFilter', function () {
        var dateFilter = $(this).val() + '/' + $('.cbYearFilter').val()
        qc_main.url_replace($(this).data('href') + '/' + dateFilter + '/' + $('.cbPayStatus').val());
    });
    $('body').on('change', '.cbYearFilter', function () {
        var dateFilter = $('.cbMonthFilter').val() + '/' + $(this).val();
        qc_main.url_replace($(this).data('href') + '/' + dateFilter + '/' + $('.cbPayStatus').val());
    });
});



