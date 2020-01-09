/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_work_allocation = {
    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
        qc_main.scrollTop();
    },
    cancel: function (href) {
        if (confirm('Bạn muốn hủy phân việc này')) {
            qc_ad3d_submit.ajaxHasReload(href, '', false);
        }
    }
}

//-------------------- filter ------------
$(document).ready(function () {
    var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val() + '/' + $('.cbPaymentStatus').val();
    //khi tìm theo tên ..
    $('body').on('click', '.btFilterName', function () {
        var name = $('.textFilterName').val();
        if (name.length == 0) {
            alert('Bạn phải nhập thông tin tìm kiếm');
            $('.textFilterName').focus();
            return false;
        } else {
            qc_main.url_replace($(this).data('href') + '/' + dateFilter + '/' + name);
        }
    })
    // theo ngay tháng ...
    $('body').on('change', '.cbDayFilter', function () {
        var name = $('.textFilterName').val();
        var dateFilter = $(this).val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val() + '/' + $('.cbPaymentStatus').val();
        qc_main.url_replace($(this).data('href') + '/' + dateFilter + '/' + name);
    })
    $('body').on('change', '.cbMonthFilter', function () {
        var name = $('.textFilterName').val();
        var dateFilter = $('.cbDayFilter').val() + '/' + $(this).val() + '/' + $('.cbYearFilter').val() + '/' + $('.cbPaymentStatus').val();
        qc_main.url_replace($(this).data('href') + '/' + dateFilter + '/' + name);
    })
    $('body').on('change', '.cbYearFilter', function () {
        var name = $('.textFilterName').val();
        var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $(this).val() + '/' + $('.cbPaymentStatus').val();
        qc_main.url_replace($(this).data('href') + '/' + dateFilter + '/' + name);
    })
    $('body').on('change', '.cbPaymentStatus', function () {
        var name = $('.textFilterName').val();
        var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val() + '/' + $(this).val();
        qc_main.url_replace($(this).data('href') + '/' + dateFilter + '/' + name);
    })
});

//xem thong tin phan viec
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_view', function () {
        qc_ad3d_work_allocation.view($(this).parents('.qc_ad3d_list_object'));
    });
});

$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_cancel', function () {
        qc_ad3d_work_allocation.cancel($(this).data('href'));
    })
});

