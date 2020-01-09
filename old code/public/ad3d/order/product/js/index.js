/**
 * Created by HUY on 12/29/2017.
 */
var qc_ad3d_order_product = {
    view: function (listObject) {
        qc_ad3d_submit.ajaxNotReload($(listObject).parents('.qc_ad3d_list_content').data('href-view') + '/' + $(listObject).data('object'), $('#' + qc_ad3d.bodyIdName()), false);
        qc_main.scrollTop();
    },
    workAllocation: {
        addStaff: function (href) {
            qc_ad3d_submit.ajaxNotReload(href, '#qc_product_work_allocation_staff_wrap', false);
        },
        save: function (form) {
            if ($(form).find('.qc_ad3d_product_work_allocation_staff_add').length > 0) {
                //qc_ad3d_submit.ajaxFormHasReload(form, '', false);
                //qc_main.scrollTop();
                //qc_ad3d_submit.normalForm(form);
                //qc_ad3d_order_order.add.checkProductInput();
                qc_ad3d_order_product.workAllocation.checkSubmit(form);
            } else {
                alert('Chọn nhân viên');
                return false;
            }
        },
        checkSubmit: function (form) {
            $('#frmAd3dAdd .qc_ad3d_product_work_allocation_staff_add').filter(function () {
                if ($(this).is(':last-child')) {
                    if (qc_ad3d_order_product.workAllocation.checkInfo(this)) {
                        //qc_ad3d_submit.ajaxFormNotReload(form,'',false);
                        qc_ad3d_submit.normalForm(form);
                    }
                } else {
                    if(!qc_ad3d_order_product.workAllocation.checkInfo(this)){
                        return false;
                    }
                }
            });
        },
        checkInfo: function (object) {
            var cbStaff = $(object).find('.cbReceiveStaff');
            var cbDayAllocation = $(object).find('.cbDayAllocation');
            var cbMonthAllocation = $(object).find('.cbMonthAllocation');
            var cbYearAllocation = $(object).find('.cbYearAllocation');
            var cbHoursAllocation = $(object).find('.cbHoursAllocation');
            var cbMinuteAllocation = $(object).find('.cbMinuteAllocation');
            var cbDayDeadline = $(object).find('.cbDayDeadline');
            var cbMonthDeadline = $(object).find('.cbMonthDeadline');
            var cbYearDeadline = $(object).find('.cbYearDeadline');
            var cbHoursDeadline = $(object).find('.cbHoursDeadline');
            var cbMinuteDeadline = $(object).find('.cbMinuteDeadline');
            if (cbStaff.val() != '' || cbDayAllocation.val() != '' || cbMonthAllocation.val() != '' || cbHoursAllocation.val() != '' || cbDayAllocation.val() != '' || cbMonthDeadline.val() != '' || cbHoursDeadline.val() != '') {
                if (qc_main.check.inputNull(cbStaff, 'Chọn nhân viên')) {
                    cbStaff.focus();
                    return false;
                }
                if (qc_main.check.inputNull(cbDayAllocation, 'Chọn ngày nhận')) {
                    cbDayAllocation.focus();
                    return false;
                }
                if (qc_main.check.inputNull(cbMonthAllocation, 'Chọn tháng nhận')) {
                    cbMonthAllocation.focus();
                    return false;
                }
                if (qc_main.check.inputNull(cbHoursAllocation, 'Chọn giờ nhận')) {
                    cbHoursAllocation.focus();
                    return false;
                }
                if (qc_main.check.inputNull(cbDayDeadline, 'Chọn ngày hết hạn')) {
                    cbDayDeadline.focus();
                    return false;
                }
                if (qc_main.check.inputNull(cbMonthDeadline, 'Chọn tháng hết hạn')) {
                    cbMonthDeadline.focus();
                    return false;
                }
                if (qc_main.check.inputNull(cbHoursDeadline, 'Chọn giờ nhận')) {
                    cbHoursDeadline.focus();
                    return false;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        }
    },
    add: {
        addProduct: function (href) {
            qc_ad3d_submit.ajaxNotReload(href, '#qc_order_add_product_wrap', false);
        }
    },
    confirm: {
        get: function (listObject) {
            var href = $(listObject).parents('.qc_ad3d_list_content').data('href-confirm') + '/' + $(listObject).data('object');
            var contain = qc_ad3d.bodyIdName();
            qc_ad3d_submit.ajaxNotReload(href, $('#' + contain), false);
        },
        save: function (formObject) {
            var cbDay = $(formObject).find("select[name='cbDay']");
            var cbMonth = $(formObject).find("select[name='cbMonth']");
            var cbYear = $(formObject).find("select[name='cbYear']");
            var containNotify = $(formObject).find('.frm_notify');
            if (qc_main.check.inputNull(cbDay, 'Chọn ngày')) {
                cbDay.focus()
                return false;
            }
            if (qc_main.check.inputNull(cbMonth, 'Chọn tháng')) {
                cbMonth.focus();
                return false;
            }
            if (qc_main.check.inputNull(cbYear, 'Chọn Năm')) {
                cbYear.focus();
                return false;
            } else {
                qc_ad3d_submit.ajaxFormHasReload(formObject, containNotify, true);
                $('#qc_container_content').animate({
                    scrollTop: 0
                })
            }

        }
    },
    delete: function (listObject) {
        if (confirm('Nếu hủy đơn hàng, sẻ không phục hồi lại được. Hủy?')) {
            qc_ad3d_submit.ajaxHasReload($(listObject).parents('.qc_ad3d_list_content').data('href-del') + '/' + $(listObject).data('object'), '', false);
        }
    }
}

$(document).ready(function () {
    //khi chọn công ty...
    var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val() + '/' + $('.cbFinishStatus').val();
    $('body').on('change', '.cbCompanyFilter', function () {
        var companyId = $(this).val();
        var href = $(this).data('href-filter');
        if (companyId != '') {
            href = href + '/' + companyId + '/' + dateFilter;
        }
        qc_main.url_replace(href);
    })

    //khi tìm theo tên ..
    $('body').on('click', '.btFilterAction', function () {
        var name = $('.textKeywordFilter').val();
        if (name.length == 0) {
            alert('Bạn phải nhập thông tin tìm kiếm');
            $('.textFilterName').focus();
            return false;
        } else {
            qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + name);
        }
    })
    // theo ngay tháng ...
    $('body').on('change', '.cbDayFilter', function () {
        var name = $('.textKeywordFilter').val();
        var dateFilter = $(this).val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val() + '/' + $('.cbFinishStatus').val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + name);
    })
    $('body').on('change', '.cbMonthFilter', function () {
        var name = $('.textKeywordFilter').val();
        var dateFilter = $('.cbDayFilter').val() + '/' + $(this).val() + '/' + $('.cbYearFilter').val() + '/' + $('.cbFinishStatus').val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + name);
    })
    $('body').on('change', '.cbYearFilter', function () {
        var name = $('.textKeywordFilter').val();
        var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $(this).val() + '/' + $('.cbFinishStatus').val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + name);
    })
    $('body').on('change', '.cbFinishStatus', function () {
        var name = $('.textKeywordFilter').val();
        var dateFilter = $('.cbDayFilter').val() + '/' + $('.cbMonthFilter').val() + '/' + $('.cbYearFilter').val() + '/' + $(this).val();
        qc_main.url_replace($(this).data('href') + '/' + $('.cbCompanyFilter').val() + '/' + dateFilter + '/' + name);
    })
});
//xem chi tiet
$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_view', function () {
        qc_ad3d_order_product.view($(this).parents('.qc_ad3d_list_object'));
    })
});

$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_confirm', function () {
        qc_ad3d_order_product.confirm.get($(this).parents('.qc_ad3d_list_object'));
    })

    $('body').on('click', '.frmProductConfirm .qc_save', function () {
        qc_ad3d_order_product.confirm.save($(this).parents('.frmProductConfirm'));
    })
});

$(document).ready(function () {
    $('.qc_ad3d_list_object').on('click', '.qc_delete', function () {
        qc_ad3d_order_product.delete($(this).parents('.qc_ad3d_list_object'));
    })
});
//phan viec
$(document).ready(function () {
    $('#frmAd3dAdd').on('click', '.qc_product_work_allocation_staff_add', function () {
        qc_ad3d_order_product.workAllocation.addStaff($(this).data('href'));
    });
    //xoa nhan vien
    $('body').on('click', '.qc_ad3d_product_work_allocation_staff_add .qc_delete', function () {
        $(this).parents('.qc_ad3d_product_work_allocation_staff_add').remove();
    });
    //giao viec
    $('#frmAd3dAdd').on('click', '.qc_save', function () {
        qc_ad3d_order_product.workAllocation.save($(this).parents('#frmAd3dAdd'));
    });
});
//them san pham
$(document).ready(function () {
    //them san pham
    $('#frmAd3dAdd').on('click', '.qc_save', function () {
        qc_ad3d_order_product.add.addProduct($(this).data('href'));
    })
    //xoa san pham
    $('body').on('click', '.qc_ad3d_order_product_object .qc_delete', function () {
        $(this).parents('.qc_ad3d_order_product_object').remove();
    })
})