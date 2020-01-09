/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_pay_salary_before_pay = {
    add: {
        save: function (frm) {
            var cbWork = $(frm).find("select[name='cbWork']");
            var txtMoney = $(frm).find("input[name='txtMoney']");
            var txtLimitBeforePay = $(frm).find("input[name='txtLimitBeforePay']");
            var txtDescription = $(frm).find("input[name='txtDescription']");
            if (qc_main.check.inputNull(cbWork, 'Chọn nhân viên')) {
                return false;
            }
            if (qc_main.check.inputNull(txtMoney, 'Nhập số tiền ứng')) {
                return false;
            } else {
                var money = parseInt(qc_main.getNumberInput(txtMoney.val()));
                if (money == 0 || money < 50000) {
                    alert('Phải nhập số tiền ứng phải lớn hơn: ' + money);
                    txtMoney.focus();
                    return false;
                } else {
                    var checkMoney = parseInt(qc_main.getNumberInput(txtLimitBeforePay.val()));
                    if (money > checkMoney) {
                        alert('Nhập số tiền phải nhỏ hơn giới hạn ứng');
                        txtMoney.focus();
                        return false;
                    }
                }

            }
            if (qc_main.check.inputNull(txtDescription, 'Nhập lý do ứng')) {
                return false;
            } else {
                if (confirm('Tôi đồng ý với thông tin ứng này')) {
                    qc_master_submit.ajaxFormHasReload(frm, '', false);
                    qc_main.scrollTop();
                }
            }

        }
    },
    edit: {
        getFrm: function (href) {
            qc_master_submit.ajaxNotReload(href, '#qc_master', false);
        },
        save: function (frm) {
            var containerNotify = $(frm).find(".qc_notify");
            var txtMoney = $(frm).find("input[name='txtMoney']");
            var txtLimitBeforePay = $(frm).find("input[name='txtLimitBeforePay']");
            var txtDescription = $(frm).find("input[name='txtDescription']");
            if (qc_main.check.inputNull(txtMoney, 'Nhập số tiền ứng')) {
                return false;
            } else {
                var money = parseInt(qc_main.getNumberInput(txtMoney.val()));
                if (money == 0 || money < 50000) {
                    alert('Phải nhập số tiền ứng phải lớn hơn: ' + money);
                    txtMoney.focus();
                    return false;
                } else {
                    var checkMoney = parseInt(qc_main.getNumberInput(txtLimitBeforePay.val()));
                    if (money > checkMoney) {
                        alert('Nhập số tiền phải nhỏ hơn giới hạn ứng');
                        txtMoney.focus();
                        return false;
                    } else {
                        if (confirm('Tôi đồng ý cập nhật thông tin ứng này')) {
                            qc_master_submit.ajaxFormHasReload(frm, containerNotify, true);
                        }
                    }
                }
            }
        }
    },
    delete: function (href) {
        if (confirm('Bạn muốn hủy ứng lương này?')) {
            qc_master_submit.ajaxHasReload(href, '', false);
        }
    }
}
//===================== XOA - SỬA ===========================
$(document).ready(function () {
    // sua thong tin ung
    $('.qc_work_pay_salary_before_pay_list').on('click', '.qc_edit', function () {
        qc_work_pay_salary_before_pay.edit.getFrm($(this).data('href'));
    });

    $('body').on('click', '#frmWorkPaySalaryBeforePayEdit .qc_save', function () {
        qc_work_pay_salary_before_pay.edit.save($(this).parents('#frmWorkPaySalaryBeforePayEdit'));
    });
    // huy ung
    $('.qc_work_pay_salary_before_pay_list').on('click', '.qc_delete', function () {
        qc_work_pay_salary_before_pay.delete($(this).data('href'));
    });
});
//===================== FORM UNG LUONG ===========================
$(document).ready(function () {
    $('#frmWorkPaySalaryBeforePayAdd').on('change', '.cbWork', function () {
        qc_main.url_replace($(this).data('href') + '/' + $(this).val());
    });

    $('#frmWorkPaySalaryBeforePayAdd').on('click', '.qc_save', function () {
        qc_work_pay_salary_before_pay.add.save($(this).parents('#frmWorkPaySalaryBeforePayAdd'));
    });
});

