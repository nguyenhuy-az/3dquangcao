/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_money_payment = {


}
$(document).ready(function () {
    $('body').on('change', '.qc_work_money_payment_filter_object', function () {
        qc_main.url_replace($(this).data('href') + '/' + $(this).val() + '/' + $('.qc_work_money_payment_filter_month').val()  + '/' + $('.qc_work_money_payment_filter_year').val());
    });

    $('body').on('change', '.qc_work_money_payment_filter_month', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.qc_work_money_payment_filter_object').val() + '/' + $(this).val() + '/' + $('.qc_work_money_payment_filter_year').val());
    });

    $('body').on('change', '.qc_work_money_payment_filter_year', function () {
        qc_main.url_replace($(this).data('href')+ '/' + $('.qc_work_money_payment_filter_object').val() + '/' + $('.qc_work_money_payment_filter_month').val() + '/' + $(this).val());
    });
});

