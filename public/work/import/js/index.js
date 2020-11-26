/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_import = {
    filter: function (href) {
        qc_main.url_replace(href);
    },
    add: {
        addImage: function (href) {
            qc_master_submit.ajaxNotReloadNoScrollTop(href, '#qc_work_import_add_image_wrap', false);
        },
        addObject: function (href) {
            qc_master_submit.ajaxNotReloadNoScrollTop(href, '#qc_work_import_add_object_wrap', false);
        },
        save: function (frm) {
            var img = $(frm).find('.txtImportImage');
            if (qc_main.check.inputNull(img, 'PHẢI CÓ ẢNH HÓA ĐƠN')) {
                img.focus();
                return false;
            } else {
                qc_work_import.add.checkInput();
            }
            //qc_master_submit.normalForm(form);
        },
        checkInput: function () {
            $('#frm_work_import_add .qc_work_import_object_add').filter(function () {
                // neu la phan tu sau cung
                if ($(this).is(':last-child')) {
                    var txtImportName = $(this).find('.txtImportName');
                    var txtObjectAmount = $(this).find('.txtObjectAmount');
                    var txtObjectUnit = $(this).find('.txtObjectUnit');
                    var txtObjectMoney = $(this).find('.txtObjectMoney');
                    if (txtImportName.val() != '' || txtObjectAmount.val() != '' || txtObjectUnit.val() != '' || txtObjectMoney.val() != '') {
                        if (qc_main.check.inputNull(txtImportName, 'Nhập tên dụng cụ/vật tư')) {
                            txtImportName.focus();
                            return false;
                        } else {
                            if (txtImportName.val().length > 50) {
                                alert('Tên không dài quá 50 ký tự');
                                txtImportName.focus();
                                return false;
                            }
                        }
                        if (txtObjectAmount.val() == 0 || txtObjectAmount.val() == '') {
                            alert('Nhập số lượng');
                            txtObjectAmount.focus();
                            return false;
                        }

                        if (txtObjectUnit.val() == 0 || txtObjectUnit.val() == '') {
                            alert('Nhập đơn vị');
                            txtObjectUnit.focus();
                            return false;
                        }
                        if (txtObjectMoney.val() == 0 || txtObjectMoney.val() == '') {
                            alert('Nhập giá');
                            txtObjectMoney.focus();
                            return false;
                        } else {
                            if (confirm('Thông tin đã đúng và ý tạo hóa đơn này ?')) {
                                qc_master_submit.normalForm('#frm_work_import_add');
                                qc_main.scrollTop();
                            }
                        }
                    } else {
                        alert('Nhập thông tin mua');
                        return false;
                    }
                } else {
                    var txtImportName = $(this).find('.txtImportName');
                    var txtObjectAmount = $(this).find('.txtObjectAmount');
                    var txtObjectUnit = $(this).find('.txtObjectUnit');
                    var txtObjectMoney = $(this).find('.txtObjectMoney');
                    if (txtImportName.val() != '' || txtObjectAmount.val() != '' || txtObjectUnit.val() != '' || txtObjectMoney.val() != '') {
                        if (qc_main.check.inputNull(txtImportName, 'Nhập tên dụng cụ/vật tư')) {
                            txtImportName.focus();
                            return false;
                        } else {
                            if (txtImportName.val().length > 50) {
                                alert('Tên không dài quá 50 ký tự');
                                txtImportName.focus();
                                return false;
                            }
                        }
                        if (txtObjectAmount.val() == 0 || txtObjectAmount.val() == '') {
                            alert('Nhập số lượng');
                            txtObjectAmount.focus();
                            return false;
                        }

                        if (txtObjectUnit.val() == 0 || txtObjectUnit.val() == '') {
                            alert('Nhập đơn vị');
                            txtObjectUnit.focus();
                            return false;
                        }
                        if (txtObjectMoney.val() == 0 || txtObjectMoney.val() == '') {
                            alert('Nhập giá');
                            txtObjectMoney.focus();
                            return false;
                        }
                    } else {
                        alert('Nhập thông tin mua');
                        return false;
                    }
                }

            });
        },
    },
    delete: function (href) {
        if (confirm('Bạn muốn xóa hóa đơn này?')) {
            qc_master_submit.ajaxHasReload(href, '#qc_master', false);
        }
    },
    image: {
        updateGet: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        },
        updateSave: function (frm) {
            var img = $(frm).find('.txtImportImage');
            if (qc_main.check.inputNull(img, 'PHẢI CHỌN HÌNH ẢNH')) {
                img.focus();
                return false;
            } else {
                if (confirm('Tôi đồng cập nhật ảnh hóa đơn này?')) {
                    qc_master_submit.ajaxFormHasReload(frm, '', false);
                    qc_main.scrollTop();
                }
            }
        }
    },
    confirmPay: function (href) {
        if (confirm('Tôi đã được thanh toán hóa đơn này')) {
            qc_master_submit.ajaxHasReload(href, '#qc_master', false);
        }
    }
}

//====================== MUA VAT TU ========================
$(document).ready(function () {
    //theo ngày
    $('body').on('change', '.qc_work_import_day_filter', function () {
        qc_work_import.filter($(this).data('href') + '/' + $(this).val() + '/' + $('.qc_work_import_month_filter').val() + '/' + $('.qc_work_import_year_filter').val() + '/' + $('.qc_work_import_pay_status').val());
    });
    //theo tháng
    $('body').on('change', '.qc_work_import_month_filter', function () {
        qc_work_import.filter($(this).data('href') + '/' + $('.qc_work_import_day_filter').val() + '/' + $(this).val() + '/' + $('.qc_work_import_year_filter').val() + '/' + $('.qc_work_import_pay_status').val());
    });
    // năm
    $('body').on('change', '.qc_work_import_year_filter', function () {
        qc_work_import.filter($(this).data('href') + '/' + $('.qc_work_import_day_filter').val() + '/' + $('.qc_work_import_month_filter').val() + '/' + $(this).val() + '/' + $('.qc_work_import_pay_status').val());
    })
    //theo trang thai thanh toan
    $('body').on('change', '.qc_work_import_pay_status', function () {
        qc_work_import.filter($(this).data('href') + '/' + $('.qc_work_import_day_filter').val() + '/' + $('.qc_work_import_month_filter').val() + '/' + $('.qc_work_import_year_filter').val() + '/' + $(this).val());
    });
});
$(document).ready(function () {
    //============= ===========  them vat tu / dụng cụ ============= ====================
    $('body').on('click', '#frm_work_import_add .qc_work_import_add_object', function () {
        qc_work_import.add.addObject($(this).data('href'));
    });
    /*kiem tra goi y vat tu co san */
    $('body').on('keyup', '#frm_work_import_add .qc_work_import_object_add .txtImportName', function () {
        var objectWrap = $(this).parents('.qc_work_import_object_add');
        var suggestions_wrap = objectWrap.find('.qc_import_add_object_suggestions_wrap');
        var suggestions_content = objectWrap.find('.qc_import_add_object_suggestions_content');
        var name = $(this).val();
        var addHref = $(this).data('href-check-name');
        if (name.length > 0) {
            var data = {
                txtName: name
            };
            $.ajax({
                type: 'GET',
                dataType: 'json',
                url: addHref + '/' + name,
                data: data,
                beforeSend: function () {
                    //$('#Loading').show();
                },
                success: function (result) {
                    if (!result.hasOwnProperty('status')) {
                        alert('Invalid');
                        return false;
                    }
                    else if (result['status'] == 'exist') {
                        var content = result['content'];
                        $(suggestions_wrap).show();
                        $(suggestions_content).empty(); // xoa thong tin cu
                        for (var i = 0; i < content.length; i++) {
                            var contentName = content[i]['name'];
                            var contentUnit = content[i]['unit'];
                            $(suggestions_content).append(
                                "<a class='qc_import_add_object_suggestions_select qc-link' data-name='" + contentName + "' data-unit='" + contentUnit + "'> " + contentName + "</a><br/>"
                            );
                        }
                    } else if (result['status'] == 'notExist') {
                        $(suggestions_wrap).hide();
                        $(suggestions_content).empty();
                    }
                },
                complete: function () {
                    //$('#Loading').hide();
                },
                error: function () {
                    //alert('Error');
                }
            });
        }
    });
    //chon loai vat tu goi ý
    $('body').on('click', '#frm_work_import_add .qc_import_add_object_suggestions_select', function () {
        var name = $(this).data('name');
        var unit = $(this).data('unit');
        var objectWrap = $(this).parents('.qc_work_import_object_add');
        objectWrap.find('.txtImportName').val(name);
        objectWrap.find('.txtObjectUnit').val(unit);
        objectWrap.find('.qc_import_add_object_suggestions_wrap').hide();

    });

    $('body').on('click', '#frm_work_import_add .qc_work_import_object_add .qc_import_add_object_suggestions_close', function () {
        $(this).parents('.qc_import_add_object_suggestions_wrap').hide();
    });

    $('body').on('click', '#frm_work_import_add .qc_work_import_object_add .qc_delete', function () {
        $(this).parents('.qc_work_import_object_add').remove();
    });

    //lưu
    $('body').on('click', '#frm_work_import_add .qc_work_import_save', function () {
        qc_work_import.add.save($(this).parents('#frm_work_import_add'));
    });

    //xác nhận thanh toán
    $('body').on('click', '.qc_work_import_wrap .qc_work_import_confirm_pay_act', function () {
        qc_work_import.confirmPay($(this).data('href'));
    });
    // ---------- ---------- cap nhat anh hoa dơn ---------- ----------
    $('.qc_work_import_wrap').on('click', '.qc_work_import_update_image_get', function () {
        qc_work_import.image.updateGet($(this).data('href'));
    });
    $('body').on('click', '.qc_frm_import_update_image .qc_save', function () {
        qc_work_import.image.updateSave($(this).parents('.qc_frm_import_update_image'));
    });
    //xóa
    $('body').on('click', '.qc_work_import_wrap .qc_work_import_delete', function () {
        qc_work_import.delete($(this).data('href'));
    });
});
