/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_order_allocation = {
    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
        qc_main.scrollTop();
    },
    confirm: {
        getForm: function (listObject) {
            qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-confirm-finish') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
            qc_main.scrollTop();
        },
        save: function (frm) {
            if (confirm('Bạn Đồng ý với xác nhận này?')) {
                var containNotify = $(frm).find('.frm_notify');
                qc_ad3d_submit.ajaxFormHasReload(frm, containNotify, true);
            }
        }
    },

    cancel: function (href) {
        if (confirm('Bạn muốn hủy bàn giao công trình này?')) {
            qc_ad3d_submit.ajaxHasReload(href);
        }
    }
}

//-------------------- filter ------------
$(document).ready(function () {
    //khi chọn công ty...
    var dateFilter =
    $('body').on('change', '.cbCompanyFilter', function () {
        var companyId = $(this).val();
        var href = $(this).data('href-filter');
        if (companyId != '') {
            href = href + '/' + companyId + '/' + $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val() + '/' + $('.cbFinishStatus').val();
        }
        qc_main.url_replace(href);
    })

    /*//khi tìm theo tên ..
    $('body').on('click', '.btFilterName', function () {
        var name = $('.textFilterName').val();
        if (name.length == 0) {
            alert('Bạn phải nhập thông tin tìm kiếm');
            $('.textFilterName').focus();
            return false;
        } else {
            qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + name);
        }
    })*/
    // theo ngay tháng ...
    $('body').on('change', '.cbDayFilter', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + $(this).val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val() + '/' + $('.cbFinishStatus').val());
    })
    $('body').on('change', '.cbMonthFilter', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + $('.cbDayFilter').val() + '/' + $(this).val() + '/' + $('.cbYearFilter').val() + '/' + $('.cbFinishStatus').val());
    })
    $('body').on('change', '.cbYearFilter', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $(this).val() + '/' + $('.cbFinishStatus').val());
    })
    $('body').on('change', '.cbFinishStatus', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val() + '/' + $(this).val());
    })
});

/*xac nhan don hang*/
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_confirm_finish', function () {
        qc_ad3d_order_allocation.confirm.getForm($(this).parents('.qc_ad3d_list_object'));
    })

    $('body').on('click', '.frmAd3dOrderAllocationConfirm .qc_save', function () {
        qc_ad3d_order_allocation.confirm.save($(this).parents('.frmAd3dOrderAllocationConfirm'));
    })
});

//huy ban giao cong trinh
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_cancel', function () {
        qc_ad3d_order_allocation.cancel($(this).data('href'));
    })
});

$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_delete', function () {
        //qc_ad3d_order_order.delete($(this).parents('.qc_ad3d_list_object'));
    })
});


//-------------------- add ------------
$(document).ready(function () {
    //add product
    $('#frmAd3dOrderAdd').on('click', '.qc_order_product_add', function () {
        //qc_ad3d_order_order.add.addProduct($(this).data('href'));
    })
    //delete product
    $('body').on('click', '.qc_ad3d_order_product_add .qc_delete', function () {
        //$(this).parents('.qc_ad3d_order_product_add').remove();
    })

    //save
    $('#frmAd3dOrderAdd').on('click', '.qc_save', function () {
        //qc_ad3d_order_order.add.save($(this).parents('#frmAd3dOrderAdd'));
    })
});
