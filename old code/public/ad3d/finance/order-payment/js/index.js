/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_order_order_payment = {
    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
        qc_main.scrollTop();
    },
    add: {
        addProduct: function (href) {
            qc_ad3d_submit.ajaxNotReload(href, '#qc_order_add_product_wrap', false);
        },
        save: function (formObject) {
            var cbOrder = $(formObject).find("select[name='cbOrder']");
            var txtMoneyPay = $(formObject).find("input[name='txtMoneyPay']");
            var txtDatePay = $(formObject).find("input[name='txtDatePay']");
            var totalUnpaid = parseInt($(formObject).find(".txtTotalUnpaid").data('unpaid'));

            var txtMoneyPayVal = qc_main.getNumberInput(txtMoneyPay.val());
            if (qc_main.check.inputNull(cbOrder, 'Chọn đơn hàng')) {
                cbOrder.focus();
                return false;
            }

            if (qc_main.check.inputNull(txtMoneyPay, 'Nhập tiền thanh toán')) {
                txtMoneyPay.focus();
                return false;
            } else {
                if(txtMoneyPayVal > totalUnpaid){
                    alert('Số tiền thanh toán không quá số tiền còn lại');
                    txtMoneyPay.focus();
                    return false;
                }
            }
            if (qc_main.check.inputNull(txtDatePay, 'Nhập ngày thanh toán')) {
                txtDatePay.focus();
                return false;
            }else{
                if (confirm('Thanh toán đơn hàng này ?')) {
                    //.ajaxFormHasReload(formObject, '', false);
                    //qc_main.scrollTop();
                }
            }

        },
    },
    delete: function (listObject) {
        if (confirm('Bạn muốn hủy thanh toán này này?')) {
            qc_ad3d_submit.ajaxHasReload($(listObject).parents('.qc_ad3d_list_content').data('href-del') + '/' + $(listObject).data('object'), '', false);
        }
    }
}
$(document).ready(function () {
    //khi chọn công ty...
    $('body').on('keypress', '.txtPrice', function () {
        //qc_ad3d_order_order_payment.productPriceAdd();
    })
});
//-------------------- filter ------------
$(document).ready(function () {
    //khi chọn công ty...
    var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val();
    $('body').on('change', '.cbCompanyFilter', function () {
        var orderName = $('.textOrderFilterName').val();
        if (orderName.length == 0) orderName = null ;
        var companyId = $(this).val();
        var href = $(this).data('href-filter');
        if (companyId != '') {
            href = href + '/' + companyId + '/' + dateFilter + '/' + orderName + '/' + $('.cbStaffFilterId').val() + '/' + $('.cbTransferStatus').val();
        }
        qc_main.url_replace(href);
    })

    //khi tìm theo tên ..
    $('body').on('click', '.btOrderFilterName', function () {
        var orderName = $('.textOrderFilterName').val();
        if (orderName.length == 0) {
            alert('Bạn phải nhập thông tin tìm kiếm');
            $('.textOrderFilterName').focus();
            return false;
        } else {
            qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + orderName + '/' + $('.cbStaffFilterId').val() + '/' + $('.cbTransferStatus').val());
        }
    });

    //loc theo ten nv vien nhan
    $('body').on('change', '.cbStaffFilterId', function () {
        var orderName = $('.textOrderFilterName').val();
        if (orderName.length == 0) orderName = null ;
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + orderName + '/' + $(this).val() + '/' + $('.cbTransferStatus').val());
    });

    //loc trang thai ban gia
    $('body').on('change', '.cbTransferStatus', function () {
        var orderName = $('.textOrderFilterName').val();
        if (orderName.length == 0) orderName = null ;
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + orderName + '/' + $('.cbStaffFilterId').val() + '/' + $(this).val());
    });

    // loc theo ngay tháng ...
    $('body').on('change', '.cbDayFilter', function () {
        var orderName = $('.textOrderFilterName').val();
        if (orderName.length == 0) orderName = null ;
        var dateFilter = $(this).val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + orderName  + '/' + $('.cbStaffFilterId').val() + '/' + $('.cbTransferStatus').val());
    });

    $('body').on('change', '.cbMonthFilter', function () {
        var orderName = $('.textOrderFilterName').val();
        if (orderName.length == 0)  orderName = null ;
        var dateFilter = $('.cbDayFilter').val() + '/' + $(this).val() + '/' + $('.cbYearFilter').val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + orderName + '/' + $('.cbStaffFilterId').val() + '/' + $('.cbTransferStatus').val());
    });

    $('body').on('change', '.cbYearFilter', function () {
        var orderName = $('.textOrderFilterName').val();
        if (orderName.length == 0) orderName = null ;
        var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $(this).val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + orderName + '/' + $('.cbStaffFilterId').val() + '/' + $('.cbTransferStatus').val());
    });

    /*$('body').on('change', '.cbPaymentStatus', function () {
        var orderName = $('.textOrderFilterName').val();
        var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val() + '/' + $(this).val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + orderName + '/' + staffName);
    })*/
});


//view
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_view', function () {
        qc_ad3d_order_order_payment.view($(this).parents('.qc_ad3d_list_object'));
    })
});

$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_delete', function () {
        qc_ad3d_order_order_payment.delete($(this).parents('.qc_ad3d_list_object'));
    })
});


//-------------------- add ------------
$(document).ready(function () {
    //add product
    $('#frmAd3dOrderPayAdd').on('change', '.cbOrder', function () {
        qc_main.url_replace($(this).data('href') + '/' + $(this).val());
    })

    //save
    $('#frmAd3dOrderPayAdd').on('click', '.qc_save', function () {
        qc_ad3d_order_order_payment.add.save($(this).parents('#frmAd3dOrderPayAdd'));
    })
});
