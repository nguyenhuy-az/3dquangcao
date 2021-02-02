/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_finance_bonus = {
    cancel: {
        get: function (href) {
            qc_ad3d_submit.ajaxNotReload(href, $('#' + qc_ad3d.bodyIdName()), false);
        },
        save: function (frm) {
            var notifyContent = $(frm).find('.qc_notify');
            if (confirm('Khi Hủy Thưởng sẽ KHÔNG THỂ PHỤC HỒI , đồng ý hủy')) {
                qc_ad3d_submit.ajaxFormHasReload(frm, notifyContent, true);
                qc_main.scrollTop();
            }
        }

    }

}

//-------------------- filter ------------
$(document).ready(function () {
    //khi chọn công ty...
    $('body').on('change', '.cbCompanyFilter', function () {
        var companyId = $(this).val();
        var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val();
        var href = $(this).data('href-filter');
        if (companyId != '') {
            href = href + '/' + companyId + '/' + dateFilter;
        }
        qc_main.url_replace(href);
    });

    //khi tìm theo tên ..
    $('body').on('change', '.cbStaffFilter', function () {
        var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + $(this).val());
    });
    // theo ngay tháng ...
    $('body').on('change', '.cbDayFilter', function () {
        var dateFilter = $(this).val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + $('.cbStaffFilter').val());
    });
    $('body').on('change', '.cbMonthFilter', function () {
        var dateFilter = $('.cbDayFilter').val() + '/' + $(this).val() + '/' + $('.cbYearFilter').val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + $('.cbStaffFilter').val());
    });
    $('body').on('change', '.cbYearFilter', function () {
        var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $(this).val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + $('.cbStaffFilter').val());
    });
});

//cancel
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_cancel_act', function () {
        qc_ad3d_finance_bonus.cancel.get($(this).data('href'));
    });
    $('body').on('click', '.qc_frm_bonus_cancel .qc_save', function () {
        qc_ad3d_finance_bonus.cancel.save($(this).parents('.qc_frm_bonus_cancel'));
    });
});
