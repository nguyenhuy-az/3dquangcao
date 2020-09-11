/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_pay_salary = {
    filter: function (href) {
        qc_main.url_replace(href);
    },
    /*getAdd: function (href) {
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
     },*/
    showPay: function (frm) {
        var txtSalaryVal = parseInt($(frm).find("input[name='txtSalary']").val());
        var txtImportVal = parseInt($(frm).find("input[name='txtImport']").val());
        var txtKPIVal = parseInt($(frm).find("input[name='txtKPI']").val());
        var txtBenefitMoney = $(frm).find("input[name='txtBenefitMoney']");
        var txtKeepMoney = $(frm).find("input[name='txtKeepMoney']");
        var txtBenefitMoneyVal = txtBenefitMoney.val();
        if (txtBenefitMoneyVal == '') txtBenefitMoneyVal = '0';
        $(frm).find('.qc_showBenefitMoney').text(txtBenefitMoneyVal);
        var numberBenefitMoneyVal = parseInt(qc_main.getNumberInput(txtBenefitMoneyVal)); //chuyen sang kieu so
        var txtKeepMoneyVal = txtKeepMoney.val();
        if (txtKeepMoneyVal == '') txtKeepMoneyVal = '0';
        $(frm).find('.qc_showKeepMoney').text(txtKeepMoneyVal);
        var numberKeepMoneyVal = parseInt(qc_main.getNumberInput(txtKeepMoneyVal)); //chuyen sang kieu so
        var showTotalMoneyPay = txtSalaryVal + txtImportVal + txtKPIVal + numberBenefitMoneyVal - numberKeepMoneyVal;
        $(frm).find('.qc_salary_showTotalMoneyPay').val(qc_main.formatCurrency(String(showTotalMoneyPay)));

    },
    deletePay: function (href) {
        if (confirm('Khi hủy sẽ không được phục hồi, Bạn đồng ý xóa?')) {
            qc_master_submit.ajaxHasReload(href, '', false);
        }
    }
}
// thanh toan lương
$(document).ready(function () {
    //--------------- loc thong ton ----------------
    //theo tháng
    $('body').on('change', '.qc_work_pay_salary_month_filter', function () {
        qc_work_pay_salary.filter($(this).data('href') + '/' + $(this).val() + '/' + $('.qc_work_pay_salary_year_filter').val());
    });
    // năm
    $('body').on('change', '.qc_work_pay_salary_year_filter', function () {
        qc_work_pay_salary.filter($(this).data('href') + '/' + $('.qc_work_pay_salary_month_filter').val() + '/' + $(this).val());
    })
    //theo trang thai thanh toan
    /*$('body').on('change', '.qc_work_pay_salary_pay_status_filter', function () {
        qc_work_pay_salary.filter($(this).data('href') + '/' + $('.qc_work_pay_salary_month_filter').val() + '/' + $('.qc_work_pay_salary_year_filter').val() + '/' + $(this).val());
    });*/

    //--------------- THANH TOAN LUONG ----------------
    $('body').on('keyup', '#frm_work_pay_salary_pay .txtBenefitMoney', function () {
        qc_work_pay_salary.showPay($(this).parents('#frm_work_pay_salary_pay'));
    });
    $('body').on('keyup', '#frm_work_pay_salary_pay .txtKeepMoney', function () {
        qc_work_pay_salary.showPay($(this).parents('#frm_work_pay_salary_pay'));
    });

    $('body').on('click', '#frm_work_pay_salary_pay .qc_save', function () {
        if (confirm('Khi thanh toán sẽ không được thay đổi, Bạn đồng ý thanh toán?')) {
            qc_master_submit.ajaxFormHasReload('#frm_work_pay_salary_pay', '', false);
        }
    });
    //--------------- huy thanh toan ----------------
    $('.qc_work_pay_salary_wrap').on('click', '.qc_salary_pay_del', function () {
        qc_work_pay_salary.deletePay($(this).data('href'));
    });
});
