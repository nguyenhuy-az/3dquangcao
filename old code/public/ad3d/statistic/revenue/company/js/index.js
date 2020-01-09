/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_statistic_revenue_company = {
    view: function (href) {
        qc_ad3d_submit.ajaxNotReload(href, $('#' + qc_ad3d.bodyIdName()), false);
        qc_main.scrollTop();
    }
}
//-------------------- filter ------------
$(document).ready(function () {
    //khi chọn công ty...
    var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val();
    $('body').on('change', '.cbCompanyFilter', function () {
        var companyId = $(this).val();
        var href = $(this).data('href-filter');
        if (companyId != '') {
            href = href + '/' + companyId + '/' + dateFilter;
        }
        qc_main.url_replace(href);
    })
    // theo ngay tháng ...
    $('body').on('change', '.cbDayFilter', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + $(this).val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val());
    })
    $('body').on('change', '.cbMonthFilter', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + $('.cbDayFilter').val() + '/' + $(this).val() + '/' + $('.cbYearFilter').val());
    })
    $('body').on('change', '.cbYearFilter', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $(this).val());
    })
});

$(document).ready(function () {
    $('.qc_ad3d_statistic_revenue_company_detail').on('click', '.qc_detail_view', function () {
        qc_ad3d_statistic_revenue_company.view($(this).data('href'));
    })
});

