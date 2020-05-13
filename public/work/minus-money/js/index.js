/**
 * Created by HUY on 12/29/2017.
 */
var qc_work_minus_money = {

}
$(document).ready(function () {
    $('body').on('change', '.qc_work_minus_money_month', function () {
        qc_main.url_replace($(this).data('href') + '/' + $(this).val() + '/' + $('.qc_work_minus_money_year').val());
    });
    $('body').on('change', '.qc_work_minus_money_year', function () {
        qc_main.url_replace($(this).data('href') + '/' + $('.qc_work_minus_money_month').val() + '/' + $(this).val());
    });
});

