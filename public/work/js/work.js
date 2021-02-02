/**
 * Created by HUY on 12/29/2017.
 */
var qc_work = {
    login: function (formObject) {
        var txtCode = $(formObject).find("input[name='txtCode']");
        if (qc_main.check.inputNull(txtCode, 'Nhập mã truy cập')) {
            $(txtCode).focus();
            return false;
        } else {
            //var href = $(formObject).attr('action') + '/' + $(txtCode).val();
            //qc_main.url_replace(href);
        }
    },
    workAllocation: {
        construction: {
            filter: function (href) {
                qc_main.url_replace(href);
            },
            getConfirm: function (href) {
                qc_master_submit.ajaxNotReload(href, '#qc_master', false);
            },
            saveConfirm: function (form) {
                if (confirm('Bạn đông ý xác nhận hoàn thành công trình này?')) {
                    qc_master_submit.ajaxFormHasReload(form, '', true);
                    //qc_master_submit.ajaxFormNotReload(form, '', true);
                }
            },
            product: {
                getConfirm: function (href) {
                    qc_master_submit.ajaxNotReload(href, '#qc_master', false);
                },
                saveConfirm: function (form) {
                    if (confirm('Bạn đông ý xác nhận hoàn thành sản phẩm này?')) {
                        qc_master_submit.ajaxFormHasReload(form, '', true);
                    }
                }
            }
        }
    },
    work: {
        filter: function (href) {
            qc_main.url_replace(href);
        }
    },
    money: {
        history: {
            receiveFilter: function (href) {
                qc_main.url_replace(href);
            }
        }
    },
    productType: {}
}

//===================== KPI ===========================
$(document).ready(function () {
    // loc loai SP theo ten
    $('.qc_work_kpi_statistic_wrap').on('click', '.qc_work_kpi_register_get', function () {
        qc_work.kpi.getRegister($(this).data('href'));
    });
    $('body').on('change', '.frm_work_kpi_register_add .cbKpi', function () {
        var kpiId = $(this).val();
        var href = $(this).data('href');
        if (kpiId > 0) {
            href = href + '/' + kpiId;
        }
        qc_main.remove('.frm_work_kpi_register_add_wrap');
        qc_work.kpi.getRegister(href);
    });
});
//===================== LOAI SAN PHAM ===========================
$(document).ready(function () {
    // loc loai SP theo ten
    $('.qc_work_product_type_wrap').on('click', '.btFilterName', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.qc_work_textNameFilter').val());
    });
});

//=========================== PHAN VIEC =============================
$(document).ready(function () {

    //--------------------- viec da hoan thanh -----------------------
    //xem  bao cao
    /*$('body').on('click', '.qc_work_allocation_finish_contain .qc_work_allocation_report_view', function () {
     $(this).parents('.qc_work_allocation_finish_contain').find('.qc_work_allocation_report_wrap').show();
     });
     //ẩn báo cáo
     $('body').on('click', '.qc_work_allocation_finish_contain .qc_work_allocation_report_hide', function () {
     $(this).parents('.qc_work_allocation_report_wrap').hide();
     });*/

    //------------------- quan ly giao cong trinh -------------------------
    //theo tháng
    $('body').on('change', '.qc_work_orders_allocation_login_month', function () {
        qc_work.orders.filter($(this).data('href') + '/' + $(this).val() + '/' + $('.qc_work_orders_allocation_login_year').val());
    });

    // năm
    $('body').on('change', '.qc_work_orders_allocation_login_year', function () {
        qc_work.orders.filter($(this).data('href') + '/' + $('.qc_work_orders_allocation_login_month').val() + '/' + $(this).val());
    })

    // xac nhan cong trinh hoan thanh
    $('.qc_work_allocation_construction_list_content').on('click', '.qc_confirm_act', function () {
        qc_work.workAllocation.construction.getConfirm($(this).data('href'));
    })
    $('body').on('click', '.frmWorkAllocationConstructionConfirm .qc_save', function () {
        qc_work.workAllocation.construction.saveConfirm($(this).parents('.frmWorkAllocationConstructionConfirm'));
    })
    // xac nhan sp giao cong trinh hoan thanh
    $('.qc_work_allocation_construction_product_list').on('click', '.qc_confirm_act', function () {
        qc_work.workAllocation.construction.product.getConfirm($(this).data('href'));
    })
    $('body').on('click', '.frmWorkAllocationProductConfirm .qc_save', function () {
        qc_work.workAllocation.construction.product.saveConfirm($(this).parents('.frmWorkAllocationProductConfirm'));
    })

});

//====================== DANG NHAP ========================
$(document).ready(function () {
    $('body').on('click', '.qc_work_login', function () {
        qc_work.login($(this).parents('.frmWorkLogin'));
    })
});

$(document).ready(function () {
    $('body').on('click', '.qc_work_change_account', function () {
        qc_work.changeAccount($(this).parents('.frmWorkChangeAccount'));
    })
});

//========================= LƯƠNG - UNG LUONG ===============================
$(document).ready(function () {
    //------ LUONG -----
    $('.qc_work_salary_salary_content').on('click', '.qc_salary_pay_confirm_get', function () {
        qc_work.salary.salary.getConfirm($(this).data('href'));
    });

    $('body').on('click', '#frmWorkSalaryPayConfirm .qc_save', function () {
        qc_work.salary.salary.save($(this).parents('#frmWorkSalaryPayConfirm'));
    });

});
//============================ tin tuc =================
$(document).ready(function () {
    //------ ------ thong bao -------- -------
    /*theo tháng*/
    $('body').on('change', '.qc_work_news_notify_filter_month', function () {
        qc_main.url_replace(($(this).data('href') + '/' + $(this).val() + '/' + $('.qc_work_news_notify_filter_year').val()));
    });

    /*năm*/
    $('body').on('change', '.qc_work_news_notify_filter_year', function () {
        qc_work.work.filter($(this).data('href') + '/' + $('.qc_work_news_notify_filter_month').val() + '/' + $(this).val());
    });
    //------ ------ ngay nghi -------- -------
    /*năm*/
    $('body').on('change', '.qc_work_news_date_off_filter_year', function () {
        qc_work.work.filter($(this).data('href') + '/' + $(this).val());
    });
});

//============================ THONG TIN LAM VIEC =================
$(document).ready(function () {
    //theo tháng
    $('body').on('change', '.qc_work_login_month', function () {
        qc_work.work.filter($(this).data('href') + '/' + $(this).val() + '/' + $('.qc_work_login_year').val());
    });

    // năm
    $('body').on('change', '.qc_work_login_year', function () {
        qc_work.work.filter($(this).data('href') + '/' + $('.qc_work_login_month').val() + '/' + $(this).val());
    })
});
//========================= THU - CHI ===============================
$(document).ready(function () {
    //------------- CHI -------------------
    //theo ngày
    $('body').on('change', '.qc_work_money_pay_import_login_day', function () {
        qc_work.money.history.receiveFilter($(this).data('href') + '/' + $(this).val() + '/' + $('.qc_work_money_pay_import_login_month').val() + '/' + $('.qc_work_money_pay_import_login_year').val() + '/' + $('.qc_work_money_pay_import_status').val());
    });
    //theo tháng
    $('body').on('change', '.qc_work_money_pay_import_login_month', function () {
        qc_work.money.history.receiveFilter($(this).data('href') + '/' + $('.qc_work_money_pay_import_login_day').val() + '/' + $(this).val() + '/' + $('.qc_work_money_pay_import_login_year').val() + '/' + $('.qc_work_money_pay_import_status').val());
    });

    // năm
    $('body').on('change', '.qc_work_money_pay_import_login_year', function () {
        qc_work.money.history.receiveFilter($(this).data('href') + '/' + $('.qc_work_money_pay_import_login_day').val() + '/' + $('.qc_work_money_pay_import_login_month').val() + '/' + $(this).val() + '/' + $('.qc_work_money_pay_import_status').val());
    });
    // trang thai thanh toan
    $('body').on('change', '.qc_work_money_pay_import_status', function () {
        qc_work.money.history.receiveFilter($(this).data('href') + '/' + $('.qc_work_money_pay_import_login_day').val() + '/' + $('.qc_work_money_pay_import_login_month').val() + '/' + $('.qc_work_money_pay_import_login_year').val() + '/' + $(this).val());
    });

    //-------------lịch sử thu chi -------------------
    //theo ngày
    /*$('body').on('change', '.qc_work_money_history_receive_login_day', function () {
        qc_work.money.history.receiveFilter($(this).data('href') + '/' + $(this).val() + '/' + $('.qc_work_money_history_receive_login_month').val() + '/' + $('.qc_work_money_history_receive_login_year').val());
    });
    //theo tháng
    $('body').on('change', '.qc_work_money_history_receive_login_month', function () {
        qc_work.money.history.receiveFilter($(this).data('href') + '/' + $('.qc_work_money_history_receive_login_day').val() + '/' + $(this).val() + '/' + $('.qc_work_money_history_receive_login_year').val());
    });

    // năm
    $('body').on('change', '.qc_work_money_history_receive_login_year', function () {
        qc_work.money.history.receiveFilter($(this).data('href') + '/' + $('.qc_work_money_history_receive_login_day').val() + '/' + $('.qc_work_money_history_receive_login_month').val() + '/' + $(this).val());
    });
*/

    //-------------thong ke -------------------




});