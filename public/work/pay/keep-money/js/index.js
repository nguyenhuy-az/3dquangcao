/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_pay_keep_money = {
    pay: {
        get: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        },
        save: function (form) {
            var notifyContent = $(form).find('.frm_notify');
            if (confirm('Tôi đồng ý thanh toán số tiền này')) {
                //qc_master_submit.ajaxFormHasReload(form, notifyContent, true);
                //qc_main.scrollTop();
            }

        },
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
    //khi tìm theo tên ..
    $('body').on('change', '.cbStaffFilterId', function () {
        var dateFilter = $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val();
        qc_main.url_replace($(this).data('href') + '/' + dateFilter + '/' + $(this).val() + '/' + $('.cbPayStatus').val());
    });

    //khi tìm theo tên ..
    $('body').on('change', '.cbPayStatus', function () {
        var dateFilter = $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val();
        qc_main.url_replace($(this).data('href') + '/' + dateFilter + '/' + $('.cbStaffFilterId').val() + '/' + $(this).val());
    });

    $('body').on('change', '.cbMonthFilter', function () {
        var dateFilter = $(this).val() + '/' + $('.cbYearFilter').val()
        qc_main.url_replace($(this).data('href') + '/' + dateFilter + '/' + $('.cbStaffFilterId').val() + '/' + $('.cbPayStatus').val());
    });
    $('body').on('change', '.cbYearFilter', function () {
        var dateFilter = $('.cbMonthFilter').val() + '/' + $(this).val();
        qc_main.url_replace($(this).data('href') + '/' + dateFilter + '/' + $('.cbStaffFilterId').val() + '/' + $('.cbPayStatus').val());
    });
});

// thanh toan
$(document).ready(function () {
    $('body').on('click', '#frm_work_pay_keep_money .qc_save', function () {
        qc_work_pay_keep_money.pay.save('#frm_work_pay_keep_money');
    });
});


