/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_work_time_keeping = {
    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view'), $('#' + qc_ad3d.bodyIdName()), false);
        qc_main.scrollTop();
    },
    viewImage: function (href) {
        qc_ad3d_submit.ajaxNotReload(href, $('#' + qc_ad3d.bodyIdName()), false);
        qc_main.scrollTop();
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
    })

    //khi tìm theo tên ..
    $('body').on('change', '.cbStaffFilter', function () {
        var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val() + '/' + $(this).val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter);
    })
    // theo ngay tháng ...
    $('body').on('change', '.cbDayFilter', function () {
        var dateFilter = $(this).val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val()+ '/' + $('.cbStaffFilter').val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter);
    })
    $('body').on('change', '.cbMonthFilter', function () {
        var dateFilter = $('.cbDayFilter').val() + '/' + $(this).val() + '/' + $('.cbYearFilter').val()+ '/' + $('.cbStaffFilter').val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter);
    })
    $('body').on('change', '.cbYearFilter', function () {
        var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $(this).val()+ '/' + $('.cbStaffFilter').val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter);
    })
});


//view
 $(document).ready(function () {
     $('.qc_ad3d_list_object').on('click', '.qc_ad3d_timekeeping_image_view', function () {
        qc_ad3d_work_time_keeping.viewImage($(this).data('href'));
     })
 });
