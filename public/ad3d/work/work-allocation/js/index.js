/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_work_allocation = {
    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
        qc_main.scrollTop();
    },
    minusMoney: {
        getMinus: function (href) {
            qc_ad3d_submit.ajaxNotReload(href, $('#' + qc_ad3d.bodyIdName()), false);
            qc_main.scrollTop();
        },
        save: function (frm) {
            var txtMoney = $(frm).find("input[name='txtMoney']");
            var txtNote = $(frm).find("input[name='txtNote']");
            var frm_notify = $(frm).find("input[name='frm_notify']");
            if (qc_main.check.inputNull(txtMoney, 'Nhập số tiền phạt')) {
                txtMoney.focus();
                return false;
            } else {
                //alert(txtMoney);
                var money = qc_main.getNumberInput(txtMoney.val());
                if (money < 50000) {
                    alert('Tiền phạt phải lớn hơn 0');
                    txtMoney.focus();
                    return false;
                }
            }
            if (qc_main.check.inputNull(txtNote, 'Phải nhập lý do phạt')) {
                return false;
            }
            if (confirm('Thông tin phạt sẽ không được thay đổi, Đồng ý thông tin phạt này?')) {
                qc_ad3d_submit.ajaxFormHasReload(frm, frm_notify, false);
                qc_main.scrollTop();
            }
        }
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
    });
});
//---------- ----------- ap dung  phat ---------- ------------
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_minus_money_get', function () {
        qc_ad3d_work_allocation.minusMoney.getMinus($(this).data('href'));
    });
    $('body').on('click', '#qcFrmMinusMoneyForSupplies .qc_save', function () {
        qc_ad3d_work_allocation.minusMoney.save($(this).parents('#qcFrmMinusMoneyForSupplies'));
    });
});

