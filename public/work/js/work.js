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
    changeAccount: function (formObject) {
        var txtOldAccount = $(formObject).find("input[name='txtOldAccount']");
        var txtNewAccount = $(formObject).find("input[name='txtNewAccount']");

        if (qc_main.check.inputNull(txtOldAccount, 'Nhập mã truy cập hiện tại')) {
            $(txtOldAccount).focus();
            return false;
        }

        if (qc_main.check.inputNull(txtNewAccount, 'Nhập mã truy cập mới')) {
            $(txtNewAccount).focus();
            return false;
        } else {
            qc_master_submit.normalForm(formObject);
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
        receive: {
            postTransfer: function (form) {
                var staff = $(form).find("select[name='cbStaffReceive']");
                if (qc_main.check.inputNull(staff, 'Chọn người nhận')) {
                    $(staff).focus();
                    return false;
                } else {
                    qc_master_submit.normalForm(form);
                }
                //qc_master_submit.ajaxFormHasReload(form, notifyContent, true);
                //qc_main.scrollTop();
            }
        },
        transfers: {
            filter: function (href) {
                qc_main.url_replace(href);
            },
        },
        history: {
            reciveFilter: function (href) {
                qc_main.url_replace(href);
            }
        }
    },
    pay: {
        activity: {
            saveAdd: function (frm) {
                var cbPayActivityList = $(frm).find("select[name='cbPayActivityList']");
                var txtMoney = $(frm).find("input[name='txtMoney']");
                if (qc_main.check.inputNull(cbPayActivityList, 'Chọn danh muc chi')) {
                    $(cbPayActivityList).focus();
                    return false;
                }
                if (qc_main.check.inputNull(txtMoney, 'Nhận số tiền')) {
                    $(txtMoney).focus();
                    return false;
                } else {
                    qc_master_submit.ajaxFormHasReload(frm, '', false);
                }
            },
            delete: function (href) {
                if (confirm('Bạn muốn ủy hóa đơn này?')) {
                    qc_master_submit.ajaxHasReload(href, '', false);
                }
            }
        },
        salary: {
            filter: function (href) {
                qc_main.url_replace(href);
            },
            getAdd: function (href) {
                qc_master_submit.ajaxNotReload(href, '#qc_master', false);
            },
            saveAdd: function (frm) {
                var txtMoney = $(frm).find("input[name='txtMoney']");
                var valCheck = txtMoney.data('pay-limit');
                var txtMoneyPayVal = qc_main.getNumberInput(txtMoney.val());
                if (qc_main.check.inputNull(txtMoney, 'Nhập số tiền thanh toán')) {
                    return false;
                } else {
                    if (txtMoneyPayVal > valCheck) {
                        alert('Số tiền thanh toán không quá số tiền còn lại');
                        txtMoney.focus();
                        return false;
                    } else {
                        if (confirm('Bạn muốn thanh toán lương này?')) {
                            var containNotify = $(frm).find('.frm_notify');
                            qc_master_submit.ajaxFormHasReload(frm, containNotify, true);
                            $('#qc_container_content').animate({
                                scrollTop: 0
                            })
                        }
                    }
                }
            },
            showPay: function (frm) {
                var txtSalaryVal = parseInt($(frm).find("input[name='txtSalary']").val());
                var txtImportVal = parseInt($(frm).find("input[name='txtImport']").val());
                var txtKPIVal = parseInt($(frm).find("input[name='txtKPI']").val());
                var txtBenefitMoney = $(frm).find("input[name='txtBenefitMoney']");
                var txtKeepMoney = $(frm).find("input[name='txtKeepMoney']");
                var txtBenefitMoneyVal = txtBenefitMoney.val();
                if (txtBenefitMoneyVal == '') txtBenefitMoneyVal = '0';
                var numberBenefitMoneyVal = parseInt(qc_main.getNumberInput(txtBenefitMoneyVal)); //chuyen sang kieu so
                var txtKeepMoneyVal = txtKeepMoney.val();
                if (txtKeepMoneyVal == '') txtKeepMoneyVal = '0';
                var numberKeepMoneyVal = parseInt(qc_main.getNumberInput(txtKeepMoneyVal)); //chuyen sang kieu so
                var showTotalMoneyPay = txtSalaryVal + txtImportVal + txtKPIVal + numberBenefitMoneyVal - numberKeepMoneyVal;
                //alert(showTotalMoneyPay);
                //$(frm).find('.qc_salary_showTotalMoneyPay').val(showTotalMoneyPay);
                $(frm).find('.qc_salary_showTotalMoneyPay').val(qc_main.formatCurrency(String(showTotalMoneyPay)));

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

//===================== CHI ===========================
//chi hoat dong
$(document).ready(function () {
    // them thong tin chi
    $('#frm_work_pay_activity_add').on('click', '.qc_save', function () {
        qc_work.pay.activity.saveAdd($(this).parents('#frm_work_pay_activity_add'));
    });

    // xóa
    $('.qc_work_pay_activity_wrap').on('click', '.qc_delete', function () {
        qc_work.pay.activity.delete($(this).data('href'));
    });
});
// thanh toan lương
$(document).ready(function () {
    //theo tháng
    $('body').on('change', '.qc_work_pay_salary_login_month', function () {
        qc_work.pay.salary.filter($(this).data('href') + '/' + $(this).val() + '/' + $('.qc_work_pay_salary_login_year').val() + '/' + $('.qc_work_pay_salary_login_pay_status').val());
    });
    // năm
    $('body').on('change', '.qc_work_pay_salary_login_year', function () {
        qc_work.pay.salary.filter($(this).data('href') + '/' + $('.qc_work_pay_salary_login_month').val() + '/' + $(this).val() + '/' + $('.qc_work_pay_salary_login_pay_status').val());
    })
    //theo trang thai thanh toan
    $('body').on('change', '.qc_work_pay_salary_login_pay_status', function () {
        qc_work.pay.salary.filter($(this).data('href') + '/' + $('.qc_work_pay_salary_login_month').val() + '/' + $('.qc_work_pay_salary_login_year').val() + '/' + $(this).val());
    });

    //--------------- THANH TOAN LUONG ----------------
    /*$('.qc_work_pay_salary_wrap').on('click', '.qc_pay_add_get', function () {
     qc_work.pay.salary.getAdd($(this).data('href'));
     });*/
    $('body').on('keyup', '#frm_work_pay_salary_pay .txtBenefitMoney', function () {
        qc_work.pay.salary.showPay($(this).parents('#frm_work_pay_salary_pay'));
    });
    $('body').on('keyup', '#frm_work_pay_salary_pay .txtKeepMoney', function () {
        qc_work.pay.salary.showPay($(this).parents('#frm_work_pay_salary_pay'));
    });

    $('body').on('click', '#frm_work_pay_salary_pay .qc_save', function () {
        if (confirm('Khi thanh toán sẽ không được thay đổi, Bạn đồng ý thanh toán?')) {
            qc_master_submit.ajaxFormHasReload('#frm_work_pay_salary_pay', '', false);
        }
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
    // thu va giao tien
    $('body').on('click', '.qc_work_money_receive_list_content', function () {
        //qc_work.money.receive.getTransfer($(this).data('href'));
    });

    //theo tháng
    $('body').on('change', '.qc_work_money_receive_filter_month', function () {
        qc_work.money.transfers.filter($(this).data('href') + '/' + $(this).val() + '/' + $('.qc_work_money_receive_filter_year').val());
    });

    // năm
    $('body').on('change', '.qc_work_money_receive_filter_year', function () {
        qc_work.money.transfers.filter($(this).data('href') + '/' + $('.qc_work_money_receive_filter_month').val() + '/' + $(this).val());
    });

    $('.qc_work_frm_transfer_receive').on('click', '.qc_transfer_save', function () {
        qc_work.money.receive.postTransfer($(this).parents('.qc_work_frm_transfer_receive'));
    });
});
$(document).ready(function () {
    //------------- CHI -------------------
    //theo ngày
    $('body').on('change', '.qc_work_money_pay_import_login_day', function () {
        qc_work.money.history.reciveFilter($(this).data('href') + '/' + $(this).val() + '/' + $('.qc_work_money_pay_import_login_month').val() + '/' + $('.qc_work_money_pay_import_login_year').val() + '/' + $('.qc_work_money_pay_import_status').val());
    });
    //theo tháng
    $('body').on('change', '.qc_work_money_pay_import_login_month', function () {
        qc_work.money.history.reciveFilter($(this).data('href') + '/' + $('.qc_work_money_pay_import_login_day').val() + '/' + $(this).val() + '/' + $('.qc_work_money_pay_import_login_year').val() + '/' + $('.qc_work_money_pay_import_status').val());
    });

    // năm
    $('body').on('change', '.qc_work_money_pay_import_login_year', function () {
        qc_work.money.history.reciveFilter($(this).data('href') + '/' + $('.qc_work_money_pay_import_login_day').val() + '/' + $('.qc_work_money_pay_import_login_month').val() + '/' + $(this).val() + '/' + $('.qc_work_money_pay_import_status').val());
    });
    // trang thai thanh toan
    $('body').on('change', '.qc_work_money_pay_import_status', function () {
        qc_work.money.history.reciveFilter($(this).data('href') + '/' + $('.qc_work_money_pay_import_login_day').val() + '/' + $('.qc_work_money_pay_import_login_month').val() + '/' + $('.qc_work_money_pay_import_login_year').val() + '/' + $(this).val());
    });

    //-------------lịch sử thu chi -------------------
    //theo ngày
    $('body').on('change', '.qc_work_money_history_receive_login_day', function () {
        qc_work.money.history.reciveFilter($(this).data('href') + '/' + $(this).val() + '/' + $('.qc_work_money_history_receive_login_month').val() + '/' + $('.qc_work_money_history_receive_login_year').val());
    });
    //theo tháng
    $('body').on('change', '.qc_work_money_history_receive_login_month', function () {
        qc_work.money.history.reciveFilter($(this).data('href') + '/' + $('.qc_work_money_history_receive_login_day').val() + '/' + $(this).val() + '/' + $('.qc_work_money_history_receive_login_year').val());
    });

    // năm
    $('body').on('change', '.qc_work_money_history_receive_login_year', function () {
        qc_work.money.history.reciveFilter($(this).data('href') + '/' + $('.qc_work_money_history_receive_login_day').val() + '/' + $('.qc_work_money_history_receive_login_month').val() + '/' + $(this).val());
    });


    //-------------thong ke -------------------

    //theo tháng
    $('body').on('change', '.qc_work_money_statistical_login_month', function () {
        qc_work.money.history.reciveFilter($(this).data('href') + '/' + $(this).val() + '/' + $('.qc_work_money_statistical_login_year').val());
    });

    // năm
    $('body').on('change', '.qc_work_money_statistical_login_year', function () {
        qc_work.money.history.reciveFilter($(this).data('href') + '/' + $('.qc_work_money_statistical_login_month').val() + '/' + $(this).val());
    });


});