/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_finance_payment = {
    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
        qc_main.scrollTop();
    },
    payment: {
        get: function (listObject) {
            qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-add') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
            qc_main.scrollTop();
        },
        save: function (frm) {
            var txtMoney = $(frm).find("input[name='txtMoney']");
            var valCheck = txtMoney.data('pay-limit');
            var txtMoneyPayVal = qc_main.getNumberInput(txtMoney.val());
            if (qc_main.check.inputNull(txtMoney, 'Nhập số tiền thanh toán')) {
                return false;
            } else {
                if(txtMoneyPayVal > valCheck){
                    alert('Số tiền thanh toán không quá số tiền còn lại');
                    txtMoney.focus();
                    return false;
                }else{
                    if (confirm('Bạn muốn thanh toán lương này?')) {
                        var containNotify = $(frm).find('.frm_notify');
                        qc_ad3d_submit.ajaxFormHasReload(frm, containNotify, true);
                        $('#qc_container_content').animate({
                            scrollTop: 0
                        })
                    }
                }
            }
        }
    },
    edit: {
        get: function (listObject) {
            var href = $(listObject).parents('.qc_ad3d_list_content').data('href-edit');
            var contain = qc_ad3d.bodyIdName();
            qc_ad3d_submit.ajaxNotReload(href, $('#' + contain), false);
        },
        post: function () {

        }
    },
    delete: function (orderObject) {
        if (confirm('Bạn muốn xóa đơn hàng này')) {
            alert('Chưa phát triển tính năng này');
        }
        ;
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
    $('body').on('click', '.btFilterName', function () {
        var name = $('.textFilterName').val();
        var dateFilter = $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val()
        if (name.length == 0) {
            alert('Bạn phải nhập thông tin tìm kiếm');
            $('.textFilterName').focus();
            return false;
        } else {
            qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + name);
        }
    })
    // theo ngay tháng ...
    $('body').on('change', '.cbMonthFilter', function () {
        var name = $('.textFilterName').val();
        var dateFilter = $(this).val() + '/' + $('.cbYearFilter').val()
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + name);
    })
    $('body').on('change', '.cbYearFilter', function () {
        var name = $('.textFilterName').val();
        var dateFilter = $('.cbMonthFilter').val() + '/' + $(this).val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + name);
    })
});
//view
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_view', function () {
        qc_ad3d_finance_payment.view($(this).parents('.qc_ad3d_list_object'));
    })
});


$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_delete', function () {
        qc_ad3d_finance_payment.delete($(this).parents('.qc_ad3d_list_object'));
    })
});

$(document).ready(function () {
    //thanh toan
    $('.qc_ad3d_list_object').on('click', '.qc_add', function () {
        qc_ad3d_finance_payment.payment.get($(this).parents('.qc_ad3d_list_object'));
    })

    $('body').on('click', '.frm_salary_pay .qc_save', function () {
        qc_ad3d_finance_payment.payment.save($(this).parents('.frm_salary_pay'));
    })
});
