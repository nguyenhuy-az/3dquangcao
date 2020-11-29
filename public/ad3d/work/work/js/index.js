/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_work = {
    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
        qc_main.scrollTop();
    },
    add: {
        save: function (formObject) {
            alert('Phát triển sau');
            return false;
        }
    },
    end: {
        get: function (listObject) {
            var href = $(listObject).parents('.qc_ad3d_list_content').data('href-end') + '/' + $(listObject).data('object');
            qc_ad3d_submit.ajaxNotReload(href, $('#' + qc_ad3d.bodyIdName()), false);
        },
        save: function (formObject) {
            if (confirm(' Bạn muốn tính lương cho nhân viên này?')) {
                var containNotify = $(formObject).find('.frm_notify');
                qc_ad3d_submit.ajaxFormHasReload(formObject, containNotify, true);
                $('#qc_container_content').animate({
                    scrollTop: 0
                })
            }
        }
    }
}

//-------------------- filter ------------
$(document).ready(function () {
    //khi chọn công ty...
    $('body').on('change', '.cbCompanyFilter', function () {
        var companyId = $(this).val();
        var dateFilter = $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val();
        var href = $(this).data('href-filter');
        if (companyId != '') {
            href = href + '/' + companyId + '/' + dateFilter;
        }
        qc_main.url_replace(href);
    })

    //khi tìm theo tên ..
    $('body').on('change', '.cbStaffFilter', function () {
        var dateFilter = $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val() + '/' + $(this).val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter);
    })
    // theo ngay tháng ...
    $('body').on('change', '.cbMonthFilter', function () {
        var dateFilter = $(this).val() + '/' + $('.cbYearFilter').val()+ '/' + $('.cbStaffFilter').val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter );
    })
    $('body').on('change', '.cbYearFilter', function () {
        var dateFilter = $('.cbMonthFilter').val() + '/' + $(this).val() + '/' + $('.cbStaffFilter').val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter);
    })
});

//view
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_view', function () {
        qc_ad3d_work.view($(this).parents('.qc_ad3d_list_object'));
    })
});


$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_end', function () {
        qc_ad3d_work.end.get($(this).parents('.qc_ad3d_list_object'));
    })
});

//----------- Payment ------- -----
$(document).ready(function () {
    //add benefit
    $('body').on('keyup', '.frmWordEnd .txtBenefit', function () {
        var benefit = parseInt($(this).val());
        var totalPay = parseInt($('.qcPay').data('salary'));
        $('.txtTotalPay').val(benefit + totalPay);
    })
    //save
    $('body').on('click', '.frmWordEnd .save', function () {
        qc_ad3d_work.end.save($(this).parents('.frmWordEnd'));
    })
});

